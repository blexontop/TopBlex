<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'No autorizado.');
        }

        $adminEmails = array_filter(config('app.admin_emails', []));

        $isAllowedByEmail = in_array($user->email, $adminEmails, true);
        $isFallbackAdmin = empty($adminEmails) && (int) $user->id === 1;

        if (!$isAllowedByEmail && !$isFallbackAdmin) {
            abort(403, 'No autorizado.');
        }

        return $next($request);
    }
}
