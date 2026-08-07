document.querySelector('.menu')?.addEventListener('click',()=>document.querySelector('nav')?.classList.toggle('open'));document.querySelectorAll('.filters button').forEach(b=>b.addEventListener('click',()=>{document.querySelectorAll('.filters button').forEach(x=>x.classList.remove('selected'));b.classList.add('selected')}));

const publicScript = document.currentScript;
const publicBase = publicScript?.src ? new URL(publicScript.src).pathname.replace(/\/assets\/js\/site-public\.js$/, '') : '';
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
