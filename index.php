<?php

use SM\SettingsManager\SettingsManager;

require_once "vendor/autoload.php";

$array = [
    'driver' => 'mysql',
    'drivers' => [
        'sqlite' => [
            'database' => 'database.sqlite',
            'prefix' => ''
        ],
        'mysql' => [
            'host' => 'localhost',
            'database' => 'blog',
            'username' => 'blogger',
            'password' => 'hunter2',
            'charset' => 'utf8',
            'prefix' => ''
        ]
    ]
];

//var_dump(SettingsManager::init());
$smanager = new SM\SettingsManager\SettingsManager($array);
var_dump($smanager->getSettings());
