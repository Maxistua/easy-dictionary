<?php

namespace EasyDictionary;

interface DataProviderInterface
{
    const TYPE_ARRAY = 'array';

    public function getData();
}
