<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class UserAdmin
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
            $user = $request->user;

            if($user->rol == 'administrador'){
                $request->user = $user;
                return $next($request);
            }else{
                return response( 'El usuario no tiene los permisos necesarios');

            }
        }catch (\Exception $error){
            $response['msg'] = "Ha ocurrido un error ".$error->getMessage();
            $response['status'] = 0;
    }
}}
