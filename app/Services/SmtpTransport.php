<?php
namespace App\Services;

final class SmtpTransport {
 private string $lastError='';

 public function send(string $recipient,string $subject,string $html,array $headers):bool {
  $socket=null;
  try{
   $host=(string)config('smtp_host');$port=(int)config('smtp_port');$encryption=(string)config('smtp_encryption');
   $target=($encryption==='ssl'?'ssl://':'').$host.':'.$port;
   $socket=@stream_socket_client($target,$errno,$error,12,STREAM_CLIENT_CONNECT);
   if(!$socket)throw new \RuntimeException('smtp_connection_'.$errno.'_'.($error?:'failed'));
   stream_set_timeout($socket,12);
   $this->expect($socket,[220]);
   $hostname=parse_url((string)config('site_url'),PHP_URL_HOST)?:'rutadocente.com';
   $this->command($socket,'EHLO '.$hostname,[250]);

   if($encryption==='tls'){
    $this->command($socket,'STARTTLS',[220]);
    if(!@stream_socket_enable_crypto($socket,true,STREAM_CRYPTO_METHOD_TLS_CLIENT))throw new \RuntimeException('smtp_tls_failed');
    $this->command($socket,'EHLO '.$hostname,[250]);
   }

   $username=(string)config('smtp_username');$password=(string)config('smtp_password');
   if($username!==''){
    $this->command($socket,'AUTH LOGIN',[334]);
    $this->command($socket,base64_encode($username),[334]);
    $this->command($socket,base64_encode($password),[235]);
   }

   $from=$this->address((string)config('mail_from_address'));
   $to=$this->address($recipient);
   $this->command($socket,'MAIL FROM:<'.$from.'>',[250]);
   $this->command($socket,'RCPT TO:<'.$to.'>',[250,251]);
   $this->command($socket,'DATA',[354]);

   $messageHeaders=array_merge([
    'Date: '.date(DATE_RFC2822),
    'To: <'.$to.'>',
    'Subject: '.$subject,
    'Message-ID: <'.bin2hex(random_bytes(12)).'@'.$hostname.'>',
   ],$headers);
   $body=$this->crlf(implode("\r\n",$messageHeaders)."\r\n\r\n".$html);
   $body=preg_replace('/^\./m','..',$body)??$body;
   fwrite($socket,$body."\r\n.\r\n");
   $this->expect($socket,[250]);
   $this->command($socket,'QUIT',[221]);
   fclose($socket);return true;
  }catch(\Throwable $error){
   $this->lastError=$error->getMessage();
   if(is_resource($socket))fclose($socket);
   return false;
  }
 }

 public function error():string{return $this->lastError;}

 private function command($socket,string $command,array $codes):string {
  if(fwrite($socket,$command."\r\n")===false)throw new \RuntimeException('smtp_write_failed');
  return $this->expect($socket,$codes);
 }

 private function expect($socket,array $codes):string {
  $response='';
  do{
   $line=fgets($socket,1024);
   if($line===false)throw new \RuntimeException('smtp_no_response');
   $response.=$line;
  }while(strlen($line)>3&&$line[3]==='-');
  $code=(int)substr($response,0,3);
  if(!in_array($code,$codes,true))throw new \RuntimeException('smtp_response_'.$code);
  return $response;
 }

 private function address(string $value):string {
  $value=trim(str_replace(["\r","\n"],'',$value));
  if(!filter_var($value,FILTER_VALIDATE_EMAIL))throw new \RuntimeException('smtp_invalid_address');
  return $value;
 }

 private function crlf(string $value):string{return preg_replace("~(?<!\r)\n|\r(?!\n)~","\r\n",$value)??$value;}
}
