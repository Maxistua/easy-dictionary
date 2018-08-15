<?php

require __DIR__ . '/../vendor/autoload.php';

$repository = new EasyDictionary\Repository();

$formatDictionary = $repository->get('country');

foreach ($formatDictionary->withView(function($current, $key) {
    yield $current['code'] => $key;
}) as $key => $item) {
    echo $key . ' ' . print_r($item, true) . PHP_EOL;
}
