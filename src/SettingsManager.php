<?php


namespace Quetzal\SettingsManager;


use Quetzal\SettingsManager\Loaders\Loaders;

class SettingsManager implements \ArrayAccess
{
    public static string $settings_dir_path = __DIR__."/../../Config/";
    public static string $default_settings_filename = "settings";
    public static string $default_settings_loader = Loaders::YAML;


    /**
     * @var array
     */
    private $settings = [];

    public function __construct($settings, int $depth = 0) {
        $set_type = gettype($settings);

        if ($set_type == "string") {
            $this->settings = $this->load($settings, $depth);
        } else if ($set_type == "array") {
            $this->settings = $this->loadArray($settings);
        } else {
            throw new \Exception("Unsupported type of settings");
        }
    }

    // Load everything with default values
    public static function init() {
        $settings = self::load(self::$settings_dir_path . self::$default_settings_filename);
        return $settings;
    }

    private function loadArray(array $filenames) {
        foreach ($filenames as $filename) {
            $config = $this->load($filename);
            foreach ($config as $key => $value) {
                $configs[$key] = $value;
            }
        }
        return $configs;
    }

    private function load(string $filename, int $depth = 0) {
        if (!file_exists($filename)) {
            throw new \Exception("File ".$filename." does not exists");
        }
        if (empty($filename)) {
            throw new \Exception("Empty filename");
        }

        $file = new \SplFileInfo($filename);

        if ($file->isDir()) {
            // load from dir
            $dir = new \DirectoryIterator($filename);
            foreach ($dir as $file){
                if (!$file->isDot()){
                    if ($file->isDir() and $depth != 0){
                        $configs[$file->getBasename()] = $this->load($file->getPathname(), $depth-1);
                    } else if (!$file->isDir()) {
//                        $this->load($file->getPathname());
//                        echo $file->getPathname().PHP_EOL;
                        $setname = $file->getFileInfo()->getBasename(".".$file->getExtension());
                        $parserClassPath = "Quetzal\\SettingsManager\\Loaders\\".ucfirst($file->getExtension())."Parser";
                        $parser = new $parserClassPath();
                        $configs[$setname] = $parser->parse(file_get_contents($file->getPathname()));
                    }
                }
            }
            return $configs;
        } else {
            $parserClassPath = "Quetzal\\SettingsManager\\Loaders\\".ucfirst($file->getExtension())."Parser";
            $parser = new $parserClassPath();
            $setname = $file->getFileInfo()->getBasename(".".$file->getExtension());
            $arr[$setname] = $parser->parse(file_get_contents($filename));
            return $arr;
            // use default loader
        }
    }

    private function getFilename(string $path): string {
        return pathinfo($path)['filename'];
    }

    private function resolveParser(\SplFileInfo $file): Loaders {
        $className = ucfirst($file->getExtension());
    }

    public function getSettings() :array {
        return $this->settings;
    }

    /**
     * Whether a offset exists
     * @link https://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->settings[$offset]);
    }

    /**
     * Offset to retrieve
     * @link https://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return isset($this->settings[$offset]) ? $this->settings[$offset] : null;
    }

    /**
     * Offset to set
     * @link https://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->settings[] = $value;
        } else {
            $this->settings[$offset] = $value;
        }
    }

    /**
     * Offset to unset
     * @link https://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        unset($this->settings[$offset]);
    }
}