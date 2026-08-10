<?php
namespace App\Services;

final class RegistrationMailer {
 private string $lastError='';

 public function send(array $user,string $plainPassword):bool {
  $email=strtolower(trim((string)($user['email']??'')));
  if(!filter_var($email,FILTER_VALIDATE_EMAIL)||$plainPassword===''){return false;}

  $loginUrl=absolute_url('/login');
  $logoUrl=absolute_url('/assets/img/logo-ruta-docente.png');
  ob_start();
  require dirname(__DIR__).'/Views/emails/user-registration.php';
  $html=(string)ob_get_clean();

  $fromAddress=$this->headerValue((string)config('mail_from_address'));
  $fromName=$this->headerValue((string)config('mail_from_name'));
  $replyTo=$this->headerValue((string)config('mail_reply_to'));
  $encodedName=function_exists('mb_encode_mimeheader')?mb_encode_mimeheader($fromName,'UTF-8'):$fromName;
  $subject='Tus datos de acceso | Ruta Docente';
  $encodedSubject=function_exists('mb_encode_mimeheader')?mb_encode_mimeheader($subject,'UTF-8'):$subject;
  $headers=[
   'MIME-Version: 1.0',
   'Content-Type: text/html; charset=UTF-8',
   'Content-Transfer-Encoding: 8bit',
   'From: '.$encodedName.' <'.$fromAddress.'>',
   'Reply-To: '.$replyTo,
   'X-Mailer: Ruta Docente',
  ];
  $smtp=new SmtpTransport;
  if($smtp->send($email,$encodedSubject,$html,$headers)){$this->log($email,'smtp','accepted');return true;}

  $this->lastError=$smtp->error();
  if(config('native_mail_fallback')&&function_exists('mail')&&mail($email,$encodedSubject,$html,implode("\r\n",$headers))){$this->log($email,'mail','accepted_after_smtp_failure');return true;}
  $this->log($email,'failed',$this->lastError?:'transport_unavailable');
  return false;
 }

 public function error():string{return $this->lastError;}

 private function headerValue(string $value):string{return trim(str_replace(["\r","\n"],'',$value));}
 private function log(string $email,string $transport,string $status):void {
  $directory=dirname(__DIR__,2).'/storage/logs';
  if(!is_dir($directory))@mkdir($directory,0755,true);
  $line=sprintf("[%s] registration_email recipient=%s transport=%s status=%s\n",date('c'),$email,$transport,preg_replace('/\s+/','_',$status));
  @file_put_contents($directory.'/mail.log',$line,FILE_APPEND|LOCK_EX);
 }
}
