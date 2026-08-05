<?php
namespace App\Models;
use App\Core\Database;
abstract class BaseModel {
 protected string $table;
 protected function db(){return Database::connection();}
 public function all(string $order='id DESC'):array{return $this->db()->query("SELECT * FROM {$this->table} ORDER BY $order")->fetchAll();}
 public function find(int $id):?array{$s=$this->db()->prepare("SELECT * FROM {$this->table} WHERE id=?");$s->execute([$id]);return $s->fetch()?:null;}
 public function delete(int $id):void{$s=$this->db()->prepare("DELETE FROM {$this->table} WHERE id=?");$s->execute([$id]);}
}

