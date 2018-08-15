<?php

namespace EasyDictionary\DictionaryType;

class KeyValue implements \EasyDictionary\DictionaryInterface
{
    protected $name = '';

    /**
     * @param string $name
     * @return KeyValue
     */
    public function setName(string $name):self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function getItems()
    {

    }
}
