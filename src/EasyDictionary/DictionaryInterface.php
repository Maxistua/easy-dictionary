<?php

namespace EasyDictionary;

use Psr\SimpleCache\CacheInterface;

interface DictionaryInterface extends \IteratorAggregate, \Countable
{
    const DATA_VALUE_TYPE_FLAT  = 'flat';
    const DATA_VALUE_TYPE_ARRAY = 'array';

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param string $dataValueType
     */
    public function setDataValueType(string $dataValueType);

    /**
     * @param DataProviderInterface $provider
     */
    public function setDataProvider(DataProviderInterface $provider);

    /**
     * @return DataProviderInterface
     */
    public function getDataProvider(): DataProviderInterface;

    /**
     * @param callable $view
     */
    public function setDefaultView(callable $view = null);

    /**
     * @param callable $callable
     * @return \Generator
     */
    public function withView(callable $callable = null);

    /**
     * @param CacheInterface $cache
     * @param int $ttl
     */
    public function setCache(CacheInterface $cache, int $ttl = 3600);

    /**
     * @param array $searchFields
     * @return mixed
     */
    public function setSearchFields(array $searchFields);

    /**
     * @param string $pattern
     * @param bool $strict
     * @return iterable
     */
    public function search(string $pattern, bool $strict = false):iterable;
}
