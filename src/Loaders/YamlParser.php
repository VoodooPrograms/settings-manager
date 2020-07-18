<?php


namespace Quetzal\SettingsManager\Loaders;


use Symfony\Component\Yaml\Yaml;

class YamlParser implements IParser
{

    public function parse(string $context): array
    {
        return Yaml::parse($context);
    }
}