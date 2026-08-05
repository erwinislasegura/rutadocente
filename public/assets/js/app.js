document.querySelectorAll('[data-tab]').forEach(button => button.addEventListener('click', () => {
  document.querySelectorAll('[data-tab]').forEach(item => item.classList.remove('active'));
  button.classList.add('active');
  const accessType = document.querySelector('#access_type');
  if (accessType) accessType.value = button.dataset.tab;
}));

const mobileNav = document.querySelector('.mobile-nav');
const sidebar = document.querySelector('.sidebar');

mobileNav?.addEventListener('click', () => {
  const open = sidebar.classList.toggle('open');
  mobileNav.setAttribute('aria-expanded', open);
  mobileNav.textContent = open ? '×' : '☰';
});

document.addEventListener('click', event => {
  if (window.innerWidth <= 900 && sidebar?.classList.contains('open') &&
      !sidebar.contains(event.target) && !mobileNav?.contains(event.target)) {
    sidebar.classList.remove('open');
    mobileNav.setAttribute('aria-expanded', 'false');
    mobileNav.textContent = '☰';
  }
});
