<main class="content container-fluid">
 <div class="page-toolbar flex-wrap">
  <div><span class="eyebrow">INTEGRACIONES</span><p class="text-secondary mb-0 mt-2">Mide las visitas de toda la web pública desde Google Analytics 4.</p></div>
  <a class="btn btn-outline-secondary" href="https://analytics.google.com/" target="_blank" rel="noopener">Abrir Google Analytics ↗</a>
 </div>
 <section class="card panel-card"><div class="card-body p-4 p-xl-5"><div class="row g-5">
  <div class="col-lg-7">
   <span class="eyebrow">CONFIGURACIÓN GA4</span><h2 class="mt-2">Seguimiento de páginas públicas</h2>
   <p class="text-secondary">El código se instalará automáticamente en Inicio, Asignaturas, Portafolio, Clases asincrónicas, Tests, Tabuladores, Recursos, Contacto, Preguntas frecuentes y el formulario de inscripción.</p>
   <form method="post" action="<?=url('/admin/analytics')?>" class="mt-4"><?=csrf_field()?>
    <label class="form-label" for="measurement_id">ID de medición</label>
    <input class="form-control form-control-lg text-uppercase" id="measurement_id" name="measurement_id" maxlength="22" value="<?=e($analytics['measurement_id'])?>" placeholder="G-XXXXXXXXXX" autocomplete="off" spellcheck="false">
    <div class="form-text">Lo encuentras en Google Analytics → Administrar → Flujos de datos → Web.</div>
    <div class="form-check form-switch permission-check mt-4"><input class="form-check-input" type="checkbox" role="switch" id="analytics_enabled" name="enabled" value="1" <?=$analytics['enabled']?'checked':''?>><label class="form-check-label" for="analytics_enabled"><strong>Activar medición</strong><span>Registrará páginas vistas y la inscripción completada como evento.</span></label></div>
    <button class="btn btn-brand mt-4" type="submit">Guardar configuración</button>
   </form>
  </div>
  <div class="col-lg-5"><div class="p-4 rounded-3 bg-light border"><span class="eyebrow">ALCANCE</span><h3 class="h5 mt-2">Medición incluida</h3><ul class="text-secondary mb-0 ps-3"><li class="mb-2">Visitas y páginas vistas en toda la web pública.</li><li class="mb-2">Dispositivos, canales, ubicación y campañas.</li><li>Evento <code>generate_lead</code> al confirmar una inscripción.</li></ul></div></div>
 </div></div></section>
</main>
