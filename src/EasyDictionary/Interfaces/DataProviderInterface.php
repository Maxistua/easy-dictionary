<?php

namespace EasyDictionary\Interfaces;

/**
 * Interface DataProviderInterface
 * 
 * @package EasyDictionary
 */
interface DataProviderInterface
{
    /**
     * @return mixed
     */
    public function getData():iterable;
}
