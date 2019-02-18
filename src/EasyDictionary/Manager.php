<?php

declare(strict_types=1);

namespace EasyDictionary;

use EasyDictionary\Exception\InvalidConfigurationException;
use EasyDictionary\Exception\RuntimeException;
use EasyDictionary\Interfaces\ConfigInterface;
use EasyDictionary\Interfaces\DataProviderInterface;
use EasyDictionary\Interfaces\DictionaryInterface;

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
    protected $config = null;

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
     *
     * @return DictionaryInterface
     * @throws InvalidConfigurationException
     * @throws RuntimeException
     */
    public function get(string $name): ?DictionaryInterface
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

            $this->add($this->create($name, $dictionaryConfig, $config));
        }

        return $this->dictionaries[$name] ?? null;
    }

    /**
     * @return ConfigInterface|null
     */
    public function getConfig(): ?ConfigInterface
    {
        return $this->config;
    }

    /**
     * @param ConfigInterface $config
     *
     * @return $this
     */
    public function setConfig(ConfigInterface $config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @param DictionaryInterface $dictionary
     *
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
     * @param string $name
     * @param array $dictionaryConfig
     * @param ConfigInterface $config
     * @return DictionaryInterface
     * @throws InvalidConfigurationException
     */
    protected function create(string $name, array $dictionaryConfig, ConfigInterface $config): DictionaryInterface
    {
        $dataProvider = $this->createDataProvider(
            $dictionaryConfig['data']['class'] ?? $config->getDefaultDataProviderClass(),
            $dictionaryConfig['data'] ?? []
        );

        $dictionary = $this->createDictionary(
            $dictionaryConfig['class'] ?? $config->getDefaultDictionaryClass()
        );

        $dictionary->setName($name);
        $dictionary->setDataProvider($dataProvider);
        $dictionary->setDefaultView($dictionaryConfig['view'] ?? ($config->getDefaultView() ?? null));
        $dictionary->setSearchFields($dictionaryConfig['searchFields'] ?? []);

        if (isset($dictionaryConfig['cache'])) {
            $cache = $config->getCache($dictionaryConfig['cache']);
            if (!$cache) {
                throw new InvalidConfigurationException(sprintf('Cache "%s" not found', $dictionaryConfig['cache']));
            }

            $dictionary->setCache(
                $cache,
                $dictionaryConfig['cacheTTL'] ?? ConfigInterface::DEFAULT_CACHE_TTL
            );
        }

        return $dictionary;
    }

    /**
     * @param string $class
     * @param array $config
     *
     * @return DataProviderInterface
     * @throws InvalidConfigurationException
     */
    protected function createDataProvider(string $class, array $config): DataProviderInterface
    {
        if (!class_exists($class)) {
            throw new InvalidConfigurationException(sprintf('Class "%s" not found', $class));
        }

        $dataProvider = new $class($config);

        if (!($dataProvider instanceof DataProviderInterface)) {
            throw new InvalidConfigurationException(sprintf('Class "%s" is not implement required interface', $class));
        }

        return $dataProvider;
    }

    /**
     * @param string $class
     *
     * @return DictionaryInterface
     * @throws InvalidConfigurationException
     */
    public function createDictionary(string $class): DictionaryInterface
    {
        if (!class_exists($class)) {
            throw new InvalidConfigurationException(sprintf('Class "%s" not found', $class));
        }

        $dictionary = new $class;

        if (!($dictionary instanceof DictionaryInterface)) {
            throw new InvalidConfigurationException(sprintf('Class "%s" not found', $class));
        }

        return $dictionary;
    }
}
