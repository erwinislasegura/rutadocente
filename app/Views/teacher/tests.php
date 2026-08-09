<?php
$downloadable=count(array_filter($items,fn($item)=>!empty($item['file_path'])));
$online=count(array_filter($items,fn($item)=>!empty($item['external_link'])));
?>
<main class="content container-fluid teacher-library tests-library">
 <section class="library-hero">
  <div class="library-hero-copy"><span class="eyebrow">EVALUACIÓN Y PRÁCTICA</span><h2>Tests para avanzar con seguridad.</h2><p>Encuentra las evaluaciones asignadas a tu área, practica a tu ritmo y accede a cada recurso desde un espacio ordenado.</p><div class="library-hero-meta"><span><b><?=count($items)?></b> disponibles</span><span><b><?=e($user['subject']??'General')?></b> asignatura</span><span><b>24/7</b> acceso</span></div></div>
  <div class="library-hero-visual" aria-hidden="true"><div class="visual-ring"><span>✓</span></div><small>PREPARACIÓN<br>DOCENTE 2026</small></div>
 </section>

 <?php if(!$enabled):?>
  <section class="library-locked"><span class="locked-icon">T</span><div><small>MÓDULO NO HABILITADO</small><h2>Tu acceso a tests aún no está activo.</h2><p>Cuando el administrador habilite este módulo en tu perfil, aquí aparecerán las evaluaciones correspondientes a tu asignatura.</p><a class="btn btn-brand" href="<?=url('/contacto')?>">Solicitar acceso →</a></div></section>
 <?php else:?>
  <section class="library-toolbar">
   <div><span class="module-kicker">BIBLIOTECA PERSONAL</span><h2>Mis tests</h2><p>Solo ves contenido activo y compatible con tu asignatura.</p></div>
   <label class="library-search"><span>⌕</span><input type="search" data-resource-search placeholder="Buscar un test..." aria-label="Buscar un test"></label>
  </section>
  <div class="library-summary"><div><span class="summary-icon">T</span><p><b><?=count($items)?></b><small>Total de tests</small></p></div><div><span class="summary-icon">↓</span><p><b><?=$downloadable?></b><small>Descargables</small></p></div><div><span class="summary-icon">↗</span><p><b><?=$online?></b><small>Actividades en línea</small></p></div></div>

  <?php if(!$items):?><section class="library-empty"><span>✓</span><h2>Aún no hay tests publicados.</h2><p>Los nuevos recursos aparecerán automáticamente cuando sean asignados a tu área.</p><a href="<?=url('/docente')?>">Volver al inicio →</a></section><?php else:?>
   <section class="library-grid" data-resource-list>
    <?php foreach($items as $index=>$item):?>
     <article class="library-card" data-resource-card data-search="<?=e(strtolower($item['name'].' '.$item['description'].' '.($item['subject']??'')))?>">
      <div class="library-card-top"><span class="card-number"><?=str_pad((string)($index+1),2,'0',STR_PAD_LEFT)?></span><span class="card-status"><i></i> Disponible</span></div>
      <div class="library-card-icon">✓</div><span class="resource-meta"><?=e(strtoupper($item['subject']?:'Todas las asignaturas'))?></span><h3><?=e($item['name'])?></h3><p><?=e($item['description']?:'Evaluación disponible para fortalecer tu preparación docente.')?></p>
      <div class="library-card-footer"><div><small>FORMATOS</small><span><?=$item['file_path']?'Descarga':''?><?=$item['file_path']&&$item['external_link']?' · ':''?><?=$item['external_link']?'En línea':''?></span></div><div class="library-actions"><?php if($item['file_path']):?><a class="btn btn-brand btn-sm" href="<?=url('/docente/descargar?id='.$item['id'])?>">Descargar ↓</a><?php endif;?><?php if($item['external_link']):?><a class="btn btn-outline-primary btn-sm" href="<?=e($item['external_link'])?>" target="_blank" rel="noopener noreferrer">Acceder al test ↗</a><?php endif;?></div></div>
     </article>
    <?php endforeach;?>
   </section>
   <div class="library-no-results d-none" data-resource-empty>No encontramos tests con ese nombre.</div>
  <?php endif;?>
 <?php endif;?>
</main>
