<?php
namespace App\Core;
use App\Models\SiteSetting;
use App\Models\User;
use App\Models\PublicForm;
class Controller {
 protected function view(string $view,array $data=[]):void{
  extract($data);$viewFile=dirname(__DIR__).'/Views/'.$view.'.php';
  ob_start();require dirname(__DIR__).'/Views/layout.php';$html=(string)ob_get_clean();
  echo preg_replace('/<\/head>/i','<meta name="robots" content="noindex,nofollow,noarchive"></head>',$html,1)??$html;
 }
 protected function publicPage(string $view,array $data=[]):void{
  extract($data);
  ob_start();
  require dirname(__DIR__).'/Views/public/'.$view.'.php';
  $html=(string)ob_get_clean();
  $html=$this->injectSeo($html,$view);
  $analytics=(new SiteSetting)->analytics();
  if($analytics['enabled']&&preg_match('/^G-[A-Z0-9]{4,20}$/',$analytics['measurement_id'])){
   $id=$analytics['measurement_id'];
   $tag='<script async src="https://www.googletagmanager.com/gtag/js?id='.rawurlencode($id).'"></script>' . "\n"
    .'<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}gtag(\'js\',new Date());gtag(\'config\','.json_encode($id).');document.addEventListener(\'DOMContentLoaded\',function(){if(document.querySelector(\'.registration-success\'))gtag(\'event\',\'generate_lead\',{form_name:\'inscripcion_publica\'});});</script>' . "\n";
   $html=preg_replace('/<\/head>/i',$tag.'</head>',$html,1)??$html;
  }
  echo $html;
 }

 private function injectSeo(string $html,string $view):string {
  $pages=[
   'home'=>['Portafolio Docente 2026, recursos y preparación | Ruta Docente','Recursos, tests, tabuladores, clases asincrónicas y acompañamiento para preparar el Portafolio Docente 2026 en Chile.','/','Inicio'],
   'asignaturas'=>['Recursos docentes por asignatura | Ruta Docente','Material pedagógico para Educación Parvularia, Matemática, Lenguaje, Historia, Inglés y Ciencias, organizado para docentes de Chile.','/asignaturas','Asignaturas'],
   'portafolio'=>['Portafolio Docente 2026: planificación y evidencias','Orientaciones para planificar, preparar la clase grabada, analizar evidencias y fortalecer la reflexión del Portafolio Docente 2026.','/portafolio','Portafolio Docente'],
   'clases-asincronicas'=>['Clases asincrónicas para docentes | Ruta Docente','Clases grabadas, materiales y actividades para avanzar en la preparación docente a tu ritmo y desde cualquier dispositivo.','/clases-asincronicas','Clases asincrónicas'],
   'tests'=>['Tests de preparación docente 2026 | Ruta Docente','Tests y evaluaciones organizados por asignatura para practicar, reconocer avances y fortalecer la preparación docente 2026.','/tests','Tests docentes'],
   'tabuladores'=>['Tabuladores de resultados para docentes | Ruta Docente','Herramientas para registrar, visualizar y analizar resultados de aprendizaje, identificar avances y orientar decisiones pedagógicas.','/tabuladores','Tabuladores'],
   'recursos'=>['Recursos pedagógicos para docentes de Chile','Plantillas, guías, materiales descargables y herramientas para planificar, enseñar, evaluar y fortalecer la práctica docente.','/recursos','Recursos docentes'],
   'contacto'=>['Orientación para docentes y Portafolio 2026 | Contacto','Contacta a Ruta Docente para recibir orientación sobre Portafolio Docente 2026, clases asincrónicas, tests, tabuladores y recursos.','/contacto','Contacto'],
   'preguntas-frecuentes'=>['Preguntas frecuentes sobre Ruta Docente 2026','Respuestas sobre acceso, asignaturas, tests, tabuladores, recursos, dispositivos compatibles y soporte de Ruta Docente.','/preguntas-frecuentes','Preguntas frecuentes'],
   'inscripcion'=>['Inscripción a talleres para docentes | Ruta Docente','Formulario de inscripción a talleres y actividades de preparación para docentes. Revisa la información, completa tus datos y reserva tu cupo.','/inscripcion','Inscripción'],
   'workshops'=>['Talleres disponibles para docentes | Ruta Docente','Explora talleres y actividades disponibles para docentes. Revisa cada programa y completa tu inscripción en línea.','/inscripcion','Talleres disponibles'],
  ];
  $seo=$pages[$view]??$pages['home'];
  if($view==='inscripcion'&&!empty($_GET['form'])){
   $dynamic=(new PublicForm)->settingsBySlug((string)$_GET['form']);
   if($dynamic)$seo=[($dynamic['title']?:$dynamic['name']).' | Ruta Docente',trim((string)$dynamic['intro'])?:'Formulario de inscripción de Ruta Docente.','/inscripcion?form='.rawurlencode((string)$dynamic['slug']),$dynamic['name']?:'Formulario'];
  }
  [$title,$description,$path,$label]=$seo;
  $canonical=absolute_url($path);$logo=absolute_url('/assets/img/logo-ruta-docente.png');
  $graph=[
   ['@type'=>'Organization','@id'=>absolute_url('/').'#organization','name'=>'Ruta Docente','url'=>absolute_url('/'),'logo'=>['@type'=>'ImageObject','url'=>$logo],'email'=>'aulaentretenida0@gmail.com','telephone'=>'+56 9 7577 8434','sameAs'=>['https://www.facebook.com/AulaEntretenida']],
   ['@type'=>'WebSite','@id'=>absolute_url('/').'#website','url'=>absolute_url('/'),'name'=>'Ruta Docente','inLanguage'=>'es-CL','publisher'=>['@id'=>absolute_url('/').'#organization']],
   ['@type'=>'WebPage','@id'=>$canonical.'#webpage','url'=>$canonical,'name'=>$title,'description'=>$description,'inLanguage'=>'es-CL','isPartOf'=>['@id'=>absolute_url('/').'#website'],'about'=>['@id'=>absolute_url('/').'#organization']],
  ];
  if($path!=='/')$graph[]=['@type'=>'BreadcrumbList','itemListElement'=>[['@type'=>'ListItem','position'=>1,'name'=>'Inicio','item'=>absolute_url('/')],['@type'=>'ListItem','position'=>2,'name'=>$label,'item'=>$canonical]]];
  $json=json_encode(['@context'=>'https://schema.org','@graph'=>$graph],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_HEX_TAG);
  $tags='<title>'.e($title).'</title>'
   .'<meta name="description" content="'.e($description).'">'
   .'<meta name="robots" content="index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1">'
   .'<link rel="canonical" href="'.e($canonical).'">'
   .'<meta property="og:locale" content="es_CL"><meta property="og:type" content="website"><meta property="og:site_name" content="Ruta Docente">'
   .'<meta property="og:title" content="'.e($title).'"><meta property="og:description" content="'.e($description).'"><meta property="og:url" content="'.e($canonical).'"><meta property="og:image" content="'.e($logo).'"><meta property="og:image:alt" content="Logo de Ruta Docente">'
   .'<meta property="og:image:width" content="800"><meta property="og:image:height" content="400">'
   .'<meta name="twitter:card" content="summary"><meta name="twitter:title" content="'.e($title).'"><meta name="twitter:description" content="'.e($description).'"><meta name="twitter:image" content="'.e($logo).'">'
   .'<script type="application/ld+json">'.$json.'</script>';
  $html=preg_replace('/<title>.*?<\/title>/is','',$html,1)??$html;
  $html=preg_replace('/<meta\s+name=["\']description["\'][^>]*>/i','',$html,1)??$html;
  return preg_replace('/<\/head>/i',$tags.'</head>',$html,1)??$html;
 }
 protected function admin():array{if(empty($_SESSION['user'])||$_SESSION['user']['role']!=='administrador')redirect('/login');return $_SESSION['user'];}
 protected function teacher():array{
  if(empty($_SESSION['user'])||$_SESSION['user']['role']!=='docente')redirect('/login');
  $user=(new User)->findDetailed((int)$_SESSION['user']['id']);
  if(!$user||$user['role']!=='docente'||empty($user['active'])){
   unset($_SESSION['user']);
   flash('error','Tu cuenta ya no tiene acceso al panel docente.');
   redirect('/login');
  }
  unset($user['password']);
  $_SESSION['user']=$user;
  return $user;
 }
}
