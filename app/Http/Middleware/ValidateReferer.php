<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ValidateReferer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the Referer header
        $referer = $request->header('Referer');

        // Check if the Referer is from the same domain
        if ($referer && parse_url($referer, PHP_URL_HOST) !== parse_url(config('app.url'), PHP_URL_HOST)) {
            // If the Referer is not from the same domain, redirect to the safe error page
            return redirect()->route('safe_redirect');
        }

        // Continue with the request if it's safe
        return $next($request);
    }
}
