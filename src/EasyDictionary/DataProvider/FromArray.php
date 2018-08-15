<?php

namespace EasyDictionary\DataProvider;

use EasyDictionary\DataProviderInterface;

class FromArray implements DataProviderInterface
{
    protected $data = [];

    public function __construct($data = [])
    {
        $this->setData($data);
    }

    public function setData($data = [])
    {
        $this->data = $data;
    }

    public function getData()
    {
        return $this->data;
    }
}
