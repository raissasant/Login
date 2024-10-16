<?php

// app/Http/Middleware/CheckAdmin.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckIfAdmin
{
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->is_admin) {
            return $next($request); // Permitir acesso se for admin
        }

        return redirect('/home'); // Se não for admin, redireciona para o painel de usuário comum
    }
}
