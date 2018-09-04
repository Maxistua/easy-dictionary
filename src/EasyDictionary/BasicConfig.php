<?php

declare(strict_types=1);

namespace EasyDictionary;

use EasyDictionary\Interfaces\ConfigInterface;

/**
 * Class BasicConfig
 * @package EasyDictionary\Dictionary
 */
class BasicConfig implements ConfigInterface
{
    protected $defaultDataProviderClass = '\EasyDictionary\DataProvider\Simple';
    protected $defaultDictionaryClass = '\EasyDictionary\Dictionary\Simple';
    protected $dictionaryConfig = [];
    protected $defaultView = null;
    protected $caches = [];

    /**
     * @return string
     */
    public function getDefaultDataProviderClass():string
    {
        return $this->defaultDataProviderClass;
    }

    /**
     * @return string
     */
    public function getDefaultDictionaryClass():string
    {
        return $this->defaultDictionaryClass;
    }

    /**
     * @return array
     */
    public function getDictionaryConfig():array
    {
        return $this->dictionaryConfig;
    }

    /**
     * @return callable
     */
    public function getDefaultView()
    {
        return $this->defaultView;
    }

    public function getCaches():array
    {
        return $this->caches;
    }
}
