<?php
namespace App\Core;
use App\Models\SiteSetting;
class Controller {
 protected function view(string $view,array $data=[]):void{extract($data);$viewFile=dirname(__DIR__).'/Views/'.$view.'.php';require dirname(__DIR__).'/Views/layout.php';}
 protected function publicPage(string $view,array $data=[]):void{
  extract($data);
  ob_start();
  require dirname(__DIR__).'/Views/public/'.$view.'.php';
  $html=(string)ob_get_clean();
  $analytics=(new SiteSetting)->analytics();
  if($analytics['enabled']&&preg_match('/^G-[A-Z0-9]{4,20}$/',$analytics['measurement_id'])){
   $id=$analytics['measurement_id'];
   $tag='<script async src="https://www.googletagmanager.com/gtag/js?id='.rawurlencode($id).'"></script>' . "\n"
    .'<script>window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments)}gtag(\'js\',new Date());gtag(\'config\','.json_encode($id).');document.addEventListener(\'DOMContentLoaded\',function(){if(document.querySelector(\'.registration-success\'))gtag(\'event\',\'generate_lead\',{form_name:\'inscripcion_publica\'});});</script>' . "\n";
   $html=preg_replace('/<\/head>/i',$tag.'</head>',$html,1)??$html;
  }
  echo $html;
 }
 protected function admin():array{if(empty($_SESSION['user'])||$_SESSION['user']['role']!=='administrador')redirect('/login');return $_SESSION['user'];}
 protected function teacher():array{if(empty($_SESSION['user'])||$_SESSION['user']['role']!=='docente')redirect('/login');return $_SESSION['user'];}
}
