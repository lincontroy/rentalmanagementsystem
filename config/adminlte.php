<?php

// config/adminlte.php

return [
    'title' => 'Property Management System',
    'title_prefix' => '',
    'title_postfix' => '',

    'use_ico_only' => false,
    'use_full_favicon' => false,

    'logo' => '<b>Property</b>Manager',
    'logo_img' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Property Manager',

    'usermenu_enabled' => true,
    'usermenu_header' => true,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => true,
    'layout_fixed_navbar' => true,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => null,

    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_overlay' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    'use_route_url' => false,
    'use_middleware' => true,
    'use_https' => env('ADMINLTE_HTTPS', false),

    'livewire' => false,
    'menu' => [
        [
            'text' => 'Dashboard',
            'url' => '/dashboard',
            'icon' => 'fas fa-tachometer-alt',
        ],
        [
            'text' => 'Properties',
            'icon' => 'fas fa-building',
            'submenu' => [
                [
                    'text' => 'All Properties',
                    'url' => '/properties',
                    'icon' => 'far fa-circle',
                ],
                [
                    'text' => 'Add Property',
                    'url' => '/properties/create',
                    'icon' => 'far fa-circle',
                ],
            ],
        ],
        [
            'text' => 'Tenants',
            'icon' => 'fas fa-users',
            'submenu' => [
                [
                    'text' => 'All Tenants',
                    'url' => '/tenants',
                    'icon' => 'far fa-circle',
                ],
                [
                    'text' => 'Add Tenant',
                    'url' => '/tenants/create',
                    'icon' => 'far fa-circle',
                ],
            ],
        ],
        [
            'text' => 'Units',
            'url' => '/units',
            'icon' => 'fas fa-home',
        ],
        [
            'text' => 'Payments',
            'icon' => 'fas fa-money-bill-wave',
            'submenu' => [
                [
                    'text' => 'All Payments',
                    'url' => '/payments',
                    'icon' => 'far fa-circle',
                ],
                [
                    'text' => 'Record Payment',
                    'url' => '/payments/create',
                    'icon' => 'far fa-circle',
                ],
            ],
        ],
        [
            'text' => 'Reports',
            'icon' => 'fas fa-chart-bar',
            'submenu' => [
                [
                    'text' => 'Rent Report',
                    'url' => '/reports/rent',
                    'icon' => 'far fa-circle',
                ],
                [
                    'text' => 'Arrears Report',
                    'url' => '/reports/arrears',
                    'icon' => 'far fa-circle',
                ],
                [
                    'text' => 'Occupancy Report',
                    'url' => '/reports/occupancy',
                    'icon' => 'far fa-circle',
                ],
            ],
        ],
        [
            'text' => 'Expenses',
            'url' => '/expenses',
            'icon' => 'fas fa-receipt',
        ],
        [
            'text' => 'Staff Management',
            'url' => '/staff',
            'icon' => 'fas fa-user-tie',
        ],
        [
            'text' => 'Notices',
            'url' => '/notices',
            'icon' => 'fas fa-bullhorn',
        ],
        [
            'text' => 'Settings',
            'url' => '/settings',
            'icon' => 'fas fa-cogs',
        ],
    ],

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    'plugins' => [
        'Datatables' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        'Select2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        'Sweetalert2' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8/dist/sweetalert2.min.css',
                ],
            ],
        ],
        'Pace' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],

    'dashboard_url' => 'dashboard',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,

    'enabled_laravel_mix' => false,
    'laravel_mix_css_path' => 'css/app.css',
    'laravel_mix_js_path' => 'js/app.js',
];