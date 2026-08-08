<?php
function config(string $key=null){static $c;if(!$c)$c=require dirname(__DIR__,2).'/config/app.php';return $key?$c[$key]:$c;}
function url(string $path=''):string{return rtrim(config('base_url'),'/').'/'.ltrim($path,'/');}
function absolute_url(string $path=''):string{return rtrim(config('site_url'),'/').'/'.ltrim($path,'/');}
function e($v):string{return htmlspecialchars((string)$v,ENT_QUOTES,'UTF-8');}
function redirect(string $path):never{header('Location: '.url($path));exit;}
function csrf():string{if(empty($_SESSION['csrf']))$_SESSION['csrf']=bin2hex(random_bytes(32));return $_SESSION['csrf'];}
function csrf_field():string{return '<input type="hidden" name="csrf" value="'.e(csrf()).'">';}
function verify_csrf():void{if(!hash_equals($_SESSION['csrf']??'',$_POST['csrf']??'')){http_response_code(419);exit('Sesión vencida. Recarga la página.');}}
function flash(string $key,string $value=null){if($value!==null){$_SESSION['_flash'][$key]=$value;return;} $v=$_SESSION['_flash'][$key]??null;unset($_SESSION['_flash'][$key]);return $v;}
