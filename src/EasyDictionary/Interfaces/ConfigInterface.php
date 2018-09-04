<?php

namespace EasyDictionary\Interfaces;

/**
 * Interface ConfigInterface
 *
 * @package EasyDictionary
 */
interface ConfigInterface
{
    /**
     * @return string
     */
    public function getDefaultDataProviderClass():string;

    /**
     * @return string
     */
    public function getDefaultDictionaryClass():string;

    /**
     * @return array
     */
    public function getDictionaryConfig():array;

    /**
     * @return callable
     */
    public function getDefaultView();

    public function getCaches():array;
}
