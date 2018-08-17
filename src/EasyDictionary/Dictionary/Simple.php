<?php

namespace EasyDictionary\Dictionary;

use EasyDictionary\AbstractDictionary;

/**
 * Class Simple
 * @package EasyDictionary\Dictionary
 */
class Simple extends AbstractDictionary
{
    /**
     * @inheritdoc
     */
    protected function loadData()
    {
        return $this->getDataProvider()->getData();
    }
}
