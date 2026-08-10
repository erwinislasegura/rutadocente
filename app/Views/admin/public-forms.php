<?php $openCount=count(array_filter($forms,fn($form)=>$form['status']==='open'));$responseCount=array_sum(array_column($forms,'submission_count')); ?>
<main class="content container-fluid forms-manager-page">
 <header class="forms-manager-head">
  <div><span class="eyebrow">INSCRIPCIONES Y SOLICITUDES</span><h2>Formularios públicos</h2><p>Crea formularios independientes y revisa sus respuestas de manera ordenada.</p></div>
  <button class="btn btn-brand" type="button" data-bs-toggle="collapse" data-bs-target="#newPublicForm" aria-expanded="false">+ Nuevo formulario</button>
 </header>
 <section class="collapse" id="newPublicForm">
  <form class="new-public-form" method="post" action="<?=url('/admin/formulario/crear')?>"><?=csrf_field()?>
   <div><span class="form-manager-icon">+</span><div><strong>Crear formulario</strong><small>Podrás configurar textos, preguntas y pagos en el siguiente paso.</small></div></div>
   <label><span>Nombre interno</span><input class="form-control" name="name" required maxlength="160" placeholder="Ej: Taller de agosto 2026"></label>
   <label><span>URL personalizada <small>(opcional)</small></span><div class="form-slug-input"><i>/inscripcion?form=</i><input class="form-control" name="slug" maxlength="120" placeholder="taller-agosto"></div></label>
   <button class="btn btn-brand" type="submit">Crear y configurar →</button>
  </form>
 </section>
 <div class="forms-manager-summary"><div><strong><?=count($forms)?></strong><span>Formularios</span></div><div><strong><?=$openCount?></strong><span>Recibiendo respuestas</span></div><div><strong><?=$responseCount?></strong><span>Respuestas totales</span></div></div>
 <?php if(!$forms):?><section class="admin-resource-empty"><span>F</span><h3>Aún no existen formularios.</h3><p>Crea el primero para comenzar a recibir información.</p></section><?php else:?>
 <section class="forms-manager-list">
  <div class="forms-manager-list-head"><span>Formulario</span><span>Contenido</span><span>Respuestas</span><span>Estado</span><span>Acciones</span></div>
  <?php foreach($forms as $form):$publicUrl='/inscripcion'.($form['slug']==='inscripcion'?'':'?form='.rawurlencode($form['slug']));?>
   <article class="forms-manager-row">
    <div class="form-manager-identity"><span>F</span><div><strong><?=e($form['name']?:$form['title'])?></strong><small><?=e($form['title'])?></small><a href="<?=url($publicUrl)?>" target="_blank" rel="noopener noreferrer"><?=e($publicUrl)?> ↗</a></div></div>
    <div class="form-manager-content"><span><b><?=$form['field_count']?></b> preguntas</span><small><?=$form['active_field_count']?> visibles</small></div>
    <div class="form-manager-responses"><strong><?=$form['submission_count']?></strong><span>recibidas</span></div>
    <span class="status-chip <?=$form['status']==='open'?'is-open':'is-closed'?>"><?=$form['status']==='open'?'Abierto':'Cerrado'?></span>
    <div class="form-manager-actions"><a href="<?=url('/admin/formulario/editar?id='.$form['id'])?>">Configurar formulario</a><a href="<?=url('/admin/formulario/editar?id='.$form['id'].'#respuestas')?>"><?=$form['submission_count']?> respuestas →</a><a href="<?=url($publicUrl)?>" target="_blank" rel="noopener noreferrer">Vista pública ↗</a></div>
   </article>
  <?php endforeach;?>
 </section>
 <?php endif;?>
</main>
