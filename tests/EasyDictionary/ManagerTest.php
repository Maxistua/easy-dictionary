<?php

declare(strict_types=1);

namespace EasyDictionary;

use EasyDictionary\Interfaces\ConfigInterface;
use EasyDictionary\Interfaces\DataProviderInterface;
use EasyDictionary\Interfaces\DictionaryInterface;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

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
        self::assertTrue(class_exists('\EasyDictionary\Manager'));
    }

    /**
     * @covers ::__construct
     * @covers ::setConfig
     * @covers ::getConfig
     */
    public function testSetConfigFromConstructor()
    {
        $config = $this->createMock(ConfigInterface::class);
        $manager = new Manager($config);

        self::assertEquals($config, $manager->getConfig());
    }

    /**
     * @covers ::__construct
     * @covers ::getConfig
     * @covers ::get
     *
     * @expectedException \EasyDictionary\Exception\RuntimeException
     * @expectedExceptionMessage Config not found
     */
    public function testGetDictionaryWithoutConfig()
    {
        $manager = new Manager();
        $manager->get('test');
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
        $config = $this->createMock(ConfigInterface::class);
        $manager = new Manager($config);
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
        $dictionary = $this->createMock(DictionaryInterface::class);
        $dictionary->method('getName')->willReturn($dictionaryName);

        $manager = new Manager();
        $manager->add($dictionary);

        self::assertEquals($dictionary, $manager->get($dictionaryName));
    }

    /**
     * @covers ::__construct
     * @covers ::add
     * @covers ::get
     *
     * @expectedException \EasyDictionary\Exception\RuntimeException
     * @expectedExceptionMessage The dictionary with key "test" already exists
     */
    public function testAddDuplicatedDictionary()
    {
        $dictionaryName = 'test';
        $dictionary = $this->createMock(DictionaryInterface::class);
        $dictionary->method('getName')->willReturn($dictionaryName);

        $manager = new Manager();
        $manager->add($dictionary);
        $manager->add($dictionary);

        self::assertEquals($dictionary, $manager->get($dictionaryName));
    }

    /**
     * @covers ::get
     * @covers ::add
     * @covers ::create
     */
    public function testCreateSimpleDictionary()
    {
        $cacheMock = $this->createMock(CacheInterface::class);
        $dataProviderMock = $this->createMock(DataProviderInterface::class);

        $dictionaryMock = $this->createMock(DictionaryInterface::class);
        $dictionaryMock->method('getName')->willReturn('test');
        $dictionaryMock->expects(self::once())->method('getName');
        $dictionaryMock->expects(self::once())->method('setDataProvider')->with($dataProviderMock);
        $dictionaryMock->expects(self::once())->method('setName');
        $dictionaryMock->expects(self::once())->method('setDefaultView')->with(null);
        $dictionaryMock->expects(self::once())->method('setSearchFields')->with([]);
        $dictionaryMock->expects(self::once())->method('setDataValueType')
            ->with(DictionaryInterface::DATA_VALUE_TYPE_FLAT);
        $dictionaryMock->expects(self::once())->method('setCache')->with($cacheMock, 1);

        $config = $this->createMock('\EasyDictionary\Interfaces\ConfigInterface');
        $config->method('getCache')->with('testCache')->willReturn($cacheMock);
        $config->method('getDictionaryConfig')->willReturn([
            'test' => [
                'cache' => 'testCache',
                'cacheTTL' => 1,
                'dataType' => DictionaryInterface::DATA_VALUE_TYPE_FLAT,
                'data' => []
            ]
        ]);

        $manager = $this->createPartialMock('\EasyDictionary\Manager', [
            'createDictionary',
            'createDataProvider',
            'getConfig',
        ]);

        $manager->method('createDictionary')->willReturn($dictionaryMock);
        $manager->method('createDataProvider')->willReturn($dataProviderMock);
        $manager->method('getConfig')->willReturn($config);

        self::assertEquals($manager->get('test'), $manager->get('test'));
    }
}
