<?php

return [
    'name' => 'Powerlane',
    'manifest' => [
        'name' => env('APP_NAME', 'My PWA App'),
        'short_name' => 'Emplo',
        'start_url' => '/',
        'background_color' => '#f5f5f5',
        'theme_color' => '#000000',
        'display' => 'standalone',
        'orientation' => 'any',
        'status_bar' => 'black',
        'icons' => [
            '72x72' => [
                'path' => '/build/assets/images/icon-72x72.webp',
                'purpose' => 'any'
            ],
            '96x96' => [
                'path' => '/build/assets/images/icon-96x96.webp',
                'purpose' => 'any'
            ],
            '128x128' => [
                'path' => '/build/assets/images/icon-128x128.webp',
                'purpose' => 'any'
            ],
            '144x144' => [
                'path' => '/build/assets/images/icon-144x144.webp',
                'purpose' => 'any'
            ],
            '152x152' => [
                'path' => '/build/assets/images/icon-152x152.webp',
                'purpose' => 'any'
            ],
            '192x192' => [
                'path' => '/build/assets/images/icon-192x192.webp',
                'purpose' => 'any'
            ],
            '384x384' => [
                'path' => '/build/assets/images/icon-384x384.webp',
                'purpose' => 'any'
            ],
            '512x512' => [
                'path' => '/build/assets/images/icon-512x512.webp',
                'purpose' => 'any'
            ],
            '72x72' => [
                'path' => '/build/assets/images/icon-72x72.maskable.webp',
                'purpose' => 'maskable'
            ],
            '96x96' => [
                'path' => '/build/assets/images/icon-96x96.maskable.webp',
                'purpose' => 'maskable'
            ],
            '128x128' => [
                'path' => '/build/assets/images/icon-128x128.maskable.webp',
                'purpose' => 'maskable'
            ],
            '144x144' => [
                'path' => '/build/assets/images/icon-144x144.maskable.webp',
                'purpose' => 'maskable'
            ],
            '152x152' => [
                'path' => '/build/assets/images/icon-152x152.maskable.webp',
                'purpose' => 'maskable'
            ],
            '192x192' => [
                'path' => '/build/assets/images/icon-192x192.maskable.webp',
                'purpose' => 'maskable'
            ],
            '384x384' => [
                'path' => '/build/assets/images/icon-384x384.maskable.webp',
                'purpose' => 'any'
            ],
            '512x512' => [
                'path' => '/build/assets/images/icon-512x512.maskable.webp',
                'purpose' => 'maskable'
            ]
        ],

        'splash' => [
            '640x1136' => '/build/assets/images/splash-640x1136.webp',
            '750x1334' => '/build/assets/images/splash-750x1334.webp',
            '828x1792' => '/build/assets/images/splash-828x1792.webp',
            '1125x2436' => '/build/assets/images/splash-1125x2436.webp',
            '1170x2532' => '/build/assets/images/splash-1170x2532.webp',
            '1179x2556' => '/build/assets/images/splash-1179x2556.webp',
            '1242x2208' => '/build/assets/images/splash-1242x2208.webp',
            '1242x2688' => '/build/assets/images/splash-1242x2688.webp',
            '1284x2778' => '/build/assets/images/splash-1284x2778.webp',
            '1290x2796' => '/build/assets/images/splash-1290x2796.webp',
            '1136x640' => '/build/assets/images/splash-1136x640.webp',
            '1488x2266' => '/build/assets/images/splash-1488x2266.webp',
            '1334x750' => '/build/assets/images/splash-1334x750.webp',
            '1536x2048' => '/build/assets/images/splash-1536x2048.webp',
            '1620x2160' => '/build/assets/images/splash-1620x2160.webp',
            '1640x2360' => '/build/assets/images/splash-1640x2360.webp',
            '1668x2224' => '/build/assets/images/splash-1668x2224.webp',
            '1668x2388' => '/build/assets/images/splash-1668x2388.webp',
            '1792x828' => '/build/assets/images/splash-1792x828.webp',
            '2048x2732' => '/build/assets/images/splash-2048x2732.webp',
            '2048x1536' => '/build/assets/images/splash-2048x1536.webp',
            '2160x1620' => '/build/assets/images/splash-2160x1620.webp',
            '2208x1242' => '/build/assets/images/splash-2208x1242.webp',
            '2224x1668' => '/build/assets/images/splash-2224x1668.webp',
            '2266x1488' => '/build/assets/images/splash-2266x1488.webp',
            '2360x1640' => '/build/assets/images/splash-2360x1640.webp',
            '2388x1668' => '/build/assets/images/splash-2388x1668.webp',
            '2436x1125' => '/build/assets/images/splash-2436x1125.webp',
            '2556x1179' => '/build/assets/images/splash-2556x1179.webp',
            '2532x1170' => '/build/assets/images/splash-2532x1170.webp',
            '2688x1242' => '/build/assets/images/splash-2688x1242.webp',
            '2732x2048' => '/build/assets/images/splash-2732x2048.webp',
            '2778x1284' => '/build/assets/images/splash-2778x1284.webp',
            '2796x1290' => '/build/assets/images/splash-2796x1290.webp',
        ],
        'shortcuts' => [
            [
                'name' => 'Shortcut Link 1',
                'short_name' => 'Link 1',
                'description' => 'Shortcut Link 1 Description',
                'url' => '/employee/dashboard',
                'icons' => [
                    "src" => "/build/assets/images/icon-72x72.webp",
                    "sizes" => "72x72",
                    "purpose" => "any"
                ]
            ],
            [
                'name' => 'Shortcut Link 2',
                'short_name' => 'Link 2',
                'description' => 'Shortcut Link 2 Description',
                'url' => '/admin/dashboard',
                'icons' => [
                    "src" => "/build/assets/images/icon-72x72.webp",
                    "sizes" => "72x72",
                    "purpose" => "any"
                ]
            ]
        ],
        'custom' => [
            'screenshots' => [
                [
                    "src" => "/build/assets/images/screenshot-mobile-narrow.webp",
                    "sizes" => "375x812",
                    "type" => "image/webp",
                    "form_factor" => "narrow",
                    "label" => "Powerlane"
                ],
                [
                    "src" => "/build/assets/images/screenshot-desktop-wide.webp",
                    "sizes" => "936x462",
                    "type" => "image/webp",
                    "form_factor" => "wide",
                    "label" => "Powerlane"
                ],
                [
                    "src" => "/build/assets/images/screenshot-desktop-wide-full.webp",
                    "sizes" => "1490x1665",
                    "type" => "image/webp",
                    "form_factor" => "narrow",
                    "label" => "Powerlane"
                ],
            ],
        ]
    ]
];
