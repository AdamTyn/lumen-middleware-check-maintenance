<?php

namespace AdamTyn\Lumen\Middleware;

use Closure;
use Illuminate\Contracts\Foundation\Application;
use AdamTyn\Lumen\Middleware\MaintenanceModeException;
use Symfony\Component\HttpFoundation\IpUtils;

class CheckForMaintenanceMode
{
    protected $path;

    /**
     * The URIs that should be accessible while maintenance mode is enabled.
     *
     * @var array
     */
    protected $except = [];

    /**
     * CheckForMaintenanceMode constructor.
     */
    public function __construct()
    {
        $this->path = storage_path('framework/down');
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     * @throws \Illuminate\Foundation\Http\Exceptions\MaintenanceModeException
     */
    public function handle($request, Closure $next)
    {
        if ($this->isDownForMaintenance()) {
            $data = json_decode(file_get_contents($this->path), true);

            if (isset($data['allowed']) && IpUtils::checkIp($request->ip(), (array)$data['allowed'])) {
                return $next($request);
            }

            if ($this->inExceptArray($request)) {
                return $next($request);
            }

            throw new MaintenanceModeException($data['time'], $data['retry'], $data['message']);
        }

        return $next($request);
    }

    /**
     * Determine if the request has a URI that should be accessible in maintenance mode.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
                return true;
            }
        }

        return false;
    }

    protected function isDownForMaintenance()
    {
        return file_exists($this->path);
    }
}
