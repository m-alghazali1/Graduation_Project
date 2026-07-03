<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>المدن - إدارة النقاط الطبية</title>
  <link rel="stylesheet" href="{{asset('assets/styles.css')}}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body data-page="cities">
  <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <div class="logo"><i class="fas fa-heartbeat"></i></div>
      <div><h2>النقطة الطبية</h2><span>مركز الرعاية الصحية</span></div>
    </div>
    <nav class="sidebar-nav">
      <div class="nav-section">
        <div class="nav-section-title">الرئيسية</div>
        <a class="nav-item" data-page="dashboard" href="../dashboard.html"><i class="fas fa-chart-pie"></i> لوحة التحكم</a>
      </div>
      <div class="nav-section">
        <div class="nav-section-title">الثوابت</div>
        <a class="nav-item" data-page="governorates" href="governorates.html"><i class="fas fa-map-marker-alt"></i> المحافظات</a>
        <a class="nav-item active" data-page="cities" href="cities.html"><i class="fas fa-city"></i> المدن</a>
        <a class="nav-item" data-page="districts" href="districts.html"><i class="fas fa-map"></i> الأحياء</a>
        <a class="nav-item" data-page="analysis" href="analysis-types.html"><i class="fas fa-flask"></i> أنواع التحاليل</a>
        <a class="nav-item" data-page="medicines" href="medicine-types.html"><i class="fas fa-pills"></i> أنواع الأدوية</a>
      </div>
    </nav>
    <div class="sidebar-footer">
      <div class="user-info">
        <div class="avatar">أ</div>
        <div><div class="user-name">أحمد المدير</div><div class="user-role">مدير النظام</div></div>
      </div>
    </div>
  </aside>

  <main class="main-content">
    <div class="topbar">
      <button class="mobile-toggle" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
      <div>
        <div class="breadcrumb"><a href="../dashboard.html">الرئيسية</a><span>/</span><span class="current">المدن</span></div>
        <h1>المدن</h1>
      </div>
      <div class="topbar-left"><span class="date" id="currentDate"></span></div>
    </div>

    <div class="table-card">
      <div class="table-toolbar">
        <div class="search-box"><i class="fas fa-search"></i><input type="text" id="searchInput" placeholder="بحث عن مدينة..." oninput="renderTable()"></div>
        <button class="btn btn-primary btn-sm" onclick="openAddModal()"><i class="fas fa-plus"></i> إضافة مدينة</button>
      </div>
      <div class="table-container">
        <table>
          <thead><tr><th>#</th><th>الاسم</th><th>المحافظة</th><th>الحالة</th><th>الإجراءات</th></tr></thead>
          <tbody id="tableBody"></tbody>
        </table>
      </div>
    </div>
  </main>

  <div class="modal-overlay" id="formModal">
    <div class="modal">
      <div class="modal-header">
        <h3 id="modalTitle">إضافة مدينة</h3>
        <button class="modal-close" onclick="closeModal('formModal')"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>المحافظة</label>
          <select id="governorateSelect" style="width:100%;padding:12px 16px;border:1.5px solid var(--gray-200);border-radius:var(--radius-sm);font-family:'Cairo',sans-serif;font-size:14px;background:var(--gray-50);outline:none;"></select>
        </div>
        <div class="form-group">
          <label>اسم المدينة</label>
          <input type="text" id="nameInput" placeholder="أدخل اسم المدينة" required>
        </div>
        <div class="form-group">
          <label>الحالة</label>
          <select id="statusInput" style="width:100%;padding:12px 16px;border:1.5px solid var(--gray-200);border-radius:var(--radius-sm);font-family:'Cairo',sans-serif;font-size:14px;background:var(--gray-50);outline:none;"><option value="active">نشط</option><option value="inactive">غير نشط</option></select>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" onclick="closeModal('formModal')">إلغاء</button>
        <button class="btn btn-primary" onclick="saveItem()"><i class="fas fa-save"></i> حفظ</button>
      </div>
    </div>
  </div>

  <script src="{{asset('js/main.js')}}"></script>
  <script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    async function loadGovernorates() {
        const response = await fetch('/governorates');
        const govs = await response.json();
        const sel = document.getElementById('governorateSelect');
        sel.innerHTML = '<option value="" disabled selected>اختر المحافظة...</option>';
        sel.innerHTML += govs.map(g => `<option value="${g.id}">${g.name}</option>`).join('');
    }

    async function fetchCities() {
        const response = await fetch('/cities');
        const items = await response.json();
        renderTable(items);
    }

    function renderTable(items) {
        const tbody = document.getElementById('tableBody');
        if (!items || items.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5"><div class="empty-state"><p>لا توجد مدن</p></div></td></tr>';
            return;
        }

        tbody.innerHTML = items.map((item, idx) => `
            <tr>
                <td style="color:var(--gray-400);font-weight:600;">${idx + 1}</td>
                <td><strong>${item.name}</strong></td>
                <td>${item.governorate ? item.governorate.name : '---'}</td>
                <td><span class="status-badge active">نشط</span></td>
                <td>
                    <div class="actions">
                        <button class="edit-btn"><i class="fas fa-pen"></i></button>
                        <button class="delete-btn"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    function openAddModal() {
        document.getElementById('nameInput').value = '';
        loadGovernorates();
        openModal('formModal');
    }

    async function saveItem() {
        const name = document.getElementById('nameInput').value.trim();
        const governorateId = document.getElementById('governorateSelect').value;

        if (!name || !governorateId) return;

        const response = await fetch('/cities', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ name: name, governorate_id: governorateId })
        });

        if (response.ok) {
            closeModal('formModal');
            fetchCities();
        }
    }

    function openModal(modalId) { document.getElementById(modalId).style.display = 'flex'; }
    function closeModal(modalId) { document.getElementById(modalId).style.display = 'none'; }

    document.addEventListener('DOMContentLoaded', fetchCities);
</script>
</body>
</html>
