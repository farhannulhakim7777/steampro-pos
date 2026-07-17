const rupiah = value => new Intl.NumberFormat('id-ID', {style: 'currency', currency: 'IDR', maximumFractionDigits: 0}).format(value || 0);
const appBase = document.querySelector('meta[name="app-base"]')?.content || '';
const appUrl = path => `${appBase}${path}`;

document.querySelectorAll('[data-pos-form]').forEach(form => {
  const recalc = () => {
    let subtotal = 0;
    form.querySelectorAll('input[name="services[]"]:checked').forEach(input => subtotal += Number(input.dataset.price || 0));
    form.querySelectorAll('.product-list input[type="number"]').forEach(input => subtotal += Number(input.dataset.price || 0) * Number(input.value || 0));
    const discount = Math.min(Number(form.querySelector('[data-discount]')?.value || 0), subtotal);
    form.querySelector('[data-subtotal]').textContent = rupiah(subtotal);
    form.querySelector('[data-total]').textContent = rupiah(subtotal - discount);
  };
  form.addEventListener('input', recalc);
  form.addEventListener('change', recalc);
  recalc();
});

document.querySelectorAll('.fill-form').forEach(button => {
  button.addEventListener('click', event => {
    event.preventDefault();
    const data = JSON.parse(button.dataset.json);
    Object.keys(data).forEach(key => {
      const field = document.querySelector(`[name="${key}"]`);
      if (field) field.value = data[key] ?? '';
    });
    window.scrollTo({top: 0, behavior: 'smooth'});
  });
});

document.querySelectorAll('[data-history-plate]').forEach(input => {
  let timer = null;
  input.addEventListener('input', () => {
    clearTimeout(timer);
    timer = setTimeout(async () => {
      const plate = input.value.trim();
      const box = document.querySelector('#plate-history');
      if (!box || plate.length < 3) return;
      const res = await fetch(appUrl(`/api/customers/history?plate=${encodeURIComponent(plate)}`));
      const data = await res.json();
      box.textContent = data ? `${data.name}: ${data.visits} visits, total ${rupiah(data.spending)}, last ${data.last_visit || '-'}` : 'No customer history found for this plate.';
    }, 350);
  });
});

if (document.querySelector('[data-queue-board]')) {
  setInterval(async () => {
    const res = await fetch(appUrl('/api/queue'), {headers: {'Accept': 'application/json'}});
    if (res.ok) console.debug('Queue refreshed', await res.json());
  }, 15000);
}

// Custom Modal
let confirmCallback = null;
const modal = document.getElementById('confirm-modal');
const modalTitle = document.getElementById('modal-title');
const modalMessage = document.getElementById('modal-message');
const modalConfirmBtn = document.getElementById('modal-confirm-btn');

function showConfirm(message, title = 'Konfirmasi') {
  return new Promise((resolve) => {
    modalTitle.textContent = title;
    modalMessage.textContent = message;
    modal.classList.add('active');
    confirmCallback = resolve;
  });
}

function closeModal() {
  modal.classList.remove('active');
  if (confirmCallback) {
    confirmCallback(false);
    confirmCallback = null;
  }
}

modalConfirmBtn.addEventListener('click', () => {
  modal.classList.remove('active');
  if (confirmCallback) {
    confirmCallback(true);
    confirmCallback = null;
  }
});

modal.addEventListener('click', (e) => {
  if (e.target === modal) {
    closeModal();
  }
});

// Mobile Sidebar Toggle
const menuToggle = document.getElementById('menu-toggle');
const sidebar = document.getElementById('sidebar');
const sidebarOverlay = document.getElementById('sidebar-overlay');

if (menuToggle && sidebar && sidebarOverlay) {
  menuToggle.addEventListener('click', () => {
    sidebar.classList.toggle('active');
    sidebarOverlay.classList.toggle('active');
  });

  // Close sidebar when clicking overlay
  sidebarOverlay.addEventListener('click', () => {
    sidebar.classList.remove('active');
    sidebarOverlay.classList.remove('active');
  });

  // Close sidebar when clicking a link
  sidebar.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
      sidebar.classList.remove('active');
      sidebarOverlay.classList.remove('active');
    });
  });
}