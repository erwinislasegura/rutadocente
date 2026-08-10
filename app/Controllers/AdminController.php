<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Core\Database;
use App\Models\{User,Catalog,Resource,PublicForm,SiteSetting};
use App\Services\RegistrationMailer;

class AdminController extends Controller {
 function dashboard():void{$this->admin();$db=Database::connection();$stats=['usuarios'=>$db->query('SELECT COUNT(*) FROM users')->fetchColumn(),'docentes'=>$db->query("SELECT COUNT(*) FROM users u JOIN roles r ON r.id=u.role_id WHERE r.name='docente'")->fetchColumn(),'tests'=>$db->query("SELECT COUNT(*) FROM resources WHERE type='test'")->fetchColumn(),'tabuladores'=>$db->query("SELECT COUNT(*) FROM resources WHERE type='tabulador'")->fetchColumn()];$this->view('admin/dashboard',['title'=>'Panel de control','stats'=>$stats]);}

 function users():void{$this->admin();$this->view('admin/users',['title'=>'Usuarios y docentes','users'=>(new User)->detailed()]);}
 function userForm():void{$this->admin();$id=(int)($_GET['id']??0);$this->view('admin/user-form',['title'=>$id?'Editar usuario':'Nuevo usuario','user'=>$id?(new User)->findDetailed($id):null,'roles'=>(new Catalog('roles'))->all(),'subjects'=>(new Catalog('subjects'))->all()]);}
 function userView():void{$this->admin();$user=(new User)->findDetailed((int)($_GET['id']??0));if(!$user){http_response_code(404);exit('Usuario no encontrado');}$this->view('admin/user-view',['title'=>'Detalle del usuario','user'=>$user]);}
 function saveUser():void {
  $this->admin();verify_csrf();$id=!empty($_POST['id'])?(int)$_POST['id']:null;
  try{
   $users=new User;$userId=$users->save($_POST,$id);
   if(!$id){
    $created=$users->findDetailed($userId);
    try{$sent=$created&&(new RegistrationMailer)->send($created,(string)$_POST['password']);}catch(\Throwable){$sent=false;}
    flash('success',$sent?'Usuario creado y correo de acceso enviado correctamente.':'Usuario creado correctamente.');
    if(!$sent)flash('error','La cuenta fue creada, pero el servidor no pudo enviar el correo de acceso. Puedes reintentarlo desde el menú de acciones.');
   }else flash('success','Usuario actualizado correctamente.');
   redirect('/admin/usuarios');
  }catch(\InvalidArgumentException $error){
   flash('error',$error->getMessage());redirect('/admin/usuarios/formulario'.($id?'?id='.$id:''));
  }catch(\PDOException $error){
   $duplicate=(int)($error->errorInfo[1]??0)===1062;
   flash('error',$duplicate?'Ya existe un usuario registrado con ese correo electrónico.':'No fue posible guardar el usuario. Revisa los datos e inténtalo nuevamente.');
   redirect('/admin/usuarios/formulario'.($id?'?id='.$id:''));
  }
 }
 function sendUserRegistration():void {
  $this->admin();verify_csrf();$id=(int)($_POST['id']??0);$users=new User;$user=$users->findDetailed($id);
  if(!$user){flash('error','El usuario seleccionado no existe.');redirect('/admin/usuarios');}
  $password=$this->temporaryPassword();$db=Database::connection();
  try{
   $db->beginTransaction();$users->updatePassword($id,$password);
   if(!(new RegistrationMailer)->send($user,$password))throw new \RuntimeException('El servidor de correo rechazó el envío.');
   $db->commit();flash('success','Correo de registro enviado a '.$user['email'].'. Se generó una nueva contraseña temporal.');
  }catch(\Throwable $error){if($db->inTransaction())$db->rollBack();flash('error','No fue posible enviar el correo de registro. La contraseña anterior se mantuvo sin cambios.');}
  redirect('/admin/usuarios');
 }
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

 function analytics():void{$this->admin();$this->view('admin/analytics',['title'=>'Google Analytics','analytics'=>(new SiteSetting)->analytics()]);}
 function saveAnalytics():void {
  $this->admin();verify_csrf();
  $measurementId=strtoupper(trim((string)($_POST['measurement_id']??'')));$enabled=isset($_POST['enabled']);
  if($measurementId!==''&&!preg_match('/^G-[A-Z0-9]{4,20}$/',$measurementId)){flash('error','El ID de medición no es válido. Debe tener el formato G-XXXXXXXXXX.');redirect('/admin/analytics');}
  if($enabled&&$measurementId===''){flash('error','Ingresa un ID de medición antes de activar Google Analytics.');redirect('/admin/analytics');}
  (new SiteSetting)->saveAnalytics($measurementId,$enabled);
  flash('success',$enabled?'Google Analytics quedó activo en todas las páginas públicas y el formulario.':'La configuración fue guardada y la medición quedó desactivada.');redirect('/admin/analytics');
 }

 function publicForm():void {
  $this->admin();
  $this->view('admin/public-forms',['title'=>'Formularios públicos','forms'=>(new PublicForm)->forms()]);
 }

 function createPublicForm():void {
  $this->admin();verify_csrf();$name=$this->clean($_POST['name']??'',160);$slug=$this->clean($_POST['slug']??'',120);
  if($name===''){flash('error','Ingresa un nombre para crear el formulario.');redirect('/admin/formulario');}
  $id=(new PublicForm)->createForm($name,$slug);flash('success','Formulario creado. Ahora puedes configurar sus preguntas.');redirect('/admin/formulario/editar?id='.$id);
 }

 function publicFormEditor():void {
  $this->admin();$form=new PublicForm;$formId=(int)($_GET['id']??1);$settings=$form->settings($formId);
  if(!$settings){http_response_code(404);exit('Formulario no encontrado');}
  $fieldId=(int)($_GET['field']??0);
  $this->view('admin/public-form',[
   'title'=>'Editar formulario','formId'=>$formId,'settings'=>$settings,
   'fields'=>$form->fields($formId),
   'editingField'=>$fieldId?$form->field($fieldId,$formId):null,
   'submissions'=>$form->submissions($formId),
  ]);
 }

 function saveFormInformation():void {
  $this->admin();verify_csrf();$formId=(int)($_POST['form_id']??1);
  $name=$this->clean($_POST['name']??'',160);
  if($name===''){flash('error','El nombre interno del formulario es obligatorio.');redirect('/admin/formulario/editar?id='.$formId.'#informacion');}
  $forms=new PublicForm;$existing=$forms->settings($formId);if(!$existing){http_response_code(404);exit('Formulario no encontrado');}
  $cover=(string)($existing['cover_image']??'');$oldCover='';
  if(isset($_POST['remove_cover'])&&$cover!==''){$oldCover=$cover;$cover='';}
  if(!empty($_FILES['cover_image']['name'])){
   try{$newCover=$this->storeFormCover($_FILES['cover_image']);}catch(\InvalidArgumentException $error){flash('error',$error->getMessage());redirect('/admin/formulario/editar?id='.$formId.'#informacion');}
   if(!empty($existing['cover_image'])){$oldCover=(string)$existing['cover_image'];}
   $cover=$newCover;
  }
  $status=in_array($_POST['status']??'closed',['open','closed'],true)?$_POST['status']:'closed';
  $forms->saveInformation([
   'name'=>$name,
   'slug'=>$formId===1?'inscripcion':$this->clean($_POST['slug']??'',120),
   'cover_image'=>$cover?:null,
   'eyebrow'=>$this->clean($_POST['eyebrow']??'',80),
   'title'=>$this->clean($_POST['title']??'',180),
   'intro'=>$this->clean($_POST['intro']??'',3000),
   'information_title'=>$this->clean($_POST['information_title']??'',180),
   'information_body'=>$this->clean($_POST['information_body']??'',6000),
   'status'=>$status,
   'submit_label'=>$this->clean($_POST['submit_label']??'',80),
   'success_title'=>$this->clean($_POST['success_title']??'',180),
   'success_message'=>$this->clean($_POST['success_message']??'',2000),
   'consent_text'=>$this->clean($_POST['consent_text']??'',1000),
  ],$formId);
  if($oldCover!==''&&$oldCover!==$cover)$this->deleteFormCover($oldCover);
  flash('success','Información del formulario actualizada.');redirect('/admin/formulario/editar?id='.$formId.'#informacion');
 }

 function saveBankAccount():void {
  $this->admin();verify_csrf();$formId=(int)($_POST['form_id']??1);
  (new PublicForm)->saveBank([
   'bank_enabled'=>isset($_POST['bank_enabled'])?1:0,
   'bank_title'=>$this->clean($_POST['bank_title']??'',180),
   'bank_amount'=>$this->clean($_POST['bank_amount']??'',80),
   'bank_holder'=>$this->clean($_POST['bank_holder']??'',180),
   'bank_rut'=>$this->clean($_POST['bank_rut']??'',40),
   'bank_name'=>$this->clean($_POST['bank_name']??'',120),
   'bank_account_type'=>$this->clean($_POST['bank_account_type']??'',100),
   'bank_account_number'=>$this->clean($_POST['bank_account_number']??'',100),
   'bank_email'=>$this->clean($_POST['bank_email']??'',180),
   'bank_instructions'=>$this->clean($_POST['bank_instructions']??'',3000),
  ],$formId);
  flash('success','Cuenta bancaria actualizada.');redirect('/admin/formulario/editar?id='.$formId.'#cuenta-bancaria');
 }

 function saveFormField():void {
  $this->admin();verify_csrf();$formId=(int)($_POST['form_id']??1);
  $types=['text','email','tel','number','date','textarea','select','radio','checkbox','checkbox_group','file'];
  $type=in_array($_POST['field_type']??'text',$types,true)?$_POST['field_type']:'text';
  $label=$this->clean($_POST['label']??'',180);
  if($label===''){flash('error','El nombre del campo es obligatorio.');redirect('/admin/formulario/editar?id='.$formId.'#campos');}
  $options=array_values(array_filter(array_map(fn($option)=>$this->clean($option,300),preg_split('/\R/',$_POST['options']??'')?:[])));
  if(in_array($type,['select','radio','checkbox_group'],true)&&!$options){flash('error','Agrega al menos una opción para este tipo de campo.');redirect('/admin/formulario/editar?id='.$formId.'#campos');}
  (new PublicForm)->saveField([
   'label'=>$label,'field_type'=>$type,
   'placeholder'=>$this->clean($_POST['placeholder']??'',180),
   'help_text'=>$this->clean($_POST['help_text']??'',500),
   'options'=>$options,'required'=>isset($_POST['required'])?1:0,'active'=>isset($_POST['active'])?1:0,
   'sort_order'=>max(0,min(999,(int)($_POST['sort_order']??0))),
   'max_selections'=>max(0,min(20,(int)($_POST['max_selections']??0))),
  ],$formId,!empty($_POST['id'])?(int)$_POST['id']:null);
  flash('success','Campo guardado correctamente.');redirect('/admin/formulario/editar?id='.$formId.'#campos');
 }

 function deleteFormField():void {
  $this->admin();verify_csrf();$formId=(int)($_POST['form_id']??1);
  (new PublicForm)->deleteField((int)($_POST['id']??0),$formId);
  flash('success','Campo eliminado. Las respuestas históricas se conservaron.');redirect('/admin/formulario/editar?id='.$formId.'#campos');
 }

 function downloadFormFile():void {
  $this->admin();
  $submission=(new PublicForm)->submission((int)($_GET['submission']??0));
  $fieldId=(int)($_GET['field']??0);
  $answers=$submission?json_decode($submission['answers_json'],true):null;
  $file=is_array($answers)?($answers[$fieldId]['value']??null):null;
  if(!is_array($file)||empty($file['stored'])){http_response_code(404);exit('Archivo no encontrado');}
  $stored=basename((string)$file['stored']);
  $path=rtrim(config('form_upload_dir'),'/').'/'.$stored;
  if(!is_file($path)){http_response_code(404);exit('Archivo no encontrado');}
  $downloadName=preg_replace('/[^\pL\pN._ -]+/u','_',basename((string)($file['original']??$stored)))?:'comprobante';
  header('Content-Type: application/octet-stream');
  header('Content-Length: '.filesize($path));
  header('Content-Disposition: attachment; filename="'.str_replace('"','',$downloadName).'"');
  readfile($path);exit;
 }

 private function clean(mixed $value,int $max):string {
  $value=trim((string)$value);
  return function_exists('mb_substr')?mb_substr($value,0,$max):substr($value,0,$max);
 }
 private function storeFormCover(array $file):string {
  if(($file['error']??UPLOAD_ERR_NO_FILE)!==UPLOAD_ERR_OK)throw new \InvalidArgumentException('No fue posible recibir la imagen de portada.');
  if(($file['size']??0)>config('form_cover_max_mb')*1048576)throw new \InvalidArgumentException('La portada no puede superar '.config('form_cover_max_mb').' MB.');
  $mime=class_exists('finfo')?(new \finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']):($file['type']??'');
  $extensions=['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
  if(!isset($extensions[$mime]))throw new \InvalidArgumentException('La portada debe ser JPG, PNG o WebP.');
  $directory=config('form_cover_dir');if(!is_dir($directory)&&!mkdir($directory,0755,true)&&!is_dir($directory))throw new \InvalidArgumentException('No fue posible preparar la carpeta de portadas.');
  $name=bin2hex(random_bytes(18)).'.'.$extensions[$mime];
  if(!move_uploaded_file($file['tmp_name'],$directory.'/'.$name))throw new \InvalidArgumentException('No fue posible guardar la imagen de portada.');
  return $name;
 }
 private function deleteFormCover(string $name):void{$path=rtrim(config('form_cover_dir'),'/').'/'.basename($name);if(is_file($path))@unlink($path);}
 private function temporaryPassword():string{return 'RD-'.strtoupper(bin2hex(random_bytes(5)));}
}
