<?php

namespace EasyDictionary;

/**
 * Interface DataProviderFilterInterface
 * @package EasyDictionary
 */
interface DataProviderFilterInterface
{
    public function getFilter();

    public function __toString(): string;
}
