<?php

namespace Botble\Ecommerce\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CaptureCouponMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (! $request->isMethod('GET')) {
            return $next($request);
        }

        if ($request->filled('coupon') && ! Session::has('applied_coupon_code')) {
            Session::put('auto_apply_coupon_code', $request->query('coupon'));
        }

        return $next($request);
    }
}
