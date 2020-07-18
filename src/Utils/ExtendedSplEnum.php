<?php


namespace Quetzal\SettingsManager\Utils;


class ExtendedSplEnum extends Enumerate
{
    public static function hasKey($key) {
        $foundKey = false;

        try {
            $enumClassName = get_called_class();
            new $enumClassName($key);
            $foundKey = true;
        } finally {
            return $foundKey;
        }
    }
}