<?php
$fieldTypes=[
 'text'=>'Texto corto','email'=>'Correo electrónico','tel'=>'Teléfono','number'=>'Número','date'=>'Fecha',
 'textarea'=>'Texto largo','select'=>'Lista desplegable','radio'=>'Selección única','checkbox'=>'Casilla de aceptación',
 'checkbox_group'=>'Selección múltiple','file'=>'Archivo / comprobante',
];
$current=$editingField??[];
?>
<main class="content container-fluid public-form-admin">
 <div class="page-toolbar flex-wrap">
  <div><span class="eyebrow">INSCRIPCIONES</span><p class="text-secondary mb-0 mt-2">Configura la página pública, los datos de pago y sus preguntas.</p></div>
  <a class="btn btn-brand" href="<?=url('/inscripcion')?>" target="_blank" rel="noopener">Ver formulario público ↗</a>
 </div>

 <nav class="form-section-nav" aria-label="Secciones del formulario">
  <a href="#informacion">Información</a><a href="#cuenta-bancaria">Cuenta bancaria</a><a href="#campos">Campos</a><a href="#respuestas">Respuestas <span><?=count($submissions)?></span></a>
 </nav>

 <section class="card panel-card form-config-card" id="informacion">
  <div class="card-body p-4 p-xl-5">
   <div class="config-heading"><div><span>01</span><div><h2>Información del formulario</h2><p>Textos principales, disponibilidad y mensaje de confirmación.</p></div></div><span class="status-chip <?=$settings['status']==='open'?'is-open':'is-closed'?>"><?=$settings['status']==='open'?'Recibiendo respuestas':'Formulario cerrado'?></span></div>
   <form method="post" action="<?=url('/admin/formulario/informacion')?>"><?=csrf_field()?>
    <div class="row g-4">
     <div class="col-md-4"><label class="form-label">Texto superior</label><input class="form-control" name="eyebrow" maxlength="80" value="<?=e($settings['eyebrow'])?>"></div>
     <div class="col-md-8"><label class="form-label">Título</label><input class="form-control" name="title" maxlength="180" required value="<?=e($settings['title'])?>"></div>
     <div class="col-12"><label class="form-label">Presentación</label><textarea class="form-control" name="intro" rows="4"><?=e($settings['intro'])?></textarea></div>
     <div class="col-md-5"><label class="form-label">Título del bloque informativo</label><input class="form-control" name="information_title" value="<?=e($settings['information_title'])?>"></div>
     <div class="col-12"><label class="form-label">Información detallada</label><textarea class="form-control" name="information_body" rows="7" placeholder="Una línea por punto o párrafo."><?=e($settings['information_body'])?></textarea></div>
     <div class="col-md-4"><label class="form-label">Estado</label><select class="form-select" name="status"><option value="open" <?=$settings['status']==='open'?'selected':''?>>Abierto</option><option value="closed" <?=$settings['status']==='closed'?'selected':''?>>Cerrado</option></select></div>
     <div class="col-md-4"><label class="form-label">Texto del botón</label><input class="form-control" name="submit_label" value="<?=e($settings['submit_label'])?>"></div>
     <div class="col-md-4"><label class="form-label">Título de confirmación</label><input class="form-control" name="success_title" value="<?=e($settings['success_title'])?>"></div>
     <div class="col-md-6"><label class="form-label">Mensaje de confirmación</label><textarea class="form-control" name="success_message" rows="4"><?=e($settings['success_message'])?></textarea></div>
     <div class="col-md-6"><label class="form-label">Texto de consentimiento</label><textarea class="form-control" name="consent_text" rows="4"><?=e($settings['consent_text'])?></textarea></div>
    </div>
    <div class="form-footer"><button class="btn btn-brand" type="submit">Guardar información</button></div>
   </form>
  </div>
 </section>

 <section class="card panel-card form-config-card" id="cuenta-bancaria">
  <div class="card-body p-4 p-xl-5">
   <div class="config-heading"><div><span>02</span><div><h2>Cuenta bancaria</h2><p>Datos visibles para que el docente realice la transferencia.</p></div></div></div>
   <form method="post" action="<?=url('/admin/formulario/cuenta-bancaria')?>"><?=csrf_field()?>
    <div class="row g-4">
     <div class="col-12"><div class="form-check form-switch permission-check"><input class="form-check-input" type="checkbox" role="switch" id="bank_enabled" name="bank_enabled" value="1" <?=$settings['bank_enabled']?'checked':''?>><label class="form-check-label" for="bank_enabled"><strong>Mostrar datos bancarios</strong><span>La cuenta aparecerá junto al formulario público.</span></label></div></div>
     <div class="col-md-8"><label class="form-label">Título del bloque</label><input class="form-control" name="bank_title" value="<?=e($settings['bank_title'])?>"></div>
     <div class="col-md-4"><label class="form-label">Valor</label><input class="form-control" name="bank_amount" value="<?=e($settings['bank_amount'])?>" placeholder="$25.000"></div>
     <div class="col-md-6"><label class="form-label">Titular</label><input class="form-control" name="bank_holder" value="<?=e($settings['bank_holder'])?>"></div>
     <div class="col-md-6"><label class="form-label">RUT</label><input class="form-control" name="bank_rut" value="<?=e($settings['bank_rut'])?>"></div>
     <div class="col-md-4"><label class="form-label">Banco</label><input class="form-control" name="bank_name" value="<?=e($settings['bank_name'])?>"></div>
     <div class="col-md-4"><label class="form-label">Tipo de cuenta</label><input class="form-control" name="bank_account_type" value="<?=e($settings['bank_account_type'])?>"></div>
     <div class="col-md-4"><label class="form-label">Número de cuenta</label><input class="form-control" name="bank_account_number" value="<?=e($settings['bank_account_number'])?>"></div>
     <div class="col-md-6"><label class="form-label">Correo para transferencia <span class="text-secondary fw-normal">(opcional)</span></label><input class="form-control" type="email" name="bank_email" value="<?=e($settings['bank_email'])?>"></div>
     <div class="col-12"><label class="form-label">Instrucciones</label><textarea class="form-control" name="bank_instructions" rows="4"><?=e($settings['bank_instructions'])?></textarea></div>
    </div>
    <div class="form-footer"><button class="btn btn-brand" type="submit">Guardar cuenta bancaria</button></div>
   </form>
  </div>
 </section>

 <section class="card panel-card form-config-card" id="campos">
  <div class="card-body p-4 p-xl-5">
   <div class="config-heading"><div><span>03</span><div><h2>Campos del formulario</h2><p>Crea preguntas, define su orden y elige cuáles son obligatorias.</p></div></div></div>
   <div class="field-builder-grid">
    <div>
     <?php if(!$fields):?><div class="empty-state">Aún no hay campos configurados.</div><?php endif;?>
     <div class="field-list">
      <?php foreach($fields as $field):?>
       <article class="field-row <?=$field['active']?'':'is-inactive'?>">
        <span class="field-order"><?=e($field['sort_order'])?></span>
        <div><b><?=e($field['label'])?></b><small><?=e($fieldTypes[$field['field_type']]??$field['field_type'])?> · <?=$field['required']?'Obligatorio':'Opcional'?><?=$field['field_type']==='checkbox_group'&&$field['max_selections']?' · Máx. '.e($field['max_selections']):''?></small></div>
        <a class="btn btn-sm btn-outline-secondary" href="<?=url('/admin/formulario?field='.$field['id'].'#campos')?>">Editar</a>
        <form method="post" action="<?=url('/admin/formulario/campos/eliminar')?>" onsubmit="return confirm('¿Eliminar este campo?')"><?=csrf_field()?><input type="hidden" name="id" value="<?=$field['id']?>"><button class="btn btn-sm btn-outline-danger" type="submit" aria-label="Eliminar <?=e($field['label'])?>">×</button></form>
       </article>
      <?php endforeach;?>
     </div>
    </div>
    <form class="field-editor" method="post" action="<?=url('/admin/formulario/campos/guardar')?>">
     <?=csrf_field()?><input type="hidden" name="id" value="<?=e($current['id']??'')?>">
     <div class="d-flex align-items-start justify-content-between gap-3 mb-4"><div><span class="eyebrow"><?=$current?'EDITAR PREGUNTA':'NUEVA PREGUNTA'?></span><h3 class="mt-2 mb-0"><?=$current?'Ajusta este campo':'Agrega un campo'?></h3></div><?php if($current):?><a class="btn btn-sm btn-outline-secondary" href="<?=url('/admin/formulario#campos')?>">Cancelar</a><?php endif;?></div>
     <div class="mb-3"><label class="form-label">Pregunta o etiqueta</label><input class="form-control" required maxlength="180" name="label" value="<?=e($current['label']??'')?>"></div>
     <div class="row g-3">
      <div class="col-md-7"><label class="form-label">Tipo de campo</label><select class="form-select" name="field_type" data-field-type><?php foreach($fieldTypes as $value=>$label):?><option value="<?=$value?>" <?=($current['field_type']??'text')===$value?'selected':''?>><?=e($label)?></option><?php endforeach;?></select></div>
      <div class="col-md-5"><label class="form-label">Orden</label><input class="form-control" type="number" min="0" max="999" name="sort_order" value="<?=e($current['sort_order']??(count($fields)+1)*10)?>"></div>
     </div>
     <div class="mt-3"><label class="form-label">Placeholder</label><input class="form-control" name="placeholder" value="<?=e($current['placeholder']??'')?>" placeholder="Texto de ejemplo"></div>
     <div class="mt-3"><label class="form-label">Texto de ayuda</label><input class="form-control" name="help_text" value="<?=e($current['help_text']??'')?>"></div>
     <div class="mt-3" data-options-wrap><label class="form-label">Opciones <span class="text-secondary fw-normal">(una por línea)</span></label><textarea class="form-control" name="options" rows="6"><?=e(implode("\n",$current['options']??[]))?></textarea></div>
     <div class="mt-3" data-max-selections-wrap><label class="form-label">Máximo de selecciones <span class="text-secondary fw-normal">(0 = sin límite)</span></label><input class="form-control" type="number" min="0" max="20" name="max_selections" value="<?=e($current['max_selections']??0)?>"></div>
     <div class="field-editor-checks mt-4">
      <label><input type="checkbox" name="required" value="1" <?=!isset($current['required'])||$current['required']?'checked':''?>> Campo obligatorio</label>
      <label><input type="checkbox" name="active" value="1" <?=!isset($current['active'])||$current['active']?'checked':''?>> Campo visible</label>
     </div>
     <button class="btn btn-brand w-100 mt-4" type="submit"><?=$current?'Guardar cambios':'Agregar campo'?></button>
    </form>
   </div>
  </div>
 </section>

 <section class="card panel-card form-config-card" id="respuestas">
  <div class="card-body p-4 p-xl-5">
   <div class="config-heading"><div><span>04</span><div><h2>Respuestas recibidas</h2><p>Últimas <?=count($submissions)?> inscripciones y sus comprobantes.</p></div></div></div>
   <?php if(!$submissions):?><div class="empty-state">Todavía no hay respuestas. Comparte la página pública para comenzar.</div><?php else:?>
    <div class="submission-list">
     <?php foreach($submissions as $submission):$answers=json_decode($submission['answers_json'],true)?:[];?>
      <details class="submission-card">
       <summary><span class="submission-id">#<?=$submission['id']?></span><div><b><?=e($submission['contact_name']?:'Sin nombre')?></b><small><?=e($submission['contact_email']?:'Sin correo')?> · <?=e(date('d-m-Y H:i',strtotime($submission['created_at'])))?></small></div><span class="submission-open">Ver detalle</span></summary>
       <div class="submission-answers">
        <?php foreach($answers as $fieldId=>$answer):?>
         <div><small><?=e($answer['label']??'Respuesta')?></small>
          <?php if(($answer['type']??'')==='file'&&is_array($answer['value']??null)):?><a class="brand-link fw-bold" href="<?=url('/admin/formulario/archivo?submission='.$submission['id'].'&field='.(int)$fieldId)?>">Descargar <?=e($answer['value']['original']??'archivo')?> ↓</a>
          <?php elseif(is_array($answer['value']??null)):?><b><?=e(implode(' · ',$answer['value']))?></b>
          <?php else:?><b><?=nl2br(e($answer['value']??''))?></b><?php endif;?>
         </div>
        <?php endforeach;?>
       </div>
      </details>
     <?php endforeach;?>
    </div>
   <?php endif;?>
  </div>
 </section>
</main>
