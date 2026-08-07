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
