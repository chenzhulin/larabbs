<?php

namespace App\Http\Middleware;

use Closure;

class EnsureEmailIsVerified
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
	    if($request->user() && ! $request->user()->hasVerifiedEmail() && ! $request->is('email/*','logout') ){
	    
	    	//根据客户端返回内容

		    return $request->expectsJson()
			 	? abor(403,'Your email address is not verified.') 
			      	: redirect()->route('verification.notice');	
	    
	    }

	    return $next($request);
    }
}
