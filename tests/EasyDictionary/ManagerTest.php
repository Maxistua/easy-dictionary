<?php

declare(strict_types=1);

namespace EasyDictionary;

use EasyDictionary\DataProvider\Simple;
use EasyDictionary\Dictionary\Simple as SimpleDictionary;
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
        $dictionaryMock->expects(self::once())->method('setCache')->with($cacheMock, 1);

        $config = $this->createMock(ConfigInterface::class);
        $config->method('getCache')->with('testCache')->willReturn($cacheMock);
        $config->expects(self::once())->method('getDefaultDataProviderClass');
        $config->expects(self::once())->method('getDefaultDictionaryClass');
        $config->method('getDictionaryConfig')->willReturn([
            'test' => [
                'cache' => 'testCache',
                'cacheTTL' => 1,
                'data' => []
            ]
        ]);

        $manager = $this->createPartialMock(Manager::class, [
            'createDictionary',
            'createDataProvider',
            'getConfig',
        ]);

        $manager->method('createDictionary')->willReturn($dictionaryMock);
        $manager->method('createDataProvider')->willReturn($dataProviderMock);
        $manager->method('getConfig')->willReturn($config);

        self::assertEquals($manager->get('test'), $manager->get('test'));
    }

    /**
     * @covers \EasyDictionary\AbstractDictionary
     * @covers \EasyDictionary\DataProvider\Simple
     * @covers ::<public>
     * @covers ::create
     * @covers ::createDataProvider
     */
    public function testCreateRealDictionary()
    {
        $config = $this->createMock(ConfigInterface::class);
        $config->expects(self::once())->method('getDefaultDataProviderClass')
            ->willReturn(Simple::class);
        $config->expects(self::once())->method('getDefaultDictionaryClass')
            ->willReturn(SimpleDictionary::class);

        $config->method('getDictionaryConfig')->willReturn([
            'test' => [
                'data' => []
            ]
        ]);

        $manager = new Manager();
        $manager->setConfig($config);

        self::assertEquals($manager->get('test'), $manager->get('test'));
    }

    /**
     * @covers ::<public>
     * @covers ::create
     * @covers ::createDataProvider
     * @expectedException \EasyDictionary\Exception\InvalidConfigurationException
     * @expectedExceptionMessage Class "/bad/dataprovider/class" not found
     */
    public function testGetExceptionOfUndefinedDataProviderClass()
    {
        $config = $this->createMock(ConfigInterface::class);
        $config->method('getDictionaryConfig')->willReturn([
            'test' => [
                'data' => [
                    'class' => '/bad/dataprovider/class',
                ]
            ]
        ]);

        $manager = new Manager();
        $manager->setConfig($config);
        $manager->get('test');
    }

    /**
     * @covers ::<public>
     * @covers ::create
     * @covers ::createDataProvider
     * @expectedException \EasyDictionary\Exception\InvalidConfigurationException
     * @expectedExceptionMessage Class "stdClass" is not implement required interface
     */
    public function testGetExceptionOfBadDataProviderClass()
    {
        $config = $this->createMock(ConfigInterface::class);
        $config->method('getDictionaryConfig')->willReturn([
            'test' => [
                'data' => [
                    'class' => \stdClass::class,
                ]
            ]
        ]);

        $manager = new Manager();
        $manager->setConfig($config);
        $manager->get('test');
    }

    /**
     * @covers \EasyDictionary\DataProvider\Simple
     * @covers ::<public>
     * @covers ::create
     * @covers ::createDataProvider
     * @expectedException \EasyDictionary\Exception\InvalidConfigurationException
     * @expectedExceptionMessage Class "/bad/dictionary/class" not found
     */
    public function testGetExceptionOfUndefinedDictionaryClass()
    {
        $config = $this->createMock(ConfigInterface::class);
        $config->method('getDictionaryConfig')->willReturn([
            'test' => [
                'class' => '/bad/dictionary/class',
                'data' => [
                    'class' => Simple::class,
                ]
            ]
        ]);

        $manager = new Manager();
        $manager->setConfig($config);
        $manager->get('test');
    }

    /**
     * @covers \EasyDictionary\DataProvider\Simple
     * @covers ::<public>
     * @covers ::create
     * @covers ::createDataProvider
     * @expectedException \EasyDictionary\Exception\InvalidConfigurationException
     * @expectedExceptionMessage Class "stdClass" not found
     */
    public function testGetExceptionOfBadDictionaryClass()
    {
        $config = $this->createMock(ConfigInterface::class);
        $config->method('getDictionaryConfig')->willReturn([
            'test' => [
                'class' => \stdClass::class,
                'data' => [
                    'class' => Simple::class,
                ]
            ]
        ]);

        $manager = new Manager();
        $manager->setConfig($config);
        $manager->get('test');
    }
}
