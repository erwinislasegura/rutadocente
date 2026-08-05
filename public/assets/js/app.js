document.querySelectorAll('[data-tab]').forEach(button => button.addEventListener('click', () => {
  document.querySelectorAll('[data-tab]').forEach(item => item.classList.remove('active'));
  button.classList.add('active');
  const accessType = document.querySelector('#access_type');
  if (accessType) accessType.value = button.dataset.tab;
}));

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
