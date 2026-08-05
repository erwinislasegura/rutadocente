<?php
namespace App\Core;
class Controller {
 protected function view(string $view,array $data=[]):void{extract($data);$viewFile=dirname(__DIR__).'/Views/'.$view.'.php';require dirname(__DIR__).'/Views/layout.php';}
 protected function publicPage(string $view):void{require dirname(__DIR__).'/Views/public/'.$view.'.php';}
 protected function admin():array{if(empty($_SESSION['user'])||$_SESSION['user']['role']!=='administrador')redirect('/login');return $_SESSION['user'];}
 protected function teacher():array{if(empty($_SESSION['user'])||$_SESSION['user']['role']!=='docente')redirect('/login');return $_SESSION['user'];}
}
