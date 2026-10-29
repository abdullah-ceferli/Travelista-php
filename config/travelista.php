<?php

return [
    'security' => [
        'allowed_hosts' => array_values(array_filter(array_map('trim', explode(',', (string) env('ALLOWED_HOSTS', '127.0.0.1,localhost'))))),
        'csrf_trusted_origins' => array_values(array_filter(array_map('trim', explode(',', (string) env('CSRF_TRUSTED_ORIGINS', ''))))),
        'force_https' => (bool) env('SECURE_SSL_REDIRECT', false),
        'hsts_seconds' => max(0, (int) env('SECURE_HSTS_SECONDS', 0)),
        'hsts_include_subdomains' => (bool) env('SECURE_HSTS_INCLUDE_SUBDOMAINS', false),
        'hsts_preload' => (bool) env('SECURE_HSTS_PRELOAD', false),
        'use_forwarded_proto' => (bool) env('USE_X_FORWARDED_PROTO', false),
        // Forwarded HTTPS headers are accepted only from explicitly listed proxies.
        'trusted_proxies' => array_values(array_filter(array_map('trim', explode(',', (string) env('TRUSTED_PROXIES', ''))))),
    ],
];
