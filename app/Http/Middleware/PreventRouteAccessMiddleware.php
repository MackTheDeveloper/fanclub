<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Auth;

class PreventRouteAccessMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = 'admin')
    {
        if (Auth::guard($guard)->check()) {
            $user_id = Auth::guard($guard)->user()->id;

            if ($user_id != 1) {
                $routes = \App\Models\User::leftJoin('permission_user', 'permission_user.user_id', '=', 'users.id')
                    ->leftJoin('permissions', 'permissions.id', '=', 'permission_user.permission_id')
                    ->where('users.id', $user_id)
                    ->pluck('permissions.permission_route')
                    ->toArray();
                if (empty($routes)) {
                    $routes = \App\Models\User::leftJoin('role_user', 'role_user.admin_id', '=', 'users.id')
                        ->leftJoin('permission_role', 'permission_role.role_id', '=', 'role_user.role_id')
                        ->leftJoin('permissions', 'permissions.id', '=', 'permission_role.permission_id')
                        ->where('users.id', $user_id)
                        ->pluck('permissions.permission_route')
                        ->toArray();
                }

                
                /* $allow = 0;
                foreach ($routes as $k => $v) {
                    $sRoute = explode(',', $v);
                    if (in_array($request->path(), $sRoute)) {
                        $allow = 1;
                        continue;
                    }
                }

                if ($allow == 1)
                    return $next($request);
                else
                    return redirect(config('app.adminPrefix').'/access-denied'); */

                if (request()->id) {
                    $path = str_replace(request()->id, '*', $request->path());
                } elseif (request()->token) {
                    $path = str_replace(request()->token, '*', $request->path());
                } elseif (request()->lang_id) {
                    $path = str_replace(request()->lang_id, '*', $request->path());
                } else {
                    $path = $request->path();
                }

                if (in_array($path, $routes)) {
                    return $next($request);
                } else {
                    // return redirect(config('app.adminPrefix').'/access-denied');
                    return abort(403);
                }
            }
        }
        return $next($request);
    }
}
