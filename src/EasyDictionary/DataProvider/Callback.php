<?php

declare(strict_types=1);

namespace EasyDictionary\DataProvider;

use EasyDictionary\Interfaces\DataProviderInterface;

/**
 * Class Callback
 * @package EasyDictionary\DataProvider
 */
class Callback implements DataProviderInterface
{
    protected $callback = null;
    protected $arguments = [];

    /**
     * Callback constructor.
     * @param $params
     */
    public function __construct($params)
    {
        $this->setCallback($params['callable'] ?? null, $params['callableArgs'] ?? []);
    }

    /**
     * @param callable|null $callback
     * @param array $arguments
     * @return $this
     */
    public function setCallback(callable $callback = null, array $arguments = [])
    {
        $this->callback = $callback;
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * @return iterable
     */
    public function getData():iterable
    {
        return is_callable($this->callback)
            ? call_user_func_array($this->callback, $this->arguments)
            : [];
    }
}
