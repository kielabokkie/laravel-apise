<?php

namespace Kielabokkie\Apise\Http\Middleware;

use Kielabokkie\Apise\Apise;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        return Apise::check($request) ? $next($request) : abort(403);
    }
}
