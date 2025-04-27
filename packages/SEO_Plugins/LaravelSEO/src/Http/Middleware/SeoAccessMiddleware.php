<?php

namespace SEO_Plugins\LaravelSEO\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SeoAccessMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // User from web or api guard
        $user = auth('api')->user() ?? auth()->user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Get allowed roles from config
        $roles = config('seo.access_roles', ['Super Admin', 'SEO Manager']);

        // Check if user has one of the allowed roles
        if (method_exists($user, 'hasAnyRole') && $user->hasAnyRole($roles)) {
            return $next($request);
        }

        return response()->json(['error' => 'Forbidden – insufficient role'], 403);
    }
}
