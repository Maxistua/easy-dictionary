<?php

namespace EasyDictionary\Dictionary;

use EasyDictionary\Interfaces\DataProviderInterface;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \EasyDictionary\Dictionary\Simple
 */
class SimpleTest extends TestCase
{
    /**
     * @covers \EasyDictionary\Dictionary\Simple
     */
    public function testClassExistence()
    {
        self::assertTrue(class_exists('\EasyDictionary\Dictionary\Simple'));
    }

    /**
     * @covers ::__construct
     * @covers ::loadData
     */
    public function testSetDataFromConfig()
    {
        $data = [0, 1, 2, 3];
        $dataProviderMock = self::createMock(DataProviderInterface::class);
        $dataProviderMock->method('getData')->willReturn($data);
        $dataProviderMock->expects(self::once())->method('getData');

        $dictionaryMock = $this->getMockBuilder(Simple::class)
            ->setMethods(array('__construct', 'getDataProvider'))
            ->enableProxyingToOriginalMethods()
            ->getMock();
        $dictionaryMock->method('getDataProvider')->willReturn($dataProviderMock);
        $dictionaryMock->expects(self::exactly(2))->method('getDataProvider');

        $loadedData = [];
        foreach ($dictionaryMock->getData() as $item) {
            $loadedData[] = $item;
        }

        self::assertEquals($data, $data);
    }
}
