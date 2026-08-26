<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>أنواع التحاليل - إدارة النقاط الطبية</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/assets/styles.css')}}">
</head>

<body data-page="analysis">
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
                <a class="nav-item active" data-page="analysis" href="/dashboard/analyses"><i class="fas fa-flask"></i>
                    أنواع التحاليل</a>
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
                <div class="breadcrumb"><a href="/dashboard">الرئيسية</a><span>/</span><span class="current">أنواع
                        التحاليل</span></div>
                <h1>أنواع التحاليل</h1>
            </div>
            <div class="topbar-left"><span class="date" id="currentDate"></span></div>
        </div>

        <div class="table-card">
            <div class="table-toolbar">
                <div class="search-box"><i class="fas fa-search"></i><input type="text" id="searchInput"
                        placeholder="بحث عن تحليل..." oninput="renderTable()"></div>
                <button class="btn btn-primary btn-sm" onclick="openAddModal()"><i class="fas fa-plus"></i> إضافة
                    تحليل</button>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>الوصف</th>
                            <th>السعر</th>
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
                <h3 id="modalTitle">إضافة تحليل</h3>
                <button class="modal-close" onclick="closeModal('formModal')"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>اسم التحليل</label>
                    <input type="text" id="nameInput" placeholder="أدخل اسم التحليل" required>
                </div>
                <div class="form-group">
                    <label>الوصف</label>
                    <textarea id="descInput" rows="3" placeholder="وصف التحليل"
                        style="width:100%;padding:12px 16px;border:1.5px solid var(--gray-200);border-radius:var(--radius-sm);font-family:'Cairo',sans-serif;font-size:14px;background:var(--gray-50);outline:none;resize:vertical;"></textarea>
                </div>
                <div class="form-group">
                    <label>السعر (رس)</label>
                    <input type="number" id="priceInput" placeholder="0" min="0" step="0.01">
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

    <script src="{{ asset('./js/main.js') }}"></script>
    <script>
        // التحقق من وجود التوكن، وإذا لم يكن موجوداً يتم طرده لصفحة تسجيل الدخول فوراً
        if (!localStorage.getItem('auth_token')) {
            window.location.href = '/login';
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        async function fetchAnalyses() {
            const res = await fetch('/api/analyses'); // تم إضافة /api/ هنا
            const items = await res.json();
            const tbody = document.getElementById('tableBody');

            if (!items || items.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6"><div class="empty-state"><i class="fas fa-flask"></i><p>لا توجد أنواع تحاليل مضافة بعد</p></div></td></tr>';
                return;
            }

            tbody.innerHTML = items.map((item, idx) => `
            <tr>
                <td style="color:var(--gray-400);font-weight:600;">${idx + 1}</td>
                <td><strong>${item.name}</strong></td>
                <td style="color:var(--gray-500);max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${item.description || '---'}</td>
                <td style="font-weight:600;color:var(--primary);">${item.price ? Number(item.price).toLocaleString() + ' رس' : '---'}</td>
                <td><span class="status-badge ${item.status === 'active' ? 'active' : 'inactive'}">${item.status === 'active' ? 'نشط' : 'غير نشط'}</span></td>
                <td>
                    <div class="actions">
                        <button class="edit-btn"><i class="fas fa-pen"></i></button>
                        <button class="delete-btn"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>
        `).join('');
        }

        async function saveItem() {
            const data = {
                name: document.getElementById('nameInput').value.trim(),
                description: document.getElementById('descInput').value.trim(),
                price: document.getElementById('priceInput').value || 0,
                status: document.getElementById('statusInput').value
            };

            if (!data.name) return;

            const res = await fetch('/api/analyses', { // تم إضافة /api/ هنا أيضاً
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data)
            });

            if (res.ok) {
                closeModal('formModal');
                fetchAnalyses();
            }
        }

        function openAddModal() {
            document.getElementById('nameInput').value = '';
            document.getElementById('descInput').value = '';
            document.getElementById('priceInput').value = '';
            document.getElementById('statusInput').value = 'active';
            openModal('formModal');
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
        function openModal(id) { document.getElementById(id).style.display = 'flex'; }
        function closeModal(id) { document.getElementById(id).style.display = 'none'; }

        document.addEventListener('DOMContentLoaded', fetchAnalyses);
    </script>
</body>

</html>
