<?php
namespace App\Models;
class User extends BaseModel {
 protected string $table='users';
 public function byEmail(string $email):?array{$s=$this->db()->prepare('SELECT u.*,r.name role,s.name subject FROM users u JOIN roles r ON r.id=u.role_id LEFT JOIN subjects s ON s.id=u.subject_id WHERE u.email=? AND u.active=1');$s->execute([$email]);return $s->fetch()?:null;}
 public function detailed():array{return $this->db()->query('SELECT u.*,r.name role,s.name subject FROM users u JOIN roles r ON r.id=u.role_id LEFT JOIN subjects s ON s.id=u.subject_id ORDER BY u.id DESC')->fetchAll();}
 public function findDetailed(int $id):?array{$s=$this->db()->prepare('SELECT u.*,r.name role,s.name subject FROM users u JOIN roles r ON r.id=u.role_id LEFT JOIN subjects s ON s.id=u.subject_id WHERE u.id=?');$s->execute([$id]);return $s->fetch()?:null;}
 public function save(array $d,?int $id=null):int {
  $firstName=trim((string)($d['first_name']??''));
  $lastName=trim((string)($d['last_name']??''));
  $email=strtolower(trim((string)($d['email']??'')));
  $password=(string)($d['password']??'');
  if($firstName===''||$lastName==='')throw new \InvalidArgumentException('El nombre y el apellido son obligatorios.');
  if(!filter_var($email,FILTER_VALIDATE_EMAIL))throw new \InvalidArgumentException('Ingresa un correo electrónico válido.');
  if((int)($d['role_id']??0)<1)throw new \InvalidArgumentException('Selecciona un rol válido.');
  if(!$id&&strlen($password)<8)throw new \InvalidArgumentException('La contraseña debe tener al menos 8 caracteres.');
  if($password!==''&&strlen($password)<8)throw new \InvalidArgumentException('La contraseña debe tener al menos 8 caracteres.');
  $check=$this->db()->prepare('SELECT id FROM users WHERE email=? AND id<>? LIMIT 1');
  $check->execute([$email,$id??0]);
  if($check->fetch())throw new \InvalidArgumentException('Ya existe un usuario registrado con ese correo electrónico.');

  $v=[
   'first_name'=>$firstName,
   'last_name'=>$lastName,
   'email'=>$email,
   'phone'=>trim((string)($d['phone']??''))?:null,
   'role_id'=>(int)$d['role_id'],
   'subject_id'=>!empty($d['subject_id'])?(int)$d['subject_id']:null,
   'test_enabled'=>!empty($d['test_enabled'])?1:0,
   'tabulator_enabled'=>!empty($d['tabulator_enabled'])?1:0,
   'active'=>!empty($d['active'])?1:0,
  ];
  if($password!=='')$v['password']=password_hash($password,PASSWORD_DEFAULT);
  if($id){
   $set=implode(',',array_map(fn($key)=>"$key=?",array_keys($v)));
   $stmt=$this->db()->prepare("UPDATE users SET $set WHERE id=?");
   $stmt->execute([...array_values($v),$id]);
   return $id;
  }else{
   $keys=array_keys($v);
   $stmt=$this->db()->prepare('INSERT INTO users ('.implode(',',$keys).') VALUES ('.implode(',',array_fill(0,count($keys),'?')).')');
   $stmt->execute(array_values($v));
   return (int)$this->db()->lastInsertId();
  }
 }
 public function updatePassword(int $id,string $password):void{$s=$this->db()->prepare('UPDATE users SET password=? WHERE id=?');$s->execute([password_hash($password,PASSWORD_DEFAULT),$id]);}
 public function updateProfile(int $id,array $d,?string $avatarPath=null):void{if($avatarPath){$s=$this->db()->prepare('UPDATE users SET first_name=?,last_name=?,phone=?,avatar_path=? WHERE id=?');$s->execute([$d['first_name'],$d['last_name'],$d['phone'],$avatarPath,$id]);}else{$s=$this->db()->prepare('UPDATE users SET first_name=?,last_name=?,phone=? WHERE id=?');$s->execute([$d['first_name'],$d['last_name'],$d['phone'],$id]);}}
}
