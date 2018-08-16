<?php

namespace EasyDictionary;

use EasyDictionary\Exception\InvalidConfigurationException;
use EasyDictionary\Exception\RuntimeException;

class Manager
{
    protected $dictionaries = [];

    public $defaultDictionaryType = 'EasyDictionary\Dictionary\Simple';
    public $defaultDataProvider = 'EasyDictionary\DataProvider\Simple';

    public $config = [];

    /**
     * @param DictionaryInterface $dictionary
     * @return Manager
     * @throws \Exception
     */
    public function add(DictionaryInterface $dictionary)
    {
        $name = $dictionary->getName();

        if (isset($this->dictionaries[$name])) {
            throw new \Exception(sprintf('The key "%s" already exists', $name));
        }

        $this->dictionaries[$name] = $dictionary;

        return $this;
    }

    /**
     * @param string $name
     * @return DictionaryInterface
     * @throws \Exception
     */
    public function get(string $name): DictionaryInterface
    {
        if (!isset($this->dictionaries[$name])) {
            $config = $this->config['dictionaries'][$name] ?? null;
            if (!$config) {
                throw new RuntimeException(sprintf('Dictionary with key "%s" not found', $name));
            }

            $this->add($this->create($name, $config));
        }

        return $this->dictionaries[$name];
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

        $dataProvider = new $dataProviderClass($config['data']);

        /** @var DictionaryInterface $dictionary */
        $dictionary = new $dictionaryClass($name);
        $dictionary->setDataProvider($dataProvider);
        $dictionary->setDefaultView($config['view'] ?? null);

        return $dictionary;
    }
}
