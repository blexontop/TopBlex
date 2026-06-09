<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Middleware "esadmin": el portero del panel de administración.
// Se ejecuta ANTES de cada ruta /admin y solo deja pasar a los administradores.
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

        // Si no hay usuario logueado, se bloquea con error 403 (prohibido).
        if (!$user) {
            abort(403, 'No autorizado.');
        }

        // Lista opcional de emails de administrador definida en la configuración.
        $adminEmails = array_filter(config('app.admin_emails', []));

        $isAllowedByEmail = in_array($user->email, $adminEmails, true);
        // Plan B: si no hay lista de emails, el usuario con id 1 actúa como admin.
        $isFallbackAdmin = empty($adminEmails) && (int) $user->id === 1;

        // Si no es admin por ninguna vía, se bloquea el acceso.
        if (!$user->isAdmin() && !$isAllowedByEmail && !$isFallbackAdmin) {
            abort(403, 'No autorizado.');
        }

        // Si es admin, deja continuar hacia la ruta solicitada.
        return $next($request);
    }
}
