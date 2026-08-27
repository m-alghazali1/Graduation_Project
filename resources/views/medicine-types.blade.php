<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>أنواع ومخزون الأدوية - إدارة النقاط الطبية</title>
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}">
</head>

<body data-page="medicines">
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
                <div class="nav-section-title">الصيدلية والمخزون</div>
                <a class="nav-item" data-page="pharmacy" href="/dashboard/pharmacy"><i class="fas fa-prescription-bottle-alt"></i> صرف الوصفات الطبية</a>
                <a class="nav-item active" data-page="medicines" href="/dashboard/medicine-types"><i class="fas fa-pills"></i> أنواع ومخزون الأدوية</a>
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
                    <div class="avatar">ص</div>
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
                <div class="breadcrumb"><a href="/dashboard">الرئيسية</a><span>/</span><span class="current">مخزون الأدوية</span></div>
                <h1>إدارة أنواع ومخزون الأدوية</h1>
            </div>
            <div class="topbar-left"><span class="date" id="currentDate"></span></div>
        </div>

        <div class="table-card">
            <div class="table-toolbar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="بحث عن دواء أو عيار..." oninput="renderTable()">
                </div>
                <button class="btn btn-primary btn-sm" onclick="openAddModal()"><i class="fas fa-plus"></i> إضافة دواء جديد</button>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم التجاري والعلمي</th>
                            <th>التركيز / العيار</th>
                            <th>رصيد المخزون</th>
                            <th>حالة التوفر</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr><td colspan="6" style="text-align:center; padding:30px;">جاري تحميل الأدوية...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- مودال إضافة / تعديل دواء -->
    <div class="modal-overlay" id="formModal">
        <div class="modal">
            <div class="modal-header">
                <h3 id="modalTitle">إضافة دواء جديد</h3>
                <button class="modal-close" onclick="closeModal('formModal')"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>اسم الدواء <span style="color:red;">*</span></label>
                    <input type="text" id="nameInput" placeholder="مثال: Panadol Extra" required>
                </div>
                <div class="form-group">
                    <label>التركيز / العيار</label>
                    <input type="text" id="strengthInput" placeholder="مثال: 500mg أو 1g">
                </div>
                <div class="form-group">
                    <label>الكمية المتوفرة بالمخزون <span style="color:red;">*</span></label>
                    <input type="number" id="stockInput" value="0" min="0" required>
                </div>
                <div class="form-group">
                    <label>حالة التوفر</label>
                    <select id="availableInput" style="width:100%; padding:12px 16px; border:1.5px solid var(--gray-200); border-radius:var(--radius-sm); font-family:'Cairo',sans-serif; font-size:14px; background:var(--gray-50); outline:none;">
                        <option value="1">متوفر وجاهز للصرف</option>
                        <option value="0">غير متوفر / نفد</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('formModal')">إلغاء</button>
                <button class="btn btn-primary" onclick="saveMedicine()"><i class="fas fa-save"></i> حفظ الدواء</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        let medicinesList = [];
        let editingMedId = null;

        async function fetchMedicines() {
            const res = await apiCall('/medicines');
            if (res.ok) {
                medicinesList = res.data;
                renderTable();
            }
        }

        function renderTable() {
            const q = (document.getElementById('searchInput')?.value || '').trim().toLowerCase();
            const items = medicinesList.filter(i => (i.name || '').toLowerCase().includes(q) || (i.strength || '').toLowerCase().includes(q));

            const tbody = document.getElementById('tableBody');
            if (!items.length) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding:30px; color:var(--gray-400);">لا توجد أدوية مسجلة</td></tr>';
                return;
            }

            tbody.innerHTML = items.map((item, idx) => {
                const isLow = item.stock_quantity <= 10;
                return `
                    <tr>
                        <td style="color:var(--gray-400); font-weight:600;">${idx + 1}</td>
                        <td><strong>${item.name}</strong></td>
                        <td>${item.strength || '---'}</td>
                        <td>
                            <strong style="color:${isLow ? 'var(--danger)' : 'var(--success)'}; font-size:15px;">
                                ${item.stock_quantity}
                            </strong>
                            ${isLow ? '<span style="font-size:11px; color:red; margin-right:4px;">(منخفض!)</span>' : ''}
                        </td>
                        <td><span class="status-badge ${item.is_available ? 'active' : 'inactive'}">${item.is_available ? 'متوفر' : 'غير متوفر'}</span></td>
                        <td>
                            <div style="display:flex; gap:6px;">
                                <button class="btn btn-sm btn-secondary" onclick="openEditModal(${item.id})" title="تعديل"><i class="fas fa-pen"></i></button>
                                <button class="btn btn-sm btn-danger" onclick="deleteMedicine(${item.id})" title="حذف"><i class="fas fa-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function openAddModal() {
            editingMedId = null;
            document.getElementById('modalTitle').textContent = 'إضافة دواء جديد';
            document.getElementById('nameInput').value = '';
            document.getElementById('strengthInput').value = '';
            document.getElementById('stockInput').value = '50';
            document.getElementById('availableInput').value = '1';
            openModal('formModal');
        }

        function openEditModal(id) {
            const m = medicinesList.find(x => x.id == id);
            if (!m) return;
            editingMedId = id;
            document.getElementById('modalTitle').textContent = 'تعديل بيانات ومخزون الدواء';
            document.getElementById('nameInput').value = m.name;
            document.getElementById('strengthInput').value = m.strength || '';
            document.getElementById('stockInput').value = m.stock_quantity;
            document.getElementById('availableInput').value = m.is_available ? '1' : '0';
            openModal('formModal');
        }

        async function saveMedicine() {
            const name = document.getElementById('nameInput').value.trim();
            const strength = document.getElementById('strengthInput').value.trim();
            const stock = parseInt(document.getElementById('stockInput').value) || 0;
            const available = document.getElementById('availableInput').value === '1';

            if (!name) {
                showToast('يرجى إدخال اسم الدواء', 'error');
                return;
            }

            const data = {
                name: name,
                strength: strength || null,
                stock_quantity: stock,
                is_available: available
            };

            let res;
            if (editingMedId) {
                res = await apiCall(`/medicines/${editingMedId}`, 'PUT', data);
            } else {
                res = await apiCall('/medicines', 'POST', data);
            }

            if (res.ok) {
                showToast(editingMedId ? 'تم تعديل بيانات الدواء بنجاح' : 'تمت إضافة الدواء للمستودع');
                closeModal('formModal');
                fetchMedicines();
            } else {
                showToast(res.data?.message || 'فشل حفظ الدواء', 'error');
            }
        }

        async function deleteMedicine(id) {
            if (!confirm('هل أنت متأكد من حذف هذا الدواء؟')) return;
            const res = await apiCall(`/medicines/${id}`, 'DELETE');
            if (res.ok) {
                showToast('تم حذف الدواء بنجاح');
                fetchMedicines();
            } else {
                showToast(res.data?.message || 'فشل حذف الدواء', 'error');
            }
        }

        document.addEventListener('DOMContentLoaded', fetchMedicines);
    </script>
</body>
</html>
