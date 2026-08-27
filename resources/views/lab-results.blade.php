<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المختبر والفحوصات الطبية - إدارة النقاط الطبية</title>
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
                <a class="nav-item" data-page="dashboard" href="/dashboard"><i class="fas fa-chart-pie"></i> لوحة التحكم</a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">المختبر</div>
                <a class="nav-item active" data-page="lab" href="/dashboard/lab-results"><i class="fas fa-vials"></i> طلبات ونتائج التحاليل</a>
                <a class="nav-item" data-page="analysis" href="/dashboard/analyses"><i class="fas fa-flask"></i> أنواع التحاليل والمدى الطبيعي</a>
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
                        <div class="user-role">فني مختبر</div>
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
                <div class="breadcrumb"><a href="/dashboard">الرئيسية</a><span>/</span><span class="current">المختبر والتحاليل</span></div>
                <h1>شاشة المختبر والفحوصات الطبية</h1>
            </div>
            <div class="topbar-left"><span class="date" id="currentDate"></span></div>
        </div>

        <!-- التبويبات -->
        <div class="clinical-tabs">
            <button class="clinical-tab active" id="tabPending" onclick="switchTab('pending')">
                <i class="fas fa-hourglass-half"></i> طلبات التحاليل الواردة المعلقة (<span id="pendingCount">0</span>)
            </button>
            <button class="clinical-tab" id="tabCompleted" onclick="switchTab('completed')">
                <i class="fas fa-check-circle"></i> التحاليل المكتملة والمعتمدة
            </button>
        </div>

        <div class="table-card">
            <div class="table-toolbar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="بحث باسم المريض أو الفحص..." oninput="renderTable()">
                </div>
                <div style="display:flex; gap:8px;">
                    <button class="btn btn-secondary btn-sm" onclick="fetchLabData()"><i class="fas fa-sync"></i> تحديث</button>
                    <button class="btn btn-primary btn-sm" onclick="openManualOrderModal()"><i class="fas fa-plus"></i> إضافة فحص مباشر</button>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>المريض</th>
                            <th>الطبيب المحول</th>
                            <th>نوع التحليل</th>
                            <th>المدى الطبيعي المرجعي</th>
                            <th>نتيجة الفحص</th>
                            <th>ملاحظات المختبر</th>
                            <th>الحالة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr><td colspan="9" style="text-align:center; padding:30px;">جاري تحميل بيانات الفحوصات...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- مودال إدخال نتيجة الفحص المخبري -->
    <div class="modal-overlay" id="resultModal">
        <div class="modal" style="width: 540px;">
            <div class="modal-header">
                <h3>إدخال نتيجة الفحص المخبري</h3>
                <button class="modal-close" onclick="closeModal('resultModal')"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div style="background:var(--gray-50); padding:12px; border-radius:4px; margin-bottom:16px; font-size:13px;">
                    <div><strong>المريض:</strong> <span id="resPatientName">---</span></div>
                    <div style="margin-top:4px;"><strong>نوع التحليل:</strong> <span id="resTestName" style="color:#7e22ce; font-weight:bold;">---</span></div>
                    <div style="margin-top:4px;"><strong>المدى المرجعي الطبيعي:</strong> <span id="resNormalRange" style="color:var(--primary-dark); font-weight:bold;">---</span></div>
                </div>

                <div class="form-group">
                    <label>قيمة النتيجة (Result Value) <span style="color:red;">*</span></label>
                    <input type="number" step="0.01" id="resValueInput" placeholder="أدخل القراءة الرقمية..." oninput="checkRangeStatus()" style="width:100%; padding:10px; border:1px solid var(--gray-200); border-radius:4px; font-size:16px; font-weight:bold;" required>
                    <div id="rangeAlert" style="margin-top:6px; font-size:13px; font-weight:bold; display:none;"></div>
                </div>

                <div class="form-group">
                    <label>ملاحظات فني المختبر</label>
                    <textarea id="resNotesInput" rows="3" placeholder="ملاحظات العينة أو تعليق المختبر..." style="width:100%; padding:10px; border:1px solid var(--gray-200); border-radius:4px; font-size:13px;"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('resultModal')">إلغاء</button>
                <button class="btn btn-primary" onclick="saveLabResult()"><i class="fas fa-save"></i> اعتماد وحفظ النتيجة</button>
            </div>
        </div>
    </div>

    <!-- مودال إضافة فحص يدوي مباشر -->
    <div class="modal-overlay" id="manualOrderModal">
        <div class="modal" style="width: 520px;">
            <div class="modal-header">
                <h3>تسجيل طلب فحص مخبري</h3>
                <button class="modal-close" onclick="closeModal('manualOrderModal')"><i class="fas fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>الزيارة (المريض) <span style="color:red;">*</span></label>
                    <select id="manualVisitSelect" style="width:100%; padding:10px; border:1px solid var(--gray-200); border-radius:4px;">
                        <option value="">اختر الزيارة...</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>نوع التحليل <span style="color:red;">*</span></label>
                    <select id="manualTestSelect" style="width:100%; padding:10px; border:1px solid var(--gray-200); border-radius:4px;">
                        <option value="">اختر نوع التحليل...</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('manualOrderModal')">إلغاء</button>
                <button class="btn btn-primary" onclick="createManualOrder()"><i class="fas fa-plus"></i> إضافة الطلب</button>
            </div>
        </div>
    </div>

    <!-- قسم الطباعة لتقرير التحليل المخبري -->
    <div id="printLabSection" class="print-area" style="display:none;">
        <div style="text-align:center; border-bottom:2px solid #000; padding-bottom:15px; margin-bottom:20px;">
            <h2 style="font-size:22px; margin-bottom:4px;">مركز الرعاية الصحية - قسم المختبر والتحاليل الطبية</h2>
            <p style="font-size:14px;">تقرير نتيجة فحص مخبري رسمي</p>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:20px; font-size:14px;" id="printLabHeader"></div>
        <table style="width:100%; border-collapse:collapse; margin-bottom:20px; font-size:14px;">
            <thead>
                <tr style="border-bottom:2px solid #333; background:#f0f0f0;">
                    <th style="padding:8px; text-align:right;">الفحص المخبري</th>
                    <th style="padding:8px; text-align:center;">النتيجة</th>
                    <th style="padding:8px; text-align:center;">المدى الطبيعي المرجعي</th>
                    <th style="padding:8px; text-align:right;">الملاحظات</th>
                </tr>
            </thead>
            <tbody id="printLabBody"></tbody>
        </table>
        <div style="display:flex; justify-content:space-between; margin-top:50px; padding-top:20px; border-top:1px dashed #999;">
            <div>توقيع فني المختبر: ________________</div>
            <div>ختم المختبر الطبي: ________________</div>
        </div>
    </div>

    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        let currentTab = 'pending';
        let labResultsList = [];
        let testTypesList = [];
        let visitsList = [];
        let activeLabItem = null;

        async function fetchLabData() {
            const [lrRes, ttRes, vRes] = await Promise.all([
                apiCall('/lab-results'),
                apiCall('/test-types'),
                apiCall('/visits')
            ]);

            if (lrRes.ok) labResultsList = lrRes.data;
            if (ttRes.ok) testTypesList = ttRes.data;
            if (vRes.ok) visitsList = vRes.data;

            const pendingCount = labResultsList.filter(l => l.status === 'pending').length;
            document.getElementById('pendingCount').textContent = pendingCount;

            populateSelects();
            renderTable();
        }

        function populateSelects() {
            const vSel = document.getElementById('manualVisitSelect');
            vSel.innerHTML = '<option value="">اختر الزيارة والمريض...</option>' +
                visitsList.map(v => `<option value="${v.id}">${v.person?.full_name || 'زيارة #' + v.id} (${new Date(v.appointment_date).toLocaleDateString('ar-SA')})</option>`).join('');

            const tSel = document.getElementById('manualTestSelect');
            tSel.innerHTML = '<option value="">اختر نوع التحليل...</option>' +
                testTypesList.map(t => `<option value="${t.id}">${t.name} ${t.unit ? '(' + t.unit + ')' : ''}</option>`).join('');
        }

        function switchTab(tab) {
            currentTab = tab;
            document.getElementById('tabPending').classList.toggle('active', tab === 'pending');
            document.getElementById('tabCompleted').classList.toggle('active', tab === 'completed');
            renderTable();
        }

        function renderTable() {
            const q = (document.getElementById('searchInput')?.value || '').trim().toLowerCase();

            const items = labResultsList.filter(item => {
                const isMatchTab = currentTab === 'pending' ? item.status === 'pending' : (item.status === 'completed' || item.status === 'reviewed');
                if (!isMatchTab) return false;

                const pName = (item.visit?.person?.full_name || '').toLowerCase();
                const tName = (item.test_type?.name || '').toLowerCase();
                return pName.includes(q) || tName.includes(q);
            });

            const tbody = document.getElementById('tableBody');
            if (!items.length) {
                const emptyText = currentTab === 'pending' ? 'لا توجد طلبات تحاليل معلقة بانتظار الفحص' : 'لا توجد تحاليل مكتملة مسجلة بعد';
                tbody.innerHTML = `<tr><td colspan="9" style="text-align:center; padding:30px; color:var(--gray-400);">${emptyText}</td></tr>`;
                return;
            }

            tbody.innerHTML = items.map((item, idx) => {
                const pName = item.visit?.person?.full_name || '---';
                const dName = item.visit?.doctor?.person?.full_name || (item.visit?.doctor?.email || 'طبيب المركز');
                const tObj = item.test_type;
                const refRange = tObj ? `${tObj.min_range || 0} - ${tObj.max_range || 0} ${tObj.unit || ''}` : '---';

                let valDisplay = '<span style="color:gray;">بانتظار الفحص</span>';
                if (item.result_value !== null && item.result_value !== undefined) {
                    let isAbnormal = false;
                    if (tObj && tObj.min_range !== null && item.result_value < tObj.min_range) isAbnormal = true;
                    if (tObj && tObj.max_range !== null && item.result_value > tObj.max_range) isAbnormal = true;

                    valDisplay = `<strong style="color:${isAbnormal ? 'red' : 'green'}; font-size:14px;">${item.result_value} ${tObj?.unit || ''}</strong>`;
                }

                return `
                    <tr>
                        <td style="color:var(--gray-400); font-weight:600;">${idx + 1}</td>
                        <td><strong>${pName}</strong></td>
                        <td>${dName}</td>
                        <td><strong style="color:#7e22ce;">${tObj ? tObj.name : 'تحليل #' + item.test_type_id}</strong></td>
                        <td style="font-size:12px; color:var(--gray-600);">${refRange}</td>
                        <td>${valDisplay}</td>
                        <td style="font-size:12px; color:var(--gray-500); max-width:180px;">${item.lab_notes || '---'}</td>
                        <td><span class="status-badge ${item.status}">${item.status === 'completed' ? 'مكتمل' : 'معلق بالمختبر'}</span></td>
                        <td>
                            <div style="display:flex; gap:6px;">
                                ${item.status === 'pending' ? `
                                    <button class="btn btn-sm btn-primary" onclick="openEnterResultModal(${item.id})" style="padding:4px 8px; font-size:12px; background:#7e22ce;">
                                        <i class="fas fa-edit"></i> إدخال النتيجة
                                    </button>
                                ` : `
                                    <button class="btn btn-sm btn-secondary" onclick="openEnterResultModal(${item.id})" title="تعديل النتيجة" style="padding:4px 8px; font-size:12px;">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <button class="btn btn-sm btn-secondary" onclick="printLabReport(${item.id})" title="طباعة التقرير" style="padding:4px 8px; font-size:12px;">
                                        <i class="fas fa-print"></i>
                                    </button>
                                `}
                                <button class="btn btn-sm btn-danger" onclick="deleteLabItem(${item.id})" title="حذف" style="padding:4px 8px; font-size:12px;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function openEnterResultModal(id) {
            const item = labResultsList.find(l => l.id == id);
            if (!item) return;
            activeLabItem = item;

            document.getElementById('resPatientName').textContent = item.visit?.person?.full_name || 'مريض غير محدد';
            document.getElementById('resTestName').textContent = item.test_type ? `${item.test_type.name} (${item.test_type.code || ''})` : 'تحليل #' + item.test_type_id;
            
            const t = item.test_type;
            const refText = t ? `${t.min_range || 0} إلى ${t.max_range || 0} ${t.unit || ''}` : 'غير محدد';
            document.getElementById('resNormalRange').textContent = refText;

            document.getElementById('resValueInput').value = item.result_value !== null ? item.result_value : '';
            document.getElementById('resNotesInput').value = item.lab_notes || '';

            checkRangeStatus();
            openModal('resultModal');
        }

        function checkRangeStatus() {
            const valStr = document.getElementById('resValueInput').value;
            const alertBox = document.getElementById('rangeAlert');
            if (valStr === '' || !activeLabItem || !activeLabItem.test_type) {
                alertBox.style.display = 'none';
                return;
            }

            const val = parseFloat(valStr);
            const t = activeLabItem.test_type;
            const min = t.min_range;
            const max = t.max_range;

            if (min !== null && val < min) {
                alertBox.style.display = 'block';
                alertBox.style.color = '#dc2626';
                alertBox.innerHTML = `<i class="fas fa-arrow-down"></i> القيمة أقل من المدى الطبيعي (${min})`;
            } else if (max !== null && val > max) {
                alertBox.style.display = 'block';
                alertBox.style.color = '#dc2626';
                alertBox.innerHTML = `<i class="fas fa-arrow-up"></i> القيمة أعلى من المدى الطبيعي (${max})`;
            } else {
                alertBox.style.display = 'block';
                alertBox.style.color = '#16a34a';
                alertBox.innerHTML = `<i class="fas fa-check-circle"></i> القيمة ضمن المدى الطبيعي السليم`;
            }
        }

        async function saveLabResult() {
            const val = document.getElementById('resValueInput').value;
            if (val === '') {
                showToast('يرجى كتابة نتيجة التحليل الرقمية', 'error');
                return;
            }

            const data = {
                result_value: parseFloat(val),
                lab_notes: document.getElementById('resNotesInput').value.trim() || null,
                status: 'completed'
            };

            const res = await apiCall(`/lab-results/${activeLabItem.id}`, 'PUT', data);
            if (res.ok) {
                showToast('تم اعتماد نتيجة الفحص بنجاح وإرسالها لملف المريض!');
                closeModal('resultModal');
                fetchLabData();
            } else {
                showToast(res.data?.message || 'فشل حفظ النتيجة', 'error');
            }
        }

        function openManualOrderModal() {
            document.getElementById('manualVisitSelect').value = '';
            document.getElementById('manualTestSelect').value = '';
            openModal('manualOrderModal');
        }

        async function createManualOrder() {
            const vId = document.getElementById('manualVisitSelect').value;
            const tId = document.getElementById('manualTestSelect').value;

            if (!vId || !tId) {
                showToast('يرجى اختيار الزيارة ونوع التحليل', 'error');
                return;
            }

            const res = await apiCall('/lab-results', 'POST', {
                visit_id: vId,
                test_type_id: tId,
                status: 'pending'
            });

            if (res.ok) {
                showToast('تمت إضافة طلب الفحص بنجاح');
                closeModal('manualOrderModal');
                fetchLabData();
            } else {
                showToast(res.data?.message || 'فشل إضافة الفحص', 'error');
            }
        }

        async function deleteLabItem(id) {
            if (!confirm('هل أنت متأكد من حذف هذا التحليل؟')) return;
            const res = await apiCall(`/lab-results/${id}`, 'DELETE');
            if (res.ok) {
                showToast('تم حذف التحليل');
                fetchLabData();
            }
        }

        function printLabReport(id) {
            const item = labResultsList.find(l => l.id == id);
            if (!item) return;

            const p = item.visit?.person;
            const t = item.test_type;

            document.getElementById('printLabHeader').innerHTML = `
                <div><strong>اسم المريض:</strong> ${p ? p.full_name : '---'}</div>
                <div><strong>الرقم الوطني:</strong> ${p ? p.national_id : '---'}</div>
                <div><strong>تاريخ الفحص:</strong> ${new Date(item.updated_at || item.created_at).toLocaleDateString('ar-SA')}</div>
                <div><strong>الطبيب المحول:</strong> ${item.visit?.doctor?.person?.full_name || 'طبيب المركز'}</div>
            `;

            document.getElementById('printLabBody').innerHTML = `
                <tr style="border-bottom:1px solid #ccc;">
                    <td style="padding:10px;"><strong>${t ? t.name : 'فحص'}</strong></td>
                    <td style="padding:10px; text-align:center; font-size:16px; font-weight:bold;">${item.result_value} ${t?.unit || ''}</td>
                    <td style="padding:10px; text-align:center;">${t?.min_range || 0} - ${t?.max_range || 0} ${t?.unit || ''}</td>
                    <td style="padding:10px;">${item.lab_notes || 'فحص مطابق للمعايير المخبرية'}</td>
                </tr>
            `;

            window.print();
        }

        document.addEventListener('DOMContentLoaded', () => {
            fetchLabData();
            setInterval(fetchLabData, 15000);
        });
    </script>
</body>
</html>
