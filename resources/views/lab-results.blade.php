<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نتائج التحاليل - إدارة النقاط الطبية</title>
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}">
</head>

<body data-page="lab">
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
                <a class="nav-item active" data-page="lab" href="/dashboard/lab-results"><i class="fas fa-vials"></i>
                    نتائج التحاليل</a>
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
                <div class="breadcrumb"><a href="/dashboard">الرئيسية</a><span>/</span><span class="current">نتائج
                        التحاليل</span></div>
                <h1>إدخال نتائج التحاليل</h1>
            </div>
            <div class="topbar-left"><span class="date" id="currentDate"></span></div>
        </div>

        <div class="table-card">
            <div class="table-toolbar">
                <div class="search-box"><i class="fas fa-search"></i><input type="text" id="searchInput"
                        placeholder="بحث عن نتيجة..." oninput="renderTable()"></div>
                <button class="btn btn-primary btn-sm" onclick="openAddModal()"><i class="fas fa-plus"></i> إضافة نتيجة
                    تحليل</button>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الزيارة / المريض</th>
                            <th>نوع التحليل</th>
                            <th>المدى الطبيعي</th>
                            <th>قيمة النتيجة</th>
                            <th>ملاحظات المختبر</th>
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
        <div class="modal" style="width:600px; background:#fff; padding:24px; border-radius:8px;">
            <div class="modal-header"
                style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                <h3 id="modalTitle">إضافة نتيجة تحليل</h3>
                <button class="modal-close" onclick="closeModal('formModal')"
                    style="background:none; border:none; font-size:18px; cursor:pointer;"><i
                        class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group" style="margin-bottom:12px;">
                    <label>الزيارة (المريض) <span style="color:red;">*</span></label>
                    <select id="visitInput" style="width:100%;padding:10px;border:1px solid #ccc;border-radius:4px;"
                        required>
                        <option value="">اختر الزيارة</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:12px;">
                    <label>نوع التحليل <span style="color:red;">*</span></label>
                    <select id="testTypeInput" style="width:100%;padding:10px;border:1px solid #ccc;border-radius:4px;"
                        required onchange="showRange()">
                        <option value="">اختر نوع التحليل</option>
                    </select>
                    <small id="rangeHint" style="display:none;margin-top:6px;color:green;font-weight:600;"><i
                            class="fas fa-info-circle"></i> المدى الطبيعي: <span id="rangeText"></span></small>
                </div>
                <div class="form-group" style="margin-bottom:12px;">
                    <label>قيمة النتيجة (Result Value)</label>
                    <input type="number" step="0.01" id="resultValueInput" placeholder="أدخل القراءة التي ظهرت بالمختبر"
                        style="width:100%;padding:10px;border:1px solid #ccc;border-radius:4px;">
                </div>
                <div class="form-group" style="margin-bottom:12px;">
                    <label>الحالة</label>
                    <select id="statusInput" style="width:100%;padding:10px;border:1px solid #ccc;border-radius:4px;">
                        <option value="pending">معلق</option>
                        <option value="completed">مكتمل</option>
                        <option value="reviewed">تمت مراجعته</option>
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:12px;">
                    <label>ملاحظات المختبر</label>
                    <textarea id="notesInput" rows="3" placeholder="اكتب ملاحظات المختبر هنا..."
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
        let labResultsData = [];
        let visitsData = [];
        let testTypesData = [];

        const STATUS_TEXT = {
            pending: 'معلق',
            completed: 'مكتمل',
            reviewed: 'تمت مراجعته'
        };

        function openModal(modalId) {
            document.getElementById(modalId).style.display = 'flex';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        // جلب الزيارات وأنواع التحاليل لتعبئة القوائم
        async function loadSelects() {
            try {
                const [visitsRes, typesRes] = await Promise.all([
                    fetch(`${API_BASE_URL}/visits`),
                    fetch(`${API_BASE_URL}/analyses`) // تم تعديل الراوت هنا ليطابق جدول analyses
                ]);

                if (visitsRes.ok) {
                    visitsData = await visitsRes.json();
                    const visitSel = document.getElementById('visitInput');
                    visitSel.innerHTML = '<option value="">اختر الزيارة</option>' + visitsData.map(v => {
                        const patientName = v.person ? v.person.full_name : 'زيارة #' + v.id;
                        const date = v.appointment_date ? new Date(v.appointment_date).toLocaleDateString('ar-SA') : '';
                        return `<option value="${v.id}">${patientName}${date ? ' - ' + date : ''}</option>`;
                    }).join('');
                }

                if (typesRes.ok) {
                    testTypesData = await typesRes.json();
                    const typeSel = document.getElementById('testTypeInput');
                    typeSel.innerHTML = '<option value="">اختر نوع التحليل</option>' + testTypesData.map(t => `<option value="${t.id}">${t.name}</option>`).join('');
                }
            } catch (e) {
                console.error('خطأ في جلب القوائم:', e);
            }
        }

        function showRange() {
            const hint = document.getElementById('rangeHint');
            const selectedId = document.getElementById('testTypeInput').value;
            const t = testTypesData.find(x => x.id == selectedId);
            if (t && (t.min_range !== undefined || t.max_range !== undefined || t.minRange !== undefined)) {
                const min = t.min_range ?? t.minRange ?? '---';
                const max = t.max_range ?? t.maxRange ?? '---';
                document.getElementById('rangeText').textContent = `${min} إلى ${max}`;
                hint.style.display = 'block';
            } else {
                hint.style.display = 'none';
            }
        }

        function visitLabel(visitId) {
            const v = visitsData.find(x => x.id == visitId);
            if (!v) return '---';
            const patientName = v.person ? v.person.full_name : 'زيارة #' + v.id;
            const date = v.appointment_date ? new Date(v.appointment_date).toLocaleDateString('ar-SA') : '';
            return `${patientName}${date ? ' - ' + date : ''}`;
        }

        function getTestType(testTypeId) {
            return testTypesData.find(x => x.id == testTypeId);
        }

        function rangeText(item) {
            const t = getTestType(item.test_type_id || item.testTypeId);
            if (!t) return '---';
            const min = t.min_range ?? t.minRange ?? '---';
            const max = t.max_range ?? t.maxRange ?? '---';
            return `${min} - ${max}`;
        }

        function resultCell(item) {
            const val = item.result_value ?? item.resultValue;
            if (val === null || val === undefined || val === '') {
                return '<span style="color:gray;">---</span>';
            }
            const t = getTestType(item.test_type_id || item.testTypeId);
            let outOfRange = false;
            if (t && !isNaN(val)) {
                const min = t.min_range ?? t.minRange;
                const max = t.max_range ?? t.maxRange;
                if (min !== '' && min !== null && min !== undefined && val < Number(min)) outOfRange = true;
                if (max !== '' && max !== null && max !== undefined && val > Number(max)) outOfRange = true;
            }
            const color = outOfRange ? 'red' : 'green';
            return `<strong style="color:${color};">${val}</strong>`;
        }

        // جلب نتائج التحاليل من السيرفر
        async function fetchLabResults() {
            try {
                const res = await fetch(`${API_BASE_URL}/lab-results`);
                if (res.ok) {
                    labResultsData = await res.json();
                    renderTable();
                }
            } catch (e) {
                console.error('خطأ في جلب نتائج التحاليل:', e);
            }
        }

        function renderTable() {
            const q = (document.getElementById('searchInput')?.value || '').trim().toLowerCase();
            const items = labResultsData.filter(i => {
                const vLabel = visitLabel(i.visit_id || i.visitId).toLowerCase();
                const tObj = getTestType(i.test_type_id || i.testTypeId);
                const tName = tObj ? (tObj.name || '').toLowerCase() : '';
                return vLabel.includes(q) || tName.includes(q);
            });

            const tbody = document.getElementById('tableBody');
            if (!items.length) {
                tbody.innerHTML = '<tr><td colspan="8" style="text-align:center;padding:20px;">لا توجد نتائج تحاليل مسجلة بعد</td></tr>';
                return;
            }

            tbody.innerHTML = items.map((item, idx) => {
                const tObj = getTestType(item.test_type_id || item.testTypeId);
                return `
          <tr>
            <td>${idx + 1}</td>
            <td><strong>${visitLabel(item.visit_id || item.visitId)}</strong></td>
            <td>${tObj ? tObj.name : '---'}</td>
            <td style="font-size:13px;color:gray;">${rangeText(item)}</td>
            <td>${resultCell(item)}</td>
            <td style="color:gray;max-width:160px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${item.notes || '---'}</td>
            <td><span>${STATUS_TEXT[item.status] || item.status}</span></td>
            <td>
              <button class="delete-btn" onclick="deleteItem('${item.id}')" title="حذف" style="background:red;color:#fff;border:none;padding:4px 8px;border-radius:4px;cursor:pointer;">حذف</button>
            </td>
          </tr>
        `;
            }).join('');
        }

        function openAddModal() {
            loadSelects();
            document.getElementById('visitInput').value = '';
            document.getElementById('testTypeInput').value = '';
            document.getElementById('resultValueInput').value = '';
            document.getElementById('notesInput').value = '';
            document.getElementById('statusInput').value = 'pending';
            showRange();
            openModal('formModal');
        }

        // حفظ نتيجة التحليل عبر الـ API
        async function saveItem() {
            const visit_id = document.getElementById('visitInput').value;
            const test_type_id = document.getElementById('testTypeInput').value;

            if (!visit_id) { alert('يرجى اختيار الزيارة'); return; }
            if (!test_type_id) { alert('يرجى اختيار نوع التحليل'); return; }

            const rawValue = document.getElementById('resultValueInput').value;
            const data = {
                visit_id: visit_id,
                test_type_id: test_type_id,
                result_value: rawValue === '' ? null : parseFloat(rawValue),
                notes: document.getElementById('notesInput').value.trim(),
                status: document.getElementById('statusInput').value
            };

            try {
                const response = await fetch(`${API_BASE_URL}/lab-results`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                if (response.ok) {
                    alert('تم إضافة نتيجة التحليل بنجاح وحفظها في قاعدة البيانات!');
                    closeModal('formModal');
                    fetchLabResults();
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
            if (!confirm('هل أنت متأكد من حذف هذه النتيجة؟')) return;
            try {
                const res = await fetch(`${API_BASE_URL}/lab-results/${id}`, { method: 'DELETE' });
                if (res.ok) {
                    fetchLabResults();
                } else {
                    alert('فشل حذف النتيجة');
                }
            } catch (e) {
                console.error(e);
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

        document.addEventListener('DOMContentLoaded', () => {
            loadSelects();
            fetchLabResults();
        });
    </script>
</body>

</html>
