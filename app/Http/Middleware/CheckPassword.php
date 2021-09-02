<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CheckPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Hash::check('uwuqQ6NP?3E4',auth()->user()->password)) {
            return $next($request);
        }
        return redirect()->to('change-password')->with('success', 'Please create a new password for your account.');
    }
}
