<?php
namespace App\Controllers;
use App\Core\Controller;
class PublicController extends Controller {
 function home():void{$this->publicPage('home');}
 function asignaturas():void{$this->publicPage('asignaturas');}
 function portafolio():void{$this->publicPage('portafolio');}
 function clasesAsincronicas():void{$this->publicPage('clases-asincronicas');}
 function correctoresIa():void{$this->publicPage('correctores-ia');}
 function tabuladores():void{$this->publicPage('tabuladores');}
 function recursos():void{$this->publicPage('recursos');}
 function contacto():void{$this->publicPage('contacto');}
 function preguntasFrecuentes():void{$this->publicPage('preguntas-frecuentes');}
}
