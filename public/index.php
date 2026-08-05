<?php
declare(strict_types=1); session_start();
spl_autoload_register(function($class){$prefix='App\\';if(str_starts_with($class,$prefix)){$file=dirname(__DIR__).'/app/'.str_replace('\\','/',substr($class,strlen($prefix))).'.php';if(file_exists($file))require $file;}});
require dirname(__DIR__).'/app/Core/helpers.php';
use App\Core\Router; use App\Controllers\{PublicController,AuthController,AdminController,TeacherController};
$r=new Router;
$r->get('/',[PublicController::class,'home']);
$r->get('/asignaturas',[PublicController::class,'asignaturas']);
$r->get('/portafolio',[PublicController::class,'portafolio']);
$r->get('/clases-asincronicas',[PublicController::class,'clasesAsincronicas']);
$r->get('/correctores-ia',[PublicController::class,'correctoresIa']);
$r->get('/tabuladores',[PublicController::class,'tabuladores']);
$r->get('/recursos',[PublicController::class,'recursos']);
$r->get('/contacto',[PublicController::class,'contacto']);
$r->get('/preguntas-frecuentes',[PublicController::class,'preguntasFrecuentes']);
$r->get('/login',[AuthController::class,'show']); $r->post('/login',[AuthController::class,'login']); $r->post('/logout',[AuthController::class,'logout']);
$r->get('/admin',[AdminController::class,'dashboard']); $r->get('/admin/usuarios',[AdminController::class,'users']); $r->post('/admin/usuarios/guardar',[AdminController::class,'saveUser']); $r->post('/admin/usuarios/eliminar',[AdminController::class,'deleteUser']);
$r->get('/admin/catalogos',[AdminController::class,'catalogs']); $r->post('/admin/catalogos/guardar',[AdminController::class,'saveCatalog']); $r->get('/admin/recursos',[AdminController::class,'resources']); $r->post('/admin/recursos/guardar',[AdminController::class,'saveResource']); $r->post('/admin/recursos/eliminar',[AdminController::class,'deleteResource']);
$r->get('/docente',[TeacherController::class,'dashboard']); $r->get('/docente/perfil',[TeacherController::class,'profile']); $r->post('/docente/perfil',[TeacherController::class,'update']); $r->get('/docente/descargar',[TeacherController::class,'download']); $r->dispatch();
