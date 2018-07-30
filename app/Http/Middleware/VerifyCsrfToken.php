<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     * CSRF【Cross-site request forgery跨站请求伪造】验证时，应排除的URI地址块，形如：'test/login'、'test/*'
     * @var array
     */
    protected $except = [
        'wx/*',
    ];
}
