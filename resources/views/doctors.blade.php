<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الأطباء - إدارة النقاط الطبية</title>
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
                        class="current">الأطباء</span></div>
                <h1>الأطباء</h1>
            </div>
            <div class="topbar-left"><span class="date" id="currentDate"></span></div>
        </div>

        <div class="table-card">
            <div class="table-toolbar">
                <div class="search-box"><i class="fas fa-search"></i><input type="text" id="searchInput"
                        placeholder="بحث عن طبيب..." oninput="renderTable()"></div>
                <button class="btn btn-primary btn-sm" onclick="openAddModal()"><i class="fas fa-plus"></i> إضافة
                    طبيب</button>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>اسم الطبيب</th>
                            <th>التخصص</th>
                            <th>الحالة</th>
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
        <div class="modal" style="width:500px; background:#fff; padding:24px; border-radius:8px;">
            <div class="modal-header"
                style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                <h3 id="modalTitle">إضافة طبيب</h3>
                <button class="modal-close" onclick="closeModal('formModal')"
                    style="background:none; border:none; font-size:18px; cursor:pointer;"><i
                        class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group" style="margin-bottom:12px;">
                    <label>اسم الطبيب <span style="color:red;">*</span></label>
                    <input type="text" id="nameInput" placeholder="أدخل اسم الطبيب"
                        style="width:100%;padding:10px;border:1px solid #ccc;border-radius:4px;" required>
                </div>
                <div class="form-group" style="margin-bottom:12px;">
                    <label>التخصص</label>
                    <input type="text" id="specialtyInput" placeholder="مثال: طب عام، أطفال، نساء وتوليد"
                        style="width:100%;padding:10px;border:1px solid #ccc;border-radius:4px;">
                </div>
                <div class="form-group" style="margin-bottom:12px;">
                    <label>الحالة</label>
                    <select id="statusInput" style="width:100%;padding:10px;border:1px solid #ccc;border-radius:4px;">
                        <option value="active">نشط</option>
                        <option value="inactive">غير نشط</option>
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
        let doctorsData = [];
        let editId = null;

        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // جلب الأطباء من السيرفر
        async function fetchDoctors() {
            try {
                const res = await fetch(`${API_BASE_URL}/doctors`);
                if (res.ok) {
                    doctorsData = await res.json();
                    renderTable();
                }
            } catch (e) {
                console.error('خطأ في جلب الأطباء:', e);
            }
        }

        function renderTable() {
            const q = (document.getElementById('searchInput')?.value || '').trim().toLowerCase();
            const items = doctorsData.filter(i => (i.name || '').toLowerCase().includes(q));
            const tbody = document.getElementById('tableBody');
            if (!items.length) {
                tbody.innerHTML = '<tr><td colspan="5" style="text-align:center;padding:20px;">لا يوجد أطباء مضافين بعد</td></tr>';
                return;
            }
            tbody.innerHTML = items.map((item, idx) => `
        <tr>
          <td style="color:var(--gray-400);font-weight:600;">${idx + 1}</td>
          <td><strong>${item.name}</strong></td>
          <td>${item.specialty || '---'}</td>
          <td><span class="status-badge ${item.status}">${item.status === 'active' ? 'نشط' : 'غير نشط'}</span></td>
          <td>
            <div class="actions">
              <button class="delete-btn" onclick="deleteItem('${item.id}')" title="حذف" style="background:red;color:#fff;border:none;padding:4px 8px;border-radius:4px;cursor:pointer;"><i class="fas fa-trash"></i> حذف</button>
            </div>
          </td>
        </tr>
      `).join('');
        }

        function openAddModal() {
            editId = null;
            document.getElementById('modalTitle').textContent = 'إضافة طبيب جديد';
            document.getElementById('nameInput').value = '';
            document.getElementById('specialtyInput').value = '';
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
        // حفظ الطبيب عبر الـ API
        async function saveItem() {
            const name = document.getElementById('nameInput').value.trim();
            if (!name) { alert('يرجى إدخال اسم الطبيب'); return; }

            const data = {
                name,
                specialty: document.getElementById('specialtyInput').value.trim(),
                status: document.getElementById('statusInput').value
            };

            try {
                const url = editId ? `${API_BASE_URL}/doctors/${editId}` : `${API_BASE_URL}/doctors`;
                const method = editId ? 'PUT' : 'POST';

                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                if (response.ok) {
                    alert('تم حفظ بيانات الطبيب بنجاح في قاعدة البيانات!');
                    closeModal('formModal');
                    fetchDoctors();
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
            if (!confirm('هل أنت متأكد من حذف هذا الطبيب؟')) return;
            try {
                const res = await fetch(`${API_BASE_URL}/doctors/${id}`, { method: 'DELETE' });
                if (res.ok) {
                    fetchDoctors();
                } else {
                    alert('فشل حذف الطبيب');
                }
            } catch (e) {
                console.error(e);
            }
        }

        document.addEventListener('DOMContentLoaded', fetchDoctors);
    </script>
</body>

</html>
