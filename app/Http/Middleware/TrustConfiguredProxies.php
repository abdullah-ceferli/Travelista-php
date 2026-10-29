<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies;
use Illuminate\Http\Request;

class TrustConfiguredProxies extends TrustProxies
{
    protected $headers = Request::HEADER_X_FORWARDED_PROTO;

    protected function proxies()
    {
        return config('travelista.security.use_forwarded_proto')
            ? config('travelista.security.trusted_proxies', [])
            : [];
    }
}
