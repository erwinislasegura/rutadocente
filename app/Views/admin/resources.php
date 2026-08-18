<?php
$isTest=$type==='test';
$activeCount=count(array_filter($resources,fn($resource)=>!empty($resource['active'])));
$fileCount=count(array_filter($resources,fn($resource)=>!empty($resource['file_path'])));
$linkCount=count(array_filter($resources,fn($resource)=>!empty($resource['external_link'])));
?>
<main class="content container-fluid admin-resource-page <?=$isTest?'tests-admin-page':'tabulators-admin-page'?>">
 <header class="admin-resource-hero">
  <div class="admin-resource-title">
   <span class="admin-resource-kicker"><?=$isTest?'EVALUACIÓN Y PRÁCTICA':'ANÁLISIS PEDAGÓGICO'?></span>
   <h2><?=$isTest?'Tests':'Talleres asincrónicos'?></h2>
   <p><?=$isTest?'Gestiona evaluaciones, archivos y actividades en línea según cada asignatura.':'Organiza herramientas de análisis por asignatura, grupo y subgrupo.'?></p>
  </div>
  <a class="btn btn-brand admin-resource-create" href="<?=url('/admin/recursos/formulario?type='.$type)?>">+ Crear <?=$isTest?'test':'taller asincrónico'?></a>
  <div class="admin-resource-stats">
   <div><span class="resource-stat-icon">Σ</span><p><strong><?=count($resources)?></strong><small>Total registrado</small></p></div>
   <div><span class="resource-stat-icon active">✓</span><p><strong><?=$activeCount?></strong><small>Visibles</small></p></div>
   <div><span class="resource-stat-icon file">↓</span><p><strong><?=$fileCount?></strong><small>Con archivo</small></p></div>
   <div><span class="resource-stat-icon link">↗</span><p><strong><?=$linkCount?></strong><small>Con enlace</small></p></div>
  </div>
 </header>

 <section class="admin-resource-toolbar">
  <div><span class="eyebrow">BIBLIOTECA</span><h3>Contenido disponible</h3><p>Revisa el alcance, los formatos y el estado de cada recurso.</p></div>
  <label class="admin-resource-search"><span>⌕</span><input type="search" placeholder="Buscar por nombre o asignatura..." aria-label="Buscar recursos" data-admin-resource-search></label>
 </section>

 <?php if(!$resources):?>
  <section class="admin-resource-empty"><span><?=$isTest?'✓':'▦'?></span><h3>Aún no hay <?=$isTest?'tests':'talleres asincrónicos'?>.</h3><p>Crea el primer recurso para comenzar a construir la biblioteca docente.</p><a class="btn btn-brand" href="<?=url('/admin/recursos/formulario?type='.$type)?>">Crear ahora →</a></section>
 <?php else:?>
  <section class="admin-resource-list" data-admin-resource-list>
   <div class="admin-resource-list-head" aria-hidden="true"><span>Recurso</span><span>Disponibilidad</span><span>Estado</span><span>Acciones</span></div>
   <?php foreach($resources as $index=>$r):$search=strtolower($r['name'].' '.($r['description']??'').' '.($r['subject']??'').' '.($r['group_name']??'').' '.($r['subgroup_name']??''));?>
    <article class="admin-resource-list-row <?=$r['active']?'is-active':'is-inactive'?>" data-admin-resource-card data-search="<?=e($search)?>">
     <div class="admin-resource-main">
      <span class="admin-resource-symbol"><?=$isTest?'✓':'▦'?></span>
      <div><span class="resource-meta"><?=str_pad((string)($index+1),2,'0',STR_PAD_LEFT)?> · <?=e(strtoupper($r['subject']??'Todas las asignaturas'))?></span><h3><?=e($r['name'])?></h3><p class="admin-resource-description"><?=e($r['description']?:'Sin descripción')?></p><?php if(!$isTest&&($r['group_name']||$r['subgroup_name'])):?><div class="admin-resource-taxonomy"><?php if($r['group_name']):?><span><?=e($r['group_name'])?></span><?php endif;?><?php if($r['subgroup_name']):?><span><?=e($r['subgroup_name'])?></span><?php endif;?></div><?php endif;?></div>
     </div>
     <div class="admin-resource-formats"><span class="<?=$r['file_path']?'available':''?>">↓ <?=$r['file_path']?'Archivo':'Sin archivo'?></span><span class="<?=$r['external_link']?'available':''?>">↗ <?=$r['external_link']?'Enlace activo':'Sin enlace'?></span></div>
     <span class="admin-resource-status"><i></i><?=$r['active']?'Visible':'Oculto'?></span>
     <div class="admin-resource-actions"><a class="resource-view-action" href="<?=url('/admin/recursos/ver?id='.$r['id'])?>">Ver</a><a class="resource-edit-action" href="<?=url('/admin/recursos/formulario?id='.$r['id'])?>">Editar</a><form method="post" action="<?=url('/admin/recursos/eliminar')?>" onsubmit="return confirm('¿Eliminar este recurso?')"><?=csrf_field()?><input type="hidden" name="id" value="<?=$r['id']?>"><input type="hidden" name="type" value="<?=$type?>"><button type="submit" aria-label="Eliminar <?=e($r['name'])?>">Eliminar</button></form></div>
    </article>
   <?php endforeach;?>
  </section>
  <div class="admin-resource-no-results d-none" data-admin-resource-empty>No encontramos recursos con ese nombre o asignatura.</div>
 <?php endif;?>
</main>
<script>
document.addEventListener('DOMContentLoaded',()=>{const input=document.querySelector('[data-admin-resource-search]');if(!input)return;const cards=[...document.querySelectorAll('[data-admin-resource-card]')],empty=document.querySelector('[data-admin-resource-empty]');input.addEventListener('input',()=>{const term=input.value.trim().toLocaleLowerCase('es');let visible=0;cards.forEach(card=>{const show=!term||(card.dataset.search||'').includes(term);card.classList.toggle('d-none',!show);if(show)visible++});empty?.classList.toggle('d-none',visible!==0)})});
</script>
