<?php
namespace App\Models;

class PublicForm extends BaseModel {
    protected string $table='public_form_settings';

    public function settings():array {
        $settings=$this->db()->query('SELECT * FROM public_form_settings WHERE id=1')->fetch();
        return $settings?:[];
    }

    public function saveInformation(array $data):void {
        $sql='UPDATE public_form_settings SET eyebrow=?,title=?,intro=?,information_title=?,information_body=?,status=?,submit_label=?,success_title=?,success_message=?,consent_text=? WHERE id=1';
        $this->db()->prepare($sql)->execute([
            $data['eyebrow'],$data['title'],$data['intro'],$data['information_title'],$data['information_body'],
            $data['status'],$data['submit_label'],$data['success_title'],$data['success_message'],$data['consent_text'],
        ]);
    }

    public function saveBank(array $data):void {
        $sql='UPDATE public_form_settings SET bank_enabled=?,bank_title=?,bank_amount=?,bank_holder=?,bank_rut=?,bank_name=?,bank_account_type=?,bank_account_number=?,bank_email=?,bank_instructions=? WHERE id=1';
        $this->db()->prepare($sql)->execute([
            $data['bank_enabled'],$data['bank_title'],$data['bank_amount'],$data['bank_holder'],$data['bank_rut'],
            $data['bank_name'],$data['bank_account_type'],$data['bank_account_number'],$data['bank_email'],$data['bank_instructions'],
        ]);
    }

    public function fields(bool $onlyActive=false):array {
        $where=$onlyActive?' WHERE active=1':'';
        $rows=$this->db()->query("SELECT * FROM public_form_fields{$where} ORDER BY sort_order,id")->fetchAll();
        return array_map([$this,'hydrateField'],$rows);
    }

    public function field(int $id):?array {
        $stmt=$this->db()->prepare('SELECT * FROM public_form_fields WHERE id=?');
        $stmt->execute([$id]);
        $row=$stmt->fetch();
        return $row?$this->hydrateField($row):null;
    }

    public function saveField(array $data,?int $id=null):void {
        $options=json_encode($data['options'],JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        if($id){
            $sql='UPDATE public_form_fields SET label=?,field_type=?,placeholder=?,help_text=?,options_json=?,required=?,active=?,sort_order=?,max_selections=? WHERE id=?';
            $params=[$data['label'],$data['field_type'],$data['placeholder'],$data['help_text'],$options,$data['required'],$data['active'],$data['sort_order'],$data['max_selections'],$id];
        }else{
            $sql='INSERT INTO public_form_fields(label,field_type,placeholder,help_text,options_json,required,active,sort_order,max_selections) VALUES(?,?,?,?,?,?,?,?,?)';
            $params=[$data['label'],$data['field_type'],$data['placeholder'],$data['help_text'],$options,$data['required'],$data['active'],$data['sort_order'],$data['max_selections']];
        }
        $this->db()->prepare($sql)->execute($params);
    }

    public function submissions(int $limit=100):array {
        $limit=max(1,min(250,$limit));
        return $this->db()->query("SELECT * FROM public_form_submissions ORDER BY created_at DESC,id DESC LIMIT {$limit}")->fetchAll();
    }

    public function submission(int $id):?array {
        $stmt=$this->db()->prepare('SELECT * FROM public_form_submissions WHERE id=?');
        $stmt->execute([$id]);
        return $stmt->fetch()?:null;
    }

    public function createSubmission(array $answers,string $name,string $email):int {
        $stmt=$this->db()->prepare('INSERT INTO public_form_submissions(contact_name,contact_email,answers_json) VALUES(?,?,?)');
        $stmt->execute([$name,$email,json_encode($answers,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)]);
        return (int)$this->db()->lastInsertId();
    }

    private function hydrateField(array $field):array {
        $decoded=json_decode($field['options_json']??'[]',true);
        $field['options']=is_array($decoded)?array_values(array_filter(array_map('strval',$decoded),fn($option)=>trim($option)!=='')):[];
        return $field;
    }
}
