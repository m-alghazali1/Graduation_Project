<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المستخدمين والصلاحيات - إدارة النقاط الطبية</title>
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
                <a class="nav-item" data-page="dashboard" href="/dashboard"><i class="fas fa-chart-pie"></i> لوحة التحكم</a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">الطاقم والمستخدمون</div>
                <a class="nav-item active" data-page="users" href="/dashboard/users"><i class="fas fa-user-shield"></i> المستخدمون والصلاحيات</a>
                <a class="nav-item" data-page="doctors" href="/dashboard/doctors"><i class="fas fa-user-md"></i> الأطباء</a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">الاستقبال والكشف</div>
                <a class="nav-item" data-page="patients" href="/dashboard/persons"><i class="fas fa-users"></i> المرضى</a>
                <a class="nav-item" data-page="visits" href="/dashboard/visits"><i class="fas fa-stethoscope"></i> الزيارات والكشف</a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">المختبر</div>
                <a class="nav-item" data-page="lab" href="/dashboard/lab-results"><i class="fas fa-vials"></i> نتائج التحاليل</a>
                <a class="nav-item" data-page="analysis" href="/dashboard/analyses"><i class="fas fa-flask"></i> أنواع التحاليل</a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">الصيدلية</div>
                <a class="nav-item" data-page="pharmacy" href="/dashboard/pharmacy"><i class="fas fa-prescription-bottle-alt"></i> صرف الوصفات الطبية</a>
                <a class="nav-item" data-page="medicines" href="/dashboard/medicine-types"><i class="fas fa-pills"></i> أنواع الأدوية</a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">الثوابت</div>
                <a class="nav-item" data-page="governorates" href="/dashboard/governorates"><i class="fas fa-map-marker-alt"></i> المحافظات</a>
                <a class="nav-item" data-page="cities" href="/dashboard/cities"><i class="fas fa-city"></i> المدن</a>
                <a class="nav-item" data-page="districts" href="/dashboard/neighborhoods"><i class="fas fa-map"></i> الأحياء</a>
            </div>
        </nav>
        <div class="sidebar-footer">
            <div class="user-info" style="display:flex; align-items:center; justify-content:space-between; width:100%;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div class="avatar">أ</div>
                    <div>
                        <div class="user-name">جاري التحميل...</div>
                        <div class="user-role">مدير النظام</div>
                    </div>
                </div>
                <button onclick="handleLogout()" title="تسجيل الخروج" style="background:none; border:none; color:var(--danger); cursor:pointer; font-size:16px;">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </div>
        </div>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <button class="mobile-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
            <div>
                <div class="breadcrumb"><a href="/dashboard">الرئيسية</a><span>/</span><span class="current">المستخدمون والصلاحيات</span></div>
                <h1>إدارة طاقم العمل وحسابات النظام</h1>
            </div>
            <div class="topbar-left"><span class="date" id="currentDate"></span></div>
        </div>

        <div class="table-card">
            <div class="table-toolbar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="بحث بالبريد أو الاسم أو الدور..." oninput="renderTable()">
                </div>
                <button class="btn btn-primary btn-sm" onclick="openAddModal()"><i class="fas fa-plus"></i> إضافة مستخدم جديد</button>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الشخص المرتبط</th>
                            <th>البريد الإلكتروني</th>
                            <th>الدور والصلاحية (Role)</th>
                            <th>تاريخ الإنشاء</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr><td colspan="6" style="text-align:center; padding:30px;">جاري تحميل المستخدمين...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- مودال إضافة / تعديل مستخدم -->
    <div class="modal-overlay" id="formModal">
        <div class="modal" style="width: 540px;">
            <div class="modal-header">
                <h3 id="modalTitle">إضافة مستخدم جديد</h3>
                <button class="modal-close" onclick="closeModal('formModal')"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>ربط بالملف الشخصي (اختياري)</label>
                    <select id="personInput" style="width:100%; padding:10px; border:1px solid var(--gray-200); border-radius:4px;">
                        <option value="">بدون ربط بملف شخصي</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>البريد الإلكتروني <span style="color:red;">*</span></label>
                    <input type="email" id="emailInput" placeholder="user@clinic.com" dir="ltr" style="text-align:right;" required>
                </div>
                <div class="form-group">
                    <label id="pwdLabel">كلمة المرور <span style="color:red;">*</span> <small>(8 خانات على الأقل)</small></label>
                    <input type="password" id="passwordInput" placeholder="••••••••" minlength="8" dir="ltr">
                </div>
                <div class="form-group">
                    <label>الدور والصلاحية (Role) <span style="color:red;">*</span></label>
                    <select id="roleInput" style="width:100%; padding:10px; border:1px solid var(--gray-200); border-radius:4px;" required>
                        <option value="admin">مدير النظام (Admin)</option>
                        <option value="doctor">طبيب (Doctor)</option>
                        <option value="lab_employee">فني مختبر (Lab Technician)</option>
                        <option value="pharmacist">صيدلي (Pharmacist)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('formModal')">إلغاء</button>
                <button class="btn btn-primary" onclick="saveUser()"><i class="fas fa-save"></i> حفظ المستخدم</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        let usersList = [];
        let personsList = [];
        let editingUserId = null;

        const ROLE_MAP = {
            admin: { text: 'مدير النظام', cls: 'admin' },
            doctor: { text: 'طبيب', cls: 'doctor' },
            lab_employee: { text: 'فني مختبر', cls: 'lab_employee' },
            pharmacist: { text: 'صيدلي', cls: 'pharmacist' }
        };

        async function initData() {
            const [uRes, pRes] = await Promise.all([
                apiCall('/users'),
                apiCall('/persons')
            ]);

            if (uRes.ok) usersList = uRes.data;
            if (pRes.ok) personsList = pRes.data;

            populatePersons();
            renderTable();
        }

        function populatePersons() {
            const sel = document.getElementById('personInput');
            sel.innerHTML = '<option value="">بدون ربط بملف شخصي</option>' +
                personsList.map(p => `<option value="${p.id}">${p.full_name} (${p.national_id})</option>`).join('');
        }

        function renderTable() {
            const q = (document.getElementById('searchInput')?.value || '').trim().toLowerCase();
            const items = usersList.filter(u => {
                const em = (u.email || '').toLowerCase();
                const pn = (u.person?.full_name || '').toLowerCase();
                const r = (ROLE_MAP[u.role]?.text || u.role).toLowerCase();
                return em.includes(q) || pn.includes(q) || r.includes(q);
            });

            const tbody = document.getElementById('tableBody');
            if (!items.length) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding:30px; color:var(--gray-400);">لا يوجد مستخدمون مسجلون</td></tr>';
                return;
            }

            tbody.innerHTML = items.map((u, idx) => {
                const rInfo = ROLE_MAP[u.role] || { text: u.role, cls: 'inactive' };
                const pName = u.person ? u.person.full_name : '<span style="color:gray;">غير مرتبط</span>';
                return `
                    <tr>
                        <td style="color:var(--gray-400); font-weight:600;">${idx + 1}</td>
                        <td><strong>${pName}</strong></td>
                        <td dir="ltr" style="text-align:right; font-family:monospace; font-weight:600;">${u.email}</td>
                        <td><span class="status-badge ${rInfo.cls}">${rInfo.text}</span></td>
                        <td style="font-size:12px; color:var(--gray-500);">${new Date(u.created_at).toLocaleDateString('ar-SA')}</td>
                        <td>
                            <div style="display:flex; gap:6px;">
                                <button class="btn btn-sm btn-secondary" onclick="openEditModal(${u.id})" title="تعديل"><i class="fas fa-pen"></i></button>
                                <button class="btn btn-sm btn-danger" onclick="deleteUser(${u.id})" title="حذف"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function openAddModal() {
            editingUserId = null;
            document.getElementById('modalTitle').textContent = 'إضافة مستخدم جديد';
            document.getElementById('pwdLabel').innerHTML = 'كلمة المرور <span style="color:red;">*</span> <small>(8 خانات على الأقل)</small>';
            document.getElementById('personInput').value = '';
            document.getElementById('emailInput').value = '';
            document.getElementById('passwordInput').value = '';
            document.getElementById('roleInput').value = 'doctor';
            openModal('formModal');
        }

        function openEditModal(id) {
            const u = usersList.find(x => x.id == id);
            if (!u) return;
            editingUserId = id;
            document.getElementById('modalTitle').textContent = 'تعديل بيانات المستخدم';
            document.getElementById('pwdLabel').innerHTML = 'كلمة المرور الجديدة <small>(اتركه فارغاً للإبقاء على الحالية)</small>';
            document.getElementById('personInput').value = u.person_id || '';
            document.getElementById('emailInput').value = u.email;
            document.getElementById('passwordInput').value = '';
            document.getElementById('roleInput').value = u.role;
            openModal('formModal');
        }

        async function saveUser() {
            const email = document.getElementById('emailInput').value.trim();
            const pwd = document.getElementById('passwordInput').value;
            const role = document.getElementById('roleInput').value;
            const personId = document.getElementById('personInput').value || null;

            if (!email) {
                showToast('يرجى إدخال البريد الإلكتروني', 'error');
                return;
            }

            if (!editingUserId && (!pwd || pwd.length < 8)) {
                showToast('كلمة المرور يجب ألا تقل عن 8 خانات', 'error');
                return;
            }

            const data = {
                email: email,
                role: role,
                person_id: personId
            };

            if (pwd) data.password = pwd;

            let res;
            if (editingUserId) {
                res = await apiCall(`/users/${editingUserId}`, 'PUT', data);
            } else {
                res = await apiCall('/users', 'POST', data);
            }

            if (res.ok) {
                showToast(editingUserId ? 'تم تعديل المستخدم بنجاح' : 'تمت إضافة المستخدم بنجاح');
                closeModal('formModal');
                initData();
            } else {
                showToast(res.data?.message || 'فشل حفظ المستخدم', 'error');
            }
        }

        async function deleteUser(id) {
            if (!confirm('هل أنت متأكد من رغبتك في حذف هذا المستخدم؟')) return;
            const res = await apiCall(`/users/${id}`, 'DELETE');
            if (res.ok) {
                showToast('تم حذف المستخدم بنجاح');
                initData();
            } else {
                showToast(res.data?.message || 'فشل حذف المستخدم', 'error');
            }
        }

        document.addEventListener('DOMContentLoaded', initData);
    </script>
</body>
</html>
