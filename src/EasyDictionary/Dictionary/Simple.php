<?php
namespace EasyDictionary\Dictionary;

use EasyDictionary\AbstractDictionary;

class Simple extends AbstractDictionary
{
    protected function loadData()
    {
        return $this->getDataProvider()->getData();
    }
}
