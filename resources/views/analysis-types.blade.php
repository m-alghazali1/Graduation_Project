<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>أنواع التحاليل المخبرية - إدارة النقاط الطبية</title>
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}">
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
                <a class="nav-item" data-page="dashboard" href="/dashboard"><i class="fas fa-chart-pie"></i> لوحة التحكم</a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">المختبر</div>
                <a class="nav-item" data-page="lab" href="/dashboard/lab-results"><i class="fas fa-vials"></i> طلبات ونتائج التحاليل</a>
                <a class="nav-item active" data-page="analysis" href="/dashboard/analyses"><i class="fas fa-flask"></i> أنواع التحاليل والمدى الطبيعي</a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">الاستقبال والكشف</div>
                <a class="nav-item" data-page="patients" href="/dashboard/persons"><i class="fas fa-users"></i> المرضى</a>
                <a class="nav-item" data-page="visits" href="/dashboard/visits"><i class="fas fa-stethoscope"></i> الزيارات والكشف</a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">الصيدلية</div>
                <a class="nav-item" data-page="pharmacy" href="/dashboard/pharmacy"><i class="fas fa-prescription-bottle-alt"></i> صرف الوصفات الطبية</a>
                <a class="nav-item" data-page="medicines" href="/dashboard/medicine-types"><i class="fas fa-pills"></i> مخزون الأدوية</a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">الثوابت والمستخدمون</div>
                <a class="nav-item" data-page="users" href="/dashboard/users"><i class="fas fa-user-shield"></i> المستخدمون</a>
                <a class="nav-item" data-page="governorates" href="/dashboard/governorates"><i class="fas fa-map-marker-alt"></i> المحافظات</a>
                <a class="nav-item" data-page="cities" href="/dashboard/cities"><i class="fas fa-city"></i> المدن</a>
                <a class="nav-item" data-page="districts" href="/dashboard/neighborhoods"><i class="fas fa-map"></i> الأحياء</a>
            </div>
        </nav>
        <div class="sidebar-footer">
            <div class="user-info" style="display:flex; align-items:center; justify-content:space-between; width:100%;">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div class="avatar">م</div>
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
                <div class="breadcrumb"><a href="/dashboard">الرئيسية</a><span>/</span><span class="current">أنواع التحاليل</span></div>
                <h1>دليل وأنواع التحاليل المخبرية</h1>
            </div>
            <div class="topbar-left"><span class="date" id="currentDate"></span></div>
        </div>

        <div class="table-card">
            <div class="table-toolbar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="بحث باسم التحليل أو الرمز..." oninput="renderTable()">
                </div>
                <button class="btn btn-primary btn-sm" onclick="openAddModal()"><i class="fas fa-plus"></i> إضافة تحليل جديد</button>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم الفحص</th>
                            <th>الرمز (Code)</th>
                            <th>الوحدة</th>
                            <th>المدى المرجعي الطبيعي</th>
                            <th>السعر</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr><td colspan="8" style="text-align:center; padding:30px;">جاري تحميل التحاليل...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- مودال إضافة / تعديل نوع تحليل -->
    <div class="modal-overlay" id="formModal">
        <div class="modal" style="width: 580px;">
            <div class="modal-header">
                <h3 id="modalTitle">إضافة نوع تحليل جديد</h3>
                <button class="modal-close" onclick="closeModal('formModal')"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px;">
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label>اسم التحليل الطبي <span style="color:red;">*</span></label>
                        <input type="text" id="nameInput" placeholder="مثال: سكر الدم الصيامي (FBS)" required>
                    </div>
                    <div class="form-group">
                        <label>رمز الفحص (Code)</label>
                        <input type="text" id="codeInput" placeholder="مثال: FBS">
                    </div>
                    <div class="form-group">
                        <label>وحدة القياس (Unit)</label>
                        <input type="text" id="unitInput" placeholder="مثال: mg/dL أو %">
                    </div>
                    <div class="form-group">
                        <label>الحد الأدنى الطبيعي (Min Range)</label>
                        <input type="number" step="0.01" id="minRangeInput" placeholder="مثال: 70">
                    </div>
                    <div class="form-group">
                        <label>الحد الأعلى الطبيعي (Max Range)</label>
                        <input type="number" step="0.01" id="maxRangeInput" placeholder="مثال: 100">
                    </div>
                    <div class="form-group">
                        <label>سعر الفحص</label>
                        <input type="number" step="0.01" id="priceInput" value="0.00" min="0">
                    </div>
                    <div class="form-group">
                        <label>الحالة</label>
                        <select id="statusInput" style="width:100%; padding:10px; border:1px solid var(--gray-200); border-radius:4px;">
                            <option value="active">متاح ونشط</option>
                            <option value="inactive">غير متاح</option>
                        </select>
                    </div>
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label>الوصف والإرشادات</label>
                        <textarea id="descInput" rows="2" placeholder="إرشادات العينة أو وصف الفحص..."></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('formModal')">إلغاء</button>
                <button class="btn btn-primary" onclick="saveTestType()"><i class="fas fa-save"></i> حفظ التحليل</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        let testTypesList = [];
        let editingTypeId = null;

        async function fetchTestTypes() {
            const res = await apiCall('/test-types');
            if (res.ok) {
                testTypesList = res.data;
                renderTable();
            }
        }

        function renderTable() {
            const q = (document.getElementById('searchInput')?.value || '').trim().toLowerCase();
            const items = testTypesList.filter(i => (i.name || '').toLowerCase().includes(q) || (i.code || '').toLowerCase().includes(q));

            const tbody = document.getElementById('tableBody');
            if (!items.length) {
                tbody.innerHTML = '<tr><td colspan="8" style="text-align:center; padding:30px; color:var(--gray-400);">لا توجد أنواع تحاليل مسجلة</td></tr>';
                return;
            }

            tbody.innerHTML = items.map((t, idx) => {
                const range = (t.min_range !== null || t.max_range !== null) ? `${t.min_range || 0} - ${t.max_range || 0} ${t.unit || ''}` : '---';
                return `
                    <tr>
                        <td style="color:var(--gray-400); font-weight:600;">${idx + 1}</td>
                        <td><strong>${t.name}</strong></td>
                        <td><span style="font-family:monospace; font-weight:bold; color:#7e22ce;">${t.code || '---'}</span></td>
                        <td>${t.unit || '---'}</td>
                        <td style="font-size:13px; color:var(--gray-600); font-weight:600;">${range}</td>
                        <td>${t.price ? t.price + ' ل.س' : 'مجاني'}</td>
                        <td><span class="status-badge ${t.status === 'active' ? 'active' : 'inactive'}">${t.status === 'active' ? 'نشط' : 'معطل'}</span></td>
                        <td>
                            <div style="display:flex; gap:6px;">
                                <button class="btn btn-sm btn-secondary" onclick="openEditModal(${t.id})" title="تعديل"><i class="fas fa-pen"></i></button>
                                <button class="btn btn-sm btn-danger" onclick="deleteTestType(${t.id})" title="حذف"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function openAddModal() {
            editingTypeId = null;
            document.getElementById('modalTitle').textContent = 'إضافة نوع تحليل جديد';
            document.getElementById('nameInput').value = '';
            document.getElementById('codeInput').value = '';
            document.getElementById('unitInput').value = '';
            document.getElementById('minRangeInput').value = '';
            document.getElementById('maxRangeInput').value = '';
            document.getElementById('priceInput').value = '0.00';
            document.getElementById('statusInput').value = 'active';
            document.getElementById('descInput').value = '';
            openModal('formModal');
        }

        function openEditModal(id) {
            const t = testTypesList.find(x => x.id == id);
            if (!t) return;
            editingTypeId = id;
            document.getElementById('modalTitle').textContent = 'تعديل بيانات التحليل الطبي';
            document.getElementById('nameInput').value = t.name;
            document.getElementById('codeInput').value = t.code || '';
            document.getElementById('unitInput').value = t.unit || '';
            document.getElementById('minRangeInput').value = t.min_range !== null ? t.min_range : '';
            document.getElementById('maxRangeInput').value = t.max_range !== null ? t.max_range : '';
            document.getElementById('priceInput').value = t.price || '0.00';
            document.getElementById('statusInput').value = t.status || 'active';
            document.getElementById('descInput').value = t.description || '';
            openModal('formModal');
        }

        async function saveTestType() {
            const name = document.getElementById('nameInput').value.trim();
            if (!name) {
                showToast('يرجى إدخال اسم التحليل', 'error');
                return;
            }

            const data = {
                name: name,
                code: document.getElementById('codeInput').value.trim() || null,
                unit: document.getElementById('unitInput').value.trim() || null,
                min_range: document.getElementById('minRangeInput').value !== '' ? parseFloat(document.getElementById('minRangeInput').value) : null,
                max_range: document.getElementById('maxRangeInput').value !== '' ? parseFloat(document.getElementById('maxRangeInput').value) : null,
                price: parseFloat(document.getElementById('priceInput').value) || 0.00,
                status: document.getElementById('statusInput').value,
                description: document.getElementById('descInput').value.trim() || null
            };

            let res;
            if (editingTypeId) {
                res = await apiCall(`/test-types/${editingTypeId}`, 'PUT', data);
            } else {
                res = await apiCall('/test-types', 'POST', data);
            }

            if (res.ok) {
                showToast(editingTypeId ? 'تم تعديل التحليل بنجاح' : 'تمت إضافة التحليل الطبي بنجاح');
                closeModal('formModal');
                fetchTestTypes();
            } else {
                showToast(res.data?.message || 'فشل حفظ التحليل', 'error');
            }
        }

        async function deleteTestType(id) {
            if (!confirm('هل أنت متأكد من حذف هذا التحليل؟')) return;
            const res = await apiCall(`/test-types/${id}`, 'DELETE');
            if (res.ok) {
                showToast('تم حذف التحليل بنجاح');
                fetchTestTypes();
            } else {
                showToast(res.data?.message || 'فشل حذف التحليل', 'error');
            }
        }

        document.addEventListener('DOMContentLoaded', fetchTestTypes);
    </script>
</body>
</html>
