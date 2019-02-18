<?php

declare(strict_types=1);

namespace EasyDictionary;

use EasyDictionary\DataProvider\Simple as SimpleDataProvider;
use EasyDictionary\Dictionary\Simple as SimpleDictionary;
use EasyDictionary\Interfaces\ConfigInterface;
use Psr\SimpleCache\CacheInterface;

/**
 * Class BasicConfig
 *
 * @package EasyDictionary\Dictionary
 */
class BasicConfig implements ConfigInterface
{
    protected $defaultDataProviderClass = SimpleDataProvider::class;
    protected $defaultDictionaryClass = SimpleDictionary::class;
    protected $dictionaryConfig = [];
    protected $defaultView = null;
    protected $caches = [];

    /**
     * @return string
     */
    public function getDefaultDataProviderClass(): string
    {
        return $this->defaultDataProviderClass;
    }

    /**
     * @return string
     */
    public function getDefaultDictionaryClass(): string
    {
        return $this->defaultDictionaryClass;
    }

    /**
     * @return array
     */
    public function getDictionaryConfig(): array
    {
        return $this->dictionaryConfig;
    }

    /**
     * @param array $dictionaryConfig
     * @return $this
     */
    public function setDictionaryConfig(array $dictionaryConfig)
    {
        $this->dictionaryConfig = $dictionaryConfig;

        return $this;
    }

    /**
     * @return callable
     */
    public function getDefaultView(): ?callable
    {
        return $this->defaultView;
    }

    /**
     * @param callable $view
     * @return $this
     */
    public function setDefaultView(callable $view)
    {
        $this->defaultView = $view;

        return $this;
    }

    /**
     * @param string $name
     * @return null|CacheInterface
     */
    public function getCache(string $name): ?CacheInterface
    {
        return $this->caches[$name] ?? null;
    }

    /**
     * @param CacheInterface $cache
     * @param string $name
     * @return $this
     */
    public function addCache(CacheInterface $cache, string $name)
    {
        $this->caches[$name] = $cache;

        return $this;
    }
}
