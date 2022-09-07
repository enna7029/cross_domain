<?php

namespace Enna\CrossDomain;

use \Closure;
use think\Config;
use think\Request;
use think\Response;

/**
 * 跨域请求支持
 * Class AllowCrossDomain
 * @package Enna\CrossDomain
 */
class AllowCrossDomain
{
    protected $cookieDomain;

    protected $header = [
        'Access-Control-Allow-Credentials' => 'true',
        'Access-Control-Max-Age' => 1800,
        'Access-Control-Allow-Methods' => 'GET,POST,PATCH,PUT,DELETE,OPTIONS',
        'Access-Control-Allow-Headers' => 'Authorization,Content-Type',
    ];

    public function __construct(Config $config)
    {
        $this->cookieDomain = $config->get('cookie.domain', '');
    }

    public function handle($request, Closure $next, $header = [])
    {
        $header = !empty($header) ? array_merge($this->header, $header) : $this->header;
        if (!isset($header['Access-Control-Allow-Origin'])) {
            $origin = $request->header('origin');
            if ($origin && ($this->cookieDomain == '' || strpos($origin, $this->cookieDomain))) {
                $header['Access-Control-Allow-Origin'] = $origin;
            } else {
                $header['Access-Control-Allow-Origin'] = '*';
            }
        }

        return $next($request)->header($header);
    }
}
