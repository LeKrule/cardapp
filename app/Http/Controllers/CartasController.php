<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartasController extends Controller
{
    public function CrearCarta(Request $req) {
        $response = ['status'=> 1, 'msg'=>''];
        //recoger la info del request (viene del json)
        $JsonData = $req->getContent();
        //pasar el Json al objeto
        $Data = json_decode($JsonData);

}
