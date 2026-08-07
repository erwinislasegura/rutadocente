document.querySelector('.menu')?.addEventListener('click',()=>document.querySelector('nav')?.classList.toggle('open'));document.querySelectorAll('.filters button').forEach(b=>b.addEventListener('click',()=>{document.querySelectorAll('.filters button').forEach(x=>x.classList.remove('selected'));b.classList.add('selected')}));

const publicScript = document.currentScript;
const publicBase = publicScript?.src ? new URL(publicScript.src).pathname.replace(/\/assets\/js\/site-public\.js$/, '') : '';
const mobileTeacherBar = document.createElement('div');
mobileTeacherBar.className = 'mobile-teacher-bar';
mobileTeacherBar.innerHTML = `<div class="mobile-teacher-brand"><img src="${publicBase}/assets/img/logo-ruta-docente.png" alt="Ruta Docente"><span><b>Ruta Docente</b><small>Plataforma 2026</small></span></div><a href="${publicBase}/login"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm7 8a7 7 0 0 0-14 0"/></svg><span>Acceso docente</span></a></div>`;
document.body.appendChild(mobileTeacherBar);
