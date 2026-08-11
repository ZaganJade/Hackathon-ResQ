<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // XSS Protection
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer Policy
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Content Security Policy (CSP) - Adjust as needed for your frontend
        // Only enforced in production: the Vite dev server (npm run dev) is
        // reached via a mix of localhost/127.0.0.1/[::1] and a port that can
        // shift if 5173 is taken, and CSP's host-source grammar doesn't
        // reliably match bracketed IPv6 literals like [::1] anyway. There's
        // no real security value in restricting local dev tooling, so the
        // policy is skipped entirely outside production instead of chasing
        // exact dev-server origins.
        if (app()->environment('production')) {
            $csp = "default-src 'self'; " .
                   "script-src 'self' 'unsafe-inline' 'unsafe-eval' *.googleapis.com *.gstatic.com unpkg.com cdnjs.cloudflare.com; " .
                   "style-src 'self' 'unsafe-inline' *.googleapis.com fonts.googleapis.com fonts.bunny.net unpkg.com cdnjs.cloudflare.com; " .
                   "img-src 'self' data: blob: storage: *.googleapis.com *.gstatic.com *.google.com *.openstreetmap.org *.tile.openstreetmap.org *.cartocdn.com images.unsplash.com images.unsplash.com *.unsplash.com; " .
                   "font-src 'self' fonts.gstatic.com fonts.bunny.net cdnjs.cloudflare.com; " .
                   "connect-src 'self' *.fireworks.ai *.googleapis.com *.wablas.com *.yobase.io *.yobase.me nominatim.openstreetmap.org unpkg.com cdnjs.cloudflare.com; " .
                   "frame-src 'self' *.youtube.com *.youtu.be *.youtube-nocookie.com *.openstreetmap.org; " .
                   "object-src 'none'; " .
                   "base-uri 'self'; " .
                   "form-action 'self';";

            $response->headers->set('Content-Security-Policy', $csp);
        }

        // Permissions Policy
        $response->headers->set('Permissions-Policy', 'geolocation=(self), microphone=(), camera=()');

        // Strict Transport Security (HSTS) - Only in production
        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        }

        return $response;
    }
}
