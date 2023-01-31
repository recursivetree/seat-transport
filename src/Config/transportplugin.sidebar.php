<?php
return [
    'transportplugin' => [
        'name'          => 'Transport Calculator',
        'icon'          => 'fas fa-truck',
        'route_segment' => 'transportplugin',
        'permission' => 'transportplugin.settings',
        'entries'       => [
            [
                'name'  => 'Settings',
                'icon'  => 'fas fa-cogs',
                'route' => 'transportplugin.settings',
                'permission' => 'transportplugin.settings',
            ],
        ]
    ]
];