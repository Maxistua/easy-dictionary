<?php

namespace EasyDictionary\DataProvider;

use EasyDictionary\DataProviderInterface;

/**
 * Class Simple
 * @package EasyDictionary\DataProvider
 */
class Simple implements DataProviderInterface
{
    protected $data = [];

    public function __construct($data = [])
    {
        $this->setData($data['items'] ?? []);
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
