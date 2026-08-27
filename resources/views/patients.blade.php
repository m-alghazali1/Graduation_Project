<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سجل المرضى والاستقبال - إدارة النقاط الطبية</title>
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}">
</head>

<body data-page="patients">
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
                <div class="nav-section-title">الاستقبال والكشف</div>
                <a class="nav-item active" data-page="patients" href="/dashboard/persons"><i class="fas fa-users"></i> المرضى</a>
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
                <div class="nav-section-title">الطاقم والمستخدمون</div>
                <a class="nav-item" data-page="doctors" href="/dashboard/doctors"><i class="fas fa-user-md"></i> الأطباء</a>
                <a class="nav-item" data-page="users" href="/dashboard/users"><i class="fas fa-user-shield"></i> المستخدمون والصلاحيات</a>
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
                <div class="breadcrumb"><a href="/dashboard">الرئيسية</a><span>/</span><span class="current">سجل المرضى</span></div>
                <h1>إدارة سجلات المرضى والاستقبال</h1>
            </div>
            <div class="topbar-left"><span class="date" id="currentDate"></span></div>
        </div>

        <div class="table-card">
            <div class="table-toolbar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="بحث باسم المريض أو الرقم الوطني أو الجوال..." oninput="renderTable()">
                </div>
                <button class="btn btn-primary btn-sm" onclick="openAddModal()"><i class="fas fa-plus"></i> تسجيل مريض جديد</button>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم الكامل</th>
                            <th>الرقم الوطني</th>
                            <th>الجوال</th>
                            <th>تاريخ الميلاد</th>
                            <th>الجنس</th>
                            <th>العنوان والسكن</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr><td colspan="8" style="text-align:center; padding:30px;">جاري تحميل سجلات المرضى...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- مودال إضافة / تعديل مريض -->
    <div class="modal-overlay" id="formModal">
        <div class="modal" style="width:620px;">
            <div class="modal-header">
                <h3 id="modalTitle">تسجيل مريض جديد</h3>
                <button class="modal-close" onclick="closeModal('formModal')"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <div class="form-group">
                        <label>الاسم الكامل <span style="color:red;">*</span></label>
                        <input type="text" id="nameInput" placeholder="الاسم الثلاثي أو الرباعي" required>
                    </div>
                    <div class="form-group">
                        <label>الرقم الوطني / الهوية <span style="color:red;">*</span></label>
                        <input type="text" id="nationalIdInput" placeholder="رقم الهوية" required>
                    </div>
                    <div class="form-group">
                        <label>رقم الجوال</label>
                        <input type="text" id="mobileInput" placeholder="09xxxxxxxx" dir="ltr" style="text-align:right;">
                    </div>
                    <div class="form-group">
                        <label>تاريخ الميلاد</label>
                        <input type="date" id="dobInput">
                    </div>
                    <div class="form-group">
                        <label>الجنس <span style="color:red;">*</span></label>
                        <select id="genderInput" style="width:100%; padding:12px; border:1.5px solid var(--gray-200); border-radius:var(--radius-sm); font-family:'Cairo',sans-serif; outline:none;" required>
                            <option value="male">ذكر</option>
                            <option value="female">أنثى</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>الحي السكني</label>
                        <select id="districtInput" style="width:100%; padding:12px; border:1.5px solid var(--gray-200); border-radius:var(--radius-sm); font-family:'Cairo',sans-serif; outline:none;">
                            <option value="">اختر الحي</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('formModal')">إلغاء</button>
                <button class="btn btn-primary" onclick="savePatient()"><i class="fas fa-save"></i> حفظ البيانات</button>
            </div>
        </div>
    </div>

    <!-- مودال السجل الطبي للمريض -->
    <div class="modal-overlay" id="historyModal">
        <div class="modal" style="width: 750px;">
            <div class="modal-header">
                <h3 id="historyPatientTitle">الملف والسجل الطبي للمريض</h3>
                <button class="modal-close" onclick="closeModal('historyModal')"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" id="historyTimeline" style="max-height:65vh; overflow-y:auto;">
                <!-- تايم لاين الزيارات -->
            </div>
        </div>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        let patientsData = [];
        let districtsData = [];
        let editingPatientId = null;

        async function initData() {
            const [pRes, nRes] = await Promise.all([
                apiCall('/persons'),
                apiCall('/neighborhoods')
            ]);

            if (pRes.ok) patientsData = pRes.data;
            if (nRes.ok) districtsData = nRes.data;

            populateDistricts();
            renderTable();
        }

        function populateDistricts() {
            const sel = document.getElementById('districtInput');
            sel.innerHTML = '<option value="">اختر الحي</option>' +
                districtsData.map(d => `<option value="${d.id}">${d.name} (${d.city?.name || ''})</option>`).join('');
        }

        function renderTable() {
            const q = (document.getElementById('searchInput')?.value || '').trim().toLowerCase();
            const items = patientsData.filter(i => {
                const name = (i.full_name || '').toLowerCase();
                const natId = (i.national_id || '');
                const mob = (i.phone || '');
                return name.includes(q) || natId.includes(q) || mob.includes(q);
            });

            const tbody = document.getElementById('tableBody');
            if (!items.length) {
                tbody.innerHTML = '<tr><td colspan="8" style="text-align:center; padding:30px; color:var(--gray-400);">لا يوجد مرضى مسجلين</td></tr>';
                return;
            }

            tbody.innerHTML = items.map((item, idx) => {
                const address = item.neighborhood ? `${item.neighborhood.name} (${item.neighborhood.city?.name || ''})` : '---';
                return `
                    <tr>
                        <td style="color:var(--gray-400); font-weight:600;">${idx + 1}</td>
                        <td><strong>${item.full_name}</strong></td>
                        <td>${item.national_id || '---'}</td>
                        <td dir="ltr" style="text-align:right;">${item.phone || '---'}</td>
                        <td>${item.birth_date ? new Date(item.birth_date).toLocaleDateString('ar-SA') : '---'}</td>
                        <td>${item.gender === 'male' ? 'ذكر' : 'أنثى'}</td>
                        <td><span style="font-size:12px; color:var(--gray-600);">${address}</span></td>
                        <td>
                            <div style="display:flex; gap:6px;">
                                <button class="btn btn-sm btn-primary" onclick="viewHistory(${item.id})" style="padding:4px 8px; font-size:12px;" title="الملف الطبي">
                                    <i class="fas fa-notes-medical"></i> السجل
                                </button>
                                <button class="btn btn-sm btn-secondary" onclick="openEditModal(${item.id})" style="padding:4px 8px; font-size:12px;" title="تعديل">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deletePatient(${item.id})" style="padding:4px 8px; font-size:12px;" title="حذف">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function openAddModal() {
            editingPatientId = null;
            document.getElementById('modalTitle').textContent = 'تسجيل مريض جديد';
            document.getElementById('nameInput').value = '';
            document.getElementById('nationalIdInput').value = '';
            document.getElementById('mobileInput').value = '';
            document.getElementById('dobInput').value = '';
            document.getElementById('genderInput').value = 'male';
            document.getElementById('districtInput').value = '';
            openModal('formModal');
        }

        function openEditModal(id) {
            const p = patientsData.find(x => x.id == id);
            if (!p) return;
            editingPatientId = id;
            document.getElementById('modalTitle').textContent = 'تعديل بيانات المريض';
            document.getElementById('nameInput').value = p.full_name || '';
            document.getElementById('nationalIdInput').value = p.national_id || '';
            document.getElementById('mobileInput').value = p.phone || '';
            document.getElementById('dobInput').value = p.birth_date || '';
            document.getElementById('genderInput').value = p.gender || 'male';
            document.getElementById('districtInput').value = p.neighborhood_id || '';
            openModal('formModal');
        }

        async function savePatient() {
            const fullName = document.getElementById('nameInput').value.trim();
            const nationalId = document.getElementById('nationalIdInput').value.trim();

            if (!fullName || !nationalId) {
                showToast('يرجى إدخال اسم المريض ورقم الهوية', 'error');
                return;
            }

            const data = {
                full_name: fullName,
                national_id: nationalId,
                phone: document.getElementById('mobileInput').value.trim() || null,
                birth_date: document.getElementById('dobInput').value || null,
                gender: document.getElementById('genderInput').value,
                neighborhood_id: document.getElementById('districtInput').value || null
            };

            let res;
            if (editingPatientId) {
                res = await apiCall(`/persons/${editingPatientId}`, 'PUT', data);
            } else {
                res = await apiCall('/persons', 'POST', data);
            }

            if (res.ok) {
                showToast(editingPatientId ? 'تم تعديل بيانات المريض بنجاح' : 'تم تسجيل المريض الجديد بنجاح');
                closeModal('formModal');
                initData();
            } else {
                showToast(res.data?.message || 'فشل حفظ البيانات', 'error');
            }
        }

        async function viewHistory(personId) {
            const res = await apiCall(`/persons/${personId}/history`);
            if (res.ok && res.data) {
                const p = res.data.patient;
                document.getElementById('historyPatientTitle').textContent = `الملف الطبي: ${p.full_name} (${p.national_id})`;

                const list = res.data.history;
                const container = document.getElementById('historyTimeline');
                if (!list.length) {
                    container.innerHTML = '<p style="text-align:center; padding:30px; color:gray;">لا توجد سجلات زيارات سابقة لهذا المريض.</p>';
                } else {
                    container.innerHTML = list.map(h => `
                        <div style="border-right:3px solid var(--primary); padding-right:16px; margin-bottom:20px;">
                            <div style="font-size:13px; color:var(--primary-dark); font-weight:bold;">
                                <i class="fas fa-calendar-alt"></i> ${new Date(h.appointment_date).toLocaleDateString('ar-SA')} - د. ${h.doctor?.person?.full_name || 'طبيب المركز'}
                            </div>
                            <div style="font-size:14px; font-weight:bold; margin-top:4px;">التشخيص: ${h.diagnosis || 'فحص عام'}</div>
                            ${h.doctor_notes ? `<p style="font-size:13px; color:#555; margin-top:4px;">${h.doctor_notes}</p>` : ''}
                            
                            ${h.lab_results && h.lab_results.length ? `
                                <div style="margin-top:6px; font-size:12px; background:#f9fafb; padding:6px; border-radius:4px;">
                                    <strong>الفحوصات المخبرية:</strong> ${h.lab_results.map(l => `${l.test_type?.name || 'تحليل'}: ${l.result_value || 'معلق'}`).join(' | ')}
                                </div>
                            ` : ''}

                            ${h.prescription_items && h.prescription_items.length ? `
                                <div style="margin-top:6px; font-size:12px; background:#f0fdf4; padding:6px; border-radius:4px;">
                                    <strong>الأدوية الموصوفة:</strong> ${h.prescription_items.map(rx => `${rx.medicine?.name || 'دواء'} (${rx.instructions || 'جرعة معتادة'})`).join(' | ')}
                                </div>
                            ` : ''}
                        </div>
                    `).join('');
                }
                openModal('historyModal');
            }
        }

        async function deletePatient(id) {
            if (!confirm('تحذير: هل أنت متأكد من حذف هذا المريض؟ سيتم حذف جميع زياراته وسجلاته الطبية المرتبطة!')) return;
            const res = await apiCall(`/persons/${id}`, 'DELETE');
            if (res.ok) {
                showToast('تم حذف المريض بنجاح');
                initData();
            } else {
                showToast(res.data?.message || 'فشل حذف المريض', 'error');
            }
        }

        document.addEventListener('DOMContentLoaded', initData);
    </script>
</body>
</html>
