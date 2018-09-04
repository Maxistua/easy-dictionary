<?php

declare(strict_types=1);

namespace EasyDictionary;

use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \EasyDictionary\Manager
 */
class ManagerTest extends TestCase
{
    /**
     * @covers \EasyDictionary\Manager
     */
    public function testClassExistence()
    {
        $this->assertTrue(class_exists('\EasyDictionary\Manager'));
    }

    /**
     * @covers ::__construct
     * @covers ::setConfig
     * @covers ::getConfig
     */
    public function testSetConfigFromConstructor()
    {
        $config = $this->createMock('\EasyDictionary\Interfaces\ConfigInterface');
        $manager = new \EasyDictionary\Manager($config);
        $this->assertEquals($config, $manager->getConfig());
    }

    /**
     * @covers ::__construct
     * @covers ::setConfig
     * @covers ::getConfig
     * @covers ::get
     *
     * @expectedException \EasyDictionary\Exception\RuntimeException
     * @expectedExceptionMessage  Dictionary with key "test" not found
     */
    public function testGetANonExistentDictionary()
    {
        $config = $this->createMock('\EasyDictionary\Interfaces\ConfigInterface');
        $manager = new \EasyDictionary\Manager($config);
        $manager->get('test');
    }

    /**
     * @covers ::__construct
     * @covers ::add
     * @covers ::get
     */
    public function testAddNewDictionary()
    {
        $dictionaryName = 'test';
        $dictionary = $this->createMock('\EasyDictionary\Interfaces\DictionaryInterface');
        $dictionary->method('getName')->willReturn($dictionaryName);

        $manager = new \EasyDictionary\Manager();
        $manager->add($dictionary);

        $this->assertEquals($dictionary, $manager->get($dictionaryName));
    }
}
