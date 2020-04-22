<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Dashboard Widgets',
    'description' => 'A collection of widgets for the Dashboard of TYPO3',
    'category' => 'fe',
    'author' => 'Richard Haeser, Koen Wouters',
    'state' => 'beta',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0 - 10.4.99'
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
