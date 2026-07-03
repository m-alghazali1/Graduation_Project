<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>أنواع التحاليل - إدارة النقاط الطبية</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{asset('/assets/styles.css')}}">
</head>
<body data-page="analysis">
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
        <a class="nav-item" data-page="cities" href="cities.html"><i class="fas fa-city"></i> المدن</a>
        <a class="nav-item" data-page="districts" href="districts.html"><i class="fas fa-map"></i> الأحياء</a>
        <a class="nav-item active" data-page="analysis" href="analysis-types.html"><i class="fas fa-flask"></i> أنواع التحاليل</a>
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
        <div class="breadcrumb"><a href="../dashboard.html">الرئيسية</a><span>/</span><span class="current">أنواع التحاليل</span></div>
        <h1>أنواع التحاليل</h1>
      </div>
      <div class="topbar-left"><span class="date" id="currentDate"></span></div>
    </div>

    <div class="table-card">
      <div class="table-toolbar">
        <div class="search-box"><i class="fas fa-search"></i><input type="text" id="searchInput" placeholder="بحث عن تحليل..." oninput="renderTable()"></div>
        <button class="btn btn-primary btn-sm" onclick="openAddModal()"><i class="fas fa-plus"></i> إضافة تحليل</button>
      </div>
      <div class="table-container">
        <table>
          <thead><tr><th>#</th><th>الاسم</th><th>الوصف</th><th>السعر</th><th>الحالة</th><th>الإجراءات</th></tr></thead>
          <tbody id="tableBody"></tbody>
        </table>
      </div>
    </div>
  </main>

  <div class="modal-overlay" id="formModal">
    <div class="modal">
      <div class="modal-header">
        <h3 id="modalTitle">إضافة تحليل</h3>
        <button class="modal-close" onclick="closeModal('formModal')"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>اسم التحليل</label>
          <input type="text" id="nameInput" placeholder="أدخل اسم التحليل" required>
        </div>
        <div class="form-group">
          <label>الوصف</label>
          <textarea id="descInput" rows="3" placeholder="وصف التحليل" style="width:100%;padding:12px 16px;border:1.5px solid var(--gray-200);border-radius:var(--radius-sm);font-family:'Cairo',sans-serif;font-size:14px;background:var(--gray-50);outline:none;resize:vertical;"></textarea>
        </div>
        <div class="form-group">
          <label>السعر (رس)</label>
          <input type="number" id="priceInput" placeholder="0" min="0" step="0.01">
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

<script src="{{ asset('./js/main.js') }}"></script>
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    async function fetchAnalyses() {
        const res = await fetch('/analyses');
        const items = await res.json();
        const tbody = document.getElementById('tableBody');

        if (!items || items.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6"><div class="empty-state"><i class="fas fa-flask"></i><p>لا توجد أنواع تحاليل مضافة بعد</p></div></td></tr>';
            return;
        }

        tbody.innerHTML = items.map((item, idx) => `
            <tr>
                <td style="color:var(--gray-400);font-weight:600;">${idx + 1}</td>
                <td><strong>${item.name}</strong></td>
                <td style="color:var(--gray-500);max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">${item.description || '---'}</td>
                <td style="font-weight:600;color:var(--primary);">${item.price ? Number(item.price).toLocaleString() + ' رس' : '---'}</td>
                <td><span class="status-badge ${item.status === 'active' ? 'active' : 'inactive'}">${item.status === 'active' ? 'نشط' : 'غير نشط'}</span></td>
                <td>
                    <div class="actions">
                        <button class="edit-btn"><i class="fas fa-pen"></i></button>
                        <button class="delete-btn"><i class="fas fa-trash"></i></button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    async function saveItem() {
        const data = {
            name: document.getElementById('nameInput').value.trim(),
            description: document.getElementById('descInput').value.trim(),
            price: document.getElementById('priceInput').value || 0,
            status: document.getElementById('statusInput').value
        };

        if (!data.name) return;

        const res = await fetch('/analyses', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(data)
        });

        if (res.ok) {
            closeModal('formModal');
            fetchAnalyses();
        }
    }

    function openAddModal() {
        document.getElementById('nameInput').value = '';
        document.getElementById('descInput').value = '';
        document.getElementById('priceInput').value = '';
        document.getElementById('statusInput').value = 'active';
        openModal('formModal');
    }

    function openModal(id) { document.getElementById(id).style.display = 'flex'; }
    function closeModal(id) { document.getElementById(id).style.display = 'none'; }

    document.addEventListener('DOMContentLoaded', fetchAnalyses);
</script>
</body>
</html>
