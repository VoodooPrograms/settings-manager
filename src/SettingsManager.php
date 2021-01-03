<?php


namespace SM\SettingsManager;


use SM\SettingsManager\Loaders\Loaders;

class SettingsManager implements \ArrayAccess
{
    public static string $settings_dir_path = __DIR__."Config/";
    public static string $default_settings_filename = "settings.yaml";
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
    public static function init(string $initFile = null) {
        if (isset($initFile)) {
            return self::loadFromInitFile($initFile);
        }
        $settings = new self(self::$settings_dir_path . self::$default_settings_filename);
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
            throw new \Exception("File ".$filename." does not exists in: " . self::$settings_dir_path);
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
                        $parserClassPath = "SM\\SettingsManager\\Loaders\\".ucfirst($file->getExtension())."Parser";
                        $parser = new $parserClassPath();
                        $configs[$setname] = $parser->parse(file_get_contents($file->getPathname()));
                    }
                }
            }
            return $configs;
        } else {
            $parserClassPath = "SM\\SettingsManager\\Loaders\\".ucfirst($file->getExtension())."Parser";
            $parser = new $parserClassPath();
            $setname = $file->getFileInfo()->getBasename(".".$file->getExtension());
            $arr[$setname] = $parser->parse(file_get_contents($filename));
            return $arr;
            // use default loader
        }
    }

    public static function loadFromInitFile(string $file) {
        $sm = new SettingsManager($file);
        $paths = [];
        foreach ($sm['settings'] as $path) {
            $paths += $path;
        }
        $sm = new SettingsManager($paths);
        return $sm;
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
     * @return string
     */
    public static function getSettingsDirPath(): string
    {
        return self::$settings_dir_path;
    }

    /**
     * @param string $settings_dir_path
     */
    public static function setSettingsDirPath(string $settings_dir_path): void
    {
        self::$settings_dir_path = $settings_dir_path;
    }

    /**
     * @return string
     */
    public static function getDefaultSettingsFilename(): string
    {
        return self::$default_settings_filename;
    }

    /**
     * @param string $default_settings_filename
     */
    public static function setDefaultSettingsFilename(string $default_settings_filename): void
    {
        self::$default_settings_filename = $default_settings_filename;
    }

    /**
     * @return string
     */
    public static function getDefaultSettingsLoader(): string
    {
        return self::$default_settings_loader;
    }

    /**
     * @param string $default_settings_loader
     */
    public static function setDefaultSettingsLoader(string $default_settings_loader): void
    {
        self::$default_settings_loader = $default_settings_loader;
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