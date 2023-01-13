<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Subscribed {
  public function handle($request, Closure $next) {
    if ($request->user() and ! $request->user()->subscribed('default'))
      return redirect('subscribe');
    return $next($request);
  }
}

