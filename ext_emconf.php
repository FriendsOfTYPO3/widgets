<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Dashboard Widgets',
    'description' => 'A collection of widgets for the Dashboard of TYPO3',
    'category' => 'fe',
    'author' => 'Richard Haeser, Koen Wouters',
    'state' => 'stable',
    'version' => '1.1.0-dev',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0 - 11.0.99',
            'dashboard' => '10.4.0 - 11.0.99'
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
    'autoload' => [
        'psr-4' => [
            'FriendsOfTYPO3\Widgets\\' => 'Classes'
        ]
    ],
];
