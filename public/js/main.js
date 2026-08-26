// Shared JS for Medical Point Management
document.addEventListener('DOMContentLoaded', () => {
    const token = localStorage.getItem('auth_token');
    const userStr = localStorage.getItem('user');

    // 1. إذا لم يكن مسجل دخول أصلاً، طرده لصفحة تسجيل الدخول
    if (!token || !userStr) {
        window.location.href = '/login';
        return;
    }

    const user = JSON.parse(userStr);
    const role = user.role; // admin, doctor, lab, pharmacist

    // 2. حماية الصفحات حسب الدور (مثلاً: منع الطبيب من دخول صفحة التحاليل أو العكس)
    const currentPath = window.location.pathname;

    if (role === 'doctor' && currentPath.includes('/dashboard/analyses')) {
        alert('عذراً، لا تمتلك صلاحية الدخول لهذه الصفحة');
        window.location.href = '/dashboard/visits'; // توجيهه لصفحته المخصصة
    }

    if (role === 'lab' && currentPath.includes('/dashboard/visits')) {
        alert('عذراً، لا تمتلك صلاحية الدخول لهذه الصفحة');
        window.location.href = '/dashboard/lab-results';
    }
});

const API = {
  governorates: JSON.parse(localStorage.getItem('governorates') || '[]'),
  cities: JSON.parse(localStorage.getItem('cities') || '[]'),
  districts: JSON.parse(localStorage.getItem('districts') || '[]'),
  analysisTypes: JSON.parse(localStorage.getItem('analysisTypes') || '[]'),
  medicineTypes: JSON.parse(localStorage.getItem('medicineTypes') || '[]'),

  save(key) { localStorage.setItem(key, JSON.stringify(this[key])); },

  getAll(key) { return this[key] || []; },

  getById(key, id) { return (this[key] || []).find(i => i.id === id); },

  add(key, item) {
    const list = this[key];
    item.id = Date.now().toString(36) + Math.random().toString(36).slice(2, 6);
    item.createdAt = new Date().toISOString();
    list.push(item);
    this.save(key);
    return item;
  },

  update(key, id, data) {
    const list = this[key];
    const idx = list.findIndex(i => i.id === id);
    if (idx === -1) return null;
    list[idx] = { ...list[idx], ...data };
    this.save(key);
    return list[idx];
  },

  remove(key, id) {
    const list = this[key];
    const idx = list.findIndex(i => i.id === id);
    if (idx === -1) return false;
    list.splice(idx, 1);
    this.save(key);
    return true;
  }
};

function showToast(msg, type = 'success') {
  const t = document.getElementById('toast') || (() => {
    const el = document.createElement('div');
    el.id = 'toast';
    el.className = 'toast';
    document.body.appendChild(el);
    return el;
  })();
  t.className = `toast ${type}`;
  t.innerHTML = `<i class="fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${msg}`;
  t.classList.add('show');
  clearTimeout(t._timer);
  t._timer = setTimeout(() => t.classList.remove('show'), 2500);
}

function openModal(id) { document.getElementById(id)?.classList.add('show'); }
function closeModal(id) { document.getElementById(id)?.classList.remove('show'); }

function closeModalOnOverlay(e) {
  if (e.target.classList.contains('modal-overlay')) {
    e.target.classList.remove('show');
  }
}

document.addEventListener('click', function(e) {
  if (e.target.classList.contains('modal-overlay')) {
    e.target.classList.remove('show');
  }
});

function toggleSidebar() {
  document.getElementById('sidebar')?.classList.toggle('open');
  document.getElementById('sidebarOverlay')?.classList.toggle('show');
}

function setActiveNav() {
  const page = document.body.dataset.page;
  document.querySelectorAll('.nav-item').forEach(el => {
    el.classList.toggle('active', el.dataset.page === page);
  });
}

document.addEventListener('DOMContentLoaded', () => {
  setActiveNav();
  const dateEl = document.getElementById('currentDate');
  if (dateEl) {
    const now = new Date();
    const opts = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    dateEl.textContent = now.toLocaleDateString('ar-SA', opts);
  }
});

document.addEventListener('DOMContentLoaded', () => {
    setActiveNav();
    const dateEl = document.getElementById('currentDate');
    if (dateEl) {
        const now = new Date();
        const opts = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        dateEl.textContent = now.toLocaleDateString('ar-SA', opts);
    }

    const userStr = localStorage.getItem('user');
    const token = localStorage.getItem('auth_token');

    // 1. حارس أساسي: إذا لم يكن مسجل دخول، طرده لصفحة تسجيل الدخول
    if (!token || !userStr) {
        window.location.href = '/login';
        return;
    }

    const user = JSON.parse(userStr);
    const role = user.role; // admin, doctor, lab, pharmacist
    const currentPath = window.location.pathname;

    // 2. حراسة الصلاحيات ومنع الوصول المباشر عبر الروابط (URL Guard)
    if (role === 'doctor') {
        // الطبيب مسموح له فقط بـ: لوحة التحكم، المرضى، والزيارات
        if (currentPath.includes('/dashboard/analyses') ||
            currentPath.includes('/dashboard/lab-results') ||
            currentPath.includes('/dashboard/medicine-types') ||
            currentPath.includes('/dashboard/users')) {
            alert('عذراً، لا تمتلك صلاحية الدخول لهذه الصفحة');
            window.location.href = '/dashboard/visits';
            return;
        }
    } else if (role === 'lab') {
        // فني المختبر مسموح له فقط بـ: التحاليل ونتائج التحاليل
        if (currentPath.includes('/dashboard/visits') ||
            currentPath.includes('/dashboard/persons') ||
            currentPath.includes('/dashboard/medicine-types') ||
            currentPath.includes('/dashboard/users') ||
            currentPath.includes('/dashboard/doctors')) {
            alert('عذراً، لا تمتلك صلاحية الدخول لهذه الصفحة');
            window.location.href = '/dashboard/lab-results';
            return;
        }
    } else if (role === 'pharmacist') {
        // الصيدلي مسموح له فقط بـ: أنواع الأدوية
        if (!currentPath.includes('/dashboard/medicine-types') && currentPath !== '/dashboard') {
            alert('عذراً، لا تمتلك صلاحية الدخول لهذه الصفحة');
            window.location.href = '/dashboard/medicine-types';
            return;
        }
    }

    // 3. تصفية القائمة الجانبية (Sidebar) بصرياً
    if (role === 'doctor') {
        document.querySelectorAll('.nav-section').forEach(section => {
            const title = section.querySelector('.nav-section-title')?.textContent || '';
            if (title.includes('المختبر') || title.includes('الثوابت') || title.includes('الطاقم')) {
                section.style.display = 'none';
            }
        });
    } else if (role === 'lab') {
        document.querySelectorAll('.nav-section').forEach(section => {
            const title = section.querySelector('.nav-section-title')?.textContent || '';
            if (title.includes('الاستقبال') || title.includes('الطاقم') || title.includes('الثوابت')) {
                section.style.display = 'none';
            }
        });
    } else if (role === 'pharmacist') {
        document.querySelectorAll('.nav-section').forEach(section => {
            const title = section.querySelector('.nav-section-title')?.textContent || '';
            if (title.includes('الاستقبال') || title.includes('الطاقم') || title.includes('المختبر')) {
                section.style.display = 'none';
            }
        });
    }
});
