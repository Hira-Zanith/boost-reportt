<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ១. ឆែកថាតើបាន Login ឬនៅ និងមាន Role ជា admin ឬអត់
        if (auth()->check() && auth()->user()->role === 'admin') {
            return $next($request);
        }

        // ២. ប្រសិនបើមិនមែនជា Admin ទេ ឱ្យត្រឡប់ទៅវិញជាមួយសារ Error
        return redirect('/dashboard')->with('error', 'អ្នកគ្មានសិទ្ធិចូលទៅកាន់ផ្នែកនេះទេ!');
    }
}
