<?php

namespace EasyDictionary\Dictionary;

use EasyDictionary\Interfaces\DataProviderInterface;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;

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
     * @covers ::setName
     * @covers ::setDataProvider
     * @covers ::getDataProvider
     * @covers ::getCache
     * @covers ::getData
     * @covers ::loadData
     */
    public function testGetDataWithDataProvider()
    {
        $data = [0, 1, 2, 3];
        $dataProviderMock = $this->createMock(DataProviderInterface::class);
        $dataProviderMock->method('getData')->willReturn($data);
        $dataProviderMock->expects(self::once())->method('getData');

        $dictionary = new Simple();
        $dictionary->setDataProvider($dataProviderMock);
        self::assertEquals($data, $dictionary->getData());
    }

    /**
     * @covers ::__construct
     * @covers ::setName
     * @covers ::getDataProvider
     * @covers ::getCache
     * @covers ::getData
     * @covers ::loadData
     */
    public function testGetDataWithoutDataProvider()
    {
        $dictionary = new Simple();
        self::assertEquals([], $dictionary->getData());
    }

    /**
     * @covers ::__construct
     * @covers ::setName
     * @covers ::getName
     * @covers ::setCache
     * @covers ::setDataProvider
     * @covers ::getDataProvider
     * @covers ::getCache
     * @covers ::getData
     * @covers ::loadData
     */
    public function testSaveDataToCache()
    {
        $data = [0, 1, 2, 3];
        $dataProviderMock = $this->createMock(DataProviderInterface::class);
        $dataProviderMock->method('getData')->willReturn($data);
        $dataProviderMock->expects(self::once())->method('getData');

        $cacheMock = $this->createMock(CacheInterface::class);
        $cacheMock->expects(self::once())->method('get');
        $cacheMock->expects(self::once())->method('set')->with(Simple::class . '_test', $data, 3600);

        $dictionary = new Simple('test');
        $dictionary->setDataProvider($dataProviderMock);
        $dictionary->setCache($cacheMock);

        self::assertEquals($data, $dictionary->getData());
    }

    /**
     * @covers ::__construct
     * @covers ::setName
     * @covers ::getName
     * @covers ::setCache
     * @covers ::setDataProvider
     * @covers ::getDataProvider
     * @covers ::getCache
     * @covers ::getData
     * @covers ::loadData
     */
    public function testSaveDataToCacheThrowError()
    {
        $data = [0, 1, 2, 3];
        $dataProviderMock = $this->createMock(DataProviderInterface::class);
        $dataProviderMock->method('getData')->willReturn($data);
        $dataProviderMock->expects(self::once())->method('getData');

        $cacheMock = $this->createMock(CacheInterface::class);
        $cacheMock->expects(self::once())->method('get');
        $cacheMock->expects(self::once())->method('set')
            ->with(Simple::class . '_test', $data, 3600)
            ->willThrowException(new InvalidArgException);

        $dictionary = new Simple('test');
        $dictionary->setDataProvider($dataProviderMock);
        $dictionary->setCache($cacheMock);

        self::assertEquals([], $dictionary->getData());
    }

    /**
     * @covers ::__construct
     * @covers ::setName
     * @covers ::count
     * @covers ::setDataProvider
     * @covers ::getDataProvider
     * @covers ::getCache
     * @covers ::getData
     * @covers ::loadData
     */
    public function testDataCount()
    {
        $data = [0, 1, 2, 3];
        $dataProviderMock = $this->createMock(DataProviderInterface::class);
        $dataProviderMock->method('getData')->willReturn($data);
        $dataProviderMock->expects(self::once())->method('getData');

        $dictionary = new Simple('test');
        $dictionary->setDataProvider($dataProviderMock);

        self::assertTrue($dictionary instanceof \Countable);
        self::assertEquals(4, count($dictionary));
    }

    /**
     * @covers ::__construct
     * @covers ::setName
     * @covers ::getCache
     * @covers ::getData
     * @covers ::getDataProvider
     * @covers ::getDefaultView
     * @covers ::getIterator
     * @covers ::setDataProvider
     * @covers ::loadData
     */
    public function testDataIteratorWithoutView()
    {
        $data = ['a' => 0, 'b' => 1, 'c' => 2];
        $dataProviderMock = $this->createMock(DataProviderInterface::class);
        $dataProviderMock->method('getData')->willReturn($data);
        $dataProviderMock->expects(self::once())->method('getData');

        $dictionary = new Simple('test');
        $dictionary->setDataProvider($dataProviderMock);

        $loadedData = [];
        foreach ($dictionary as $key => $value) {
            $loadedData[$key] = $value;
        }

        self::assertEquals($data, $loadedData);
    }

    /**
     * @covers ::__construct
     * @covers ::setName
     * @covers ::getCache
     * @covers ::getData
     * @covers ::getDataProvider
     * @covers ::setDataProvider
     * @covers ::getDefaultView
     * @covers ::setDefaultView
     * @covers ::getIterator
     * @covers ::loadData
     * @covers ::withView
     */
    public function testDataIteratorWithGoodView()
    {
        $data = ['a' => 0, 'b' => 1, 'c' => 2];
        $dataProviderMock = $this->createMock(DataProviderInterface::class);
        $dataProviderMock->method('getData')->willReturn($data);
        $dataProviderMock->expects(self::once())->method('getData');

        $dictionary = new Simple('test');
        $dictionary->setDataProvider($dataProviderMock);
        $dictionary->setDefaultView(function ($rows) {
            foreach ($rows as $key => $row) {
                yield $key . ' ' . $row;
            }
        });

        $loadedData = [];
        foreach ($dictionary as $value) {
            $loadedData[] = $value;
        }

        self::assertEquals(['a 0', 'b 1', 'c 2'], $loadedData);
    }

    /**
     * @covers ::__construct
     * @covers ::setName
     * @covers ::getCache
     * @covers ::getData
     * @covers ::getDataProvider
     * @covers ::setDataProvider
     * @covers ::getDefaultView
     * @covers ::getIterator
     * @covers ::loadData
     * @covers ::withView
     */
    public function testDataIteratorWithBadView()
    {
        $data = ['a' => 0, 'b' => 1, 'c' => 2];
        $dataProviderMock = $this->createMock(DataProviderInterface::class);
        $dataProviderMock->method('getData')->willReturn($data);
        $dataProviderMock->expects(self::once())->method('getData');

        $dictionary = new Simple('test');
        $dictionary->setDataProvider($dataProviderMock);

        $loadedData = [];
        foreach ($dictionary->withView(null) as $key => $value) {
            $loadedData[$key] = $value;
        }

        self::assertEquals($data, $loadedData);
    }

    /**
     * @covers ::__construct
     * @covers ::setName
     * @covers ::getCache
     * @covers ::getData
     * @covers ::getDataProvider
     * @covers ::setDataProvider
     * @covers ::getDefaultView
     * @covers ::getIterator
     * @covers ::loadData
     * @covers ::setSearchFields
     * @covers ::search
     * @covers ::getSearchFields
     */
    public function testDataIteratorSearchInFlatData()
    {
        $data = ['a' => 0, 'b' => 1, 'c' => 2];
        $dataProviderMock = $this->createMock(DataProviderInterface::class);
        $dataProviderMock->method('getData')->willReturn($data);
        $dataProviderMock->expects(self::once())->method('getData');

        $dictionary = new Simple('test');
        $dictionary->setDataProvider($dataProviderMock);

        $loadedData = [];
        foreach ($dictionary->search('/a|c/') as $key => $value) {
            $loadedData[$key] = $value;
        }

        self::assertEquals(['a' => 0, 'c' => 2], $loadedData);
    }

    /**
     * @covers ::__construct
     * @covers ::setName
     * @covers ::getCache
     * @covers ::getData
     * @covers ::getDataProvider
     * @covers ::setDataProvider
     * @covers ::getDefaultView
     * @covers ::getIterator
     * @covers ::loadData
     * @covers ::setSearchFields
     * @covers ::search
     * @covers ::setSearchFields
     * @covers ::getSearchFields
     */
    public function testDataIteratorSearchInArrayData()
    {
        $data = ['a' => ['code' => 33, 'code2' => 44], 'b' => ['code' => 44]];
        $dataProviderMock = $this->createMock(DataProviderInterface::class);
        $dataProviderMock->method('getData')->willReturn($data);
        $dataProviderMock->expects(self::once())->method('getData');

        $dictionary = new Simple('test');
        $dictionary->setDataProvider($dataProviderMock);
        $dictionary->setSearchFields([
            'code' => 1,
            'code2' => 0
        ]);

        self::assertEquals(['b' => ['code' => 44]], $dictionary->search('/44/'));
    }

    /**
     * @covers ::__construct
     * @covers ::setName
     * @covers ::getCache
     * @covers ::getData
     * @covers ::getDataProvider
     * @covers ::setDataProvider
     * @covers ::getDefaultView
     * @covers ::getIterator
     * @covers ::loadData
     * @covers ::setSearchFields
     * @covers ::search
     * @covers ::getSearchFields
     * @covers ::offsetExists
     * @covers ::offsetGet
     * @covers ::offsetUnset
     * @covers ::offsetSet
     */
    public function testDataIteratorGetOneKey()
    {
        $data = ['a' => 0, 'b' => 1, 'c' => 2];
        $dataProviderMock = $this->createMock(DataProviderInterface::class);
        $dataProviderMock->method('getData')->willReturn($data);
        $dataProviderMock->expects(self::once())->method('getData');

        $dictionary = new Simple('test');
        $dictionary->setDataProvider($dataProviderMock);

        self::assertEquals(false, isset($dictionary['empty']));
        self::assertEquals(true, empty($dictionary['empty']));
        self::assertEquals(0, $dictionary['a']);
        self::assertEquals(1, $dictionary['b']);
        self::assertEquals(2, $dictionary['c']);

        unset($dictionary['c']);
        self::assertEquals(2, $dictionary['c']);

        $dictionary['c'] = 33;
        self::assertEquals(2, $dictionary['c']);
    }

    /**
     * @covers ::toArray
     * @covers ::__construct
     * @covers ::getCache
     * @covers ::getData
     * @covers ::getDataProvider
     * @covers ::getDefaultView
     * @covers ::getIterator
     * @covers ::setDataProvider
     * @covers ::setName
     * @covers ::loadData
     */
    public function testDataIteratorToArray()
    {
        $data = ['a' => 0, 'b' => 1, 'c' => 2];
        $dataProviderMock = $this->createMock(DataProviderInterface::class);
        $dataProviderMock->method('getData')->willReturn($data);
        $dataProviderMock->expects(self::once())->method('getData');

        $dictionary = new Simple('test');
        $dictionary->setDataProvider($dataProviderMock);

        $array = $dictionary->toArray();
        self::assertEquals($data, $array);
    }

    /**
     * @covers ::toArray
     * @covers ::__construct
     * @covers ::getCache
     * @covers ::getData
     * @covers ::getDataProvider
     * @covers ::getDefaultView
     * @covers ::getIterator
     * @covers ::setDataProvider
     * @covers ::setName
     * @covers ::loadData
     */
    public function testDataKeys()
    {
        $data = ['a' => 0, 'b' => 1, 'c' => 2];
        $dataProviderMock = $this->createMock(DataProviderInterface::class);
        $dataProviderMock->method('getData')->willReturn($data);
        $dataProviderMock->expects(self::once())->method('getData');

        $dictionary = new Simple('test');
        $dictionary->setDataProvider($dataProviderMock);

        $array = array_keys($dictionary->toArray());
        self::assertEquals(['a', 'b', 'c'], $array);
    }

    /**
     * @covers ::__construct
     * @covers ::setName
     * @covers ::getCache
     * @covers ::getData
     * @covers ::getDataProvider
     * @covers ::setDataProvider
     * @covers ::getDefaultView
     * @covers ::getIterator
     * @covers ::loadData
     * @covers ::setSearchFields
     * @covers ::search
     * @covers ::getSearchFields
     * @covers ::offsetGet
     * @covers ::toArray
     */
    public function testDataValues()
    {
        $data = ['a' => 0, 'b' => 1, 'c' => 2];
        $dataProviderMock = $this->createMock(DataProviderInterface::class);
        $dataProviderMock->method('getData')->willReturn($data);
        $dataProviderMock->expects(self::once())->method('getData');

        $dictionary = new Simple('test');
        $dictionary->setDataProvider($dataProviderMock);

        $array = array_values($dictionary->toArray());
        self::assertEquals([0, 1, 2], $array);
    }
}

class InvalidArgException extends \Exception implements InvalidArgumentException
{

}
