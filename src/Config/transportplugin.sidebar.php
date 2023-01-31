<?php
return [
    'transportplugin' => [
        'name'          => 'Transport Calculator',
        'icon'          => 'fas fa-truck',
        'route_segment' => 'transportplugin',
        'permission' => 'transportplugin.calculate',
        'entries'       => [
            [
                'name'  => 'Calculate',
                'icon'  => 'fas fa-calculator',
                'route' => 'transportplugin.calculate',
                'permission' => 'transportplugin.calculate',
            ],
            [
                'name'  => 'Settings',
                'icon'  => 'fas fa-cogs',
                'route' => 'transportplugin.settings',
                'permission' => 'transportplugin.settings',
            ],
        ]
    ]
];