<?php

use App\Services\AFLojaCertificatesService;
use App\Services\GenericCertificatesService;

return [
    'style' => env('CERTIFICATES_STYLE', 'none'),

    'supported' => match (env('CERTIFICATES_STYLE')) {
        'afloja' => true,
        default => false,
    },

    'none' => [
        'class' => GenericCertificatesService::class,
    ],

    'afloja' => [
        'class' => AFLojaCertificatesService::class,
    ],

    'signer_name' => env('CERTIFICATES_SIG_NAME', ''),
    'signer_function' => env('CERTIFICATES_SIG_FUNCTION', ''),
    'signature_path' => storage_path(env('CERTIFICATES_SIG_PATH', '')),
];
