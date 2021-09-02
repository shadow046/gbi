<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Hash;

use Closure;
class checkpassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Hash::check('uwuqQ6NP?3E4',auth()->user()->password)) {
            return redirect()->to('change-password')->with('success', 'Please create a new password for your account.');
        }
        return redirect('/');
    }
}
