<?php

namespace App\Http\Middleware;

// use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Auth;
use Session;

class RoleUser
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle($request, Closure $next,$role)
    {
        if(in_array($role,[2,3])){
            $authRole = Auth::user()->role_id;
            if ($role=="3" && Session::has('artist_as_fan') && Session::get('artist_as_fan')) {
                return $next($request);
            }elseif ($authRole==$role) {
            // if ($authRole==$role) {
                return $next($request);
            }else{
                abort(404,'Page not found');
            }
        }else{
            if (!Auth::check()) {
                if($role=='guest'){
                    return $next($request);
                }else{
                    abort(404,'Page not found');
                }                
            }else{
                return redirect('/');
            }
        }
        
    }
}
