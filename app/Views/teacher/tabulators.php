<?php
$groups=[];
foreach($items as $item){$group=$item['group_name']?:'Recursos generales';$groups[$group][]=$item;}
$downloadable=count(array_filter($items,fn($item)=>!empty($item['file_path'])));
?>
<main class="content container-fluid teacher-library tabulators-library">
 <section class="library-hero tabulator-hero">
  <div class="library-hero-copy"><span class="eyebrow">ANÁLISIS DE RESULTADOS</span><h2>Talleres asincrónicos para avanzar a tu ritmo.</h2><p>Organiza resultados, identifica avances y transforma los datos de tus evaluaciones en acciones pedagógicas concretas.</p><div class="library-hero-meta"><span><b><?=count($items)?></b> herramientas</span><span><b><?=count($groups)?></b> grupos</span><span><b><?=e($user['subject']??'General')?></b> asignatura</span></div></div>
  <div class="library-hero-visual chart-visual" aria-hidden="true"><div class="mini-bars"><i></i><i></i><i></i><i></i></div><small>ANÁLISIS<br>PEDAGÓGICO</small></div>
 </section>

 <?php if(!$enabled):?>
  <section class="library-locked"><span class="locked-icon">▦</span><div><small>MÓDULO NO HABILITADO</small><h2>Tu acceso a talleres asincrónicos aún no está activo.</h2><p>Solicita la habilitación para consultar las herramientas de análisis asignadas a tu perfil.</p><a class="btn btn-brand" href="<?=url('/contacto')?>">Solicitar acceso →</a></div></section>
 <?php else:?>
  <section class="library-toolbar">
   <div><span class="module-kicker">HERRAMIENTAS DE ANÁLISIS</span><h2>Mis talleres asincrónicos</h2><p>Organizados por grupo para que encuentres rápidamente lo que necesitas.</p></div>
   <label class="library-search"><span>⌕</span><input type="search" data-resource-search placeholder="Buscar un taller..." aria-label="Buscar un taller"></label>
  </section>
  <div class="library-summary tabulator-summary"><div><span class="summary-icon">▦</span><p><b><?=count($items)?></b><small>Total disponible</small></p></div><div><span class="summary-icon">◫</span><p><b><?=count($groups)?></b><small>Grupos de análisis</small></p></div><div><span class="summary-icon">↓</span><p><b><?=$downloadable?></b><small>Plantillas descargables</small></p></div></div>

  <?php if(!$items):?><section class="library-empty"><span>▦</span><h2>Aún no hay talleres asincrónicos publicados.</h2><p>Las nuevas herramientas aparecerán aquí cuando sean asignadas a tu área.</p><a href="<?=url('/docente')?>">Volver al inicio →</a></section><?php else:?>
   <div data-resource-list>
    <?php foreach($groups as $groupName=>$groupItems):?><section class="resource-group" data-resource-group><div class="resource-group-heading"><div><span class="group-mark">▦</span><div><small>GRUPO DE RECURSOS</small><h2><?=e($groupName)?></h2></div></div><span><?=count($groupItems)?> <?=count($groupItems)===1?'herramienta':'herramientas'?></span></div><div class="library-grid">
     <?php foreach($groupItems as $index=>$item):?>
      <article class="library-card tabulator-card" data-resource-card data-search="<?=e(strtolower($item['name'].' '.$item['description'].' '.($item['subject']??'').' '.($item['group_name']??'').' '.($item['subgroup_name']??'')))?>">
       <div class="library-card-top"><span class="card-number"><?=str_pad((string)($index+1),2,'0',STR_PAD_LEFT)?></span><span class="card-status"><i></i> Activo</span></div>
       <div class="library-card-icon">▦</div><span class="resource-meta"><?=e(strtoupper($item['subgroup_name']?:($item['subject']?:'Recurso general')))?></span><h3><?=e($item['name'])?></h3><p><?=e($item['description']?:'Herramienta disponible para organizar y analizar resultados.')?></p>
       <div class="library-card-footer"><div><small>ACCESO</small><span><?=$item['file_path']?'Plantilla':''?><?=$item['file_path']&&$item['external_link']?' · ':''?><?=$item['external_link']?'En línea':''?></span></div><div class="library-actions"><?php if($item['file_path']):?><a class="btn btn-brand btn-sm" href="<?=url('/docente/descargar?id='.$item['id'])?>">Descargar ↓</a><?php endif;?><?php if($item['external_link']):?><a class="btn btn-outline-primary btn-sm" href="<?=e($item['external_link'])?>" target="_blank" rel="noopener noreferrer">Acceder al taller ↗</a><?php endif;?></div></div>
      </article>
     <?php endforeach;?>
    </div></section><?php endforeach;?>
   </div>
   <div class="library-no-results d-none" data-resource-empty>No encontramos talleres con ese nombre.</div>
  <?php endif;?>
 <?php endif;?>
</main>
