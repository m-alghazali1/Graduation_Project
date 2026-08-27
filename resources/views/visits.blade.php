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
                <a class="nav-item" data-page="dashboard" href="/dashboard"><i class="fas fa-chart-pie"></i> لوحة التحكم</a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">الاستقبال والكشف</div>
                <a class="nav-item" data-page="patients" href="/dashboard/persons"><i class="fas fa-users"></i> المرضى</a>
                <a class="nav-item active" data-page="visits" href="/dashboard/visits"><i class="fas fa-stethoscope"></i> الزيارات والكشف الطبي</a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">المختبر</div>
                <a class="nav-item" data-page="lab" href="/dashboard/lab-results"><i class="fas fa-vials"></i> طلبات ونتائج التحاليل</a>
                <a class="nav-item" data-page="analysis" href="/dashboard/analyses"><i class="fas fa-flask"></i> أنواع التحاليل</a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">الصيدلية</div>
                <a class="nav-item" data-page="pharmacy" href="/dashboard/pharmacy"><i class="fas fa-prescription-bottle-alt"></i> صرف الوصفات الطبية</a>
                <a class="nav-item" data-page="medicines" href="/dashboard/medicine-types"><i class="fas fa-pills"></i> مخزون الأدوية</a>
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
                    <div class="avatar">ط</div>
                    <div>
                        <div class="user-name">جاري التحميل...</div>
                        <div class="user-role">طبيب</div>
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
                <div class="breadcrumb"><a href="/dashboard">الرئيسية</a><span>/</span><span class="current">الزيارات والكشف الطبي</span></div>
                <h1>قائمة الزيارات وعيادة الطبيب</h1>
            </div>
            <div class="topbar-left"><span class="date" id="currentDate"></span></div>
        </div>

        <div class="table-card">
            <div class="table-toolbar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="بحث باسم المريض أو الطبيب أو التشخيص..." oninput="renderTable()">
                </div>
                <div style="display:flex; gap:8px;">
                    <select id="filterStatus" onchange="renderTable()" style="padding:8px 12px; border:1px solid var(--gray-200); border-radius:var(--radius-sm); font-family:'Cairo',sans-serif;">
                        <option value="">جميع الحالات</option>
                        <option value="waiting">في الانتظار</option>
                        <option value="in_progress">قيد الكشف</option>
                        <option value="completed">مكتملة</option>
                    </select>
                    <button class="btn btn-primary btn-sm" onclick="openAddModal()"><i class="fas fa-plus"></i> حجز زيارة جديدة</button>
                </div>
            </div>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>موعد الزيارة</th>
                            <th>المريض</th>
                            <th>الطبيب المعالج</th>
                            <th>العلامات الحيوية</th>
                            <th>التشخيص</th>
                            <th>الحالة</th>
                            <th>الإجراءات السريرية</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr><td colspan="8" style="text-align:center; padding:30px;">جاري تحميل الزيارات...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- مودال حجز زيارة جديدة (للاستقبال) -->
    <div class="modal-overlay" id="bookModal">
        <div class="modal" style="width: 580px;">
            <div class="modal-header">
                <h3>حجز زيارة جديدة للمريض</h3>
                <button class="modal-close" onclick="closeModal('bookModal')"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px;">
                    <div class="form-group" style="grid-column: 1 / -1;">
                        <label>المريض <span style="color:red;">*</span></label>
                        <select id="bookPatient" style="width:100%; padding:10px; border:1px solid var(--gray-200); border-radius:4px;" required>
                            <option value="">اختر المريض</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>الطبيب المعالج</label>
                        <select id="bookDoctor" style="width:100%; padding:10px; border:1px solid var(--gray-200); border-radius:4px;">
                            <option value="">اختر الطبيب</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>موعد الزيارة <span style="color:red;">*</span></label>
                        <input type="datetime-local" id="bookDate" style="width:100%; padding:10px; border:1px solid var(--gray-200); border-radius:4px;" required>
                    </div>
                    <div class="form-group">
                        <label>ضغط الدم (mmHg)</label>
                        <input type="text" id="bookBP" placeholder="مثال: 120/80" style="width:100%; padding:10px; border:1px solid var(--gray-200); border-radius:4px;">
                    </div>
                    <div class="form-group">
                        <label>الوزن (كغ)</label>
                        <input type="number" step="0.1" id="bookWeight" placeholder="مثال: 72" style="width:100%; padding:10px; border:1px solid var(--gray-200); border-radius:4px;">
                    </div>
                    <div class="form-group">
                        <label>الحرارة (°م)</label>
                        <input type="number" step="0.1" id="bookTemp" placeholder="مثال: 37" style="width:100%; padding:10px; border:1px solid var(--gray-200); border-radius:4px;">
                    </div>
                    <div class="form-group">
                        <label>الحالة الأولية</label>
                        <select id="bookStatus" style="width:100%; padding:10px; border:1px solid var(--gray-200); border-radius:4px;">
                            <option value="waiting">في الانتظار</option>
                            <option value="in_progress">قيد الكشف فوراً</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('bookModal')">إلغاء</button>
                <button class="btn btn-primary" onclick="saveNewVisit()"><i class="fas fa-save"></i> حفظ الموعد</button>
            </div>
        </div>
    </div>

    <!-- مودال مساحة الكشف الطبي السريري المتكامل (Doctor Consultation Station) -->
    <div class="modal-overlay" id="consultationModal">
        <div class="modal" style="width: 850px; max-width: 95vw;">
            <div class="modal-header">
                <div>
                    <h3 id="consultTitle" style="font-size:18px;">الكشف الطبي والتشخيص</h3>
                    <span id="consultPatientSub" style="font-size:13px; color:var(--primary-dark); font-weight:600;"></span>
                </div>
                <div style="display:flex; gap:8px;">
                    <button class="btn btn-sm btn-secondary" onclick="printConsultation()"><i class="fas fa-print"></i> طباعة الروشتة</button>
                    <button class="modal-close" onclick="closeModal('consultationModal')"><i class="fas fa-times"></i></button>
                </div>
            </div>
            <div class="modal-body" style="padding-top:12px;">
                <!-- بطاقة العلامات الحيوية والتشخيص -->
                <div style="background:var(--gray-50); padding:16px; border-radius:var(--radius-sm); border:1px solid var(--gray-200); margin-bottom:16px;">
                    <h4 style="font-size:14px; margin-bottom:10px; color:var(--gray-800);"><i class="fas fa-heartbeat" style="color:var(--primary);"></i> العلامات الحيوية والتشخيص</h4>
                    <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:12px; margin-bottom:12px;">
                        <div>
                            <label style="font-size:12px; font-weight:600;">ضغط الدم</label>
                            <input type="text" id="consultBP" placeholder="120/80" style="width:100%; padding:8px; border:1px solid var(--gray-200); border-radius:4px; font-size:13px;">
                        </div>
                        <div>
                            <label style="font-size:12px; font-weight:600;">الوزن (كغ)</label>
                            <input type="number" step="0.1" id="consultWeight" placeholder="70" style="width:100%; padding:8px; border:1px solid var(--gray-200); border-radius:4px; font-size:13px;">
                        </div>
                        <div>
                            <label style="font-size:12px; font-weight:600;">الحرارة (°م)</label>
                            <input type="number" step="0.1" id="consultTemp" placeholder="37" style="width:100%; padding:8px; border:1px solid var(--gray-200); border-radius:4px; font-size:13px;">
                        </div>
                    </div>
                    <div style="margin-bottom:12px;">
                        <label style="font-size:12px; font-weight:600; color:var(--gray-800);">التشخيص الطبي (Clinical Diagnosis) <span style="color:red;">*</span></label>
                        <input type="text" id="consultDiagnosis" placeholder="اكتب التشخيص النهائي أو المبدئي للحالة..." style="width:100%; padding:8px; border:1px solid var(--gray-200); border-radius:4px; font-size:13px;">
                    </div>
                    <div>
                        <label style="font-size:12px; font-weight:600; color:var(--gray-800);">ملاحظات الطبيب والأعراض</label>
                        <textarea id="consultNotes" rows="2" placeholder="ملاحظات الفحص الإكلينيكي..." style="width:100%; padding:8px; border:1px solid var(--gray-200); border-radius:4px; font-size:13px;"></textarea>
                    </div>
                </div>

                <!-- قسم التحاليل المخبرية للزيارة -->
                <div style="background:#fcfaff; padding:16px; border-radius:var(--radius-sm); border:1px solid #e9d5ff; margin-bottom:16px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                        <h4 style="font-size:14px; color:#7e22ce;"><i class="fas fa-flask"></i> طلبات ونتائج التحاليل المخبرية</h4>
                    </div>
                    <div style="display:flex; gap:8px; margin-bottom:12px;">
                        <select id="selectTestType" style="flex:1; padding:8px; border:1px solid #d8b4fe; border-radius:4px; font-size:13px;">
                            <option value="">اختر التحليل المطلوب للمريض...</option>
                        </select>
                        <button class="btn btn-sm btn-primary" onclick="addLabOrderToVisit()" style="width:auto; background:#7e22ce;">
                            <i class="fas fa-plus"></i> إرسال طلب للمختبر
                        </button>
                    </div>
                    <div id="visitLabResultsList">
                        <!-- قائمة التحاليل المطلوبة لهذه الزيارة -->
                    </div>
                </div>

                <!-- قسم الوصفة الطبية (الصيدلية) -->
                <div style="background:#f0fdf4; padding:16px; border-radius:var(--radius-sm); border:1px solid #bbf7d0; margin-bottom:16px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                        <h4 style="font-size:14px; color:#15803d;"><i class="fas fa-prescription-bottle-alt"></i> الوصفة الطبية (الروشتة والعلاج)</h4>
                    </div>
                    <div style="display:grid; grid-template-columns: 2fr 1fr 2fr auto; gap:8px; margin-bottom:12px;">
                        <select id="selectMedicine" style="padding:8px; border:1px solid #86efac; border-radius:4px; font-size:13px;">
                            <option value="">اختر الدواء...</option>
                        </select>
                        <input type="number" id="inputRxQty" value="1" min="1" placeholder="الكمية" style="padding:8px; border:1px solid #86efac; border-radius:4px; font-size:13px;">
                        <input type="text" id="inputRxInstruct" placeholder="إرشادات الجرعة (مثال: حبة 3 مرات يومياً)" style="padding:8px; border:1px solid #86efac; border-radius:4px; font-size:13px;">
                        <button class="btn btn-sm btn-success" onclick="addMedicineToPrescription()" style="width:auto;">
                            <i class="fas fa-plus"></i> إضافة للروشتة
                        </button>
                    </div>
                    <div id="visitPrescriptionList">
                        <!-- قائمة الأدوية الموصوفة -->
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <label style="font-size:13px; font-weight:600; margin-left:6px;">حالة الزيارة:</label>
                    <select id="consultStatus" style="padding:6px 12px; border:1px solid var(--gray-300); border-radius:4px;">
                        <option value="in_progress">قيد الكشف</option>
                        <option value="completed">اكتمال وإنهاء الكشف</option>
                        <option value="waiting">إرجاع للانتظار</option>
                    </select>
                </div>
                <div style="display:flex; gap:8px;">
                    <button class="btn btn-secondary" onclick="closeModal('consultationModal')">إغلاق</button>
                    <button class="btn btn-primary" onclick="saveConsultation(true)" style="width:auto;">
                        <i class="fas fa-check-circle"></i> حفظ وإنهاء الكشف
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- مودال السجل الطبي التاريخي للمريض -->
    <div class="modal-overlay" id="historyModal">
        <div class="modal" style="width: 750px;">
            <div class="modal-header">
                <h3 id="historyPatientName">السجل الطبي للمريض</h3>
                <button class="modal-close" onclick="closeModal('historyModal')"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body" id="historyTimeline" style="max-height:65vh; overflow-y:auto;">
                <!-- تايم لاين الزيارات السابقة -->
            </div>
        </div>
    </div>

    <!-- منطقة الطباعة المخفية للروشتة -->
    <div id="printSection" class="print-area" style="display:none;">
        <div style="text-align:center; border-bottom:2px solid #000; padding-bottom:15px; margin-bottom:20px;">
            <h2 style="font-size:22px; margin-bottom:4px;">مركز الرعاية الصحية الأولية</h2>
            <p style="font-size:14px;">وصفة طبية وتقرير كشف إكلينيكي</p>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:20px; font-size:14px;" id="printHeaderInfo"></div>
        <div style="margin-bottom:20px;">
            <h4 style="border-bottom:1px solid #ccc; padding-bottom:4px; margin-bottom:8px;">التشخيص الطبي:</h4>
            <p id="printDiagnosisText" style="font-size:14px; font-weight:bold;"></p>
        </div>
        <div style="margin-bottom:20px;">
            <h4 style="border-bottom:1px solid #ccc; padding-bottom:4px; margin-bottom:8px;">الوصفة العلاجية (Rx):</h4>
            <div id="printMedList"></div>
        </div>
        <div style="display:flex; justify-content:space-between; margin-top:40px; padding-top:20px; border-top:1px dashed #999;">
            <div>توقيع الطبيب المعالج: ________________</div>
            <div>ختم المركز الطبي: ________________</div>
        </div>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        let visitsData = [];
        let patientsData = [];
        let doctorsData = [];
        let testTypesData = [];
        let medicinesData = [];
        let activeVisitId = null;
        let activeVisitObject = null;

        const STATUS_MAP = {
            waiting: { text: 'في الانتظار', cls: 'waiting' },
            in_progress: { text: 'قيد الكشف', cls: 'in_progress' },
            completed: { text: 'مكتملة', cls: 'completed' },
            cancelled: { text: 'ملغية', cls: 'cancelled' }
        };

        async function initData() {
            const [vRes, pRes, uRes, tRes, mRes] = await Promise.all([
                apiCall('/visits'),
                apiCall('/persons'),
                apiCall('/users'),
                apiCall('/test-types'),
                apiCall('/medicines')
            ]);

            if (vRes.ok) visitsData = vRes.data;
            if (pRes.ok) patientsData = pRes.data;
            if (uRes.ok) doctorsData = (uRes.data || []).filter(u => u.role === 'doctor');
            if (tRes.ok) testTypesData = tRes.data;
            if (mRes.ok) medicinesData = mRes.data;

            populateSelects();
            renderTable();
        }

        function populateSelects() {
            const pSel = document.getElementById('bookPatient');
            pSel.innerHTML = '<option value="">اختر المريض</option>' +
                patientsData.map(p => `<option value="${p.id}">${p.full_name} (${p.national_id})</option>`).join('');

            const dSel = document.getElementById('bookDoctor');
            dSel.innerHTML = '<option value="">اختر الطبيب</option>' +
                doctorsData.map(d => `<option value="${d.id}">${d.person ? d.person.full_name : d.email}</option>`).join('');

            const tSel = document.getElementById('selectTestType');
            tSel.innerHTML = '<option value="">اختر التحليل المطلوب للمريض...</option>' +
                testTypesData.map(t => `<option value="${t.id}">${t.name} ${t.unit ? '(' + t.unit + ')' : ''}</option>`).join('');

            const mSel = document.getElementById('selectMedicine');
            mSel.innerHTML = '<option value="">اختر الدواء...</option>' +
                medicinesData.map(m => `<option value="${m.id}">${m.name} (${m.stock_quantity} متوفر)</option>`).join('');
        }

        function renderTable() {
            const q = (document.getElementById('searchInput')?.value || '').trim().toLowerCase();
            const statusFilter = document.getElementById('filterStatus')?.value || '';

            const items = visitsData.filter(v => {
                if (statusFilter && v.status !== statusFilter) return false;
                const pName = (v.person?.full_name || '').toLowerCase();
                const dName = (v.doctor?.person?.full_name || v.doctor?.email || '').toLowerCase();
                const diag = (v.diagnosis || '').toLowerCase();
                return pName.includes(q) || dName.includes(q) || diag.includes(q);
            });

            const tbody = document.getElementById('tableBody');
            if (!items.length) {
                tbody.innerHTML = '<tr><td colspan="8" style="text-align:center; padding:30px; color:var(--gray-400);">لا توجد زيارات مطابقة</td></tr>';
                return;
            }

            tbody.innerHTML = items.map((v, idx) => {
                const st = STATUS_MAP[v.status] || { text: v.status, cls: 'inactive' };
                const pName = v.person ? v.person.full_name : '---';
                const dName = v.doctor && v.doctor.person ? v.doctor.person.full_name : (v.doctor ? v.doctor.email : 'طبيب المركز');
                const vitals = [];
                if (v.blood_pressure) vitals.push(`ضغط: ${v.blood_pressure}`);
                if (v.temperature) vitals.push(`حرارة: ${v.temperature}°`);
                if (v.weight) vitals.push(`وزن: ${v.weight}كغ`);

                return `
                    <tr>
                        <td style="color:var(--gray-400); font-weight:600;">${idx + 1}</td>
                        <td style="font-size:13px; white-space:nowrap;">${new Date(v.appointment_date).toLocaleDateString('ar-SA')} - ${new Date(v.appointment_date).toLocaleTimeString('ar-SA', {hour:'2-digit', minute:'2-digit'})}</td>
                        <td><strong>${pName}</strong></td>
                        <td>${dName}</td>
                        <td style="font-size:12px; color:var(--gray-600);">${vitals.join(' | ') || '---'}</td>
                        <td><strong style="color:var(--primary-dark); font-size:13px;">${v.diagnosis || '<span style="color:gray; font-weight:normal;">لم يحدد</span>'}</strong></td>
                        <td><span class="status-badge ${st.cls}">${st.text}</span></td>
                        <td>
                            <div style="display:flex; gap:6px;">
                                <button class="btn btn-sm btn-primary" onclick="openConsultation(${v.id})" style="padding:4px 8px; font-size:12px;">
                                    <i class="fas fa-stethoscope"></i> كشف
                                </button>
                                <button class="btn btn-sm btn-secondary" onclick="viewHistory(${v.person_id})" title="السجل المرضي" style="padding:4px 8px; font-size:12px;">
                                    <i class="fas fa-history"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="deleteVisit(${v.id})" title="حذف" style="padding:4px 8px; font-size:12px;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function openAddModal() {
            document.getElementById('bookPatient').value = '';
            document.getElementById('bookDoctor').value = '';
            const now = new Date();
            now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
            document.getElementById('bookDate').value = now.toISOString().slice(0, 16);
            document.getElementById('bookBP').value = '';
            document.getElementById('bookWeight').value = '';
            document.getElementById('bookTemp').value = '';
            document.getElementById('bookStatus').value = 'waiting';
            openModal('bookModal');
        }

        async function saveNewVisit() {
            const patientId = document.getElementById('bookPatient').value;
            const dateVal = document.getElementById('bookDate').value;
            if (!patientId || !dateVal) {
                showToast('يرجى اختيار المريض وتحديد موعد الزيارة', 'error');
                return;
            }

            const data = {
                person_id: patientId,
                doctor_id: document.getElementById('bookDoctor').value || null,
                appointment_date: dateVal.replace('T', ' '),
                blood_pressure: document.getElementById('bookBP').value || null,
                weight: document.getElementById('bookWeight').value || null,
                temperature: document.getElementById('bookTemp').value || null,
                status: document.getElementById('bookStatus').value
            };

            const res = await apiCall('/visits', 'POST', data);
            if (res.ok) {
                showToast('تم تسجيل الزيارة بنجاح');
                closeModal('bookModal');
                initData();
            } else {
                showToast(res.data?.message || 'فشل تسجيل الزيارة', 'error');
            }
        }

        // فتح شاشة الكشف الطبي السريري المتكامل
        async function openConsultation(visitId) {
            activeVisitId = visitId;
            const res = await apiCall(`/visits/${visitId}`);
            if (!res.ok || !res.data) {
                showToast('تعذر جلب تفاصيل الزيارة', 'error');
                return;
            }

            const v = res.data;
            activeVisitObject = v;

            document.getElementById('consultTitle').textContent = `الكشف الطبي - زيارة رقم #${v.id}`;
            document.getElementById('consultPatientSub').textContent = `المريض: ${v.person?.full_name || '---'} | الهوية: ${v.person?.national_id || '---'} | الجنس: ${v.person?.gender === 'male' ? 'ذكر' : 'أنثى'}`;
            document.getElementById('consultBP').value = v.blood_pressure || '';
            document.getElementById('consultWeight').value = v.weight || '';
            document.getElementById('consultTemp').value = v.temperature || '';
            document.getElementById('consultDiagnosis').value = v.diagnosis || '';
            document.getElementById('consultNotes').value = v.doctor_notes || '';
            document.getElementById('consultStatus').value = v.status === 'waiting' ? 'in_progress' : v.status;

            renderConsultLabResults(v.lab_results || []);
            renderConsultPrescriptions(v.prescription_items || []);

            openModal('consultationModal');
        }

        function renderConsultLabResults(list) {
            const container = document.getElementById('visitLabResultsList');
            if (!list.length) {
                container.innerHTML = '<p style="font-size:12px; color:gray; text-align:center; padding:8px;">لم يتم طلب أي تحاليل مخبرية لهذه الزيارة بعد.</p>';
                return;
            }

            container.innerHTML = `
                <table style="width:100%; font-size:12px; background:#fff; border-radius:4px; border-collapse:collapse;">
                    <thead>
                        <tr style="background:#f3e8ff;">
                            <th style="padding:6px;">التحليل</th>
                            <th style="padding:6px;">المدى المرجعي</th>
                            <th style="padding:6px;">قيمة النتيجة</th>
                            <th style="padding:6px;">ملاحظات المختبر</th>
                            <th style="padding:6px;">الحالة</th>
                            <th style="padding:6px;">حذف</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${list.map(l => {
                            const isDone = l.status === 'completed' || l.status === 'reviewed';
                            const val = l.result_value !== null ? l.result_value : 'بانتظار الفحص';
                            let valColor = 'gray';
                            if (isDone && l.test_type && l.result_value !== null) {
                                const min = l.test_type.min_range;
                                const max = l.test_type.max_range;
                                if ((min !== null && l.result_value < min) || (max !== null && l.result_value > max)) {
                                    valColor = 'red'; // شاذ
                                } else {
                                    valColor = 'green'; // سليم
                                }
                            }
                            return `
                                <tr style="border-bottom:1px solid #f1f5f9;">
                                    <td style="padding:6px;"><strong>${l.test_type ? l.test_type.name : 'تحليل'}</strong></td>
                                    <td style="padding:6px; color:gray;">${l.test_type ? (l.test_type.min_range + ' - ' + l.test_type.max_range + ' ' + (l.test_type.unit || '')) : '---'}</td>
                                    <td style="padding:6px;"><strong style="color:${valColor}; font-size:13px;">${val}</strong></td>
                                    <td style="padding:6px; color:gray;">${l.lab_notes || '---'}</td>
                                    <td style="padding:6px;"><span class="status-badge ${l.status}">${l.status === 'completed' ? 'مكتمل' : 'معلق بالمختبر'}</span></td>
                                    <td style="padding:6px;">
                                        <button onclick="removeLabOrder(${l.id})" style="background:none; border:none; color:red; cursor:pointer;" title="إلغاء الطلب"><i class="fas fa-trash"></i></button>
                                    </td>
                                </tr>
                            `;
                        }).join('')}
                    </tbody>
                </table>
            `;
        }

        function renderConsultPrescriptions(list) {
            const container = document.getElementById('visitPrescriptionList');
            if (!list.length) {
                container.innerHTML = '<p style="font-size:12px; color:gray; text-align:center; padding:8px;">لم تتم إضافة أدوية للوصفة بعد.</p>';
                return;
            }

            container.innerHTML = `
                <table style="width:100%; font-size:12px; background:#fff; border-radius:4px; border-collapse:collapse;">
                    <thead>
                        <tr style="background:#dcfce7;">
                            <th style="padding:6px;">الدواء</th>
                            <th style="padding:6px;">الكمية</th>
                            <th style="padding:6px;">التعليمات والجرعة</th>
                            <th style="padding:6px;">حالة الصرف (الصيدلية)</th>
                            <th style="padding:6px;">حذف</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${list.map(rx => `
                            <tr style="border-bottom:1px solid #f1f5f9;">
                                <td style="padding:6px;"><strong>${rx.medicine ? rx.medicine.name : 'دواء'}</strong></td>
                                <td style="padding:6px; font-weight:bold;">${rx.prescribed_quantity}</td>
                                <td style="padding:6px;">${rx.instructions || rx.dosage || 'حسب الإرشادات'}</td>
                                <td style="padding:6px;"><span class="status-badge ${rx.is_dispensed ? 'dispensed' : 'undispensed'}">${rx.is_dispensed ? 'تم الصرف' : 'بانتظار الصرف'}</span></td>
                                <td style="padding:6px;">
                                    ${!rx.is_dispensed ? `<button onclick="removePrescriptionItem(${rx.id})" style="background:none; border:none; color:red; cursor:pointer;" title="حذف الدواء"><i class="fas fa-trash"></i></button>` : '<i class="fas fa-lock" style="color:gray;"></i>'}
                                </td>
                            </tr>
                        `).join('')}
                    </tbody>
                </table>
            `;
        }

        async function addLabOrderToVisit() {
            const testTypeId = document.getElementById('selectTestType').value;
            if (!testTypeId) { showToast('يرجى اختيار نوع التحليل', 'error'); return; }

            const res = await apiCall('/lab-results', 'POST', {
                visit_id: activeVisitId,
                test_type_id: testTypeId,
                status: 'pending'
            });

            if (res.ok) {
                showToast('تم إرسال طلب التحليل للمختبر بنجاح');
                document.getElementById('selectTestType').value = '';
                refreshActiveConsultation();
            } else {
                showToast(res.data?.message || 'فشل إرسال التحليل', 'error');
            }
        }

        async function addMedicineToPrescription() {
            const medId = document.getElementById('selectMedicine').value;
            const qty = document.getElementById('inputRxQty').value || 1;
            const instruct = document.getElementById('inputRxInstruct').value.trim();

            if (!medId) { showToast('يرجى اختيار الدواء المطلوب', 'error'); return; }

            const res = await apiCall('/prescriptions', 'POST', {
                visit_id: activeVisitId,
                medicine_id: medId,
                prescribed_quantity: parseInt(qty),
                instructions: instruct
            });

            if (res.ok) {
                showToast('تمت إضافة الدواء للوصفة الطبية');
                document.getElementById('selectMedicine').value = '';
                document.getElementById('inputRxInstruct').value = '';
                refreshActiveConsultation();
            } else {
                showToast(res.data?.message || 'فشل إضافة الدواء', 'error');
            }
        }

        async function removeLabOrder(id) {
            if (!confirm('هل تريد إلغاء طلب هذا التحليل؟')) return;
            const res = await apiCall(`/lab-results/${id}`, 'DELETE');
            if (res.ok) {
                showToast('تم حذف طلب التحليل');
                refreshActiveConsultation();
            }
        }

        async function removePrescriptionItem(id) {
            if (!confirm('هل تريد إزالة هذا الدواء من الوصفة؟')) return;
            const res = await apiCall(`/prescriptions/${id}`, 'DELETE');
            if (res.ok) {
                showToast('تم حذف الدواء من الوصفة');
                refreshActiveConsultation();
            }
        }

        async function refreshActiveConsultation() {
            const res = await apiCall(`/visits/${activeVisitId}`);
            if (res.ok && res.data) {
                activeVisitObject = res.data;
                renderConsultLabResults(res.data.lab_results || []);
                renderConsultPrescriptions(res.data.prescription_items || []);
            }
        }

        async function saveConsultation(closeAfter = true) {
            const diagnosis = document.getElementById('consultDiagnosis').value.trim();
            const notes = document.getElementById('consultNotes').value.trim();
            const status = document.getElementById('consultStatus').value;

            const data = {
                blood_pressure: document.getElementById('consultBP').value.trim() || null,
                weight: document.getElementById('consultWeight').value || null,
                temperature: document.getElementById('consultTemp').value || null,
                diagnosis: diagnosis || null,
                doctor_notes: notes || null,
                status: status
            };

            const res = await apiCall(`/visits/${activeVisitId}`, 'PUT', data);
            if (res.ok) {
                showToast('تم حفظ الكشف الطبي بنجاح!');
                if (closeAfter) closeModal('consultationModal');
                initData();
            } else {
                showToast(res.data?.message || 'فشل حفظ الكشف', 'error');
            }
        }

        // عرض السجل الطبي التاريخي للمريض
        async function viewHistory(personId) {
            const res = await apiCall(`/persons/${personId}/history`);
            if (res.ok && res.data) {
                const p = res.data.patient;
                document.getElementById('historyPatientName').textContent = `السجل المرضي: ${p.full_name} (${p.national_id})`;

                const list = res.data.history;
                const container = document.getElementById('historyTimeline');
                if (!list.length) {
                    container.innerHTML = '<p style="text-align:center; padding:30px; color:gray;">لا توجد سجلات كشف سابقة لهذا المريض.</p>';
                } else {
                    container.innerHTML = list.map(h => `
                        <div style="border-right:3px solid var(--primary); padding-right:16px; margin-bottom:20px; position:relative;">
                            <div style="font-size:13px; color:var(--primary-dark); font-weight:bold;">
                                <i class="fas fa-calendar-alt"></i> ${new Date(h.appointment_date).toLocaleDateString('ar-SA')} - د. ${h.doctor?.person?.full_name || 'طبيب المركز'}
                            </div>
                            <div style="font-size:14px; font-weight:bold; margin-top:4px;">التشخيص: ${h.diagnosis || 'فحص عام'}</div>
                            ${h.doctor_notes ? `<p style="font-size:13px; color:#555; margin-top:4px;">${h.doctor_notes}</p>` : ''}
                            
                            <!-- التحاليل السابقة -->
                            ${h.lab_results && h.lab_results.length ? `
                                <div style="margin-top:6px; font-size:12px; background:#f9fafb; padding:6px; border-radius:4px;">
                                    <strong>الفحوصات:</strong> ${h.lab_results.map(l => `${l.test_type?.name || 'تحليل'}: ${l.result_value || 'معلق'}`).join(' | ')}
                                </div>
                            ` : ''}

                            <!-- الأدوية السابقة -->
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

        function printConsultation() {
            if (!activeVisitObject) return;
            const v = activeVisitObject;
            const p = v.person;

            document.getElementById('printHeaderInfo').innerHTML = `
                <div><strong>اسم المريض:</strong> ${p ? p.full_name : '---'}</div>
                <div><strong>الرقم الوطني:</strong> ${p ? p.national_id : '---'}</div>
                <div><strong>تاريخ الكشف:</strong> ${new Date(v.appointment_date).toLocaleDateString('ar-SA')}</div>
                <div><strong>الطبيب المعالج:</strong> ${v.doctor?.person?.full_name || 'طبيب المركز'}</div>
            `;

            document.getElementById('printDiagnosisText').textContent = v.diagnosis || 'كشف طبي عام ومتابعة سريرية';

            const rxList = v.prescription_items || [];
            if (rxList.length) {
                document.getElementById('printMedList').innerHTML = rxList.map((rx, i) => `
                    <div style="font-size:14px; margin-bottom:8px; padding-bottom:4px; border-bottom:1px solid #eee;">
                        <strong>${i+1}. ${rx.medicine ? rx.medicine.name : 'دواء'}</strong> - كمية: ${rx.prescribed_quantity}
                        <div style="font-size:12px; color:#444;">الجرعة والتعليمات: ${rx.instructions || rx.dosage || 'حسب الإرشادات الطبية'}</div>
                    </div>
                `).join('');
            } else {
                document.getElementById('printMedList').innerHTML = '<p style="font-size:13px; color:gray;">لا توجد أدوية مقررة في هذه الزيارة.</p>';
            }

            window.print();
        }

        async function deleteVisit(id) {
            if (!confirm('هل أنت متأكد من رغبتك في حذف هذه الزيارة؟')) return;
            const res = await apiCall(`/visits/${id}`, 'DELETE');
            if (res.ok) {
                showToast('تم حذف الزيارة بنجاح');
                initData();
            } else {
                showToast(res.data?.message || 'فشل الحذف', 'error');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            initData();
            setInterval(initData, 20000);
        });
    </script>
</body>
</html>
