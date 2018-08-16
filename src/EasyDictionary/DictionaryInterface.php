<?php

namespace EasyDictionary;

interface DictionaryInterface extends \IteratorAggregate, \Countable
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
     * @param callable $view
     */
    public function setDefaultView(callable $view = null);

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
