<?php
namespace App\Controllers;
use App\Core\Controller;
class PublicController extends Controller { function home():void{$this->view('public/home',['title'=>'Inicio']);} }

