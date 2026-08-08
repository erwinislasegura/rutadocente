<?php
namespace App\Models;

class SiteSetting extends BaseModel {
    protected string $table='site_settings';

    public function analytics():array {
        try {
            $row=$this->db()->query("SELECT setting_value FROM site_settings WHERE setting_key='google_analytics'")->fetch();
        } catch (\PDOException) {
            $this->ensureTable();
            $row=false;
        }
        $value=$row?json_decode((string)$row['setting_value'],true):[];
        if(!is_array($value))$value=[];
        return ['measurement_id'=>(string)($value['measurement_id']??''),'enabled'=>(bool)($value['enabled']??false)];
    }

    public function saveAnalytics(string $measurementId,bool $enabled):void {
        $this->ensureTable();
        $value=json_encode(['measurement_id'=>$measurementId,'enabled'=>$enabled],JSON_UNESCAPED_SLASHES);
        $sql="INSERT INTO site_settings(setting_key,setting_value) VALUES('google_analytics',?) ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value)";
        $this->db()->prepare($sql)->execute([$value]);
    }

    private function ensureTable():void {
        $this->db()->exec("CREATE TABLE IF NOT EXISTS site_settings(setting_key VARCHAR(80) PRIMARY KEY,setting_value TEXT NOT NULL,updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }
}
