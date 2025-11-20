<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminOnly
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || Auth::user()->role != 2) {
            abort(403, '管理者のみアクセス可能です');
        }

        return $next($request);
    }
}