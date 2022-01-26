<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class usuariosController extends Controller
{

        public function login(Request $req) {
            $response = ['status'=> 1, 'msg'=>''];
            //recoger la info del request (viene del json)
            $JsonData = $req->getContent();
            //pasar el Json al objeto
            $Data = json_decode($JsonData);

            try{
                if($Data->email) {
                    $user = Usuario::where('email', $Data->email)->first();
                    if($Data->email) {
                        if(Hash::check($Data->password, $user->password)) {
                            $token = Hash::make(now());
                            $user->api_token = $token;
                            $user->save();
                            $response['msg'] = "Sesion iniciada. Token: ".$token;
                        } else {
                            $response['msg'] = "La contraseña no coincide";
                            $response['status'] = 0;
                        }
                    } else {
                        $response['msg'] = "El usuario introducido no existe";
                        $response['status'] = 0;
                    }
                } else {
                    $response['msg'] = "Debes introducir un email";
                    $response['status'] = 0;
                }

            }catch (\Exception $error){
                $response['msg'] = "Ha ocurrido un error al añadir el usuario: ".$error->getMessage();
                $response['status'] = 0;
            }
            return response()->json($response);
        }
    //
}
