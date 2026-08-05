<?php
namespace App\Models;
class User extends BaseModel {
 protected string $table='users';
 public function byEmail(string $email):?array{$s=$this->db()->prepare('SELECT u.*,r.name role,s.name subject FROM users u JOIN roles r ON r.id=u.role_id LEFT JOIN subjects s ON s.id=u.subject_id WHERE u.email=? AND u.active=1');$s->execute([$email]);return $s->fetch()?:null;}
 public function detailed():array{return $this->db()->query('SELECT u.*,r.name role,s.name subject FROM users u JOIN roles r ON r.id=u.role_id LEFT JOIN subjects s ON s.id=u.subject_id ORDER BY u.id DESC')->fetchAll();}
 public function findDetailed(int $id):?array{$s=$this->db()->prepare('SELECT u.*,r.name role,s.name subject FROM users u JOIN roles r ON r.id=u.role_id LEFT JOIN subjects s ON s.id=u.subject_id WHERE u.id=?');$s->execute([$id]);return $s->fetch()?:null;}
 public function save(array $d,?int $id=null):void{$cols=['first_name','last_name','email','phone','role_id','subject_id','test_enabled','tabulator_enabled','active'];$v=[];foreach($cols as $c)$v[$c]=$d[$c]??null;if(!empty($d['password']))$v['password']=password_hash($d['password'],PASSWORD_DEFAULT);if($id){$set=implode(',',array_map(fn($k)=>"$k=?",array_keys($v)));$s=$this->db()->prepare("UPDATE users SET $set WHERE id=?");$s->execute([...array_values($v),$id]);}else{$keys=array_keys($v);$s=$this->db()->prepare('INSERT INTO users ('.implode(',',$keys).') VALUES ('.implode(',',array_fill(0,count($keys),'?')).')');$s->execute(array_values($v));}}
 public function updateProfile(int $id,array $d,?string $avatarPath=null):void{if($avatarPath){$s=$this->db()->prepare('UPDATE users SET first_name=?,last_name=?,phone=?,avatar_path=? WHERE id=?');$s->execute([$d['first_name'],$d['last_name'],$d['phone'],$avatarPath,$id]);}else{$s=$this->db()->prepare('UPDATE users SET first_name=?,last_name=?,phone=? WHERE id=?');$s->execute([$d['first_name'],$d['last_name'],$d['phone'],$id]);}}
}
