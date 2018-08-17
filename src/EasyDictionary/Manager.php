<?php

namespace EasyDictionary;

use EasyDictionary\Exception\InvalidConfigurationException;
use EasyDictionary\Exception\RuntimeException;

/**
 * Class Manager
 * @package EasyDictionary
 */
class Manager
{
    /**
     * @var string
     */
    public $defaultDictionaryType = 'EasyDictionary\Dictionary\Simple';
    /**
     * @var string
     */
    public $defaultDataProvider = 'EasyDictionary\DataProvider\Simple';
    /**
     * [
     *      "dictionary_name" => [
     *          ["class" => EasyDictionary\DictionaryInterface,]
     *          ["cache" => Psr\SimpleCache\CacheInterface,]
     *          ["cacheTTL" => 60,]
     *          "data" => [
     *              ["class" => EasyDictionary\DataProviderInterface,]
     *              [data provider arguments]
     *          ]
     *      ],
     *      ...
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
     * @param $config
     * @return DictionaryInterface
     * @throws InvalidConfigurationException
     */
    protected function create(string $name, array $config): DictionaryInterface
    {
        $dictionaryClass = $config['class'] ?? $this->defaultDictionaryType;
        if (!class_exists($dictionaryClass)) {
            throw new InvalidConfigurationException(sprintf('Class "%s" not found', $dictionaryClass));
        }

        $dataProviderClass = $config['data']['class'] ?? $this->defaultDataProvider;
        if (!class_exists($dataProviderClass)) {
            throw new InvalidConfigurationException(sprintf('Class "%s" not found', $dataProviderClass));
        }

        /** @var DataProviderInterface $dataProvider */
        $dataProvider = new $dataProviderClass($config['data']);

        /** @var DictionaryInterface $dictionary */
        $dictionary = new $dictionaryClass($name);
        $dictionary->setDataProvider($dataProvider);
        $dictionary->setDefaultView($config['view'] ?? null);
        $dictionary->setCache($config['cache'] ?? null, $config['cacheTTL'] ?? 60);

        return $dictionary;
    }
}
