const userSearch = document.querySelector('#userSearch');
userSearch?.addEventListener('input', () => {
  const term = userSearch.value.trim().toLocaleLowerCase('es');
  const rows = [...document.querySelectorAll('[data-user-row]')];
  let visible = 0;
  rows.forEach(row => {
    const match = !term || (row.dataset.search || '').includes(term);
    row.classList.toggle('d-none', !match);
    if (match) visible++;
  });
  document.querySelector('#usersNoResults')?.classList.toggle('d-none', visible !== 0);
});

const fieldType = document.querySelector('[data-field-type]');
const syncFieldOptions = () => {
  if (!fieldType) return;
  const type = fieldType.value;
  document.querySelector('[data-options-wrap]')?.classList.toggle('d-none', !['select', 'radio', 'checkbox_group'].includes(type));
  document.querySelector('[data-max-selections-wrap]')?.classList.toggle('d-none', type !== 'checkbox_group');
};
fieldType?.addEventListener('change', syncFieldOptions);
syncFieldOptions();

// Saca temporalmente los menús de acciones fuera de la tabla para evitar que
// las últimas filas los recorten por el overflow del contenedor responsive.
const floatingUserMenus = new WeakMap();
document.addEventListener('shown.bs.dropdown', event => {
  const toggle = event.target;
  if (!toggle.matches('.user-actions-trigger')) return;
  const menu = toggle.closest('.user-actions-dropdown')?.querySelector('.dropdown-menu');
  if (!menu) return;
  const marker = document.createComment('user-actions-menu');
  menu.before(marker);
  document.body.append(menu);
  menu.classList.add('user-actions-floating');
  const triggerRect = toggle.getBoundingClientRect();
  const menuRect = menu.getBoundingClientRect();
  const left = Math.max(8, Math.min(triggerRect.right - menuRect.width, window.innerWidth - menuRect.width - 8));
  const fitsBelow = window.innerHeight - triggerRect.bottom >= menuRect.height + 8;
  const top = fitsBelow ? triggerRect.bottom + 5 : Math.max(8, triggerRect.top - menuRect.height - 5);
  menu.style.setProperty('--actions-left', `${left}px`);
  menu.style.setProperty('--actions-top', `${top}px`);
  floatingUserMenus.set(toggle, {menu, marker});
});
document.addEventListener('hide.bs.dropdown', event => {
  const toggle = event.target;
  const state = floatingUserMenus.get(toggle);
  if (!state) return;
  state.marker.replaceWith(state.menu);
  state.menu.classList.remove('user-actions-floating');
  state.menu.style.removeProperty('--actions-left');
  state.menu.style.removeProperty('--actions-top');
  floatingUserMenus.delete(toggle);
});

document.querySelectorAll('[data-resource-search]').forEach(input => input.addEventListener('input', () => {
  const term = input.value.trim().toLocaleLowerCase('es');
  const cards = [...document.querySelectorAll('[data-resource-card]')];
  let visible = 0;
  cards.forEach(card => {
    const match = !term || (card.dataset.search || '').toLocaleLowerCase('es').includes(term);
    card.classList.toggle('d-none', !match);
    if (match) visible++;
  });
  document.querySelectorAll('[data-resource-group]').forEach(group => {
    group.classList.toggle('d-none', !group.querySelector('[data-resource-card]:not(.d-none)'));
  });
  document.querySelector('[data-resource-empty]')?.classList.toggle('d-none', visible !== 0);
}));

// La plantilla del panel es compartida: agrega la integración solo al menú administrador.
const catalogsLink = document.querySelector('.sidebar-nav a[href$="/admin/catalogos"]');
if (catalogsLink && !document.querySelector('.sidebar-nav a[href$="/admin/analytics"]')) {
  const integrationLabel = document.createElement('span');
  integrationLabel.className = 'sidebar-label inner';
  integrationLabel.textContent = 'INTEGRACIONES';
  const divider = document.createElement('div');
  divider.className = 'sidebar-divider';
  const analyticsLink = document.createElement('a');
  analyticsLink.className = `nav-link${location.pathname.endsWith('/admin/analytics') ? ' active' : ''}`;
  analyticsLink.href = catalogsLink.href.replace(/\/admin\/catalogos$/, '/admin/analytics');
  analyticsLink.innerHTML = '<span class="nav-icon">A</span><span>Google Analytics</span>';
  catalogsLink.after(divider, integrationLabel, analyticsLink);
}
