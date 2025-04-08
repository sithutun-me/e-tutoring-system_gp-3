<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackBrowserUsage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // $agent = $request->header('User-Agent');

        // $browser = 'Unknown';

        // if (stripos($agent, 'Firefox') !== false) {
        //     $browser = 'Firefox';
        // } elseif (stripos($agent, 'Chrome') !== false && stripos($agent, 'Edg') === false) {
        //     $browser = 'Chrome';
        // } elseif (stripos($agent, 'Edg') !== false || stripos($agent, 'Edge') !== false) {
        //     $browser = 'Microsoft Edge';
        // }

        // \DB::table('browser_logs')->insert([
        //     'browser' => $browser,
        //     'visited_at' => now(),
        // ]);
        

        if (!session()->has('browser_logged')) {
            $userAgent = $request->header('User-Agent');
            $browser = 'Unknown';
    
            if (str_contains($userAgent, 'Chrome') && !str_contains($userAgent, 'Edg')) {
                $browser = 'Chrome';
            } elseif (str_contains($userAgent, 'Firefox')) {
                $browser = 'Firefox';
            } elseif (str_contains($userAgent, 'Edg')) {
                $browser = 'Microsoft Edge';
            }
    
            \DB::table('browser_logs')->insert([
                'browser' => $browser,
                'visited_at' => now(),
            ]);
    
            session()->put('browser_logged', true); // prevent repeated loggi
        }
        return $next($request);
    }
}
