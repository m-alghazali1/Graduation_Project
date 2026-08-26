<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المستخدمون والصلاحيات - إدارة النقاط الطبية</title>
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}">
</head>

<body data-page="users">
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo"><i class="fas fa-heartbeat"></i></div>
            <div>
                <h2>النقطة الطبية</h2><span>مركز الرعاية الصحية</span>
            </div>
        </div>
        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">الرئيسية</div>
                <a class="nav-item" data-page="dashboard" href="/dashboard"><i class="fas fa-chart-pie"></i> لوحة
                    التحكم</a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">الاستقبال</div>
                <a class="nav-item" data-page="patients" href="/dashboard/persons"><i class="fas fa-users"></i>
                    المرضى</a>
                <a class="nav-item" data-page="visits" href="/dashboard/visits"><i class="fas fa-stethoscope"></i>
                    الزيارات والكشف</a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">الطاقم والمستخدمون</div>
                <a class="nav-item" data-page="doctors" href="/dashboard/doctors"><i class="fas fa-user-md"></i>
                    الأطباء</a>
                <a class="nav-item" data-page="users" href="/dashboard/users"><i class="fas fa-user-shield"></i>
                    المستخدمون والصلاحيات</a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">المختبر</div>
                <a class="nav-item" data-page="analysis" href="/dashboard/analyses"><i class="fas fa-flask"></i> أنواع
                    التحاليل</a>
                <a class="nav-item" data-page="lab" href="/dashboard/lab-results"><i class="fas fa-vials"></i> نتائج
                    التحاليل</a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">الثوابت</div>
                <a class="nav-item" data-page="governorates" href="/dashboard/governorates"><i
                        class="fas fa-map-marker-alt"></i> المحافظات</a>
                <a class="nav-item" data-page="cities" href="/dashboard/cities"><i class="fas fa-city"></i> المدن</a>
                <a class="nav-item" data-page="districts" href="/dashboard/neighborhoods"><i class="fas fa-map"></i>
                    الأحياء</a>
                <a class="nav-item" data-page="medicines" href="/dashboard/medicine-types"><i class="fas fa-pills"></i>
                    أنواع الأدوية</a>
            </div>
            <div class="sidebar-footer mt-4 border-t border-gray-100 pt-4">
                <!-- معلومات المستخدم -->
                <div
                    class="user-info bg-gray-50 p-3 rounded-xl flex items-center justify-between gap-3 border border-gray-100">
                    <div class="flex items-center gap-3">
                        <div
                            class="avatar bg-[#0f816b] text-white w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm">
                            أ</div>
                        <div>
                            <div class="user-name text-xs font-bold text-gray-800">أحمد المدير</div>
                            <div class="user-role text-[10px] text-gray-400">مدير النظام</div>
                        </div>
                    </div>
                    <!-- زر تسجيل الخروج -->
                    <button onclick="handleLogout()" title="تسجيل الخروج"
                        class="text-rose-500 hover:text-rose-700 p-2 rounded-lg transition-all hover:bg-rose-50">
                        <i class="fas fa-right-from-bracket text-base"></i>
                    </button>
                </div>
            </div>
        </nav>
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="avatar">أ</div>
                <div>
                    <div class="user-name">أحمد المدير</div>
                    <div class="user-role">مدير النظام</div>
                </div>
            </div>
        </div>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <button class="mobile-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
            <div>
                <div class="breadcrumb"><a href="../dashboard.html">الرئيسية</a><span>/</span><span
                        class="current">المستخدمون والصلاحيات</span></div>
                <h1>إدارة الطاقم والمستخدمين</h1>
            </div>
            <div class="topbar-left"><span class="date" id="currentDate"></span></div>
        </div>

        <div class="table-card">
            <div class="table-toolbar">
                <div class="search-box"><i class="fas fa-search"></i><input type="text" id="searchInput"
                        placeholder="بحث بالبريد أو الاسم..." oninput="renderTable()"></div>
                <button class="btn btn-primary btn-sm" onclick="openAddModal()"><i class="fas fa-plus"></i> إضافة
                    مستخدم</button>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الشخص المرتبط</th>
                            <th>البريد الإلكتروني</th>
                            <th>الدور والصلاحية</th>
                            <th>تاريخ الإضافة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody"></tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- المودال الخاص بالإضافة والتعديل -->
    <div class="modal-overlay" id="formModal"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); justify-content:center; align-items:center;">
        <div class="modal" style="width:600px; background:#fff; padding:24px; border-radius:8px;">
            <div class="modal-header"
                style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                <h3 id="modalTitle">إضافة مستخدم جديد</h3>
                <button class="modal-close" onclick="closeModal('formModal')"
                    style="background:none; border:none; font-size:18px; cursor:pointer;"><i
                        class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group" style="margin-bottom:12px;">
                    <label>ربط بشخص (اختياري)</label>
                    <select id="personInput" style="width:100%;padding:10px;border:1px solid #ccc;border-radius:4px;">
                        <option value="">بدون ربط</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:12px;">
                    <label>البريد الإلكتروني <span style="color:red;">*</span></label>
                    <input type="email" id="emailInput" placeholder="example@email.com" dir="ltr"
                        style="width:100%;padding:10px;border:1px solid #ccc;border-radius:4px;" required>
                </div>
                <div class="form-group" style="margin-bottom:12px;">
                    <label>كلمة المرور <span style="color:red;">*</span> <small>(8 خانات على الأقل)</small></label>
                    <input type="password" id="passwordInput" placeholder="••••••••" minlength="8" dir="ltr"
                        style="width:100%;padding:10px;border:1px solid #ccc;border-radius:4px;">
                </div>
                <div class="form-group" style="margin-bottom:12px;">
                    <label>الصلاحية (الدور) <span style="color:red;">*</span></label>
                    <select id="roleInput" style="width:100%;padding:10px;border:1px solid #ccc;border-radius:4px;">
                        <option value="">اختر الصلاحية</option>
                        <option value="admin">مدير نظام</option>
                        <option value="doctor">طبيب</option>
                        <option value="lab_employee">موظف مختبر</option>
                        <option value="pharmacist">صيدلي</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer" style="display:flex; justify-content:flex-end; gap:8px; margin-top:16px;">
                <button class="btn btn-secondary" onclick="closeModal('formModal')"
                    style="padding:8px 16px; cursor:pointer;">إلغاء</button>
                <button class="btn btn-primary" onclick="saveItem()"
                    style="padding:8px 16px; background:blue; color:#fff; border:none; border-radius:4px; cursor:pointer;">حفظ
                    في السيرفر</button>
            </div>
        </div>
    </div>

    <script>
        // التحقق من وجود التوكن، وإذا لم يكن موجوداً يتم طرده لصفحة تسجيل الدخول فوراً
        if (!localStorage.getItem('auth_token')) {
            window.location.href = '/login';
        }

        const API_BASE_URL = 'http://127.0.0.1:8000/api';
        let usersData = [];
        let personsData = [];

        const ROLE_TEXT = {
            admin: 'مدير نظام',
            doctor: 'طبيب',
            lab_employee: 'موظف مختبر',
            pharmacist: 'صيدلي'
        };

        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // جلب الأشخاص لربطهم بالمستخدم
        async function loadPersons() {
            try {
                const res = await fetch(`${API_BASE_URL}/persons`);
                if (res.ok) {
                    personsData = await res.json();
                    const sel = document.getElementById('personInput');
                    sel.innerHTML = '<option value="">بدون ربط</option>' +
                        personsData.map(p => `<option value="${p.id}">${p.full_name}${p.national_id ? ' - ' + p.national_id : ''}</option>`).join('');
                }
            } catch (e) {
                console.error('خطأ في جلب الأشخاص:', e);
            }
        }

        function getPersonName(person) {
            if (!person) return '---';
            return person.full_name || '---';
        }

        function roleBadge(role) {
            return `<span class="status-badge ${role}">${ROLE_TEXT[role] || role}</span>`;
        }

        function formatDate(dateStr) {
            if (!dateStr) return '---';
            return new Date(dateStr).toLocaleDateString('ar-SA');
        }

        // جلب المستخدمين من الـ API
        async function fetchUsers() {
            try {
                const res = await fetch(`${API_BASE_URL}/users`);
                if (res.ok) {
                    usersData = await res.json();
                    renderTable();
                }
            } catch (e) {
                console.error('خطأ في جلب المستخدمين:', e);
            }
        }

        function renderTable() {
            const q = (document.getElementById('searchInput')?.value || '').trim().toLowerCase();
            const items = usersData.filter(u => {
                const email = (u.email || '').toLowerCase();
                const pName = u.person ? (u.person.full_name || '').toLowerCase() : '';
                return email.includes(q) || pName.includes(q);
            });

            const tbody = document.getElementById('tableBody');
            if (!items.length) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align:center;padding:20px;">لا يوجد مستخدمون مضافون بعد</td></tr>';
                return;
            }

            tbody.innerHTML = items.map((item, idx) => `
        <tr>
          <td style="color:var(--gray-400);font-weight:600;">${idx + 1}</td>
          <td><strong>${getPersonName(item.person)}</strong></td>
          <td dir="ltr" style="text-align:right;">${item.email}</td>
          <td>${roleBadge(item.role)}</td>
          <td style="font-size:13px;color:var(--gray-500);">${formatDate(item.created_at)}</td>
          <td>
            <div class="actions">
              <button class="delete-btn" onclick="deleteItem('${item.id}')" title="حذف" style="background:red;color:#fff;border:none;padding:4px 8px;border-radius:4px;cursor:pointer;"><i class="fas fa-trash"></i> حذف</button>
            </div>
          </td>
        </tr>
      `).join('');
        }

        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'إضافة مستخدم جديد';
            loadPersons();
            document.getElementById('personInput').value = '';
            document.getElementById('emailInput').value = '';
            document.getElementById('passwordInput').value = '';
            document.getElementById('roleInput').value = '';
            openModal('formModal');
        }

        // حفظ المستخدم عبر الـ API
        async function saveItem() {
            const email = document.getElementById('emailInput').value.trim().toLowerCase();
            const password = document.getElementById('passwordInput').value;
            const role = document.getElementById('roleInput').value;

            if (!email) { alert('يرجى إدخال البريد الإلكتروني'); return; }
            if (!password || password.length < 8) { alert('كلمة المرور يجب ألا تقل عن 8 خانات'); return; }
            if (!role) { alert('يرجى اختيار الصلاحية'); return; }

            const data = {
                person_id: document.getElementById('personInput').value || null,
                email: email,
                password: password,
                role: role
            };

            try {
                const response = await fetch(`${API_BASE_URL}/users`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                if (response.ok) {
                    alert('تم إضافة المستخدم بنجاح وحفظه بقاعدة البيانات!');
                    closeModal('formModal');
                    fetchUsers();
                } else {
                    const err = await response.json();
                    alert('فشل الحفظ: ' + JSON.stringify(err.errors || err.message));
                }
            } catch (e) {
                console.error(e);
                alert('حدث خطأ في الاتصال بالسيرفر');
            }
        }

        async function deleteItem(id) {
            if (!confirm('هل أنت متأكد من حذف هذا المستخدم؟')) return;
            try {
                const res = await fetch(`${API_BASE_URL}/users/${id}`, { method: 'DELETE' });
                if (res.ok) {
                    fetchUsers();
                } else {
                    alert('فشل حذف المستخدم');
                }
            } catch (e) {
                console.error(e);
            }
        }

        function handleLogout() {
            if (confirm('هل أنت متأكد من رغبتك في تسجيل الخروج؟')) {
                // حذف بيانات الجلسة والتوكن
                localStorage.removeItem('auth_token');
                localStorage.removeItem('user');

                // التوجيه لصفحة تسجيل الدخول
                window.location.href = '/login'; // أو مسار صفحة الدخول لديك
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            fetchUsers();
        });
    </script>
</body>

</html>
