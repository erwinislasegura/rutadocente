<?php
namespace App\Controllers;
use App\Core\Controller;
use App\Models\PublicForm;

class PublicController extends Controller {
 function home():void{$this->publicPage('home');}
 function asignaturas():void{$this->publicPage('asignaturas');}
 function portafolio():void{$this->publicPage('portafolio');}
 function clasesAsincronicas():void{$this->publicPage('clases-asincronicas');}
 function tests():void{$this->publicPage('tests');}
 function tabuladores():void{$this->publicPage('tabuladores');}
 function recursos():void{$this->publicPage('recursos');}
 function contacto():void{$this->publicPage('contacto');}
 function preguntasFrecuentes():void{$this->publicPage('preguntas-frecuentes');}

 function registration():void {
  $form=new PublicForm;
  $errors=$_SESSION['_public_form_errors']??[];
  $old=$_SESSION['_public_form_old']??[];
  $success=$_SESSION['_public_form_success']??null;
  unset($_SESSION['_public_form_errors'],$_SESSION['_public_form_old'],$_SESSION['_public_form_success']);
  $settings=$form->settings();
  $fields=$form->fields(true);
  $this->publicPage('inscripcion',compact('form','errors','old','success','settings','fields'));
 }

 function submitRegistration():void {
  verify_csrf();
  $form=new PublicForm;
  $settings=$form->settings();
  if(($settings['status']??'closed')!=='open'){
   flash('error','El formulario no está recibiendo respuestas en este momento.');
   redirect('/inscripcion');
  }
  if(trim((string)($_POST['website']??''))!==''){
   $_SESSION['_public_form_success']=$settings['success_message']??'Tu respuesta fue registrada.';
   redirect('/inscripcion');
  }

  $fields=$form->fields(true);
  $posted=is_array($_POST['field']??null)?$_POST['field']:[];
  $errors=[];
  $answers=[];
  $pendingFiles=[];

  foreach($fields as $field){
   $id=(int)$field['id'];
   $type=$field['field_type'];
   $required=(bool)$field['required'];
   if($type==='file'){
    $upload=$this->formUpload($id);
    if($upload['error']===UPLOAD_ERR_NO_FILE){
     if($required)$errors[$id]='Adjunta el comprobante para continuar.';
     continue;
    }
    if($upload['error']!==UPLOAD_ERR_OK){$errors[$id]='No pudimos recibir el archivo. Inténtalo nuevamente.';continue;}
    $ext=strtolower(pathinfo($upload['name'],PATHINFO_EXTENSION));
    if(!in_array($ext,config('form_allowed_extensions'),true)){$errors[$id]='Formato no permitido. Usa JPG, PNG, WEBP o PDF.';continue;}
    $mime=class_exists('finfo')?(new \finfo(FILEINFO_MIME_TYPE))->file($upload['tmp_name']):$upload['type'];
    if(!in_array($mime,config('form_allowed_mime_types'),true)){$errors[$id]='El contenido del archivo no corresponde a un formato permitido.';continue;}
    if($upload['size']>config('form_max_upload_mb')*1048576){$errors[$id]='El archivo supera el máximo de '.config('form_max_upload_mb').' MB.';continue;}
    $pendingFiles[$id]=['upload'=>$upload,'extension'=>$ext,'field'=>$field];
    continue;
   }

   $raw=$posted[$id]??($type==='checkbox'?null:'');
   if($type==='checkbox_group'){
    $values=is_array($raw)?array_values(array_unique(array_map(fn($value)=>trim((string)$value),$raw))):[];
    $values=array_values(array_intersect($values,$field['options']));
    if($required&&!$values)$errors[$id]='Selecciona al menos una opción.';
    if((int)$field['max_selections']>0&&count($values)>(int)$field['max_selections'])$errors[$id]='Selecciona un máximo de '.$field['max_selections'].' opciones.';
    if($values)$answers[$id]=['label'=>$field['label'],'type'=>$type,'value'=>$values];
    continue;
   }

   $value=is_array($raw)?'':trim((string)$raw);
   $maxLength=$type==='textarea'?5000:500;
   if(function_exists('mb_strlen')?mb_strlen($value)>$maxLength:strlen($value)>$maxLength)$errors[$id]='La respuesta es demasiado extensa.';
   if($required&&$value==='')$errors[$id]='Este campo es obligatorio.';
   if($value!==''&&$type==='email'&&!filter_var($value,FILTER_VALIDATE_EMAIL))$errors[$id]='Ingresa un correo electrónico válido.';
   if($value!==''&&$type==='number'&&!is_numeric($value))$errors[$id]='Ingresa un número válido.';
   if($value!==''&&$type==='date'&&!preg_match('/^\d{4}-\d{2}-\d{2}$/',$value))$errors[$id]='Ingresa una fecha válida.';
   if($value!==''&&in_array($type,['select','radio'],true)&&!in_array($value,$field['options'],true))$errors[$id]='Selecciona una opción válida.';
   if($value!=='')$answers[$id]=['label'=>$field['label'],'type'=>$type,'value'=>$value];
  }

  if(trim((string)($settings['consent_text']??''))!==''&&!isset($_POST['consent']))$errors['_consent']='Debes aceptar esta declaración para enviar el formulario.';

  if($errors){
   $_SESSION['_public_form_errors']=$errors;
   $_SESSION['_public_form_old']=['field'=>$posted];
   redirect('/inscripcion#formulario');
  }

  foreach($pendingFiles as $id=>$pending){
   $dir=config('form_upload_dir');
   if(!is_dir($dir)&&!mkdir($dir,0750,true)&&!is_dir($dir)){
    $_SESSION['_public_form_errors']=[$id=>'No fue posible preparar el almacenamiento del archivo.'];
    $_SESSION['_public_form_old']=['field'=>$posted];
    redirect('/inscripcion#formulario');
   }
   $stored=bin2hex(random_bytes(18)).'.'.$pending['extension'];
   if(!move_uploaded_file($pending['upload']['tmp_name'],$dir.'/'.$stored)){
    $_SESSION['_public_form_errors']=[$id=>'No fue posible guardar el archivo. Inténtalo nuevamente.'];
    $_SESSION['_public_form_old']=['field'=>$posted];
    redirect('/inscripcion#formulario');
   }
   $answers[$id]=['label'=>$pending['field']['label'],'type'=>'file','value'=>['stored'=>$stored,'original'=>$pending['upload']['name']]];
  }

  ksort($answers);
  $name='';$email='';
  foreach($answers as $answer){
   if($answer['type']==='email'&&$email==='')$email=(string)$answer['value'];
   if($name===''&&str_contains(strtolower($answer['label']),'nombre')&&is_string($answer['value']))$name=$answer['value'];
  }
  $form->createSubmission($answers,$name,$email);
  $_SESSION['_public_form_success']=$settings['success_message']??'Tu respuesta fue registrada correctamente.';
  redirect('/inscripcion');
 }

 private function formUpload(int $id):array {
  $files=$_FILES['field_file']??[];
  return [
   'name'=>(string)($files['name'][$id]??''),
   'type'=>(string)($files['type'][$id]??''),
   'tmp_name'=>(string)($files['tmp_name'][$id]??''),
   'error'=>(int)($files['error'][$id]??UPLOAD_ERR_NO_FILE),
   'size'=>(int)($files['size'][$id]??0),
  ];
 }
}
