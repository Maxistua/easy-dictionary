<?php

namespace EasyDictionary;

/**
 * Interface DataProviderInterface
 * @package EasyDictionary
 */
interface DataProviderInterface
{
    /**
     * @param DataProviderFilterInterface|null $filter
     * @return iterable
     */
    public function getData(DataProviderFilterInterface $filter = null): iterable;
}
