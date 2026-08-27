/**
 * Medical Point Management System - Unified Core JavaScript
 */

const API_BASE_URL = '/api';

// نصوص مسميات الأدوار باللغة العربية
const ROLE_NAMES = {
    admin: 'مدير النظام',
    doctor: 'طبيب',
    lab_employee: 'فني مختبر',
    pharmacist: 'صيدلي'
};

// 1. استدعاءات الـ API المركزية مع التوكن الموحد
async function apiCall(endpoint, method = 'GET', data = null) {
    const token = localStorage.getItem('auth_token');
    
    const headers = {
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    };

    if (token) {
        headers['Authorization'] = `Bearer ${token}`;
    }

    const config = {
        method: method,
        headers: headers
    };

    if (data && (method === 'POST' || method === 'PUT' || method === 'PATCH')) {
        config.body = JSON.stringify(data);
    }

    try {
        const response = await fetch(`${API_BASE_URL}${endpoint.startsWith('/') ? '' : '/'}${endpoint}`, config);

        if (response.status === 401) {
            // توكن منتهي الصلاحية أو غير مسجل
            localStorage.removeItem('auth_token');
            localStorage.removeItem('user');
            if (!window.location.pathname.includes('/login')) {
                window.location.href = '/login';
            }
            return { ok: false, status: 401, error: 'يرجى تسجيل الدخول مجدداً' };
        }

        if (response.status === 403) {
            const err = await response.json().catch(() => ({}));
            showToast(err.message || 'عذراً، لا تمتلك الصلاحيات الكافية لهذا الإجراء.', 'error');
            return { ok: false, status: 403, error: err.message };
        }

        const resData = await response.json().catch(() => null);
        return {
            ok: response.ok,
            status: response.status,
            data: resData
        };
    } catch (err) {
        console.error('API Error:', err);
        showToast('حدث خطأ في الاتصال بالسيرفر', 'error');
        return { ok: false, error: err.message };
    }
}

// 2. حراسة الواجهات بالـ Frontend (URL Guards) وتخصيص الصلاحيات
function initAuthAndSidebar() {
    const isLoginPage = window.location.pathname.includes('/login');
    const token = localStorage.getItem('auth_token');
    const userStr = localStorage.getItem('user');

    if (isLoginPage) {
        // إذا كان مسجل دخول وموجود في صفحة تسجيل الدخول، توجيهه لصفحته
        if (token && userStr) {
            try {
                const user = JSON.parse(userStr);
                redirectToUserDashboard(user.role);
            } catch (e) {}
        }
        return;
    }

    // إذا لم يكن مسجل دخول وموجود بصفحة محمية، توجيهه لصفحة الدخول
    if (!token || !userStr) {
        window.location.href = '/login';
        return;
    }

    let user = null;
    try {
        user = JSON.parse(userStr);
    } catch (e) {
        localStorage.clear();
        window.location.href = '/login';
        return;
    }

    const role = user.role;
    const currentPath = window.location.pathname;

    // حراسة المسارات بناءً على الصلاحيات
    if (role === 'doctor') {
        const allowed = ['/dashboard', '/dashboard/visits', '/dashboard/persons'];
        if (!allowed.some(p => currentPath === p || currentPath === p + '/')) {
            showToast('عذراً، هذه الصفحة غير مصرحة لك كطبيب', 'error');
            window.location.href = '/dashboard/visits';
            return;
        }
    } else if (role === 'lab_employee') {
        const allowed = ['/dashboard', '/dashboard/lab-results', '/dashboard/analyses'];
        if (!allowed.some(p => currentPath === p || currentPath === p + '/')) {
            showToast('عذراً، هذه الصفحة غير مصرحة لك كفني مختبر', 'error');
            window.location.href = '/dashboard/lab-results';
            return;
        }
    } else if (role === 'pharmacist') {
        const allowed = ['/dashboard', '/dashboard/pharmacy', '/dashboard/medicine-types'];
        if (!allowed.some(p => currentPath === p || currentPath === p + '/')) {
            showToast('عذراً، هذه الصفحة غير مصرحة لك كصيدلي', 'error');
            window.location.href = '/dashboard/pharmacy';
            return;
        }
    }

    // تخصيص القائمة الجانبية (Sidebar) وفقاً للدور
    setupSidebarForRole(user);
}

// توجيه المستخدم حسب دوره
function redirectToUserDashboard(role) {
    if (role === 'doctor') window.location.href = '/dashboard/visits';
    else if (role === 'lab_employee') window.location.href = '/dashboard/lab-results';
    else if (role === 'pharmacist') window.location.href = '/dashboard/pharmacy';
    else window.location.href = '/dashboard';
}

// ضبط عناصر القائمة الجانبية والشارات
function setupSidebarForRole(user) {
    const role = user.role;

    // تحديث بطاقة المستخدم في القائمة الجانبية
    document.querySelectorAll('.user-name').forEach(el => {
        el.textContent = user.name || user.email || 'مستخدم النظام';
    });

    document.querySelectorAll('.user-role').forEach(el => {
        el.textContent = ROLE_NAMES[role] || role;
    });

    document.querySelectorAll('.avatar').forEach(el => {
        const firstLetter = (user.name || user.email || 'م').trim().charAt(0);
        el.textContent = firstLetter.toUpperCase();
    });

    // إخفاء الأقسام غير المسموحة
    if (role === 'doctor') {
        hideSidebarSection(['المختبر', 'الصيدلية', 'الثوابت', 'المستخدمون', 'الطاقم والمستخدمون']);
    } else if (role === 'lab_employee') {
        hideSidebarSection(['الاستقبال', 'الصيدلية', 'الثوابت', 'المستخدمون', 'الطاقم والمستخدمون']);
    } else if (role === 'pharmacist') {
        hideSidebarSection(['الاستقبال', 'المختبر', 'الثوابت', 'المستخدمون', 'الطاقم والمستخدمون']);
    }

    // تفعيل الرابط الحالي
    const currentPath = window.location.pathname;
    document.querySelectorAll('.nav-item').forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPath || (currentPath === '/dashboard' && href === '/dashboard')) {
            link.classList.add('active');
        }
    });
}

function hideSidebarSection(keywords) {
    document.querySelectorAll('.nav-section').forEach(section => {
        const title = (section.querySelector('.nav-section-title')?.textContent || '').trim();
        if (keywords.some(k => title.includes(k))) {
            section.style.display = 'none';
        }
    });
}

// 3. التنبيهات المنبثقة (Toast Notifications)
function showToast(msg, type = 'success') {
    let t = document.getElementById('toast');
    if (!t) {
        t = document.createElement('div');
        t.id = 'toast';
        t.className = 'toast';
        document.body.appendChild(t);
    }
    t.className = `toast ${type}`;
    const icon = type === 'success' ? 'fa-circle-check' : (type === 'error' ? 'fa-circle-exclamation' : 'fa-info-circle');
    t.innerHTML = `<i class="fas ${icon}"></i> <span>${msg}</span>`;
    t.classList.add('show');
    clearTimeout(t._timer);
    t._timer = setTimeout(() => t.classList.remove('show'), 3200);
}

// 4. التحكم بالنوافذ المنبثقة (Modals)
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.style.display = 'flex';
        modal.classList.add('show');
    }
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.style.display = 'none';
        modal.classList.remove('show');
    }
}

function toggleSidebar() {
    document.getElementById('sidebar')?.classList.toggle('open');
    document.getElementById('sidebarOverlay')?.classList.toggle('show');
}

// 5. تسجيل الخروج
async function handleLogout() {
    if (confirm('هل أنت متأكد من رغبتك في تسجيل الخروج؟')) {
        await apiCall('/logout', 'POST').catch(() => {});
        localStorage.removeItem('auth_token');
        localStorage.removeItem('user');
        window.location.href = '/login';
    }
}

// ضبط التاريخ الحالي في رأس الصفحة
document.addEventListener('DOMContentLoaded', () => {
    initAuthAndSidebar();

    const dateEl = document.getElementById('currentDate');
    if (dateEl) {
        const now = new Date();
        const opts = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        dateEl.textContent = now.toLocaleDateString('ar-SA', opts);
    }

    // إغلاق المودال عند النقر خارج المحتوى
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('modal-overlay')) {
            e.target.style.display = 'none';
            e.target.classList.remove('show');
        }
    });
});
