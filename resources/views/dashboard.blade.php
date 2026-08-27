<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم الرئيسية - إدارة النقاط الطبية</title>
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}">
</head>

<body data-page="dashboard">
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
                <a class="nav-item active" data-page="dashboard" href="/dashboard"><i class="fas fa-chart-pie"></i> لوحة التحكم</a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">الاستقبال والكشف</div>
                <a class="nav-item" data-page="patients" href="/dashboard/persons"><i class="fas fa-users"></i> المرضى</a>
                <a class="nav-item" data-page="visits" href="/dashboard/visits"><i class="fas fa-stethoscope"></i> الزيارات والكشف الطبي</a>
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
                    <div class="avatar">أ</div>
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
                <div class="breadcrumb"><a href="/dashboard">الرئيسية</a><span>/</span><span class="current">لوحة التحكم</span></div>
                <h1>نظرة عامة على المركز الطبي</h1>
            </div>
            <div class="topbar-left"><span class="date" id="currentDate"></span></div>
        </div>

        <!-- بطاقات المؤشرات الإحصائية الحية -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon primary"><i class="fas fa-user-injured"></i></div>
                <div class="stat-info">
                    <h3 id="statPatients">0</h3>
                    <p>إجمالي المرضى المسجلين</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-calendar-check"></i></div>
                <div class="stat-info">
                    <h3 id="statTodayVisits">0</h3>
                    <p>زيارات وكشوفات اليوم</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon amber"><i class="fas fa-clock"></i></div>
                <div class="stat-info">
                    <h3 id="statWaitingVisits">0</h3>
                    <p>حالات في الانتظار / قيد الكشف</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon purple"><i class="fas fa-vial"></i></div>
                <div class="stat-info">
                    <h3 id="statPendingLab">0</h3>
                    <p>تحاليل مخبرية معلقة</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon primary"><i class="fas fa-prescription"></i></div>
                <div class="stat-info">
                    <h3 id="statPendingRx">0</h3>
                    <p>وصفات بانتظار الصرف</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon rose"><i class="fas fa-exclamation-triangle"></i></div>
                <div class="stat-info">
                    <h3 id="statLowStock">0</h3>
                    <p>أدوية منخفضة الرصيد (أقل من 10)</p>
                </div>
            </div>
        </div>

        <!-- الجداول التفاعلية الحديثة -->
        <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(450px, 1fr)); gap:20px; margin-top:20px;">
            <!-- جدول أحدث الزيارات -->
            <div class="table-card">
                <div class="table-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                    <h3 style="font-size:16px; font-weight:700; color:var(--gray-800);"><i class="fas fa-stethoscope" style="color:var(--primary); margin-left:8px;"></i> آخر الزيارات المسجلة</h3>
                    <a href="/dashboard/visits" class="btn btn-sm btn-secondary">عرض الكل</a>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>المريض</th>
                                <th>الطبيب</th>
                                <th>الحالة</th>
                                <th>التاريخ</th>
                            </tr>
                        </thead>
                        <tbody id="recentVisitsBody">
                            <tr><td colspan="4" style="text-align:center; padding:20px;">جاري تحميل البيانات...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- جدول التحاليل المعلقة للمختبر -->
            <div class="table-card">
                <div class="table-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                    <h3 style="font-size:16px; font-weight:700; color:var(--gray-800);"><i class="fas fa-flask" style="color:#7e22ce; margin-left:8px;"></i> تحاليل بانتظار النتائج (المختبر)</h3>
                    <a href="/dashboard/lab-results" class="btn btn-sm btn-secondary">شاشة المختبر</a>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>المريض</th>
                                <th>نوع التحليل</th>
                                <th>المدى المرجعي</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody id="recentLabBody">
                            <tr><td colspan="4" style="text-align:center; padding:20px;">جاري تحميل البيانات...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- جدول الوصفات المعلقة للصيدلية -->
            <div class="table-card" style="grid-column: 1 / -1;">
                <div class="table-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                    <h3 style="font-size:16px; font-weight:700; color:var(--gray-800);"><i class="fas fa-pills" style="color:var(--primary); margin-left:8px;"></i> أدوية وروشتات بانتظار الصرف (الصيدلية)</h3>
                    <a href="/dashboard/pharmacy" class="btn btn-sm btn-primary" style="width:auto;"><i class="fas fa-arrow-left"></i> الانتقال للصيدلية</a>
                </div>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>المريض</th>
                                <th>الدواء المطلوب</th>
                                <th>الجرعة والتعليمات</th>
                                <th>الكمية المطلوبة</th>
                                <th>رصيد المخزون</th>
                                <th>الإجراء</th>
                            </tr>
                        </thead>
                        <tbody id="recentRxBody">
                            <tr><td colspan="6" style="text-align:center; padding:20px;">جاري تحميل البيانات...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        const STATUS_TEXT = {
            waiting: 'في الانتظار',
            in_progress: 'قيد الكشف',
            completed: 'مكتملة',
            cancelled: 'ملغية',
            pending: 'معلق'
        };

        async function loadDashboardData() {
            const res = await apiCall('/dashboard/stats');
            if (res.ok && res.data) {
                const s = res.data.stats;
                document.getElementById('statPatients').textContent = s.total_patients;
                document.getElementById('statTodayVisits').textContent = s.today_visits;
                document.getElementById('statWaitingVisits').textContent = s.waiting_visits;
                document.getElementById('statPendingLab').textContent = s.pending_lab_results;
                document.getElementById('statPendingRx').textContent = s.pending_prescriptions;
                document.getElementById('statLowStock').textContent = s.low_stock_medicines;

                // رندرة الزيارات الأخيرة
                const vTbody = document.getElementById('recentVisitsBody');
                if (res.data.recent_visits && res.data.recent_visits.length > 0) {
                    vTbody.innerHTML = res.data.recent_visits.map(v => `
                        <tr>
                            <td><strong>${v.person ? v.person.full_name : '---'}</strong></td>
                            <td>${v.doctor && v.doctor.person ? v.doctor.person.full_name : (v.doctor ? v.doctor.email : 'طبيب المركز')}</td>
                            <td><span class="status-badge ${v.status}">${STATUS_TEXT[v.status] || v.status}</span></td>
                            <td style="font-size:12px; color:var(--gray-500);">${new Date(v.appointment_date).toLocaleDateString('ar-SA')}</td>
                        </tr>
                    `).join('');
                } else {
                    vTbody.innerHTML = '<tr><td colspan="4" style="text-align:center; color:var(--gray-400); padding:20px;">لا توجد زيارات حديثة</td></tr>';
                }

                // رندرة التحاليل المعلقة
                const lTbody = document.getElementById('recentLabBody');
                if (res.data.recent_lab_orders && res.data.recent_lab_orders.length > 0) {
                    lTbody.innerHTML = res.data.recent_lab_orders.map(l => `
                        <tr>
                            <td><strong>${l.visit && l.visit.person ? l.visit.person.full_name : 'زيارة #' + l.visit_id}</strong></td>
                            <td>${l.test_type ? l.test_type.name : 'تحليل #' + l.test_type_id}</td>
                            <td style="font-size:12px; color:var(--gray-500);">${l.test_type ? (l.test_type.min_range + ' - ' + l.test_type.max_range + ' ' + (l.test_type.unit || '')) : '---'}</td>
                            <td><span class="status-badge pending">بانتظار الفحص</span></td>
                        </tr>
                    `).join('');
                } else {
                    lTbody.innerHTML = '<tr><td colspan="4" style="text-align:center; color:var(--gray-400); padding:20px;">لا توجد تحاليل معلقة</td></tr>';
                }

                // رندرة الوصفات المعلقة
                const rxTbody = document.getElementById('recentRxBody');
                if (res.data.recent_prescriptions && res.data.recent_prescriptions.length > 0) {
                    rxTbody.innerHTML = res.data.recent_prescriptions.map(rx => {
                        const med = rx.medicine;
                        const hasStock = med && med.stock_quantity >= rx.prescribed_quantity;
                        return `
                            <tr>
                                <td><strong>${rx.visit && rx.visit.person ? rx.visit.person.full_name : '---'}</strong></td>
                                <td>${med ? med.name : 'دواء #' + rx.medicine_id}</td>
                                <td style="font-size:13px;">${rx.instructions || rx.dosage || 'حسب إرشادات الطبيب'}</td>
                                <td><span style="font-weight:700;">${rx.prescribed_quantity}</span></td>
                                <td>
                                    <span style="color:${hasStock ? 'var(--success)' : 'var(--danger)'}; font-weight:700;">
                                        ${med ? med.stock_quantity : 0} متوفر
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="quickDispense(${rx.id})" style="padding:4px 10px; font-size:12px;">
                                        <i class="fas fa-check"></i> صرف الآن
                                    </button>
                                </td>
                            </tr>
                        `;
                    }).join('');
                } else {
                    rxTbody.innerHTML = '<tr><td colspan="6" style="text-align:center; color:var(--gray-400); padding:20px;">لا توجد وصفات طبية معلقة حالياً</td></tr>';
                }
            }
        }

        async function quickDispense(id) {
            if (!confirm('تأكيد صرف هذا الدواء للمريض وخصمه من مخزون الصيدلية؟')) return;
            const res = await apiCall(`/prescriptions/${id}/dispense`, 'POST');
            if (res.ok) {
                showToast('تم صرف الدواء بنجاح وخصم الكمية من المخزون!');
                loadDashboardData();
            } else {
                showToast(res.data?.message || 'فشل صرف الدواء', 'error');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            loadDashboardData();
            // تحديث تلقائي كل 25 ثانية
            setInterval(loadDashboardData, 25000);
        });
    </script>
</body>
</html>
