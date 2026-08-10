<!doctype html>
<html lang="es">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width,initial-scale=1">
 <meta name="description" content="<?=e($settings['title'])?> · Ruta Docente">
 <title><?=e($settings['title'])?> | Ruta Docente</title>
 <link rel="stylesheet" href="<?=url('/assets/css/site.css?v=20260810-accesshub1')?>">
 <link rel="icon" href="<?=url('/assets/img/logo-ruta-docente.png')?>">
</head>
<body class="registration-page">
<div class="topbar"><div class="container"><span>Apoyo docente para todo Chile</span><div><a href="mailto:aulaentretenida0@gmail.com">✉ aulaentretenida0@gmail.com</a><a href="tel:+56975778434">☎ +56 9 7577 8434</a></div></div></div>
<header>
 <div class="container header-inner">
  <a class="brand" href="<?=url('/')?>"><img src="<?=url('/assets/img/logo-ruta-docente.png')?>" alt="Logo Ruta Docente"><div><strong>Ruta Docente</strong><small>Portafolio Docente 2026</small></div></a>
  <button class="menu" aria-label="Abrir menú" aria-expanded="false">☰</button>
  <nav><a href="<?=url('/')?>">Inicio</a><a href="<?=url('/asignaturas')?>">Asignaturas</a><a href="<?=url('/portafolio')?>">Portafolio</a><a href="<?=url('/clases-asincronicas')?>">Clases asincrónicas</a><a href="<?=url('/tests')?>">Tus test</a><a href="<?=url('/tabuladores')?>">Tabuladores</a><a href="<?=url('/recursos')?>">Recursos</a><a class="active" href="<?=url('/inscripcion')?>">Talleres disponibles</a><a href="<?=url('/contacto')?>">Contacto</a><a href="<?=url('/preguntas-frecuentes')?>">Preguntas frecuentes</a></nav>
  <a class="pill header-cta" href="<?=url('/login')?>">Acceso docente</a>
 </div>
</header>

<main>
 <section class="registration-hero">
  <div class="container registration-hero-grid">
   <div>
    <span class="eyebrow pale"><?=e($settings['eyebrow'])?></span>
    <h1><?=e($settings['title'])?></h1>
    <p><?=e($settings['intro'])?></p>
    <div class="registration-meta"><span>✓ Formulario seguro</span><span>✓ Datos protegidos</span><span>✓ Confirmación inmediata</span></div>
   </div>
   <?php if(!empty($settings['bank_enabled'])):?><div class="registration-price"><small>VALOR DEL TALLER</small><strong><?=e($settings['bank_amount'])?></strong><span>Incluye materiales y recursos</span></div><?php endif;?>
  </div>
 </section>

 <section class="registration-content" id="formulario">
  <div class="container registration-grid">
   <div class="registration-main">
    <?php if($success):?>
     <section class="registration-success" role="status"><span>✓</span><div><small>RESPUESTA REGISTRADA</small><h2><?=e($settings['success_title'])?></h2><p><?=e($success)?></p><a class="btn" href="<?=url('/')?>">Volver al inicio →</a></div></section>
    <?php elseif(($settings['status']??'closed')!=='open'):?>
     <section class="registration-closed"><span>FORMULARIO CERRADO</span><h2>Las inscripciones no están disponibles en este momento.</h2><p>Si necesitas orientación, escríbenos y te ayudaremos a revisar las próximas fechas.</p><a class="btn" href="<?=url('/contacto')?>">Contactar a Ruta Docente →</a></section>
    <?php else:?>
     <div class="registration-form-heading"><span class="eyebrow">FORMULARIO EN LÍNEA</span><h2>Completa la información solicitada.</h2><p>Los campos marcados con <b>*</b> son obligatorios.</p></div>
     <form class="registration-form" method="post" enctype="multipart/form-data" action="<?=url($formUrl)?>" novalidate>
      <?=csrf_field()?><label class="form-honeypot" aria-hidden="true">Sitio web<input name="website" tabindex="-1" autocomplete="off"></label>
      <?php foreach($fields as $field):$id=(int)$field['id'];$type=$field['field_type'];$value=$old['field'][$id]??'';$error=$errors[$id]??null;?>
       <fieldset class="registration-field <?=$error?'has-error':''?> <?=$type==='file'?'is-file':''?>">
        <legend><?=e($field['label'])?><?=$field['required']?' <span>*</span>':''?></legend>
        <?php if(in_array($type,['text','email','tel','number','date'],true)):?>
         <input type="<?=$type?>" name="field[<?=$id?>]" value="<?=e(is_scalar($value)?$value:'')?>" placeholder="<?=e($field['placeholder'])?>" <?=$field['required']?'required':''?> <?=$error?'aria-invalid="true"':''?>>
        <?php elseif($type==='textarea'):?>
         <textarea name="field[<?=$id?>]" rows="6" placeholder="<?=e($field['placeholder'])?>" <?=$field['required']?'required':''?> <?=$error?'aria-invalid="true"':''?>><?=e(is_scalar($value)?$value:'')?></textarea>
        <?php elseif($type==='select'):?>
         <select name="field[<?=$id?>]" <?=$field['required']?'required':''?>><option value="">Selecciona una opción</option><?php foreach($field['options'] as $option):?><option value="<?=e($option)?>" <?=$value===$option?'selected':''?>><?=e($option)?></option><?php endforeach;?></select>
        <?php elseif($type==='radio'):?>
         <div class="choice-list"><?php foreach($field['options'] as $index=>$option):?><label><input type="radio" name="field[<?=$id?>]" value="<?=e($option)?>" <?=$value===$option?'checked':''?> <?=$field['required']&&$index===0?'required':''?>><span><?=e($option)?></span></label><?php endforeach;?></div>
        <?php elseif($type==='checkbox_group'):?>
         <?php $selected=is_array($value)?$value:[];?><div class="choice-list" data-max-checks="<?=e($field['max_selections'])?>"><?php foreach($field['options'] as $option):?><label><input type="checkbox" name="field[<?=$id?>][]" value="<?=e($option)?>" <?=in_array($option,$selected,true)?'checked':''?>><span><?=e($option)?></span></label><?php endforeach;?></div>
        <?php elseif($type==='checkbox'):?>
         <label class="single-check"><input type="checkbox" name="field[<?=$id?>]" value="1" <?=$value==='1'?'checked':''?> <?=$field['required']?'required':''?>><span><?=e($field['placeholder']?:'Sí, acepto')?></span></label>
        <?php elseif($type==='file'):?>
         <label class="file-upload"><span class="file-upload-icon">⇧</span><span><b>Adjuntar archivo</b><small>JPG, PNG, WEBP o PDF · máximo <?=e(config('form_max_upload_mb'))?> MB</small></span><input type="file" name="field_file[<?=$id?>]" accept=".jpg,.jpeg,.png,.webp,.pdf" <?=$field['required']?'required':''?>></label>
        <?php endif;?>
        <?php if(!empty($field['help_text'])):?><small class="field-help"><?=e($field['help_text'])?></small><?php endif;?>
        <?php if($error):?><small class="field-error" role="alert"><?=e($error)?></small><?php endif;?>
       </fieldset>
      <?php endforeach;?>
      <?php if(!empty($settings['consent_text'])):?><label class="registration-consent <?=!empty($errors['_consent'])?'has-error':''?>"><input type="checkbox" name="consent" value="1" required><span><?=e($settings['consent_text'])?></span></label><?php if(!empty($errors['_consent'])):?><small class="field-error consent-error" role="alert"><?=e($errors['_consent'])?></small><?php endif;?><?php endif;?>
      <button class="btn registration-submit" type="submit"><?=e($settings['submit_label'])?> <span>→</span></button>
      <p class="registration-privacy">Tus datos se utilizarán únicamente para gestionar esta inscripción. Nunca envíes contraseñas.</p>
     </form>
    <?php endif;?>
   </div>

   <aside class="registration-aside">
    <?php if(!empty($settings['information_title'])||!empty($settings['information_body'])):?><section class="info-card"><span class="info-card-number">01</span><small>INFORMACIÓN</small><h2><?=e($settings['information_title'])?></h2><div class="info-rich"><?php foreach(preg_split('/\R\R+/',trim((string)$settings['information_body']))?:[] as $paragraph):?><p><?=nl2br(e($paragraph))?></p><?php endforeach;?></div></section><?php endif;?>
    <?php if(!empty($settings['bank_enabled'])):?><section class="bank-card"><div class="bank-card-top"><span>02</span><small>TRANSFERENCIA</small></div><h2><?=e($settings['bank_title'])?></h2><div class="bank-amount"><small>VALOR</small><strong><?=e($settings['bank_amount'])?></strong></div><dl>
      <div><dt>Titular</dt><dd><?=e($settings['bank_holder'])?></dd></div>
      <div><dt>RUT</dt><dd><?=e($settings['bank_rut'])?></dd></div>
      <div><dt>Banco</dt><dd><?=e($settings['bank_name'])?></dd></div>
      <div><dt>Tipo de cuenta</dt><dd><?=e($settings['bank_account_type'])?></dd></div>
      <div><dt>Número de cuenta</dt><dd><span><?=e($settings['bank_account_number'])?></span><button type="button" data-copy="<?=e($settings['bank_account_number'])?>">Copiar</button></dd></div>
      <?php if(!empty($settings['bank_email'])):?><div><dt>Correo</dt><dd><?=e($settings['bank_email'])?></dd></div><?php endif;?>
     </dl><p class="bank-instructions"><?=nl2br(e($settings['bank_instructions']))?></p></section><?php endif;?>
    <section class="help-card"><span>¿Necesitas ayuda?</span><h3>Te acompañamos en el proceso.</h3><a href="https://wa.me/56975778434">Hablar por WhatsApp →</a></section>
   </aside>
  </div>
 </section>
</main>

<a class="whatsapp" href="https://wa.me/56975778434" aria-label="WhatsApp">◉</a>
<footer><div class="container footer-grid"><div><a class="brand light" href="<?=url('/')?>"><img src="<?=url('/assets/img/logo-ruta-docente.png')?>" alt="Ruta Docente"><div><strong>Ruta Docente</strong><small>Enseñar, avanzar, transformar.</small></div></a><p>Recursos claros y acompañamiento cercano para fortalecer tu práctica y tu portafolio.</p></div><div><h3>Explora</h3><a href="<?=url('/portafolio')?>">Portafolio</a><a href="<?=url('/recursos')?>">Recursos</a><a href="<?=url('/inscripcion')?>">Talleres disponibles</a></div><div><h3>Ayuda</h3><a href="<?=url('/preguntas-frecuentes')?>">Preguntas frecuentes</a><a href="<?=url('/contacto')?>">Contacto</a></div><div><h3>Conversemos</h3><a href="mailto:aulaentretenida0@gmail.com">aulaentretenida0@gmail.com</a><a href="tel:+56975778434">+56 9 7577 8434</a></div></div><div class="container copyright"><span>© 2026 Ruta Docente. Todos los derechos reservados.</span><span>Hecho con dedicación para docentes de Chile 🇨🇱</span></div></footer>
<script src="<?=url('/assets/js/site-public.js')?>"></script>
</body>
</html>
