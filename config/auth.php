<?php

return [

    'defaults' => [
        'guard'     => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        // Guard untuk Customer (Tamu)
        'web' => [
            'driver'   => 'session',
            'provider' => 'users',
        ],

        // Guard untuk Owner
        'owner' => [
            'driver'   => 'session',
            'provider' => 'owners',
        ],

        // Guard untuk Admin
        'admin' => [
            'driver' => 'session',
            'provider' => 'admin',
        ],
    ],

    'providers' => [
        // Provider Customer
        'users' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Customer::class,
        ],

        // Provider Owner
        'owners' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Owner::class,
        ],

        // Provider Admin
        'admin' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Admin::class,
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],
        'owners' => [
            'provider' => 'owners',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];
