<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>أنواع الأدوية - إدارة النقاط الطبية</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="stylesheet" href="{{ asset('assets/styles.css') }}">
</head>
<body data-page="medicines">
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
        <a class="nav-item active" data-page="medicines" href="medicine-types.html"><i class="fas fa-pills"></i> أنواع الأدوية</a>
      </div>
    </nav>
  </aside>

  <main class="main-content">
    <div class="topbar">
      <h1>أنواع الأدوية</h1>
    </div>
    <div class="table-card">
      <div class="table-toolbar">
        <div class="search-box"><i class="fas fa-search"></i><input type="text" id="searchInput" placeholder="بحث عن دواء..."></div>
        <button class="btn btn-primary btn-sm" onclick="openAddModal()"><i class="fas fa-plus"></i> إضافة دواء</button>
      </div>
      <div class="table-container">
        <table>
          <thead><tr><th>#</th><th>الاسم التجاري</th><th>التركيز</th><th>الكمية</th><th>التوفر</th><th>الإجراءات</th></tr></thead>
          <tbody id="tableBody"></tbody>
        </table>
      </div>
    </div>
  </main>

  <div class="modal-overlay" id="formModal">
    <div class="modal">
      <div class="modal-header">
        <h3 id="modalTitle">إضافة دواء</h3>
        <button class="modal-close" onclick="closeModal('formModal')"><i class="fas fa-times"></i></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>الاسم التجاري</label>
          <input type="text" id="nameInput" placeholder="أدخل اسم الدواء">
        </div>
        <div class="form-group">
          <label>التركيز / العيار</label>
          <input type="text" id="strengthInput" placeholder="مثال: 500mg">
        </div>
        <div class="form-group">
          <label>الكمية</label>
          <input type="number" id="stockInput" value="0">
        </div>
        <div class="form-group">
          <label>التوفر</label>
          <select id="availableInput">
            <option value="1">متوفر</option>
            <option value="0">غير متوفر</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" onclick="closeModal('formModal')">إلغاء</button>
        <button class="btn btn-primary" onclick="saveItem()"><i class="fas fa-save"></i> حفظ</button>
      </div>
    </div>
  </div>

  <script src="{{ asset('js/main.js') }}"></script>
  <script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    async function fetchMedicines() {
      const res = await fetch('/medicines');
      const items = await res.json();
      const tbody = document.getElementById('tableBody');

      tbody.innerHTML = items.map((item, idx) => `
        <tr>
          <td>${idx + 1}</td>
          <td><strong>${item.name}</strong></td>
          <td>${item.strength || '---'}</td>
          <td>${item.stock_quantity}</td>
          <td><span class="status-badge ${item.is_available ? 'active' : 'inactive'}">${item.is_available ? 'متوفر' : 'غير متوفر'}</span></td>
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
        name: document.getElementById('nameInput').value,
        strength: document.getElementById('strengthInput').value,
        stock_quantity: document.getElementById('stockInput').value,
        is_available: document.getElementById('availableInput').value
      };

      const res = await fetch('/medicines', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify(data)
      });

      if (res.ok) {
        closeModal('formModal');
        fetchMedicines();
      }
    }

    function openAddModal() { document.getElementById('formModal').style.display = 'flex'; }
    function closeModal(id) { document.getElementById(id).style.display = 'none'; }
    document.addEventListener('DOMContentLoaded', fetchMedicines);
  </script>
</body>
</html>
