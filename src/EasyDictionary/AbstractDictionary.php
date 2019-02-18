<?php

declare(strict_types=1);

namespace EasyDictionary;

use EasyDictionary\Interfaces\DataProviderInterface;
use EasyDictionary\Interfaces\DictionaryInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * Class AbstractDictionary
 * @package EasyDictionary
 */
abstract class AbstractDictionary implements DictionaryInterface
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var DataProviderInterface
     */
    protected $dataProvider = null;

    /**
     * @var callable
     */
    protected $view = null;

    /**
     * @var \Psr\SimpleCache\CacheInterface
     */
    protected $cache = null;

    /**
     * @var int
     */
    protected $cacheTTL = 0;

    /**
     * @var iterable
     */
    protected $data = [];

    /**
     * @var array
     */
    protected $searchFields = [];

    /**
     * @var bool
     */
    protected $dataLoaded = false;

    /**
     * AbstractDictionary constructor.
     * @param string $name
     */
    public function __construct(string $name = '')
    {
        $this->setName($name);
    }

    /**
     * @param callable|null $view
     * @return $this
     */
    public function setDefaultView(callable $view = null)
    {
        $this->view = $view;

        return $this;
    }

    /**
     * @return null|DataProviderInterface
     */
    public function getDataProvider(): ?DataProviderInterface
    {
        return $this->dataProvider;
    }

    /**
     * @param DataProviderInterface $provider
     * @return $this
     */
    public function setDataProvider(DataProviderInterface $provider)
    {
        $this->dataProvider = $provider;

        return $this;
    }

    /**
     * @return \Iterator
     */
    public function getIterator(): iterable
    {
        $view = $this->getDefaultView();

        if (is_null($view)) {
            foreach ($this->getData() as $key => $item) {
                yield $key => $item;
            }
        } else {
            yield from $this->withView($view);
        }
    }

    /**
     * @return callable|null
     */
    public function getDefaultView(): ?callable
    {
        return $this->view;
    }

    /**
     * @return mixed
     */
    public function getData(): iterable
    {
        if (false === $this->dataLoaded) {
            $cache = $this->getCache();

            if (is_null($cache)) {
                $this->data = $this->loadData();
            } else {
                $key = static::class . '_' . $this->getName();

                try {
                    if (!($this->data = $cache->get($key, []))) {
                        $this->data = $this->loadData();
                        $cache->set($key, $this->data, $this->cacheTTL);
                    }
                } catch (\Psr\SimpleCache\InvalidArgumentException $e) {
                    $this->data = [];
                }
            }

            $this->dataLoaded = true;
        }

        return $this->data;
    }

    /**
     * @return CacheInterface
     */
    public function getCache(): ?CacheInterface
    {
        return $this->cache;
    }

    /**
     * @param CacheInterface $cache
     * @param int $ttl
     * @return $this
     */
    public function setCache(CacheInterface $cache, int $ttl = 3600)
    {
        $this->cache = $cache;
        $this->cacheTTL = $ttl;

        return $this;
    }

    /**
     * @return iterable
     */
    abstract protected function loadData(): iterable;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param callable $callable
     * @return \Generator
     */
    public function withView(callable $callable = null): iterable
    {
        if (is_callable($callable)) {
            yield from call_user_func($callable, $this->getData());
        } else {
            yield from $this->getIterator();
        }
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->getData());
    }

    /**
     * @param string $pattern
     * @param bool $strict
     * @return iterable
     */
    public function search(string $pattern, bool $strict = false): iterable
    {
        $data = [];
        $searchData = [];
        $searchFields = array_filter($this->getSearchFields($strict));
        foreach ($this as $key => $value) {
            $data[$key] = $value;

            if (is_array($value)) {
                $searchData[$key] = (string)$key . ' ' .
                    join(' ', empty($searchFields) ? $value : array_intersect_key($value, $searchFields));
            } else {
                $searchData[$key] = (string)$key . ' ' . (string)$value;
            }
        }

        return array_intersect_key($data, preg_grep($pattern, $searchData));
    }

    /**
     * @param bool $strict
     * @return array
     */
    public function getSearchFields(bool $strict = false): array
    {
        return true === $strict ? array_filter($this->searchFields) : $this->searchFields;
    }

    /**
     * @param array $searchFields
     * @return $this
     */
    public function setSearchFields(array $searchFields)
    {
        $this->searchFields = $searchFields;

        return $this;
    }
}
