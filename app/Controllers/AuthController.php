<?php
namespace App\Controllers;
use App\Core\Controller;use App\Models\User;
class AuthController extends Controller {
 function show():void{if(!empty($_SESSION['user']))redirect($_SESSION['user']['role']==='administrador'?'/admin':'/docente');$this->view('auth/login',['title'=>'Acceso']);}
 function login():void{verify_csrf();$u=(new User)->byEmail(trim($_POST['email']??''));$type=$_POST['access_type']??'docente';if(!$u||!password_verify($_POST['password']??'',$u['password'])||$u['role']!==$type){flash('error','Correo, contraseña o tipo de acceso incorrecto.');redirect('/login');}session_regenerate_id(true);unset($u['password']);$_SESSION['user']=$u;redirect($u['role']==='administrador'?'/admin':'/docente');}
 function logout():void{verify_csrf();session_destroy();session_start();redirect('/login');}
}

