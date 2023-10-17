<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProcessLocale
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
	 */
	public function handle(Request $request, Closure $next): Response
	{
		if (session()->has('locale')) {
			$locale = session()->get('locale');
			if (in_array($locale, config('app.locales'))) {
				app()->setLocale($locale);
			}
		}

		return $next($request);
	}
}
