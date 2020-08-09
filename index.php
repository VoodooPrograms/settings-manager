<?php

require_once "vendor/autoload.php";

$smanager = new Quetzal\SettingsManager\SettingsManager("configs/settingsJSON.json");
var_dump($smanager->getSettings());
