<?php

namespace EasyDictionary;

class Repository
{
    protected $dictionaries = [];

    protected $config = [
        'types' => [
            DictionaryInterface::TYPE_SIMPLE => '\EasyDictionary\DictionaryType\Simple'
        ],
        'providers' => [
            DataProviderInterface::TYPE_ARRAY => '\EasyDictionary\DataProviders\FromArray'
        ],
        'dictionaries' => [
            'format' => [
                'cache' => false,
                'type' => DictionaryInterface::TYPE_SIMPLE,
                'data' => [
                    'type' => DataProviderInterface::TYPE_ARRAY,
                    'items' => [
                        0 => 'a',
                        1 => 'b',
                        2 => 'c',
                        3 => 'd',
                        4 => 'e',
                        5 => 'f',
                    ]
                ],
                'transformers' => [
                    'getName' => '',
                ]
            ]
        ]
    ];

    /**
     * @param DictionaryInterface $dictionary
     * @return Repository
     * @throws \Exception
     */
    public function add(DictionaryInterface $dictionary):self
    {
        $name = $dictionary->getName();

        if (isset($this->dictionaries[$name])) {
            throw new \Exception(sprintf('The key "%s" already exists', $name));
        }

        $this->dictionaries[$name] = $dictionary;

        return $this;
    }

    /**
     * @param string $name
     * @return DictionaryInterface
     * @throws \Exception
     */
    public function get(string $name):DictionaryInterface
    {
        if (!isset($this->dictionaries[$name])) {
            $config = $this->config;

            if (!isset($config['dictionaries'][$name])) {
                throw new \Exception(sprintf('Dictionary with key "%s" not found', $name));
            }

            $dictionaryClass = $config['types'][$config['dictionaries'][$name]['type']] ?? null;
            if (is_null($dictionaryClass) || !class_exists($dictionaryClass)) {
                throw new \Exception('Invalid configuration. Class not found: ' . $dictionaryClass);
            }

            /**
             * @var $dictionary DictionaryInterface
             */
            $dictionary = new $dictionaryClass;
            $dictionary->setDataProvider((new DataProvider\FromArray($config['dictionaries'][$name]['data']['items'])));

            $this->dictionaries[$name] = $dictionary;
        }

        return $this->dictionaries[$name];
    }
}
