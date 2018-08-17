<?php

namespace EasyDictionary;

/**
 * Interface DataProviderInterface
 * @package EasyDictionary
 */
interface DataProviderInterface
{
    /**
     * @return mixed
     */
    public function getData():iterable;
}
