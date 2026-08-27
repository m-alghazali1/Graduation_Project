<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>صرف الوصفات الطبية - إدارة الصيدلية</title>
    <link rel="stylesheet" href="{{ asset('assets/styles.css') }}">
</head>

<body data-page="pharmacy">
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
                <a class="nav-item active" data-page="pharmacy" href="/dashboard/pharmacy"><i class="fas fa-prescription-bottle-alt"></i> صرف الوصفات الطبية</a>
                <a class="nav-item" data-page="medicines" href="/dashboard/medicine-types"><i class="fas fa-pills"></i> أنواع ومخزون الأدوية</a>
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
                        <div class="user-role">صيدلي</div>
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
                <div class="breadcrumb"><a href="/dashboard">الرئيسية</a><span>/</span><span class="current">صرف الوصفات الطبية</span></div>
                <h1>شاشة صرف الأدوية (الصيدلية)</h1>
            </div>
            <div class="topbar-left"><span class="date" id="currentDate"></span></div>
        </div>

        <!-- التبويبات -->
        <div class="clinical-tabs">
            <button class="clinical-tab active" id="tabPending" onclick="switchTab('pending')">
                <i class="fas fa-clock"></i> وصفات بانتظار الصرف (<span id="pendingCountBadge">0</span>)
            </button>
            <button class="clinical-tab" id="tabDispensed" onclick="switchTab('dispensed')">
                <i class="fas fa-check-double"></i> سجل الوصفات المصروفة
            </button>
        </div>

        <div class="table-card">
            <div class="table-toolbar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="بحث باسم المريض أو الدواء..." oninput="renderTable()">
                </div>
                <div style="display:flex; gap:8px;">
                    <button class="btn btn-secondary btn-sm" onclick="fetchPrescriptions()"><i class="fas fa-sync"></i> تحديث فوري</button>
                    <a href="/dashboard/medicine-types" class="btn btn-primary btn-sm" style="width:auto;"><i class="fas fa-pills"></i> إدارة المخزون</a>
                </div>
            </div>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>المريض</th>
                            <th>الطبيب المعالج</th>
                            <th>اسم الدواء والعيار</th>
                            <th>الجرعة والتعليمات</th>
                            <th>الكمية المطلوبة</th>
                            <th>حالة المخزون</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr><td colspan="8" style="text-align:center; padding:30px;">جاري تحميل بيانات الوصفات الطبية...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        let currentTab = 'pending';
        let allPrescriptions = [];

        async function fetchPrescriptions() {
            const res = await apiCall('/prescriptions');
            if (res.ok && res.data) {
                allPrescriptions = res.data;
                const pendingCount = allPrescriptions.filter(p => !p.is_dispensed).length;
                document.getElementById('pendingCountBadge').textContent = pendingCount;
                renderTable();
            }
        }

        function switchTab(tab) {
            currentTab = tab;
            document.getElementById('tabPending').classList.toggle('active', tab === 'pending');
            document.getElementById('tabDispensed').classList.toggle('active', tab === 'dispensed');
            renderTable();
        }

        function renderTable() {
            const q = (document.getElementById('searchInput')?.value || '').trim().toLowerCase();
            
            const items = allPrescriptions.filter(item => {
                const isMatchTab = currentTab === 'pending' ? !item.is_dispensed : item.is_dispensed;
                if (!isMatchTab) return false;

                const patientName = (item.visit?.person?.full_name || '').toLowerCase();
                const medName = (item.medicine?.name || '').toLowerCase();
                return patientName.includes(q) || medName.includes(q);
            });

            const tbody = document.getElementById('tableBody');
            if (!items.length) {
                const emptyMsg = currentTab === 'pending' ? 'لا توجد وصفات طبية معلقة بانتظار الصرف' : 'لا يوجد سجل وصفات مصروفة بعد';
                tbody.innerHTML = `<tr><td colspan="8" style="text-align:center; padding:30px; color:var(--gray-400);">${emptyMsg}</td></tr>`;
                return;
            }

            tbody.innerHTML = items.map((item, idx) => {
                const pName = item.visit?.person?.full_name || '---';
                const dName = item.visit?.doctor?.person?.full_name || (item.visit?.doctor?.email || 'طبيب المركز');
                const med = item.medicine;
                const stock = med ? med.stock_quantity : 0;
                const hasStock = stock >= item.prescribed_quantity;

                let stockBadge = `<span class="status-badge ${hasStock ? 'active' : 'cancelled'}">${stock} متوفر بالمستودع</span>`;
                if (item.is_dispensed) {
                    stockBadge = `<span class="status-badge dispensed"><i class="fas fa-check"></i> تم الصرف</span>`;
                } else if (!hasStock) {
                    stockBadge = `<span class="status-badge cancelled"><i class="fas fa-times"></i> غير كافي (${stock})</span>`;
                }

                return `
                    <tr>
                        <td style="color:var(--gray-400); font-weight:600;">${idx + 1}</td>
                        <td><strong>${pName}</strong></td>
                        <td>${dName}</td>
                        <td>
                            <strong>${med ? med.name : 'دواء #' + item.medicine_id}</strong>
                            ${med?.strength ? `<div style="font-size:11px; color:var(--gray-500);">${med.strength}</div>` : ''}
                        </td>
                        <td style="font-size:13px; max-width:220px;">
                            ${item.instructions || item.dosage || 'حسب إرشادات الطبيب'}
                        </td>
                        <td><span style="font-weight:700; font-size:15px;">${item.prescribed_quantity}</span></td>
                        <td>${stockBadge}</td>
                        <td>
                            ${!item.is_dispensed ? `
                                <button class="btn btn-sm btn-primary" onclick="dispenseMed(${item.id})" ${!hasStock ? 'disabled style="opacity:0.6; cursor:not-allowed;"' : ''}>
                                    <i class="fas fa-check-circle"></i> صرف الدواء
                                </button>
                            ` : `
                                <span style="font-size:12px; color:var(--gray-500);">
                                    ${item.dispensed_at ? new Date(item.dispensed_at).toLocaleDateString('ar-SA') : 'مصروف'}
                                </span>
                            `}
                        </td>
                    </tr>
                `;
            }).join('');
        }

        async function dispenseMed(id) {
            if (!confirm('هل أنت متأكد من صرف الدواء للمريض وخصم الكمية من المخزون؟')) return;

            const res = await apiCall(`/prescriptions/${id}/dispense`, 'POST');
            if (res.ok) {
                showToast('تم صرف الدواء بنجاح وتحديث رصيد المخزون في الصيدلية!');
                fetchPrescriptions();
            } else {
                showToast(res.data?.message || 'فشل صرف الدواء', 'error');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            fetchPrescriptions();
            // تحديث دوري كل 15 ثانية لمزامنة طلبات الأطباء لحظياً
            setInterval(fetchPrescriptions, 15000);
        });
    </script>
</body>
</html>
