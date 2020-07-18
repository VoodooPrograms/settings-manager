<?php


namespace Quetzal\SettingsManager\Loaders;


class XmlParser implements IParser
{

    public function parse(string $context): array
    {
        $xml = simplexml_load_string($context);
        return json_decode(json_encode($xml), true);
    }
}