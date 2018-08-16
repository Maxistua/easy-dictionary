<?php

namespace EasyDictionary\DataProvider;

use EasyDictionary\DataProviderInterface;

class Callback implements DataProviderInterface
{
    public $useCache = false;
    public $useInMemoryCache = true;

    protected $callable = null;
    protected $callableArgs = [];

    public $cache;
    public static $memoryCache = [];

    public function __construct($params)
    {
        $this->callable = $params['callable'] ?? null;
        $this->callableArgs = $params['callableArgs'] ?? null;
    }

    protected function loadData()
    {
        return is_callable($this->callable)
            ? call_user_func_array($this->callable, $this->callableArgs)
            : [];
    }

    public function getData()
    {
        if (false === $this->useInMemoryCache) {
            return $this->loadData();
        }

        if (empty(self::$memoryCache)) {
            self::$memoryCache = $this->loadData();
        }

        return self::$memoryCache;
    }
}
