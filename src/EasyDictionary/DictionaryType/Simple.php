<?php
namespace EasyDictionary\DictionaryType;

use EasyDictionary\AbstractDictionary;

class Simple extends AbstractDictionary
{
    protected $data = [];

    protected function populateData()
    {
        if (empty($this->data)) {
            $this->data = $this->getDataProvider()->getData();
        }
    }

    public function getIterator()
    {
        $this->populateData();

        foreach ($this->data as $key => $item) {
            yield $key => $item;
        }
    }
}
