<?php


namespace Quetzal\SettingsManager\Tests;


use PHPUnit\Framework\TestCase;
use Quetzal\SettingsManager\SettingsManager;

class SettingsManagerTest extends TestCase
{
    public function testHello(){
        $smanager = new SettingsManager();
        $this->assertTrue($smanager->hello("bartek") == "Hello bartek");
    }
}