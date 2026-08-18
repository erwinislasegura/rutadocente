<!doctype html>
<html lang="es">
<head>
 <meta charset="utf-8">
 <meta name="viewport" content="width=device-width,initial-scale=1">
 <meta name="description" content="Talleres, actividades y formularios de inscripción disponibles para docentes en Ruta Docente.">
 <title>Talleres disponibles | Ruta Docente</title>
 <link rel="stylesheet" href="<?=url('/assets/css/site.css?v=20260818-public-v3')?>">
 <link rel="icon" href="<?=url('/assets/img/logo-ruta-docente.png')?>">
</head>
<body class="workshops-page">
<?php require __DIR__.'/_header.php'; ?>

<main>
 <section class="workshops-hero">
  <div class="container workshops-hero-grid"><div><span class="eyebrow pale">FORMACIÓN Y ACOMPAÑAMIENTO</span><h1>Talleres disponibles</h1><p>Explora las actividades abiertas, revisa sus detalles y completa tu inscripción en línea de manera rápida y segura.</p><div class="registration-meta"><span>✓ Contenido actualizado</span><span>✓ Inscripción en línea</span><span>✓ Acompañamiento docente</span></div></div><div class="workshops-hero-mark"><strong><?=count($forms)?></strong><span><?=count($forms)===1?'actividad disponible':'actividades disponibles'?></span></div></div>
 </section>
 <section class="workshops-content">
  <div class="container">
   <div class="workshops-heading"><div><span class="eyebrow">ELIGE TU PRÓXIMO TALLER</span><h2>Avanza con una experiencia diseñada para docentes.</h2></div><p>Cada actividad cuenta con su propio formulario, información y proceso de inscripción.</p></div>
   <?php if(!$forms):?>
    <div class="workshops-empty"><span>PRÓXIMAMENTE</span><h2>Estamos preparando nuevas actividades.</h2><p>Muy pronto encontrarás aquí los próximos talleres disponibles.</p><a class="btn" href="<?=url('/contacto')?>">Consultar próximas fechas →</a></div>
   <?php else:?>
    <div class="workshops-grid">
     <?php foreach($forms as $index=>$form):$path='/inscripcion?form='.rawurlencode($form['slug']);$intro=trim(strip_tags((string)$form['intro']));$excerpt=function_exists('mb_substr')?mb_substr($intro,0,170):substr($intro,0,170);?>
      <article class="workshop-card">
       <a class="workshop-card-image" href="<?=url($path)?>" aria-label="Ver <?=e($form['name'])?>">
        <?php if(!empty($form['cover_image'])):?><img loading="lazy" src="<?=url('/formulario/portada?id='.$form['id'].'&v='.urlencode($form['updated_at']))?>" alt="<?=e($form['name'])?>"><?php else:?><span><b>RUTA DOCENTE</b><small>Imagen de portada pendiente</small></span><?php endif;?>
        <i><?=str_pad((string)($index+1),2,'0',STR_PAD_LEFT)?></i>
       </a>
       <div class="workshop-card-body"><div class="workshop-card-meta"><span>INSCRIPCIÓN ABIERTA</span><?php if(!empty($form['bank_enabled'])&&!empty($form['bank_amount'])):?><strong><?=e($form['bank_amount'])?></strong><?php endif;?></div><h2><?=e($form['name']?:$form['title'])?></h2><?php if($form['title']&&$form['title']!==$form['name']):?><h3><?=e($form['title'])?></h3><?php endif;?><p><?=e($excerpt?:'Revisa la información de esta actividad y completa tus datos para participar.')?><?=$intro!==$excerpt?'…':''?></p><div class="workshop-card-footer"><span><b><?=$form['active_field_count']?></b> datos solicitados</span><a href="<?=url($path)?>">Ver taller e inscribirme <b>→</b></a></div></div>
      </article>
     <?php endforeach;?>
    </div>
   <?php endif;?>
  </div>
 </section>
</main>

<?php require __DIR__.'/_footer.php'; ?>
<script src="<?=url('/assets/js/site-public.js')?>"></script>
</body>
</html>
