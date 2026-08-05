<?php
namespace App\Controllers;
use App\Core\Controller;use App\Models\User;
class AuthController extends Controller {
 function show():void{if(!empty($_SESSION['user']))redirect($_SESSION['user']['role']==='administrador'?'/admin':'/docente');$this->view('auth/login',['title'=>'Acceso']);}
 function login():void{verify_csrf();$email=strtolower(trim($_POST['email']??''));$u=(new User)->byEmail($email);if(!$u||!password_verify($_POST['password']??'',$u['password'])){flash('error','Correo o contraseña incorrectos.');redirect('/login');}session_regenerate_id(true);unset($u['password']);$_SESSION['user']=$u;redirect($u['role']==='administrador'?'/admin':'/docente');}
 function logout():void{verify_csrf();session_destroy();session_start();redirect('/login');}
}
