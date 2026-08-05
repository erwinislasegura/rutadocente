document.querySelectorAll('[data-tab]').forEach(button => button.addEventListener('click', () => {
  document.querySelectorAll('[data-tab]').forEach(item => item.classList.remove('active'));
  button.classList.add('active');
  const accessType = document.querySelector('#access_type');
  if (accessType) accessType.value = button.dataset.tab;
}));
