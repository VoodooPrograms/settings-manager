<?php


namespace SM\SettingsManager\Loaders;


class PhpParser implements IParser
{

    public function parse(string $context): array
    {
        $contents = eval(ltrim( $context, '<?php'));

        if (gettype($contents) != 'array') {
            throw new \Exception($context . ' does not return a valid array');
        }

        return $contents;
    }
}