<?php

namespace EasyDictionary;

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
     * @var array
     */
    protected $data = [];

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
     * @return DataProviderInterface
     */
    public function getDataProvider(): DataProviderInterface
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
    public function getIterator()
    {
        if (is_null($this->view)) {
            foreach ($this->getData() as $key => $item) {
                yield $key => $item;
            }
        } else {
            yield from $this->withView($this->view);
        }
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        if (false === $this->dataLoaded) {
            $cache = $this->getCache();

            if (is_null($cache)) {
                $this->data = $this->loadData();
            } else {
                $key = __CLASS__ . '_' . $this->getName();

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
    public function getCache(): CacheInterface
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

    abstract protected function loadData();

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
     * @param callable $callback
     * @return \Generator
     */
    public function withView(callable $callback = null)
    {
        if (is_callable($callback)) {
            yield from call_user_func_array($callback, $this->getData());
        } else {
            yield from $this->getIterator();
        }
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->getData());
    }
}
