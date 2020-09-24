<?php

namespace EasyDictionary\DataProvider;

use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \EasyDictionary\DataProvider\Simple
 */
class SimpleTest extends TestCase
{
    /**
     * @covers \EasyDictionary\DataProvider\Simple
     */
    public function testClassExistence()
    {
        self::assertTrue(class_exists('\EasyDictionary\DataProvider\Simple'));
    }

    /**
     * @covers ::__construct
     * @covers ::setData
     * @covers ::getData
     */
    public function testSetDataFromConfig()
    {
        $config = [
            'items' => [
                1, 2, 3
            ]
        ];

        $dataProvider = new Simple($config);

        self::assertEquals([1, 2, 3], $dataProvider->getData());
    }

    /**
     * @covers ::__construct
     * @covers ::setData
     * @covers ::getData
     */
    public function testSetEmptyDataFromBadConfig()
    {
        $config = [
            'items2' => [
                1, 2, 3
            ]
        ];

        $dataProvider = new Simple($config);

        self::assertEquals([], $dataProvider->getData());
    }
}
