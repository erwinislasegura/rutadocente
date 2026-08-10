<?php
namespace App\Models;

class PublicForm extends BaseModel {
    protected string $table='public_form_settings';
    private static bool $schemaReady=false;

    public function forms():array {
        $this->ensureSchema();
        return $this->db()->query("SELECT f.*,(SELECT COUNT(*) FROM public_form_fields q WHERE q.form_id=f.id) field_count,(SELECT COUNT(*) FROM public_form_fields q WHERE q.form_id=f.id AND q.active=1) active_field_count,(SELECT COUNT(*) FROM public_form_submissions s WHERE s.form_id=f.id) submission_count FROM public_form_settings f ORDER BY f.id DESC")->fetchAll();
    }

    public function settings(int $formId=1):array {
        $this->ensureSchema();
        $stmt=$this->db()->prepare('SELECT * FROM public_form_settings WHERE id=?');
        $stmt->execute([$formId]);
        return $stmt->fetch()?:[];
    }

    public function settingsBySlug(string $slug):array {
        $this->ensureSchema();
        $stmt=$this->db()->prepare('SELECT * FROM public_form_settings WHERE slug=? LIMIT 1');
        $stmt->execute([$slug]);
        return $stmt->fetch()?:[];
    }

    public function createForm(string $name,string $requestedSlug=''):int {
        $this->ensureSchema();
        $name=trim($name)?:'Nuevo formulario';
        $slug=$this->uniqueSlug($requestedSlug?:$name);
        $sql="INSERT INTO public_form_settings(name,slug,eyebrow,title,intro,information_title,information_body,status,submit_label,success_title,success_message,consent_text,bank_enabled,bank_title,bank_instructions) VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
        $this->db()->prepare($sql)->execute([$name,$slug,'FORMULARIO RUTA DOCENTE',$name,'Completa la información solicitada para enviar tu respuesta.','','','closed','Enviar respuesta','¡Respuesta recibida!','Registramos tus datos correctamente. Pronto nos pondremos en contacto.','Declaro que los datos ingresados son correctos y autorizo su uso para gestionar esta solicitud.',0,'Datos para realizar la transferencia','']);
        return (int)$this->db()->lastInsertId();
    }

    public function saveInformation(array $data,int $formId):void {
        $this->ensureSchema();
        $slug=$this->uniqueSlug($data['slug']?:$data['name'],$formId);
        $sql='UPDATE public_form_settings SET name=?,slug=?,cover_image=?,eyebrow=?,title=?,intro=?,information_title=?,information_body=?,status=?,submit_label=?,success_title=?,success_message=?,consent_text=? WHERE id=?';
        $this->db()->prepare($sql)->execute([
            $data['name'],$slug,$data['cover_image'],$data['eyebrow'],$data['title'],$data['intro'],$data['information_title'],$data['information_body'],
            $data['status'],$data['submit_label'],$data['success_title'],$data['success_message'],$data['consent_text'],$formId,
        ]);
    }

    public function saveBank(array $data,int $formId):void {
        $this->ensureSchema();
        $sql='UPDATE public_form_settings SET bank_enabled=?,bank_title=?,bank_amount=?,bank_holder=?,bank_rut=?,bank_name=?,bank_account_type=?,bank_account_number=?,bank_email=?,bank_instructions=? WHERE id=?';
        $this->db()->prepare($sql)->execute([
            $data['bank_enabled'],$data['bank_title'],$data['bank_amount'],$data['bank_holder'],$data['bank_rut'],
            $data['bank_name'],$data['bank_account_type'],$data['bank_account_number'],$data['bank_email'],$data['bank_instructions'],$formId,
        ]);
    }

    public function fields(int $formId=1,bool $onlyActive=false):array {
        $this->ensureSchema();
        $where=$onlyActive?' AND active=1':'';
        $stmt=$this->db()->prepare("SELECT * FROM public_form_fields WHERE form_id=?{$where} ORDER BY sort_order,id");
        $stmt->execute([$formId]);
        return array_map([$this,'hydrateField'],$stmt->fetchAll());
    }

    public function field(int $id,?int $formId=null):?array {
        $this->ensureSchema();
        $sql='SELECT * FROM public_form_fields WHERE id=?'.($formId?' AND form_id=?':'');
        $stmt=$this->db()->prepare($sql);$stmt->execute($formId?[$id,$formId]:[$id]);
        $row=$stmt->fetch();
        return $row?$this->hydrateField($row):null;
    }

    public function saveField(array $data,int $formId,?int $id=null):void {
        $this->ensureSchema();
        $options=json_encode($data['options'],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        if($id){
            $sql='UPDATE public_form_fields SET label=?,field_type=?,placeholder=?,help_text=?,options_json=?,required=?,active=?,sort_order=?,max_selections=? WHERE id=? AND form_id=?';
            $params=[$data['label'],$data['field_type'],$data['placeholder'],$data['help_text'],$options,$data['required'],$data['active'],$data['sort_order'],$data['max_selections'],$id,$formId];
        }else{
            $sql='INSERT INTO public_form_fields(form_id,label,field_type,placeholder,help_text,options_json,required,active,sort_order,max_selections) VALUES(?,?,?,?,?,?,?,?,?,?)';
            $params=[$formId,$data['label'],$data['field_type'],$data['placeholder'],$data['help_text'],$options,$data['required'],$data['active'],$data['sort_order'],$data['max_selections']];
        }
        $this->db()->prepare($sql)->execute($params);
    }

    public function deleteField(int $id,int $formId):void {$this->ensureSchema();$stmt=$this->db()->prepare('DELETE FROM public_form_fields WHERE id=? AND form_id=?');$stmt->execute([$id,$formId]);}

    public function deleteForm(int $formId):array {
        $this->ensureSchema();$settings=$this->settings($formId);if(!$settings)return [];
        $stmt=$this->db()->prepare('SELECT answers_json FROM public_form_submissions WHERE form_id=?');$stmt->execute([$formId]);$files=[];
        foreach($stmt->fetchAll() as $submission){
            $answers=json_decode((string)$submission['answers_json'],true);
            if(!is_array($answers))continue;
            foreach($answers as $answer){
                $value=$answer['value']??null;
                if(($answer['type']??'')==='file'&&is_array($value)&&!empty($value['stored']))$files[]=basename((string)$value['stored']);
            }
        }
        $db=$this->db();$db->beginTransaction();
        try{
            foreach(['public_form_submissions','public_form_fields'] as $table){$delete=$db->prepare("DELETE FROM {$table} WHERE form_id=?");$delete->execute([$formId]);}
            $delete=$db->prepare('DELETE FROM public_form_settings WHERE id=?');$delete->execute([$formId]);
            $db->commit();
        }catch(\Throwable $error){if($db->inTransaction())$db->rollBack();throw $error;}
        return ['cover'=>(string)($settings['cover_image']??''),'files'=>array_values(array_unique($files))];
    }

    public function submissions(int $formId=1,int $limit=100):array {
        $this->ensureSchema();$limit=max(1,min(250,$limit));
        $stmt=$this->db()->prepare("SELECT * FROM public_form_submissions WHERE form_id=? ORDER BY created_at DESC,id DESC LIMIT {$limit}");
        $stmt->execute([$formId]);return $stmt->fetchAll();
    }

    public function submission(int $id):?array {
        $this->ensureSchema();$stmt=$this->db()->prepare('SELECT * FROM public_form_submissions WHERE id=?');$stmt->execute([$id]);return $stmt->fetch()?:null;
    }

    public function createSubmission(int $formId,array $answers,string $name,string $email):int {
        $this->ensureSchema();$stmt=$this->db()->prepare('INSERT INTO public_form_submissions(form_id,contact_name,contact_email,answers_json) VALUES(?,?,?,?)');
        $stmt->execute([$formId,$name,$email,json_encode($answers,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)]);return (int)$this->db()->lastInsertId();
    }

    private function uniqueSlug(string $value,?int $ignoreId=null):string {
        $slug=strtolower(trim($value));
        $ascii=function_exists('iconv')?(iconv('UTF-8','ASCII//TRANSLIT//IGNORE',$slug)?:$slug):$slug;
        $slug=preg_replace('/[^a-z0-9]+/','-',$ascii)??'';
        $base=substr(trim($slug,'-')?:'formulario',0,110);$slug=$base;$suffix=2;
        do{$stmt=$this->db()->prepare('SELECT id FROM public_form_settings WHERE slug=? AND id<>?');$stmt->execute([$slug,$ignoreId??0]);$exists=(bool)$stmt->fetch();if($exists)$slug=$base.'-'.$suffix++;}while($exists);
        return $slug;
    }

    private function ensureSchema():void {
        if(self::$schemaReady)return;$db=$this->db();
        $settingsColumns=$db->query('SHOW COLUMNS FROM public_form_settings')->fetchAll();
        $columns=array_column($settingsColumns,'Field');
        if(!in_array('name',$columns,true))$db->exec("ALTER TABLE public_form_settings ADD COLUMN name VARCHAR(160) NULL AFTER id");
        if(!in_array('slug',$columns,true))$db->exec("ALTER TABLE public_form_settings ADD COLUMN slug VARCHAR(120) NULL AFTER name");
        if(!in_array('cover_image',$columns,true))$db->exec("ALTER TABLE public_form_settings ADD COLUMN cover_image VARCHAR(255) NULL AFTER slug");
        if(!in_array('created_at',$columns,true))$db->exec("ALTER TABLE public_form_settings ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP");
        $idColumn=current(array_filter($settingsColumns,fn($column)=>$column['Field']==='id'))?:[];
        if(!preg_match('/^int(?:\(\d+\))? unsigned$/',strtolower((string)($idColumn['Type']??'')))||!str_contains(strtolower((string)($idColumn['Extra']??'')),'auto_increment'))$db->exec("ALTER TABLE public_form_settings MODIFY id INT UNSIGNED NOT NULL AUTO_INCREMENT");
        $db->exec("UPDATE public_form_settings SET name=COALESCE(NULLIF(name,''),title),slug=COALESCE(NULLIF(slug,''),IF(id=1,'inscripcion',CONCAT('formulario-',id))) WHERE name IS NULL OR name='' OR slug IS NULL OR slug=''");
        $indexes=$db->query("SHOW INDEX FROM public_form_settings WHERE Key_name='uq_public_form_slug'")->fetchAll();
        if(!$indexes)$db->exec('ALTER TABLE public_form_settings ADD UNIQUE KEY uq_public_form_slug(slug)');

        $fieldColumns=array_column($db->query('SHOW COLUMNS FROM public_form_fields')->fetchAll(),'Field');
        if(!in_array('form_id',$fieldColumns,true))$db->exec('ALTER TABLE public_form_fields ADD COLUMN form_id INT UNSIGNED NOT NULL DEFAULT 1 AFTER id, ADD INDEX idx_public_form_fields_form(form_id)');
        $submissionColumns=array_column($db->query('SHOW COLUMNS FROM public_form_submissions')->fetchAll(),'Field');
        if(!in_array('form_id',$submissionColumns,true))$db->exec('ALTER TABLE public_form_submissions ADD COLUMN form_id INT UNSIGNED NOT NULL DEFAULT 1 AFTER id, ADD INDEX idx_public_form_submissions_form(form_id)');
        self::$schemaReady=true;
    }

    private function hydrateField(array $field):array {
        $decoded=json_decode($field['options_json']??'[]',true);
        $field['options']=is_array($decoded)?array_values(array_filter(array_map('strval',$decoded),fn($option)=>trim($option)!=='')):[];
        return $field;
    }
}
