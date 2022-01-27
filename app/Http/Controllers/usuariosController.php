<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;



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
                    $user = User::where('email', $Data->email)->first();
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
        public function registrar(Request $req){
            //recoger la info del request (viene del json)
            $JsonData = $req->getContent();
            //pasar el Json al objeto
            $Data = json_decode($JsonData);
            $user = new User();

            try{
                $validator = Validator::make(json_decode($JsonData, true), [
                    'nombre' => 'required|unique:users| string',
                    'email' => 'required|unique:users| string | email:rfc,dns',
                    'password' => 'required',
                    'rol' => 'required|in:particular,profesional,administrador',
                    'biografia' => 'required',

                ]);

                if($validator->fails()){
                    $response = ['status'=>0, 'msg'=>$validator->errors()->first()];
                } else {
                    $user->nombre = $Data->nombre;
                    $user->email = $Data->email;
                    if(preg_match("/(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).{6,}/", $Data->password)){
                        $user->password = Hash::make($Data->password);
                    }else{
                        $response['msg'] = " la contraseña no es segura";
                        return response()->json($response);
                    }
                    $user->rol = $Data->rol;
                    $user->biografia = $Data->biografia;
                    $user->save();
                    $response['msg'] = " el usuario ha sido creado correctamente";
                    $response['status'] = 1;
                }

            }catch (\Exception $error){
                $response['msg'] = "Ha ocurrido un error al añadir el usuario: ".$error->getMessage();
                $response['status'] = 0;
            }
            return response()->json($response);
        }
        public function RecuperarPass(Request $req){
            //recoger la info del request (viene del json)
            $JsonData = $req->getContent();
            //pasar el Json al objeto
            $Data = json_decode($JsonData);

            $user = User::where('email',$Data->email)->first();

            if(isset($user)){
                $NuevaPass = Str::random(20);
                $user->password = Hash::make($NuevaPass);
                $user->save();
                $response['msg'] = "Contraseña cambiada correctamente: $NuevaPass";
                $response['status'] = 1;

            }else{
                $response['msg'] = "Ctm no se encuentra el email fds kkkkk";
                $response['status'] = 0;
            }

            return response()->json($response);
        }

}
