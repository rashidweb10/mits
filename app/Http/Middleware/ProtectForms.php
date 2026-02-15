<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class ProtectForms
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userAgent = $request->header('User-Agent');
        $ip = $request->ip();

        // 🧱 1. Block empty User-Agent
        if (empty($userAgent)) {
            \Log::warning("Empty User-Agent blocked from: $ip");
            abort(403, 'Forbidden - Missing User Agent');
        }

        // 🚫 2. Block known bad bots
        $badAgents = ['curl', 'wget', 'python', 'bot', 'scrapy', 'PostmanRuntime'];
        foreach ($badAgents as $bot) {
            if (stripos($userAgent, $bot) !== false) {
                \Log::warning("Bot User-Agent '$userAgent' blocked from: $ip");
                abort(403, 'Forbidden - Bot Detected');
            }
        }

        //🧩 3. Optional: Block non-browser requests (optional)
        // if (!$request->ajax() && !$request->expectsJson() && !$request->isMethod('post')) {
        //     \Log::warning("Suspicious form access attempt from: $ip");
        //     abort(403, 'Forbidden - Suspicious Request');
        // }

        // // ✅ 4. Filter for Suspicious Patterns Inputs
        $suspiciousPatterns = [
            '/<script\b[^>]*>(.*?)<\/script>/is',  // Full <script> tags
            '/javascript:/i',                      // "javascript:" in links
            '/on\w+\s*=\s*["\'].*?["\']/i',        // Inline event handlers like onclick=""
            '/<iframe\b[^>]*>(.*?)<\/iframe>/is',  // Malicious iframes
            '/<img\b[^>]*onerror\s*=\s*["\'].*?["\']/i', // Image-based XSS
        ];

        foreach ($request->all() as $key => $value) {
            if (!is_string($value)) continue;

            foreach ($suspiciousPatterns as $pattern) {
                if (preg_match($pattern, $value)) {
                    Log::warning("Suspicious input blocked on '$key' with value '$value' from $ip");
                    abort(403, 'Forbidden - Suspicious Input Detected');
                }
            }
        } 
        
        // ✅ 5. File upload validation for forms like career, etc.
        //if ($request->hasFile()) {
            foreach ($request->files as $key => $file) {
                if (!$file->isValid()) {
                    Log::warning("Invalid file upload in '$key' from IP: $ip");
                    abort(403, 'Invalid file upload detected');
                }

                // Allowed MIME types
                $allowedMimes = [
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'image/jpeg',
                    'image/png',
                ];

                // Check MIME type
                if (!in_array($file->getMimeType(), $allowedMimeTypes)) {
                    Log::warning("Disallowed file type '{$file->getMimeType()}' in '$key' from IP: $ip");
                    abort(403, 'Disallowed file type');
                }

                // Max file size (in bytes) - 2MB
                // if ($file->getSize() > 2 * 1024 * 1024) {
                //     Log::warning("File too large in '$key' from IP: $ip. Size: {$file->getSize()} bytes");
                //     abort(403, 'File too large. Max 2MB allowed.');
                // }
            }
        //}        

        // ✅ Optional: If you're using reCAPTCHA v3, you can validate here
        // (Let me know if you want that too)

        // 🕳️ 6. Honeypot check
        if ($request->filled('website')) { // "website" is our honeypot field
            Log::warning("Honeypot field triggered by spam bot from IP: $ip");
            abort(403, 'Forbidden - Bot detected');
        }        

        return $next($request);
    }
}
