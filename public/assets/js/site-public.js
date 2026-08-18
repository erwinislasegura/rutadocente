const publicScript = document.currentScript;
const publicBase = publicScript?.src ? new URL(publicScript.src).pathname.replace(/\/assets\/js\/site-public\.js$/, '') : '';
const menuButton = document.querySelector('.menu');
const publicNav = document.querySelector('.site-nav');
const dropdown = document.querySelector('.nav-dropdown');
const dropdownButton = document.querySelector('.nav-dropdown-toggle');
const normalizePublicPath = path => {
  const withoutBase = publicBase && path.startsWith(publicBase) ? path.slice(publicBase.length) : path;
  const normalized = withoutBase.replace(/\/+$/, '') || '/';
  if (normalized === '/correctores-ia') return '/tests';
  if (normalized === '/tabuladores') return '/talleres-asincronicos';
  return normalized;
};
const closePublicMenu = () => {
  publicNav?.classList.remove('open');
  dropdown?.classList.remove('open');
  menuButton?.setAttribute('aria-expanded', 'false');
  dropdownButton?.setAttribute('aria-expanded', 'false');
  document.body.classList.remove('nav-open');
};

menuButton?.addEventListener('click', () => {
  const isOpen = publicNav?.classList.toggle('open') || false;
  menuButton.setAttribute('aria-expanded', String(isOpen));
  document.body.classList.toggle('nav-open', isOpen);
  if (!isOpen) dropdown?.classList.remove('open');
});

dropdownButton?.addEventListener('click', event => {
  event.stopPropagation();
  const isOpen = dropdown?.classList.toggle('open') || false;
  dropdownButton.setAttribute('aria-expanded', String(isOpen));
});

document.querySelectorAll('.site-nav a').forEach(link => {
  const linkPath = normalizePublicPath(new URL(link.href, window.location.href).pathname);
  const isActive = linkPath === normalizePublicPath(window.location.pathname);
  link.classList.toggle('active', isActive);
  if (isActive) link.setAttribute('aria-current', 'page');
  if (isActive && link.closest('.nav-dropdown-menu')) dropdownButton?.classList.add('active');
  link.addEventListener('click', closePublicMenu);
});

document.addEventListener('click', event => {
  if (!event.target.closest('.site-header')) closePublicMenu();
});
document.addEventListener('keydown', event => {
  if (event.key === 'Escape') closePublicMenu();
});
window.addEventListener('resize', () => {
  if (window.innerWidth > 1100) closePublicMenu();
});

document.querySelectorAll('.filters button').forEach(button => button.addEventListener('click', () => {
  document.querySelectorAll('.filters button').forEach(item => item.classList.remove('selected'));
  button.classList.add('selected');
}));

const mobileTeacherBar = document.createElement('div');
mobileTeacherBar.className = 'mobile-teacher-bar';
mobileTeacherBar.innerHTML = `<div class="mobile-teacher-brand"><img src="${publicBase}/assets/img/logo-ruta-docente.png" alt="Ruta Docente"><span><b>Ruta Docente</b><small>Plataforma 2026</small></span></div><a href="${publicBase}/login"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm7 8a7 7 0 0 0-14 0"/></svg><span>Acceso docente</span></a></div>`;
document.body.appendChild(mobileTeacherBar);

document.querySelectorAll('[data-copy]').forEach(button => button.addEventListener('click', async () => {
  try {
    await navigator.clipboard.writeText(button.dataset.copy || '');
    const previous = button.textContent;
    button.textContent = 'Copiado';
    window.setTimeout(() => { button.textContent = previous; }, 1500);
  } catch (_) {
    button.textContent = 'Selecciona y copia';
  }
}));

document.querySelectorAll('.file-upload input[type="file"]').forEach(input => input.addEventListener('change', () => {
  const wrapper = input.closest('.file-upload');
  const title = wrapper?.querySelector('b');
  wrapper?.classList.toggle('has-file', Boolean(input.files?.length));
  if (title) title.textContent = input.files?.[0]?.name || 'Adjuntar archivo';
}));

document.querySelectorAll('[data-max-checks]').forEach(group => group.addEventListener('change', event => {
  const max = Number(group.dataset.maxChecks || 0);
  if (!max || event.target.type !== 'checkbox') return;
  const checked = [...group.querySelectorAll('input:checked')];
  if (checked.length > max) {
    event.target.checked = false;
    window.alert(`Puedes seleccionar un máximo de ${max} opciones.`);
  }
}));
