<?php

declare(strict_types=1);

namespace EasyDictionary;

use EasyDictionary\Exception\InvalidConfigurationException;
use EasyDictionary\Exception\RuntimeException;
use Psr\SimpleCache\CacheInterface;

/**
 * Class Manager
 * @package EasyDictionary
 */
class Manager
{
    /**
     * @var string
     */
    public $defaultDictionary = 'EasyDictionary\Dictionary\Simple';

    /**
     * @return string
     */
    public function getDefaultDictionary(): string
    {
        return $this->defaultDictionary;
    }

    /**
     * @param string $defaultDictionary
     * @return $this
     */
    public function setDefaultDictionary(string $defaultDictionary)
    {
        $this->defaultDictionary = $defaultDictionary;

        return $this;
    }

    /**
     * @return string
     */
    public function getDefaultDataProvider(): string
    {
        return $this->defaultDataProvider;
    }

    /**
     * @param string $defaultDataProvider
     * @return $this
     */
    public function setDefaultDataProvider(string $defaultDataProvider)
    {
        $this->defaultDataProvider = $defaultDataProvider;

        return $this;
    }

    /**
     * @var string
     */
    public $defaultDataProvider = 'EasyDictionary\DataProvider\Simple';

    /**
     * [
     *      "caches" => [
     *          "cache_name_2" => callable
     *      ]
     *      ["defaultView" => function (...$data):\Generator {},]
     *      "dictionaries" => [
     *          "dictionary_name" => [
     *              ["class" => EasyDictionary\DictionaryInterface,]
     *              ["dataType" => "flat|array"]
     *              ["cache" => "cache_name"]
     *              ["cacheTTL" => 60,]
     *              "data" => [
     *                  ["class" => EasyDictionary\DataProviderInterface,]
     *                  [data provider arguments]
     *              ],
     *              ["view" => function (...$data):\Generator {},]
     *              ["searchFields" => [<string>, ...]]
     *          ],
     *
     *          ...
     *      ]
     * ]
     *
     * @var array
     */
    public $config = [];

    /**
     * @var array
     */
    protected $dictionaries = [];

    /**
     * @param string $name
     * @return DictionaryInterface
     * @throws InvalidConfigurationException
     * @throws RuntimeException
     */
    public function get(string $name): DictionaryInterface
    {
        if (!isset($this->dictionaries[$name])) {
            $config = $this->getConfig()['dictionaries'][$name] ?? null;

            if (!$config) {
                throw new RuntimeException(sprintf('Dictionary with key "%s" not found', $name));
            }

            $this->add($this->create($name, $config));
        }

        return $this->dictionaries[$name];
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @param array $config
     * @return $this
     */
    public function setConfig(array $config)
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
        $dictionaryClass = $dictionaryConfig['class'] ?? $this->getDefaultDictionary();
        if (!class_exists($dictionaryClass)) {
            throw new InvalidConfigurationException(sprintf('Class "%s" not found', $dictionaryClass));
        }

        $dataProviderClass = $dictionaryConfig['data']['class'] ?? $this->getDefaultDataProvider();
        if (!class_exists($dataProviderClass)) {
            throw new InvalidConfigurationException(sprintf('Class "%s" not found', $dataProviderClass));
        }

        /** @var DataProviderInterface $dataProvider */
        $dataProvider = new $dataProviderClass($dictionaryConfig['data']);

        /** @var DictionaryInterface $dictionary */
        $dictionary = new $dictionaryClass($name);
        $dictionary->setDataProvider($dataProvider);
        $dictionary->setDefaultView($dictionaryConfig['view'] ?? ($this->getConfig()['defaultView'] ?? null));
        $dictionary->setSearchFields($dictionaryConfig['searchFields'] ?? []);

        if (isset($dictionaryConfig['dataType'])) {
            $dictionary->setDataValueType($dictionaryConfig['dataType']);
        }

        if (isset($dictionaryConfig['cache'])) {
            $caches = $this->getConfig()['caches'] ?? [];

            if (!isset($caches[$dictionaryConfig['cache']])) {
                throw new InvalidConfigurationException(sprintf('Cache "%s" not found', $dictionaryConfig['cache']));
            }

            if (!is_callable($caches[$dictionaryConfig['cache']])) {
                throw new InvalidConfigurationException(sprintf('Cache "%s" not callable', $dictionaryConfig['cache']));
            }

            $cache = $caches[$dictionaryConfig['cache']]();

            if (!($cache instanceof CacheInterface)) {
                throw new InvalidConfigurationException(sprintf('Object with class "%s" does not support expected interface', get_class($cache)));
            }

            $dictionary->setCache($cache, $dictionaryConfig['cacheTTL'] ?? 60);
        }

        return $dictionary;
    }
}
