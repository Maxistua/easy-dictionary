<?php

declare(strict_types=1);

namespace EasyDictionary;

use EasyDictionary\Exception\InvalidConfigurationException;
use EasyDictionary\Exception\RuntimeException;
use Psr\SimpleCache\CacheInterface;
use EasyDictionary\Interfaces\DictionaryInterface;
use EasyDictionary\Interfaces\ConfigInterface;

/**
 * Class Manager
 *
 * @package EasyDictionary
 */
class Manager
{
    /**
     * @var ConfigInterface
     */
    protected $config;

    /**
     * @var array
     */
    protected $dictionaries = [];

    /**
     * @param ConfigInterface $config
     */
    public function __construct(ConfigInterface $config = null)
    {
        if (!is_null($config)) {
            $this->setConfig($config);
        }
    }

    /**
     * Returns Dictionary object
     *
     * @param string $name
     * @return DictionaryInterface
     * @throws InvalidConfigurationException
     * @throws RuntimeException
     */
    public function get(string $name): DictionaryInterface
    {
        if (!isset($this->dictionaries[$name])) {
            $config = $this->getConfig();
            if (!$config) {
                throw new RuntimeException(sprintf('Config not found', $name));
            }

            $dictionaryConfig = $config->getDictionaryConfig()[$name] ?? null;
            if (!$dictionaryConfig) {
                throw new RuntimeException(sprintf('Dictionary with key "%s" not found', $name));
            }

            $this->add($this->create($name, $dictionaryConfig));
        }

        return $this->dictionaries[$name];
    }

    /**
     * @return ConfigInterface
     */
    public function getConfig(): ConfigInterface
    {
        return $this->config;
    }

    /**
     * @param ConfigInterface $config
     * @return $this
     */
    public function setConfig(ConfigInterface $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @param DictionaryInterface $dictionary
     * @return $this
     * @throws RuntimeException
     */
    public function add(DictionaryInterface $dictionary)
    {
        $name = $dictionary->getName();

        if (isset($this->dictionaries[$name])) {
            throw new RuntimeException(sprintf('The dictionary with key "%s" already exists', $name));
        }

        $this->dictionaries[$name] = $dictionary;

        return $this;
    }

    /**
     * @param $name
     * @param $dictionaryConfig
     * @return DictionaryInterface
     * @throws InvalidConfigurationException
     */
    protected function create(string $name, array $dictionaryConfig): DictionaryInterface
    {
        $dictionaryClass = $dictionaryConfig['class'] ?? $this->getConfig()->getDefaultDictionaryClass();
        if (!class_exists($dictionaryClass)) {
            throw new InvalidConfigurationException(sprintf('Class "%s" not found', $dictionaryClass));
        }

        $dataProviderClass = $dictionaryConfig['data']['class'] ?? $this->getConfig()->getDefaultDataProviderClass();
        if (!class_exists($dataProviderClass)) {
            throw new InvalidConfigurationException(sprintf('Class "%s" not found', $dataProviderClass));
        }

        /** @var DataProviderInterface $dataProvider */
        $dataProvider = new $dataProviderClass($dictionaryConfig['data']);

        /** @var DictionaryInterface $dictionary */
        $dictionary = new $dictionaryClass($name);
        $dictionary->setDataProvider($dataProvider);
        $dictionary->setDefaultView($dictionaryConfig['view'] ?? ($this->getConfig()->getDefaultView() ?? null));
        $dictionary->setSearchFields($dictionaryConfig['searchFields'] ?? []);

        if (isset($dictionaryConfig['dataType'])) {
            $dictionary->setDataValueType($dictionaryConfig['dataType']);
        }

        if (isset($dictionaryConfig['cache'])) {
            $caches = $this->getConfig()->getCaches() ?? [];

            if (!isset($caches[$dictionaryConfig['cache']])) {
                throw new InvalidConfigurationException(sprintf('Cache "%s" not found', $dictionaryConfig['cache']));
            }

            if (!is_callable($caches[$dictionaryConfig['cache']])) {
                throw new InvalidConfigurationException(sprintf('Cache "%s" not callable', $dictionaryConfig['cache']));
            }

            $cache = $caches[$dictionaryConfig['cache']]();

            if (!($cache instanceof CacheInterface)) {
                throw new InvalidConfigurationException(
                    sprintf('Object with class "%s" does not support expected interface', get_class($cache))
                );
            }

            $dictionary->setCache($cache, $dictionaryConfig['cacheTTL'] ?? 60);
        }

        return $dictionary;
    }
}
