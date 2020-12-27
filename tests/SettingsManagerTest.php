<?php


namespace Quetzal\SettingsManager\Tests;

use PHPUnit\Framework\TestCase;
use Quetzal\SettingsManager\SettingsManager;

class SettingsManagerTest extends TestCase
{
    public function testLoadFromDirPlaneDepth(){
        $smanager = new SettingsManager("tests/dataset/dir");
        $this->assertNotNull($smanager);
        $this->assertNotNull($smanager["settings1"]);
        $this->assertNotNull($smanager["settings2"]);
        $this->assertNotNull($smanager["settings3"]);
        $this->assertNull($smanager["settings4"]);
        $this->assertIsArray($smanager->getSettings());
        $this->assertIsArray($smanager["settings1"]);
        $this->assertNotNull($smanager["settings1"]["driver"]);
        $this->assertEquals($smanager["settings1"]["driver"], "mysql");
    }

    public function testLoadFromDirNestedDepth(){
        $smanager = new SettingsManager("tests/dataset/dir", 2);
        $this->assertNotNull($smanager);
        $this->assertNotNull($smanager["settings1"]);
        $this->assertNotNull($smanager["settings2"]);
        $this->assertNotNull($smanager["settings3"]);
        $this->assertNotNull($smanager["dir1"]["settings4"]);
        $this->assertNotNull($smanager["dir1"]["settings5"]);
        $this->assertNotNull($smanager["dir1"]["dir11"]["settings6"]);
        $this->assertIsArray($smanager->getSettings());
        $this->assertIsArray($smanager["dir1"]["dir11"]["settings6"]);
        $this->assertNotNull($smanager["dir1"]["dir11"]["settings6"]["driver"]);
        $this->assertEquals($smanager["dir1"]["dir11"]["settings6"]["driver"], "mysql");
    }

    public function testLoadSingleXMLFile(){
        $smanager = new SettingsManager("tests/dataset/xml/settings.xml");
        $this->assertNotNull($smanager);
        $this->assertNotNull($smanager["settings"]);
        $this->assertIsArray($smanager->getSettings());
        $this->assertIsArray($smanager["settings"]);
        $this->assertNotNull($smanager["settings"]["driver"]);
        $this->assertEquals($smanager["settings"]["driver"], "mysql");

    }

    public function testLoadSingleJSONFile(){
        $smanager = new SettingsManager("tests/dataset/json/settings.json");
        $this->assertNotNull($smanager);
        $this->assertNotNull($smanager["settings"]);
        $this->assertIsArray($smanager->getSettings());
        $this->assertIsArray($smanager["settings"]);
        $this->assertNotNull($smanager["settings"]["driver"]);
        $this->assertEquals($smanager["settings"]["driver"], "mysql");
    }

    public function testLoadSinglePHPFile(){
        $smanager = new SettingsManager("tests/dataset/php/settings.php");
        $this->assertNotNull($smanager);
        $this->assertNotNull($smanager["settings"]);
        $this->assertIsArray($smanager->getSettings());
        $this->assertIsArray($smanager["settings"]);
        $this->assertNotNull($smanager["settings"]["driver"]);
        $this->assertEquals($smanager["settings"]["driver"], "mysql");
    }

    public function testExceptionOnSinglePHPFile(){
        $this->expectException(\Exception::class);
        $smanager = new SettingsManager("tests/dataset/php/settings_not_array.php");
    }

    public function testLoadFromArrayOfFiles(){
        $smanager = new SettingsManager(["tests/dataset/dir/settings1.yaml", "tests/dataset/dir/settings3.yaml"]);
        $this->assertNotNull($smanager);
        $this->assertNotNull($smanager["settings1"]);
        $this->assertNotNull($smanager["settings3"]);
        $this->assertIsArray($smanager->getSettings());
        $this->assertIsArray($smanager["settings1"]);
        $this->assertNotNull($smanager["settings1"]["driver"]);
        $this->assertEquals($smanager["settings1"]["driver"], "mysql");
    }

    public function testInitWithDefaultValues(){
        SettingsManager::$settings_dir_path = 'tests/dataset/yaml/';
        $settings = SettingsManager::init();
        $this->assertNotNull($settings);
    }
}