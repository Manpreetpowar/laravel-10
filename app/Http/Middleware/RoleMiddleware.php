<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
class RoleMiddleware
{

    public function handle($request, Closure $next, ...$role)
    {
        if(Auth::check()){
            if(!$request->user()->hasRole(...$role)) {
                abort(401);
            }
        }else{
            return redirect('/');
        }

        return $next($request);

    }
}