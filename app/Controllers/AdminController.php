<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Core\Database;
use App\Models\{User,Catalog,Resource};

class AdminController extends Controller {
 function dashboard():void{$this->admin();$db=Database::connection();$stats=['usuarios'=>$db->query('SELECT COUNT(*) FROM users')->fetchColumn(),'docentes'=>$db->query("SELECT COUNT(*) FROM users u JOIN roles r ON r.id=u.role_id WHERE r.name='docente'")->fetchColumn(),'tests'=>$db->query("SELECT COUNT(*) FROM resources WHERE type='test'")->fetchColumn(),'tabuladores'=>$db->query("SELECT COUNT(*) FROM resources WHERE type='tabulador'")->fetchColumn()];$this->view('admin/dashboard',['title'=>'Panel de control','stats'=>$stats]);}

 function users():void{$this->admin();$this->view('admin/users',['title'=>'Usuarios y docentes','users'=>(new User)->detailed()]);}
 function userForm():void{$this->admin();$id=(int)($_GET['id']??0);$this->view('admin/user-form',['title'=>$id?'Editar usuario':'Nuevo usuario','user'=>$id?(new User)->findDetailed($id):null,'roles'=>(new Catalog('roles'))->all(),'subjects'=>(new Catalog('subjects'))->all()]);}
 function userView():void{$this->admin();$user=(new User)->findDetailed((int)($_GET['id']??0));if(!$user){http_response_code(404);exit('Usuario no encontrado');}$this->view('admin/user-view',['title'=>'Detalle del usuario','user'=>$user]);}
 function saveUser():void{$this->admin();verify_csrf();(new User)->save($_POST,!empty($_POST['id'])?(int)$_POST['id']:null);flash('success','Usuario guardado correctamente.');redirect('/admin/usuarios');}
 function deleteUser():void{$this->admin();verify_csrf();(new User)->delete((int)$_POST['id']);flash('success','Usuario eliminado.');redirect('/admin/usuarios');}

 function catalogs():void{$this->admin();$this->view('admin/catalogs',['title'=>'Catálogos del sistema','roles'=>(new Catalog('roles'))->all(),'subjects'=>(new Catalog('subjects'))->all(),'groups'=>(new Catalog('tabulator_groups'))->all(),'subgroups'=>(new Catalog('tabulator_subgroups'))->all()]);}
 function catalogForm():void{$this->admin();$type=$_GET['type']??'subjects';$catalog=new Catalog($type);$id=(int)($_GET['id']??0);$labels=['roles'=>'Rol','subjects'=>'Asignatura','tabulator_groups'=>'Grupo de tabuladores','tabulator_subgroups'=>'Subgrupo de tabuladores'];$this->view('admin/catalog-form',['title'=>($id?'Editar ':'Nuevo ').$labels[$type],'type'=>$type,'label'=>$labels[$type],'record'=>$id?$catalog->find($id):null,'groups'=>(new Catalog('tabulator_groups'))->all()]);}
 function saveCatalog():void{$this->admin();verify_csrf();$type=$_POST['type'];(new Catalog($type))->saveName(trim($_POST['name']),!empty($_POST['id'])?(int)$_POST['id']:null,!empty($_POST['group_id'])?(int)$_POST['group_id']:null);flash('success','Registro guardado.');redirect('/admin/catalogos');}
 function deleteCatalog():void{$this->admin();verify_csrf();try{(new Catalog($_POST['type']))->delete((int)$_POST['id']);flash('success','Registro eliminado.');}catch(\PDOException){flash('error','No se puede eliminar porque está siendo utilizado por otros registros.');}redirect('/admin/catalogos');}

 function tests():void{$this->resourceList('test');}
 function tabulators():void{$this->resourceList('tabulador');}
 private function resourceList(string $type):void{$this->admin();$this->view('admin/resources',['title'=>$type==='test'?'Tests':'Tabuladores','type'=>$type,'resources'=>(new Resource)->detailed($type)]);}
 function resourceForm():void{$this->admin();$id=(int)($_GET['id']??0);$resource=$id?(new Resource)->findDetailed($id):null;$type=$resource['type']??($_GET['type']??'test');if(!in_array($type,['test','tabulador'],true))$type='test';$this->view('admin/resource-form',['title'=>$id?'Editar '.($type==='test'?'test':'tabulador'):'Nuevo '.($type==='test'?'test':'tabulador'),'type'=>$type,'resource'=>$resource,'subjects'=>(new Catalog('subjects'))->all(),'groups'=>(new Catalog('tabulator_groups'))->all(),'subgroups'=>(new Catalog('tabulator_subgroups'))->all()]);}
 function resourceView():void{$this->admin();$resource=(new Resource)->findDetailed((int)($_GET['id']??0));if(!$resource){http_response_code(404);exit('Recurso no encontrado');}$this->view('admin/resource-view',['title'=>'Detalle del recurso','resource'=>$resource]);}
 function saveResource():void{$this->admin();verify_csrf();$file=null;if(!empty($_FILES['file']['name'])){$ext=strtolower(pathinfo($_FILES['file']['name'],PATHINFO_EXTENSION));if(!in_array($ext,config('allowed_extensions'),true))exit('Tipo de archivo no permitido');if($_FILES['file']['size']>config('max_upload_mb')*1048576)exit('Archivo demasiado grande');$dir=config('upload_dir');if(!is_dir($dir))mkdir($dir,0755,true);$name=bin2hex(random_bytes(12)).'.'.$ext;move_uploaded_file($_FILES['file']['tmp_name'],$dir.'/'.$name);$file=$name;}$_POST['file_path']=$file;(new Resource)->save($_POST,!empty($_POST['id'])?(int)$_POST['id']:null);flash('success','Recurso guardado correctamente.');redirect($_POST['type']==='test'?'/admin/tests':'/admin/tabuladores');}
 function deleteResource():void{$this->admin();verify_csrf();(new Resource)->delete((int)$_POST['id']);flash('success','Recurso eliminado.');redirect(($_POST['type']??'test')==='test'?'/admin/tests':'/admin/tabuladores');}
}
