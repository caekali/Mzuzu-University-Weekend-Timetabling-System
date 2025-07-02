<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * Trust all proxies (like Railway or Heroku).
     */
    protected $proxies = '*';

    /**
     * Use forwarded headers to detect HTTPS and host correctly.
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL;
}
