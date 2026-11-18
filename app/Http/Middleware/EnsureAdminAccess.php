<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAdminAccess
{
    /**
     * Validasi setiap request ke panel admin: user harus login dan
     * memiliki salah satu role yang terdaftar di config/rbac.php.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $roles = array_keys(config('rbac.roles', []));

        if ($roles && ! $user->hasAnyRole($roles)) {
            abort(403, 'Anda tidak memiliki akses ke panel administrasi.');
        }

        return $next($request);
    }
}
