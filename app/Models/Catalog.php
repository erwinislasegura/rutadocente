<?php
namespace App\Models;
class Catalog extends BaseModel {
 public function __construct(string $table){$allowed=['roles','subjects','tabulator_groups','tabulator_subgroups'];if(!in_array($table,$allowed,true))throw new \InvalidArgumentException;$this->table=$table;}
 public function saveName(string $name,?int $id=null,?int $groupId=null):void{if($this->table==='tabulator_subgroups'){$sql=$id?'UPDATE tabulator_subgroups SET name=?,group_id=? WHERE id=?':'INSERT INTO tabulator_subgroups(name,group_id) VALUES(?,?)';$p=$id?[$name,$groupId,$id]:[$name,$groupId];}else{$sql=$id?"UPDATE {$this->table} SET name=? WHERE id=?":"INSERT INTO {$this->table}(name) VALUES(?)";$p=$id?[$name,$id]:[$name];}$s=$this->db()->prepare($sql);$s->execute($p);}
}

