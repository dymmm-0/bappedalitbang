<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;

class RecordVisit
{
    /**
     * Catat satu kunjungan per request GET ke halaman publik.
     * Halaman admin dan prefetch tidak dihitung.
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($request->isMethod('GET')
            && ! $request->is('admin', 'admin/*')
            && ! $request->ajax()
            && ! $request->is('_debugbar/*')
        ) {
            try {
                DB::table('visits')->insert([
                    'created_at' => now(),
                ]);
            } catch (\Throwable $e) {
                // jangan sampai pencatatan kunjungan memutus request
            }
        }

        return $response;
    }
}
