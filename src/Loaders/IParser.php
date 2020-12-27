<?php


namespace SM\SettingsManager\Loaders;


interface IParser
{
    public function parse(string $context): array;
}