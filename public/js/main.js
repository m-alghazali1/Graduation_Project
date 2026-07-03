// Shared JS for Medical Point Management

const API = {
  governorates: JSON.parse(localStorage.getItem('governorates') || '[]'),
  cities: JSON.parse(localStorage.getItem('cities') || '[]'),
  districts: JSON.parse(localStorage.getItem('districts') || '[]'),
  analysisTypes: JSON.parse(localStorage.getItem('analysisTypes') || '[]'),
  medicineTypes: JSON.parse(localStorage.getItem('medicineTypes') || '[]'),

  save(key) { localStorage.setItem(key, JSON.stringify(this[key])); },

  getAll(key) { return this[key] || []; },

  getById(key, id) { return (this[key] || []).find(i => i.id === id); },

  add(key, item) {
    const list = this[key];
    item.id = Date.now().toString(36) + Math.random().toString(36).slice(2, 6);
    item.createdAt = new Date().toISOString();
    list.push(item);
    this.save(key);
    return item;
  },

  update(key, id, data) {
    const list = this[key];
    const idx = list.findIndex(i => i.id === id);
    if (idx === -1) return null;
    list[idx] = { ...list[idx], ...data };
    this.save(key);
    return list[idx];
  },

  remove(key, id) {
    const list = this[key];
    const idx = list.findIndex(i => i.id === id);
    if (idx === -1) return false;
    list.splice(idx, 1);
    this.save(key);
    return true;
  }
};

function showToast(msg, type = 'success') {
  const t = document.getElementById('toast') || (() => {
    const el = document.createElement('div');
    el.id = 'toast';
    el.className = 'toast';
    document.body.appendChild(el);
    return el;
  })();
  t.className = `toast ${type}`;
  t.innerHTML = `<i class="fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${msg}`;
  t.classList.add('show');
  clearTimeout(t._timer);
  t._timer = setTimeout(() => t.classList.remove('show'), 2500);
}

function openModal(id) { document.getElementById(id)?.classList.add('show'); }
function closeModal(id) { document.getElementById(id)?.classList.remove('show'); }

function closeModalOnOverlay(e) {
  if (e.target.classList.contains('modal-overlay')) {
    e.target.classList.remove('show');
  }
}

document.addEventListener('click', function(e) {
  if (e.target.classList.contains('modal-overlay')) {
    e.target.classList.remove('show');
  }
});

function toggleSidebar() {
  document.getElementById('sidebar')?.classList.toggle('open');
  document.getElementById('sidebarOverlay')?.classList.toggle('show');
}

function setActiveNav() {
  const page = document.body.dataset.page;
  document.querySelectorAll('.nav-item').forEach(el => {
    el.classList.toggle('active', el.dataset.page === page);
  });
}

document.addEventListener('DOMContentLoaded', () => {
  setActiveNav();
  const dateEl = document.getElementById('currentDate');
  if (dateEl) {
    const now = new Date();
    const opts = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    dateEl.textContent = now.toLocaleDateString('ar-SA', opts);
  }
});
