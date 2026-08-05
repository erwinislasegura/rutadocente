<?php
namespace App\Core;
class Router {
 private array $routes=[];
 function get(string $p,array $h){$this->routes['GET'][$p]=$h;}
 function post(string $p,array $h){$this->routes['POST'][$p]=$h;}
 function dispatch():void{$path=parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);$base=rtrim(config('base_url'),'/');if($base&&str_starts_with($path,$base))$path=substr($path,strlen($base));$path='/' . trim($path,'/');if($path==='//')$path='/';$h=$this->routes[$_SERVER['REQUEST_METHOD']][$path]??null;if(!$h){http_response_code(404);echo 'Página no encontrada';return;}[$c,$m]=$h;(new $c)->$m();}
}

