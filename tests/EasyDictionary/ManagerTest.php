<?php

declare(strict_types=1);

namespace EasyDictionary;

use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \EasyDictionary\Manager
 */
class ManagerTest extends TestCase
{
    public function testClassExistence()
    {
        $this->assertTrue(class_exists('\EasyDictionary\Manager'));
    }

    public function testCheckPublicAttributes()
    {
        $this->assertClassHasAttribute('defaultDictionary', \EasyDictionary\Manager::class);
        $this->assertClassHasAttribute('defaultDataProvider', \EasyDictionary\Manager::class);
        $this->assertClassHasAttribute('config', \EasyDictionary\Manager::class);
    }

    public function testDefaultValues()
    {
        $manager = new \EasyDictionary\Manager();
        $this->assertEquals(\EasyDictionary\Dictionary\Simple::class, $manager->defaultDictionary);
        $this->assertEquals(\EasyDictionary\DataProvider\Simple::class, $manager->defaultDataProvider);
        $this->assertEquals([], $manager->config);
    }

    /**
     * @cover ::getDefaultProvider
     */
    public function testSettersAndGetters()
    {
        $manager = new \EasyDictionary\Manager();
        $manager->setDefaultDataProvider('defaultDataProvider');
        $this->assertEquals('defaultDataProvider', $manager->getDefaultDataProvider());

        $manager->setDefaultDictionary('defaultDictionary');
        $this->assertEquals('defaultDictionary', $manager->getDefaultDictionary());

        $manager->setConfig([1, 2, 3]);
        $this->assertEquals([1, 2, 3], $manager->getConfig());
    }
}
