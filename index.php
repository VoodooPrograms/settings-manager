<?php

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

var_dump(\Quetzal\SettingsManager\SettingsManager::init());
$smanager = new Quetzal\SettingsManager\SettingsManager(["configs/settings1.yaml", "configs/settings3.yaml"]);
//var_dump($smanager->getSettings());
