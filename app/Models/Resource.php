<?php
namespace App\Models;
class Resource extends BaseModel {
 protected string $table='resources';
 public function visibleFor(array $u,string $type):array{$sql='SELECT r.*,s.name subject FROM resources r LEFT JOIN subjects s ON s.id=r.subject_id WHERE r.type=? AND r.active=1 AND (r.subject_id IS NULL OR r.subject_id=?) ORDER BY r.id DESC';$st=$this->db()->prepare($sql);$st->execute([$type,$u['subject_id']]);return $st->fetchAll();}
 public function detailed():array{return $this->db()->query('SELECT r.*,s.name subject,g.name group_name,sg.name subgroup_name FROM resources r LEFT JOIN subjects s ON s.id=r.subject_id LEFT JOIN tabulator_groups g ON g.id=r.group_id LEFT JOIN tabulator_subgroups sg ON sg.id=r.subgroup_id ORDER BY r.id DESC')->fetchAll();}
 public function save(array $d,?int $id=null):void{$v=[$d['type'],$d['name'],$d['description'],$d['subject_id']?:null,$d['group_id']?:null,$d['subgroup_id']?:null,$d['file_path']?:null,$d['external_link']?:null,(int)($d['active']??1)];if($id){$s=$this->db()->prepare('UPDATE resources SET type=?,name=?,description=?,subject_id=?,group_id=?,subgroup_id=?,file_path=COALESCE(?,file_path),external_link=?,active=? WHERE id=?');$v[]=$id;}else{$s=$this->db()->prepare('INSERT INTO resources(type,name,description,subject_id,group_id,subgroup_id,file_path,external_link,active) VALUES(?,?,?,?,?,?,?,?,?)');}$s->execute($v);}
}

