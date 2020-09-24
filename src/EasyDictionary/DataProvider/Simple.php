<?php

declare(strict_types=1);

namespace EasyDictionary\DataProvider;

use EasyDictionary\DataProviderFilterInterface;
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
     * @param DataProviderFilterInterface|null $filter
     * @return iterable
     */
    public function getData(DataProviderFilterInterface $filter = null): iterable
    {
        return $this->data;
    }

    /**
     * @param iterable $data
     * @return $this
     */
    public function setData(iterable $data = [])
    {
        $this->data = $data;

        return $this;
    }
}
