<?php

namespace EasyDictionary;

use Psr\SimpleCache\CacheInterface;

interface DictionaryInterface extends \IteratorAggregate, \Countable
{
    /**
     * @return string
     */
    public function getName(): string;

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
     * @param callable|null $view
     * @return mixed
     */
    public function setDefaultSearchView(callable $view = null);

    /**
     * @param callable $callback
     * @return \Generator
     */
    public function withView(callable $callback = null);

    /**
     * @param CacheInterface $cache
     * @param int $ttl
     */
    public function setCache(CacheInterface $cache, int $ttl = 3600);

    /**
     * @param string $query
     * @param bool $strict
     * @param callable|null $searchView
     * @return iterable
     */
    public function search(string $query, bool $strict = false, callable $searchView = null):iterable;
}
