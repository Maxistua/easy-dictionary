<?php

namespace EasyDictionary;

use Psr\SimpleCache\CacheInterface;

abstract class AbstractDictionary implements DictionaryInterface
{
    /**
     * Dictionary name
     *
     * @var string
     */
    protected $name = '';

    /**
     * @var DataProviderInterface
     */
    protected $dataProvider = null;


    /**
     * @var bool
     */
    protected $enableCache = false;

    /**
     * @var bool
     */
    protected $storeInMemory = false;

    /**
     * @var CacheInterface null
     */
    protected $cache = null;

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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param CacheInterface $cache
     * @return AbstractDictionary
     */
    public function setCache(CacheInterface $cache):self
    {
        $this->cache = $cache;

        return $this;
    }

    /**
     * @return CacheInterface
     */
    public function getCache():CacheInterface
    {
        return $this->cache;
    }

    public function setDataProvider(DataProviderInterface $provider)
    {
        $this->dataProvider = $provider;

        return $this;
    }

    public function getDataProvider():DataProviderInterface
    {
        return $this->dataProvider;
    }
}
