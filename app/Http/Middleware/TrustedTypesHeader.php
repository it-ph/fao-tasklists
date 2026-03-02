<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TrustedTypesHeader
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
        $response = $next($request);

        if ($response->headers->has('Content-Security-Policy')) {
            $csp = $response->headers->get('Content-Security-Policy');

            // Append Trusted Types directives
            $csp .= "; require-trusted-types-for 'script'; trusted-types default";

            $response->headers->set('Content-Security-Policy', $csp);
        }

        return $response;
    }
}
