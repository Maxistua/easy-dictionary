<?php

namespace EasyDictionary\DataProvider;

use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \EasyDictionary\DataProvider\Callback
 */
class CallbackTest extends TestCase
{
    /**
     * @covers \EasyDictionary\DataProvider\Callback
     */
    public function testClassExistence()
    {
        self::assertTrue(class_exists('\EasyDictionary\DataProvider\Callback'));
    }

    /**
     * @covers ::__construct
     * @covers ::setCallback
     * @covers ::getData
     */
    public function testSetDataFromConfig()
    {
        $callable = function ($b) {
            return [111, $b];
        };

        $config = [
            'callable' => $callable,
            'callableArgs' => [777],
        ];

        $dataProvider = new Callback($config);
        self::assertEquals([111, 777], $dataProvider->getData());
    }

    /**
     * @covers ::__construct
     * @covers ::setCallback
     * @covers ::getData
     */
    public function testSetBadCallbackFromBadConfig()
    {
        $dataProvider = new Callback();
        self::assertEquals([], $dataProvider->getData());
    }
}
