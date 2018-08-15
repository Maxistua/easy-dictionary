<?php

require __DIR__ . '/../vendor/autoload.php';

$repository = new EasyDictionary\Repository();

$formatDictionary = $repository->get('format');

foreach ($formatDictionary->getIterator() as $key => $item) {
    echo $key . ' ' . $item . PHP_EOL;
}
