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

    public function getItems()
    {
        $this->populateData();

        return $this->data;
    }

    public function getIterator()
    {
        $this->populateData();

        return new \ArrayIterator($this->data);
    }
}
