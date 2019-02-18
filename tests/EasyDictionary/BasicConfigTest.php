<?php

declare(strict_types=1);

namespace EasyDictionary;

use EasyDictionary\DataProvider\Simple as SimpleDataProvider;
use EasyDictionary\Dictionary\Simple as SimpleDictionary;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

/**
 * @coversDefaultClass \EasyDictionary\BasicConfig
 */
class BasicConfigTest extends TestCase
{
    /**
     * @covers \EasyDictionary\BasicConfig
     */
    public function testClassExistence()
    {
        self::assertTrue(class_exists('\EasyDictionary\BasicConfig'));
    }

    /**
     * @covers ::getDefaultDataProviderClass
     */
    public function testDefaultDataProviderClass()
    {
        $config = new BasicConfig();

        self::assertEquals(
            SimpleDataProvider::class,
            $config->getDefaultDataProviderClass()
        );
    }

    /**
     * @covers ::getDefaultDictionaryClass
     */
    public function testDefaultDictionaryClass()
    {
        $config = new BasicConfig();

        self::assertEquals(
            SimpleDictionary::class,
            $config->getDefaultDictionaryClass()
        );
    }

    /**
     * @covers ::setDictionaryConfig
     * @covers ::getDictionaryConfig
     */
    public function testSetGetConfig()
    {
        $dictionary = [
            'test' => []
        ];

        $config = new BasicConfig();
        $config->setDictionaryConfig($dictionary);

        self::assertEquals($dictionary, $config->getDictionaryConfig());
    }

    /**
     * @covers ::setDefaultView
     * @covers ::getDefaultView
     */
    public function testSetGetDefaultView()
    {
        $callable = function () {
        };

        $config = new BasicConfig();
        self::assertNull($config->getDefaultView());

        $config->setDefaultView($callable);
        self::assertEquals($callable, $config->getDefaultView());
    }

    /**
     * @covers ::addCache
     * @covers ::getCache
     */
    public function testAddGetCache()
    {
        /** @var CacheInterface $cache */
        $cache = $this->createMock(CacheInterface::class);

        $config = new BasicConfig();
        $config->addCache($cache, 'testCache');

        self::assertEquals($cache, $config->getCache('testCache'));
        self::assertNull($config->getCache('noCache'));
    }
}
