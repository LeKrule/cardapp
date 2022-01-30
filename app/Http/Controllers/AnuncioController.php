<?php

namespace App\Http\Controllers;


use App\Models\Anuncio;
use App\Models\Carta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AnuncioController extends Controller
{
    /*
    public function plantilla(Request $req) {
        $response = ['status'=> 1, 'msg'=>''];
        //recoger la info del request (viene del json)
        $JsonData = $req->getContent();
        //pasar el Json al objeto
        $Data = json_decode($JsonData);


        try {
            $validator = Validator::make(json_decode($JsonData, true), [
                'nombre' => 'required|unique:usuarios| string',
                'email' => 'required|unique:usuarios| string',
                'password' => 'required',
                'rol' => 'required|in:particular,profesional,administrador',
                'biografia' => 'required',
            ]);

            if($validator->fails()){
                $response = ['status'=>0, 'msg'=>$validator->errors()->first()];
            } else {

            }

        } catch (\Exception $error){
            $response['msg'] = "Ha ocurrido un error al añadir : ".$error->getMessage();
            $response['status'] = 0;
        }
        return response()->json($response);
    }*/
    public function buscar(Request $req) {
        $response = ['status'=> 1, 'msg'=>''];
        //recoger la info del request (viene del json)
        $JsonData = $req->getContent();
        //pasar el Json al objeto
        $data = json_decode($JsonData);

        $validator = Validator::make(json_decode($data, true), [
            'carta_nombre' => 'required|string', //
        ]);

        if ($validator->fails()) {
            $response = ['status'=>0, 'msg'=>$validator->errors()->first()];
        } else {
            $response = ['status'=>1, 'msg'=>''];

            $data = json_decode($data);
            try {
                $cartas = carta::where('nombre', 'like', '%'.$data->carta_nombre.'%')->get();
                if(carta::where('nombre', 'like', '%'.$data->carta_nombre.'%')->first()) {
                    $response['msg'] = "Carta encontrada.";
                    $response['status'] = 1;
                    $response['datos'] = $cartas;
                } else {
                    $response['msg'] = "No existe ninguna carta con esa descripcion£";
                    $response['status'] = 0;
                }
            } catch (\Exception $error) {
                $response['msg'] = "Se ha producido un error:".$error->getMessage();
                $response['status'] = 0;
            }
        }
        return response()->json($response);
    }

    public function vender(Request $req) {
        $response = ['status'=> 1, 'msg'=>''];
        //recoger la info del request (viene del json)
        $JsonData = $req->getContent();
        //pasar el Json al objeto
        $data = json_decode($JsonData);

        $validator = Validator::make(json_decode($data, true), [
            'carta_id' => 'required|integer',
            'cantidad' => 'required|integer',
            'precio' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $response = ['status'=>0, 'msg'=>$validator->errors()->first()];
        } else {
            $response = ['status'=>1, 'msg'=>''];

            $data = json_decode($data);
            try {
                $carta = carta::where('id', $data->carta_id)->first();
                if($carta) {
                    $anuncio = new anuncio();
                    $anuncio->usuario_id = $req->usuario->id;
                    $anuncio->carta_id = $data->carta_id;
                    $anuncio->cantidad = $data->cantidad;
                    $anuncio->precio= $data->precio;
                    $anuncio->save();

                    $response['msg'] = "Anuncio creado correctamente.";
                    $response['status'] = 1;
                } else {
                    $response['msg'] = "No existe ninguna carta con ese nombre";
                    $response['status'] = 0;
                }
            } catch (\Exception $error) {
                $response['msg'] = "Se ha producido un error:".$error->getMessage();
                $response['status'] = 0;
            }
        }
        return response()->json($response);
    }

    public function comprar(Request $req) {
        $response = ['status'=> 1, 'msg'=>''];
        //recoger la info del request (viene del json)
        $JsonData = $req->getContent();
        //pasar el Json al objeto
        $data = json_decode($JsonData);

        $validator = Validator::make(json_decode($data, true), [
            'carta_nombre' => 'required|string',
        ]);

        if ($validator->fails()) {
            $response = ['status'=>0, 'msg'=>$validator->errors()->first()];
        } else {
            $response = ['status'=>1, 'msg'=>''];

            $data = json_decode($data);
            try {
                $cartas = DB::table('anuncios')->select(['anuncios.id', 'cartas.nombre', 'anuncios.cantidad', 'anuncios.precio', 'usuarios.nombre'])
                                ->where('nombre', 'like', '%'.$data->carta_nombre.'%')
                                ->join('usuarios', 'anuncios.usuario_id', '=', 'usuarios.id')
                                ->join('cartas', 'anuncios.carta_id', '=', 'cartas.id')
                                ->orderBy('anuncios.precio', 'asc')
                                ->get();
                if(count($cartas) > 0) {
                    $response['msg'] = "Carta encontrada.";
                    $response['status'] = 1;
                    $response['datos'] = $cartas;
                } else {
                    $response['msg'] = "No existe ninguna carta con ese nombre.";
                    $response['status'] = 0;
                }
            } catch (\Exception $error) {
                $response['msg'] = "Se ha producido un error:".$error->getMessage();
                $response['status'] = 0;
            }
        }
        return response()->json($response);
    }

    public function buscarycomprar(Request $req) {
        $response = ['status'=> 1, 'msg'=>''];
        //recoger la info del request (viene del json)
        $JsonData = $req->getContent();
        //pasar el Json al objeto
        $data = json_decode($JsonData);

        $validator = Validator::make(json_decode($data, true), [
            'anuncio_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $response = ['status'=>0, 'msg'=>$validator->errors()->first()];
        } else {
            $response = ['status'=>1, 'msg'=>''];

            $data = json_decode($data);
            try {
                $anuncio = anuncio::where('id', $data->anuncio_id)->first();
                if($anuncio) {
                    $anuncio->delete();

                    $response['msg'] = "Carta comprada correctamente.";
                    $response['status'] = 1;
                } else {
                    $response['msg'] = "No hay ninguna oferta con ese id";
                    $response['status'] = 0;
                }
            } catch (\Exception $error) {
                $response['msg'] = "Se ha producido un error:".$error->getMessage();
                $response['status'] = 0;
            }
        }
        return response()->json($response);
    }
}
