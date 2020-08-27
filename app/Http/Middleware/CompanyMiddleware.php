<?php

namespace App\Http\Middleware;

use App\Models\Companies;
use Illuminate\Support\Facades\Auth;
use Closure;

class CompanyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::user()->is_company()) {
                return $next($request);
        }
       // return redirect()->back('message','No Acces');
       return redirect()->back();

    }
}
