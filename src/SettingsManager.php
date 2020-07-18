<?php


namespace Quetzal\SettingsManager;


use Quetzal\SettingsManager\Loaders\Loaders;
use Quetzal\SettingsManager\Loaders\XmlParser;
use Quetzal\SettingsManager\Loaders\YamlParser;

class SettingsManager
{
    public const SETTINGS_DIR_PATH = "../../Config";
    public const DEFAULT_SETTINGS_FILENAME = "settings";
    public const DEFAULT_SETTINGS_LOADER = Loaders::YAML;


    /**
     * @var array
     */
    private $settings = [];

    public function __construct($settings) {
        $set_type = gettype($settings);

        if ($set_type == "string") {
            $this->settings = $this->load($settings);
        } else if ($set_type == "array") {
            $this->settings = $this->loadArray($settings);
        } else {
            throw new \Exception("Unsupported type of settings");
        }
    }

    // Load everything with default values
    public static function init() {

    }

    private function loadArray(array $filenames) {
        foreach ($filenames as $filename) {
            $this->settings->append($this->load($filename));
        }
    }

    private function load(string $filename) {
        if (!file_exists($filename)) {
            throw new \Exception("File does not exists");
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
                    echo $file->getPath()."/".$file->getBasename();
                    $parser = new YamlParser();
                    $configs[] = $parser->parse(file_get_contents($file->getPath()."/".$file->getBasename()));
                }
            }
            return $configs;
        } else {
            $parser = new XmlParser();
            return $parser->parse(file_get_contents($filename));
            // use default loader
        }
    }

}