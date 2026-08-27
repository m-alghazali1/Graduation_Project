<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الأطباء - إدارة النقاط الطبية</title>
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}">
</head>

<body data-page="doctors">
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
                <a class="nav-item active" data-page="doctors" href="/dashboard/doctors"><i class="fas fa-user-md"></i> الأطباء</a>
                <a class="nav-item" data-page="users" href="/dashboard/users"><i class="fas fa-user-shield"></i> المستخدمون والصلاحيات</a>
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
                <div class="breadcrumb"><a href="/dashboard">الرئيسية</a><span>/</span><span class="current">الأطباء</span></div>
                <h1>قائمة الأطباء المعتمدين بالمركز</h1>
            </div>
            <div class="topbar-left"><span class="date" id="currentDate"></span></div>
        </div>

        <div class="table-card">
            <div class="table-toolbar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="بحث باسم الطبيب أو التخصص..." oninput="renderTable()">
                </div>
                <button class="btn btn-primary btn-sm" onclick="openAddModal()"><i class="fas fa-plus"></i> إضافة طبيب جديد</button>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم الطبيب</th>
                            <th>التخصص السريري</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr><td colspan="5" style="text-align:center; padding:30px;">جاري تحميل الأطباء...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- مودال إضافة / تعديل طبيب -->
    <div class="modal-overlay" id="formModal">
        <div class="modal" style="width: 500px;">
            <div class="modal-header">
                <h3 id="modalTitle">إضافة طبيب جديد</h3>
                <button class="modal-close" onclick="closeModal('formModal')"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>اسم الطبيب <span style="color:red;">*</span></label>
                    <input type="text" id="nameInput" placeholder="د. الاسم الكامل" required>
                </div>
                <div class="form-group">
                    <label>التخصص الطبي</label>
                    <input type="text" id="specialtyInput" placeholder="مثال: طب عام، باطنة، أطفال">
                </div>
                <div class="form-group">
                    <label>الحالة</label>
                    <select id="statusInput" style="width:100%; padding:10px; border:1px solid var(--gray-200); border-radius:4px;">
                        <option value="active">نشط ومتاح</option>
                        <option value="inactive">غير نشط / إجازة</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('formModal')">إلغاء</button>
                <button class="btn btn-primary" onclick="saveDoctor()"><i class="fas fa-save"></i> حفظ البيانات</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        let doctorsList = [];
        let editingDoctorId = null;

        async function fetchDoctors() {
            const res = await apiCall('/doctors');
            if (res.ok) {
                doctorsList = res.data;
                renderTable();
            }
        }

        function renderTable() {
            const q = (document.getElementById('searchInput')?.value || '').trim().toLowerCase();
            const items = doctorsList.filter(i => (i.name || '').toLowerCase().includes(q) || (i.specialty || '').toLowerCase().includes(q));

            const tbody = document.getElementById('tableBody');
            if (!items.length) {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align:center; padding:30px; color:var(--gray-400);">لا يوجد أطباء مسجلون</td></tr>';
                return;
            }

            tbody.innerHTML = items.map((doc, idx) => `
                <tr>
                    <td style="color:var(--gray-400); font-weight:600;">${idx + 1}</td>
                    <td><strong>${doc.name}</strong></td>
                    <td>${doc.specialty || 'طب عام'}</td>
                    <td><span class="status-badge ${doc.status === 'active' ? 'active' : 'inactive'}">${doc.status === 'active' ? 'نشط' : 'غير نشط'}</span></td>
                    <td>
                        <div style="display:flex; gap:6px;">
                            <button class="btn btn-sm btn-secondary" onclick="openEditModal(${doc.id})" title="تعديل"><i class="fas fa-pen"></i></button>
                            <button class="btn btn-sm btn-danger" onclick="deleteDoctor(${doc.id})" title="حذف"><i class="fas fa-trash"></i></button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function openAddModal() {
            editingDoctorId = null;
            document.getElementById('modalTitle').textContent = 'إضافة طبيب جديد';
            document.getElementById('nameInput').value = '';
            document.getElementById('specialtyInput').value = '';
            document.getElementById('statusInput').value = 'active';
            openModal('formModal');
        }

        function openEditModal(id) {
            const doc = doctorsList.find(x => x.id == id);
            if (!doc) return;
            editingDoctorId = id;
            document.getElementById('modalTitle').textContent = 'تعديل بيانات الطبيب';
            document.getElementById('nameInput').value = doc.name;
            document.getElementById('specialtyInput').value = doc.specialty || '';
            document.getElementById('statusInput').value = doc.status || 'active';
            openModal('formModal');
        }

        async function saveDoctor() {
            const name = document.getElementById('nameInput').value.trim();
            if (!name) {
                showToast('يرجى إدخال اسم الطبيب', 'error');
                return;
            }

            const data = {
                name: name,
                specialty: document.getElementById('specialtyInput').value.trim() || null,
                status: document.getElementById('statusInput').value
            };

            let res;
            if (editingDoctorId) {
                res = await apiCall(`/doctors/${editingDoctorId}`, 'PUT', data);
            } else {
                res = await apiCall('/doctors', 'POST', data);
            }

            if (res.ok) {
                showToast(editingDoctorId ? 'تم تعديل بيانات الطبيب' : 'تمت إضافة الطبيب بنجاح');
                closeModal('formModal');
                fetchDoctors();
            } else {
                showToast(res.data?.message || 'فشل حفظ البيانات', 'error');
            }
        }

        async function deleteDoctor(id) {
            if (!confirm('هل أنت متأكد من حذف هذا الطبيب؟')) return;
            const res = await apiCall(`/doctors/${id}`, 'DELETE');
            if (res.ok) {
                showToast('تم حذف الطبيب بنجاح');
                fetchDoctors();
            } else {
                showToast(res.data?.message || 'فشل حذف الطبيب', 'error');
            }
        }

        document.addEventListener('DOMContentLoaded', fetchDoctors);
    </script>
</body>
</html>
