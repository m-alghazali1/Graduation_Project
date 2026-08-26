<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المحافظات - إدارة النقاط الطبية</title>
    <link rel="stylesheet" href="{{asset('assets/styles.css')}}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body data-page="governorates">
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo"><i class="fas fa-heartbeat"></i></div>
            <div>
                <h2>النقطة الطبية</h2>
                <span>مركز الرعاية الصحية</span>
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
                <a class="nav-item active" data-page="governorates" href="/dashboard/governorates"><i
                        class="fas fa-map-marker-alt"></i> المحافظات</a>
                <a class="nav-item" data-page="cities" href="/dashboard/cities"><i class="fas fa-city"></i> المدن</a>
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
                <div class="breadcrumb">
                    <a href="/dashboard">الرئيسية</a>
                    <span>/</span>
                    <span class="current">المحافظات</span>
                </div>
                <h1>المحافظات</h1>
            </div>
            <div class="topbar-left">
                <span class="date" id="currentDate"></span>
            </div>
        </div>

        <div class="table-card">
            <div class="table-toolbar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="بحث عن محافظة..." oninput="renderTable()">
                </div>
                <button class="btn btn-primary btn-sm" onclick="openAddModal()"><i class="fas fa-plus"></i> إضافة
                    محافظة</button>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>الحالة</th>
                            <th>تاريخ الإضافة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody"></tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Add/Edit Modal -->
    <div class="modal-overlay" id="formModal">
        <div class="modal">
            <div class="modal-header">
                <h3 id="modalTitle">إضافة محافظة</h3>
                <button class="modal-close" onclick="closeModal('formModal')"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>اسم المحافظة</label>
                    <input type="text" id="nameInput" placeholder="أدخل اسم المحافظة" required>
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
        let editId = null;
        let governoratesData = [];

        // 1. دالة جلب المحافظات من قاعدة البيانات
        async function fetchGovernorates() {
            try {
                const response = await fetch(`${API_BASE_URL}/governorates`);
                governoratesData = await response.json();
                renderTable();
            } catch (error) {
                console.error('Error fetching data:', error);
            }
        }

        // 2. دالة عرض البيانات في الجدول
        function renderTable() {
            const q = (document.getElementById('searchInput')?.value || '').trim().toLowerCase();
            const items = governoratesData.filter(i => (i.name || '').toLowerCase().includes(q));
            const tbody = document.getElementById('tableBody');

            if (!items || items.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5"><div class="empty-state"><p>لا توجد محافظات مضافة بعد</p></div></td></tr>';
                return;
            }

            tbody.innerHTML = items.map((item, idx) => `
            <tr>
                <td style="color:var(--gray-400);font-weight:600;">${idx + 1}</td>
                <td><strong>${item.name}</strong></td>
                <td><span class="status-badge active">نشط</span></td>
                <td style="color:var(--gray-400);font-size:13px;">${new Date(item.created_at).toLocaleDateString('ar-SA')}</td>
                <td>
                    <div class="actions">
                        <button class="edit-btn" onclick="alert('سيتم برمجتها لاحقاً')" title="تعديل"><i class="fas fa-pen"></i></button>
                        <button class="delete-btn" onclick="alert('سيتم برمجتها لاحقاً')" title="حذف"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>
        `).join('');
        }

        // 3. فتح الموديل (النافذة المنبثقة)
        function openAddModal() {
            editId = null;
            document.getElementById('modalTitle').textContent = 'إضافة محافظة';
            document.getElementById('nameInput').value = '';
            openModal('formModal');
        }

        // 4. دالة حفظ محافظة جديدة في قاعدة البيانات
        async function saveItem() {
            const name = document.getElementById('nameInput').value.trim();
            const status = document.getElementById('statusInput').value;
            if (!name) {
                alert('يرجى إدخال اسم المحافظة');
                return;
            }

            try {
                const response = await fetch(`${API_BASE_URL}/governorates`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ name: name, status: status })
                });

                if (response.ok) {
                    alert('تم إضافة المحافظة بنجاح!');
                    closeModal('formModal');
                    fetchGovernorates(); // تحديث الجدول فوراً وبشكل ديناميكي بدون ريفريش
                } else {
                    const err = await response.json();
                    alert('حدث خطأ: ' + JSON.stringify(err.errors || err.message));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('حدث خطأ في الاتصال بالسيرفر');
            }
        }

        // تحميل البيانات تلقائياً أول ما الصفحة تفتح
        document.addEventListener('DOMContentLoaded', fetchGovernorates);
    </script>
</body>

</html>
