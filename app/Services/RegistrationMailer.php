<?php
namespace App\Services;

final class RegistrationMailer {
 public function send(array $user,string $plainPassword):bool {
  $email=strtolower(trim((string)($user['email']??'')));
  if(!filter_var($email,FILTER_VALIDATE_EMAIL)||$plainPassword===''||!function_exists('mail'))return false;

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
  return mail($email,$encodedSubject,$html,implode("\r\n",$headers));
 }

 private function headerValue(string $value):string{return trim(str_replace(["\r","\n"],'',$value));}
}
