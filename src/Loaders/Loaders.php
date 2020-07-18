<?php


namespace Quetzal\SettingsManager\Loaders;



use Quetzal\SettingsManager\Utils\Enumerate;

class Loaders extends Enumerate
{
    const __default = self::YAML;

    const YAML = "yaml";
    const PHP = "php";
    const XML = "xml";
    const JSON = "xml";
}