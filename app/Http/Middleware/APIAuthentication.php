<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;

class ApiAuthentication
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
		if ($request->header('ClientKey')!=='imCrsVauxAMTO44' && $request->header('ClientSecret')!=='Mldi1@86f9As!0vzr$skfnwO@avVsW12fz' ){
			return response([
				'message' => "Unauthenticated",
				'status' => 401
			], 401);
		}
		return $next($request);
	}
}
