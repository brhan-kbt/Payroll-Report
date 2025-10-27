<?php

namespace App\Http\Middleware;

use App\Services\AppVersionService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip maintenance check for admin routes
        if ($request->is('api/v1/admin/*')) {
            return $next($request);
        }

        // Check if maintenance mode is enabled
        if (AppVersionService::isMaintenanceMode()) {
            return response()->json([
                'success' => false,
                'message' => AppVersionService::getMaintenanceMessage(),
                'maintenance_mode' => true
            ], 503);
        }

        return $next($request);
    }
}
