<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المدن - إدارة النقاط الطبية</title>
    <link rel="stylesheet" href="{{asset('assets/styles.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body data-page="cities">
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
                <a class="nav-item active" data-page="cities" href="/dashboard/cities"><i class="fas fa-city"></i>
                    المدن</a>
                <a class="nav-item" data-page="districts" href="/dashboard/neighborhoods"><i class="fas fa-map"></i>
                    الأحياء</a>
                <a class="nav-item" data-page="medicines" href="/dashboard/medicine-types"><i class="fas fa-pills"></i>
                    أنواع الأدوية</a>
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
                <div class="breadcrumb"><a href="/dashboard">الرئيسية</a><span>/</span><span
                        class="current">المدن</span></div>
                <h1>المدن</h1>
            </div>
            <div class="topbar-left"><span class="date" id="currentDate"></span></div>
        </div>

        <div class="table-card">
            <div class="table-toolbar">
                <div class="search-box"><i class="fas fa-search"></i><input type="text" id="searchInput"
                        placeholder="بحث عن مدينة..." oninput="renderTable()"></div>
                <button class="btn btn-primary btn-sm" onclick="openAddModal()"><i class="fas fa-plus"></i> إضافة
                    مدينة</button>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>المحافظة</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody"></tbody>
                </table>
            </div>
        </div>
    </main>

    <div class="modal-overlay" id="formModal">
        <div class="modal">
            <div class="modal-header">
                <h3 id="modalTitle">إضافة مدينة</h3>
                <button class="modal-close" onclick="closeModal('formModal')"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>المحافظة</label>
                    <select id="governorateSelect"
                        style="width:100%;padding:12px 16px;border:1.5px solid var(--gray-200);border-radius:var(--radius-sm);font-family:'Cairo',sans-serif;font-size:14px;background:var(--gray-50);outline:none;"></select>
                </div>
                <div class="form-group">
                    <label>اسم المدينة</label>
                    <input type="text" id="nameInput" placeholder="أدخل اسم المدينة" required>
                </div>
                <div class="form-group">
                    <label>الحالة</label>
                    <select id="statusInput"
                        style="width:100%;padding:12px 16px;border:1.5px solid var(--gray-200);border-radius:var(--radius-sm);font-family:'Cairo',sans-serif;font-size:14px;background:var(--gray-50);outline:none;">
                        <option value="active">نشط</option>
                        <option value="inactive">غير نشط</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('formModal')">إلغاء</button>
                <button class="btn btn-primary" onclick="saveItem()"><i class="fas fa-save"></i> حفظ</button>
            </div>
        </div>
    </div>

    <script src="{{asset('js/main.js')}}"></script>
    <script>
        // التحقق من وجود التوكن، وإذا لم يكن موجوداً يتم طرده لصفحة تسجيل الدخول فوراً
        if (!localStorage.getItem('auth_token')) {
            window.location.href = '/login';
        }

        const API_BASE_URL = 'http://127.0.0.1:8000/api';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let citiesData = [];

        async function loadGovernorates() {
            try {
                const response = await fetch(`${API_BASE_URL}/governorates`);
                const govs = await response.json();
                const sel = document.getElementById('governorateSelect');
                sel.innerHTML = '<option value="" disabled selected>اختر المحافظة...</option>';
                sel.innerHTML += govs.map(g => `<option value="${g.id}">${g.name}</option>`).join('');
            } catch (e) {
                console.error('خطأ في جلب المحافظات', e);
            }
        }

        async function fetchCities() {
            try {
                const response = await fetch(`${API_BASE_URL}/cities`);
                citiesData = await response.json();
                renderTable();
            } catch (e) {
                console.error('خطأ في جلب المدن', e);
            }
        }

        function renderTable() {
            const q = (document.getElementById('searchInput')?.value || '').trim().toLowerCase();
            const items = citiesData.filter(i => (i.name || '').toLowerCase().includes(q));
            const tbody = document.getElementById('tableBody');

            if (!items || items.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5"><div class="empty-state"><p>لا توجد مدن</p></div></td></tr>';
                return;
            }

            tbody.innerHTML = items.map((item, idx) => `
            <tr>
                <td style="color:var(--gray-400);font-weight:600;">${idx + 1}</td>
                <td><strong>${item.name}</strong></td>
                <td>${item.governorate ? item.governorate.name : '---'}</td>
                <td><span class="status-badge active">نشط</span></td>
                <td>
                    <div class="actions">
                        <button class="edit-btn"><i class="fas fa-pen"></i></button>
                        <button class="delete-btn"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>
        `).join('');
        }

        function openAddModal() {
            document.getElementById('nameInput').value = '';
            loadGovernorates();
            openModal('formModal');
        }

        async function saveItem() {
            const name = document.getElementById('nameInput').value.trim();
            const governorateId = document.getElementById('governorateSelect').value;
            const status = document.getElementById('statusInput').value;

            if (!name || !governorateId) {
                alert('يرجى اختيار المحافظة وإدخال اسم المدينة');
                return;
            }

            try {
                const response = await fetch(`${API_BASE_URL}/cities`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ name: name, governorate_id: governorateId, status: status })
                });

                if (response.ok) {
                    alert('تم إضافة المدينة بنجاح!');
                    closeModal('formModal');
                    fetchCities();
                } else {
                    const err = await response.json();
                    alert('خطأ: ' + JSON.stringify(err.errors || err.message));
                }
            } catch (e) {
                console.error(e);
                alert('حدث خطأ في الاتصال بالسيرفر');
            }
        }

        function openModal(modalId) { document.getElementById(modalId).style.display = 'flex'; }
        function closeModal(modalId) { document.getElementById(modalId).style.display = 'none'; }

        document.addEventListener('DOMContentLoaded', fetchCities);
    </script>
</body>

</html>
