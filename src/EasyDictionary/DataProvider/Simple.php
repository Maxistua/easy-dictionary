<?php

declare(strict_types=1);

namespace EasyDictionary\DataProvider;

use EasyDictionary\Interfaces\DataProviderInterface;

/**
 * Class Simple
 *
 * @package EasyDictionary\DataProvider
 */
class Simple implements DataProviderInterface
{
    protected $data = [];

    /**
     * @param array $datProviderConfig
     */
    public function __construct($datProviderConfig = [])
    {
        $this->setData($datProviderConfig['items'] ?? []);
    }

    /**
     * @return iterable
     */
    public function getData():iterable
    {
        return $this->data;
    }

    /**
     * @param iterable $data
     *
     * @return $this
     */
    public function setData(iterable $data = [])
    {
        $this->data = $data;

        return $this;
    }
}
