<?php

namespace EasyDictionary;

interface DictionaryInterface extends \IteratorAggregate
{
    /**
     * @return string
     */
    public function getName():string;

    /**
     * @param DataProviderInterface $provider
     */
    public function setDataProvider(DataProviderInterface $provider);

    /**
     * @return DataProviderInterface
     */
    public function getDataProvider():DataProviderInterface;

    /**
     * @param callable $callback
     * @return \Generator
     */
    public function withView(callable $callback = null);
}
