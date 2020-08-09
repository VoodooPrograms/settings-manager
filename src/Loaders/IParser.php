<?php


namespace Quetzal\SettingsManager\Loaders;


interface IParser
{
    public function parse(string $context): array;
}