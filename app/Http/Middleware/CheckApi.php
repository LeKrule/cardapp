<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class CheckApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try{
            if($request->has('api_token')){
                $token = $request->input('api_token');
                $user = User::where('api_token',$token->first());
                if(!$user){
                    return response('el apitoken no es valido');
                }else{
                    return response('No se ha encontrado la apitoken');
                }
            }
        }
        catch (\Exception $error){
            $response['msg'] = "Ha ocurrido un error al añadir el usuario: ".$error->getMessage();
            $response['status'] = 0;
        }
    }
}
