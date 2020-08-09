<?php


namespace Quetzal\SettingsManager\Loaders;


class JsonParser implements IParser
{

    public function parse(string $context): array
    {
        return json_decode($context, true);
    }
}