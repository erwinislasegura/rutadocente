<?php
namespace App\Controllers;
use App\Core\Controller;use App\Models\{User,Resource};
class TeacherController extends Controller {
 function dashboard():void{$u=$this->teacher();$tests=$u['test_enabled']?(new Resource)->visibleFor($u,'test'):[];$tabs=$u['tabulator_enabled']?(new Resource)->visibleFor($u,'tabulador'):[];$this->view('teacher/dashboard',['title'=>'Área docente','user'=>$u,'tests'=>$tests,'tabulators'=>$tabs]);}
 function profile():void{$u=$this->teacher();$this->view('teacher/profile',['title'=>'Mi perfil','user'=>$u]);}
 function update():void{$u=$this->teacher();verify_csrf();(new User)->updateProfile($u['id'],$_POST);$_SESSION['user']=array_merge($u,array_intersect_key($_POST,array_flip(['first_name','last_name','phone'])));flash('success','Perfil actualizado.');redirect('/docente/perfil');}
 function download():void{$u=$this->teacher();$r=(new Resource)->find((int)($_GET['id']??0));if(!$r||!$r['active']||($r['subject_id']&&$r['subject_id']!=$u['subject_id'])||($r['type']==='test'&&!$u['test_enabled'])||($r['type']==='tabulador'&&!$u['tabulator_enabled'])){http_response_code(403);exit('Acceso denegado');}$file=config('upload_dir').'/'.basename($r['file_path']??'');if(!is_file($file)){http_response_code(404);exit('Archivo no encontrado');}header('Content-Type: application/octet-stream');header('Content-Disposition: attachment; filename="'.preg_replace('/[^a-zA-Z0-9._-]/','_',basename($r['name'])).'.'.pathinfo($file,PATHINFO_EXTENSION).'"');header('Content-Length: '.filesize($file));readfile($file);exit;}
}
