<?php

require_once "vendor/autoload.php";

$smanager = new Quetzal\SettingsManager\SettingsManager("settings.xml");
var_dump($smanager);