<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الزيارات والكشف الطبي - إدارة النقاط الطبية</title>
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}">
</head>

<body data-page="visits">
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
                        class="current">الزيارات والكشف الطبي</span></div>
                <h1>الزيارات والكشف الطبي</h1>
            </div>
            <div class="topbar-left"><span class="date" id="currentDate"></span></div>
        </div>

        <div class="table-card">
            <div class="table-toolbar">
                <div class="search-box"><i class="fas fa-search"></i><input type="text" id="searchInput"
                        placeholder="بحث عن زيارة..." oninput="renderTable()"></div>
                <button class="btn btn-primary btn-sm" onclick="openAddModal()"><i class="fas fa-plus"></i> حجز زيارة
                    جديدة</button>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>موعد الزيارة</th>
                            <th>المريض</th>
                            <th>الطبيب المعالج</th>
                            <th>القراءات الحيوية</th>
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
        <div class="modal" style="width:620px; background:#fff; padding:24px; border-radius:8px;">
            <div class="modal-header"
                style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                <h3 id="modalTitle">حجز زيارة جديدة</h3>
                <button class="modal-close" onclick="closeModal('formModal')"
                    style="background:none; border:none; font-size:18px; cursor:pointer;"><i
                        class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                    <div class="form-group" style="margin-bottom:12px;">
                        <label>المريض <span style="color:red;">*</span></label>
                        <select id="patientInput"
                            style="width:100%;padding:10px;border:1px solid #ccc;border-radius:4px;" required>
                            <option value="">اختر المريض</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom:12px;">
                        <label>الطبيب المعالج (اختياري)</label>
                        <select id="doctorInput"
                            style="width:100%;padding:10px;border:1px solid #ccc;border-radius:4px;">
                            <option value="">اختر الطبيب</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom:12px;">
                        <label>موعد الزيارة <span style="color:red;">*</span></label>
                        <input type="datetime-local" id="visitAtInput"
                            style="width:100%;padding:10px;border:1px solid #ccc;border-radius:4px;" required>
                    </div>
                    <div class="form-group" style="margin-bottom:12px;">
                        <label>حالة الزيارة</label>
                        <select id="statusInput"
                            style="width:100%;padding:10px;border:1px solid #ccc;border-radius:4px;">
                            <option value="waiting">في الانتظار</option>
                            <option value="in_progress">قيد الكشف</option>
                            <option value="completed">مكتملة</option>
                            <option value="cancelled">ملغية</option>
                        </select>
                    </div>
                    <div class="form-group" style="margin-bottom:12px;">
                        <label>ضغط الدم (mmHg)</label>
                        <input type="number" step="0.01" min="0" id="bloodPressureInput" placeholder="مثال: 120"
                            style="width:100%;padding:10px;border:1px solid #ccc;border-radius:4px;">
                    </div>
                    <div class="form-group" style="margin-bottom:12px;">
                        <label>الوزن (كجم)</label>
                        <input type="number" step="0.01" min="0" id="weightInput" placeholder="مثال: 70"
                            style="width:100%;padding:10px;border:1px solid #ccc;border-radius:4px;">
                    </div>
                    <div class="form-group" style="margin-bottom:12px;">
                        <label>درجة الحرارة (°م)</label>
                        <input type="number" step="0.01" min="0" id="temperatureInput" placeholder="مثال: 37"
                            style="width:100%;padding:10px;border:1px solid #ccc;border-radius:4px;">
                    </div>
                </div>
                <div class="form-group" style="margin-top:12px;">
                    <label>ملاحظات الطبيب</label>
                    <textarea id="notesInput" rows="3" placeholder="اكتب ملاحظات الكشف هنا..."
                        style="width:100%;padding:10px;border:1px solid #ccc;border-radius:4px;"></textarea>
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
        let visitsData = [];
        let patientsData = [];
        let usersData = [];

        const STATUS_TEXT = {
            waiting: 'في الانتظار',
            in_progress: 'قيد الكشف',
            completed: 'مكتملة',
            cancelled: 'ملغية'
        };

        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // جلب المرضى والأطباء لتعبئة القوائم المنسدلة
        async function loadSelects() {
            try {
                const [patientsRes, usersRes] = await Promise.all([
                    fetch(`${API_BASE_URL}/persons`),
                    fetch(`${API_BASE_URL}/users`)
                ]);

                if (patientsRes.ok) {
                    patientsData = await patientsRes.json();
                    const patientSel = document.getElementById('patientInput');
                    patientSel.innerHTML = '<option value="">اختر المريض</option>' +
                        patientsData.map(p => `<option value="${p.id}">${p.full_name}${p.national_id ? ' - ' + p.national_id : ''}</option>`).join('');
                }

                if (usersRes.ok) {
                    usersData = await usersRes.json();
                    const doctors = usersData.filter(u => u.role === 'doctor');
                    const doctorSel = document.getElementById('doctorInput');
                    doctorSel.innerHTML = '<option value="">اختر الطبيب</option>' +
                        doctors.map(d => {
                            const label = d.person ? d.person.full_name : (d.email ? d.email.split('@')[0] : 'طبيب #' + d.id);
                            return `<option value="${d.id}">${label}</option>`;
                        }).join('');
                }
            } catch (e) {
                console.error('خطأ في جلب القوائم:', e);
            }
        }

        function getPatientName(item) {
            if (item.person) return item.person.full_name;
            const p = patientsData.find(x => x.id == item.person_id);
            return p ? p.full_name : '---';
        }

        function getDoctorName(item) {
            if (item.doctor && item.doctor.person) return item.doctor.person.full_name;
            if (item.doctor && item.doctor.email) return item.doctor.email.split('@')[0];
            const d = usersData.find(x => x.id == item.doctor_id);
            if (d) {
                return d.person ? d.person.full_name : (d.email ? d.email.split('@')[0] : 'طبيب #' + d.id);
            }
            return '---';
        }

        function formatDateTime(v) {
            if (!v) return '---';
            const d = new Date(v);
            return d.toLocaleDateString('ar-SA') + ' - ' + d.toLocaleTimeString('ar-SA', { hour: '2-digit', minute: '2-digit' });
        }

        function vitalsText(item) {
            const parts = [];
            if (item.blood_pressure) parts.push(`ضغط: ${item.blood_pressure}`);
            if (item.weight) parts.push(`وزن: ${item.weight} كجم`);
            if (item.temperature) parts.push(`حرارة: ${item.temperature}°`);
            return parts.length ? parts.join(' | ') : '---';
        }

        // جلب الزيارات من الـ API
        async function fetchVisits() {
            try {
                const res = await fetch(`${API_BASE_URL}/visits`);
                if (res.ok) {
                    visitsData = await res.json();
                    renderTable();
                }
            } catch (e) {
                console.error('خطأ في جلب الزيارات:', e);
            }
        }

        function renderTable() {
            const q = (document.getElementById('searchInput')?.value || '').trim().toLowerCase();
            const items = visitsData.filter(i => {
                const pName = getPatientName(i).toLowerCase();
                const dName = getDoctorName(i).toLowerCase();
                return pName.includes(q) || dName.includes(q);
            });

            const tbody = document.getElementById('tableBody');
            if (!items.length) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:20px;">لا توجد زيارات محجوزة بعد</td></tr>';
                return;
            }

            tbody.innerHTML = items.map((item, idx) => `
        <tr>
          <td style="color:var(--gray-400);font-weight:600;">${idx + 1}</td>
          <td style="font-size:13px;white-space:nowrap;">${formatDateTime(item.appointment_date)}</td>
          <td><strong>${getPatientName(item)}</strong></td>
          <td>${getDoctorName(item)}</td>
          <td style="font-size:13px;">${vitalsText(item)}</td>
          <td><span class="status-badge ${item.status}">${STATUS_TEXT[item.status] || item.status}</span></td>
          <td>
            <div class="actions">
              <button class="delete-btn" onclick="deleteItem('${item.id}')" title="حذف" style="background:red;color:#fff;border:none;padding:4px 8px;border-radius:4px;cursor:pointer;"><i class="fas fa-trash"></i> حذف</button>
            </div>
          </td>
        </tr>
      `).join('');
        }

        function openAddModal() {
            loadSelects();
            document.getElementById('patientInput').value = '';
            document.getElementById('doctorInput').value = '';
            document.getElementById('visitAtInput').value = '';
            document.getElementById('bloodPressureInput').value = '';
            document.getElementById('weightInput').value = '';
            document.getElementById('temperatureInput').value = '';
            document.getElementById('notesInput').value = '';
            document.getElementById('statusInput').value = 'waiting';
            openModal('formModal');
        }

        // حفظ الزيارة عبر الـ API
        async function saveItem() {
            const person_id = document.getElementById('patientInput').value;
            const appointment_date = document.getElementById('visitAtInput').value;

            if (!person_id) { alert('يرجى اختيار المريض'); return; }
            if (!appointment_date) { alert('يرجى تحديد موعد الزيارة'); return; }

            const data = {
                person_id: person_id,
                doctor_id: document.getElementById('doctorInput').value || null,
                appointment_date: appointment_date.replace('T', ' '),
                blood_pressure: document.getElementById('bloodPressureInput').value || null,
                weight: document.getElementById('weightInput').value || null,
                temperature: document.getElementById('temperatureInput').value || null,
                doctor_notes: document.getElementById('notesInput').value.trim(),
                status: document.getElementById('statusInput').value
            };

            try {
                const response = await fetch(`${API_BASE_URL}/visits`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                if (response.ok) {
                    alert('تم حجز الزيارة بنجاح وحفظها في قاعدة البيانات!');
                    closeModal('formModal');
                    fetchVisits();
                } else {
                    const err = await response.json();
                    alert('فشل الحفظ: ' + JSON.stringify(err.errors || err.message));
                }
            } catch (e) {
                console.error(e);
                alert('حدث خطأ في الاتصال بالسيرفر');
            }
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

        async function deleteItem(id) {
            if (!confirm('هل أنت متأكد من حذف هذه الزيارة؟')) return;
            try {
                const res = await fetch(`${API_BASE_URL}/visits/${id}`, { method: 'DELETE' });
                if (res.ok) {
                    fetchVisits();
                } else {
                    alert('فشل حذف الزيارة');
                }
            } catch (e) {
                console.error(e);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadSelects();
            fetchVisits();
        });
    </script>
</body>

</html>
