<?php

declare(strict_types=1);

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
    protected function loadData(): iterable
    {
        return $this->getDataProvider()->getData($this->dataProviderFilter);
    }
}
