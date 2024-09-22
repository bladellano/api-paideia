<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminPassword
{
  /**
   * Handle an incoming request.
   */
  public function handle(Request $request, Closure $next): Response
  {
    if ($request->session()->get('admin_logged_in')) 
      return $next($request);

    if ($request->isMethod('get') && $request->input('password') === '6m!nFwVm') {
      $request->session()->put('admin_logged_in', true);
      return redirect('/admin');
    }

    return response()->view('auth.admin-login');
  }
}
