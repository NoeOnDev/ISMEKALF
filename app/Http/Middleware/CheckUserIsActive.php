<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserIsActive
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si el usuario está autenticado pero no está activo
        if (Auth::check() && !Auth::user()->active) {
            // Cerrar sesión
            Auth::logout();

            // Invalidar la sesión y regenerar el token CSRF
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            // Redirigir al login con un mensaje
            return redirect()->route('login')
                ->with('error', 'Tu cuenta ha sido desactivada. Contacta al administrador.');
        }

        return $next($request);
    }
}
