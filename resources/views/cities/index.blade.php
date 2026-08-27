<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المدن - إدارة النقاط الطبية</title>
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}">
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
                <a class="nav-item" data-page="dashboard" href="/dashboard"><i class="fas fa-chart-pie"></i> لوحة التحكم</a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">الثوابت</div>
                <a class="nav-item" data-page="governorates" href="/dashboard/governorates"><i class="fas fa-map-marker-alt"></i> المحافظات</a>
                <a class="nav-item active" data-page="cities" href="/dashboard/cities"><i class="fas fa-city"></i> المدن</a>
                <a class="nav-item" data-page="districts" href="/dashboard/neighborhoods"><i class="fas fa-map"></i> الأحياء</a>
                <a class="nav-item" data-page="medicines" href="/dashboard/medicine-types"><i class="fas fa-pills"></i> أنواع الأدوية</a>
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
            </div>
            <div class="nav-section">
                <div class="nav-section-title">الطاقم والمستخدمون</div>
                <a class="nav-item" data-page="doctors" href="/dashboard/doctors"><i class="fas fa-user-md"></i> الأطباء</a>
                <a class="nav-item" data-page="users" href="/dashboard/users"><i class="fas fa-user-shield"></i> المستخدمون والصلاحيات</a>
            </div>
        </nav>
        <div class="sidebar-footer">
            <div class="user-info" style="display:flex; align-items:center; justify-content:space-between; width:100%;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div class="avatar">أ</div>
                    <div>
                        <div class="user-name">جاري التحميل...</div>
                        <div class="user-role">---</div>
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
                <div class="breadcrumb"><a href="/dashboard">الرئيسية</a><span>/</span><span class="current">المدن</span></div>
                <h1>إدارة المدن</h1>
            </div>
            <div class="topbar-left"><span class="date" id="currentDate"></span></div>
        </div>

        <div class="table-card">
            <div class="table-toolbar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="بحث باسم المدينة أو المحافظة..." oninput="renderTable()">
                </div>
                <button class="btn btn-primary btn-sm" onclick="openAddModal()"><i class="fas fa-plus"></i> إضافة مدينة</button>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم المدينة</th>
                            <th>المحافظة التابعة لها</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr><td colspan="5" style="text-align:center; padding:30px;">جاري التحميل...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div class="modal-overlay" id="formModal">
        <div class="modal">
            <div class="modal-header">
                <h3>إضافة مدينة</h3>
                <button class="modal-close" onclick="closeModal('formModal')"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>المحافظة <span style="color:red;">*</span></label>
                    <select id="governorateSelect" style="width:100%; padding:10px; border:1px solid var(--gray-200); border-radius:4px;" required></select>
                </div>
                <div class="form-group">
                    <label>اسم المدينة <span style="color:red;">*</span></label>
                    <input type="text" id="nameInput" placeholder="أدخل اسم المدينة" required>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('formModal')">إلغاء</button>
                <button class="btn btn-primary" onclick="saveCity()"><i class="fas fa-save"></i> حفظ</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        let citiesList = [];
        let governoratesList = [];

        async function initData() {
            const [cRes, gRes] = await Promise.all([
                apiCall('/cities'),
                apiCall('/governorates')
            ]);

            if (cRes.ok) citiesList = cRes.data;
            if (gRes.ok) governoratesList = gRes.data;

            populateGovs();
            renderTable();
        }

        function populateGovs() {
            const sel = document.getElementById('governorateSelect');
            sel.innerHTML = '<option value="">اختر المحافظة...</option>' +
                governoratesList.map(g => `<option value="${g.id}">${g.name}</option>`).join('');
        }

        function renderTable() {
            const q = (document.getElementById('searchInput')?.value || '').trim().toLowerCase();
            const items = citiesList.filter(i => (i.name || '').toLowerCase().includes(q) || (i.governorate?.name || '').toLowerCase().includes(q));
            const tbody = document.getElementById('tableBody');

            if (!items.length) {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; padding:30px; color:var(--gray-400);">لا توجد مدن مضافة</td></tr>';
                return;
            }

            tbody.innerHTML = items.map((item, idx) => `
                <tr>
                    <td style="color:var(--gray-400); font-weight:600;">${idx + 1}</td>
                    <td><strong>${item.name}</strong></td>
                    <td>${item.governorate ? item.governorate.name : '---'}</td>
                    <td><span class="status-badge active">نشط</span></td>
                    <td>
                        <button class="btn btn-sm btn-danger" onclick="deleteCity(${item.id})" title="حذف"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            `).join('');
        }

        function openAddModal() {
            document.getElementById('nameInput').value = '';
            document.getElementById('governorateSelect').value = '';
            openModal('formModal');
        }

        async function saveCity() {
            const name = document.getElementById('nameInput').value.trim();
            const govId = document.getElementById('governorateSelect').value;

            if (!name || !govId) {
                showToast('يرجى اختيار المحافظة وإدخال اسم المدينة', 'error');
                return;
            }

            const res = await apiCall('/cities', 'POST', { name: name, governorate_id: govId });
            if (res.ok) {
                showToast('تمت إضافة المدينة بنجاح');
                closeModal('formModal');
                initData();
            } else {
                showToast(res.data?.message || 'فشل حفظ المدينة', 'error');
            }
        }

        async function deleteCity(id) {
            if (!confirm('هل أنت متأكد من حذف هذه المدينة؟')) return;
            const res = await apiCall(`/cities/${id}`, 'DELETE');
            if (res.ok) {
                showToast('تم حذف المدينة بنجاح');
                initData();
            } else {
                showToast(res.data?.message || 'فشل الحذف', 'error');
            }
        }

        document.addEventListener('DOMContentLoaded', initData);
    </script>
</body>
</html>
