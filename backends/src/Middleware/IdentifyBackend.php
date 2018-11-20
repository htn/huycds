<?php

namespace Huycds\Backends\Middleware;

use Huycds\Backends\Backends;
use Closure;

class IdentifyBackend
{
    /**
     * @var Huycds\Backends
     */
    protected $backend;

    /**
     * Create a new IdentifyBackend instance.
     *
     * @param Huycds\Backends $backend
     */
    public function __construct(Backends $backend)
    {
        $this->backend = $backend;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $slug = null)
    {
        $request->session()->flash('backend', $this->backend->where('slug', $slug));

        return $next($request);
    }
}
