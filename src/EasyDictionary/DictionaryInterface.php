<?php

namespace EasyDictionary;

interface DictionaryInterface extends \IteratorAggregate
{
    const TYPE_SIMPLE = 'simple';

    /**
     * @return string
     */
    public function getName():string;

    /**
     * @return mixed
     */
    public function getItems();

    public function setDataProvider(DataProviderInterface $provider);
}
