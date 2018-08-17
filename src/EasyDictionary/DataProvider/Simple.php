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

    /**
     * Simple constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->setData($data['items'] ?? []);
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData($data = [])
    {
        $this->data = $data;
    }
}
