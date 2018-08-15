<?php

namespace EasyDictionary;

abstract class AbstractDictionary implements DictionaryInterface
{
    protected $fieldMap = [];

    public function __construct(string $name = '')
    {
        $this->setName($name);
    }

    /**
     * Dictionary name
     *
     * @var string
     */
    protected $name = '';

    /**
     * @var DataProviderInterface
     */
    protected $dataProvider = null;

    /**
     * @param string $name
     * @return self
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param DataProviderInterface $provider
     * @return $this
     */
    public function setDataProvider(DataProviderInterface $provider)
    {
        $this->dataProvider = $provider;

        return $this;
    }

    /**
     * @return DataProviderInterface
     */
    public function getDataProvider(): DataProviderInterface
    {
        return $this->dataProvider;
    }

    /**
     * @return \Iterator
     */
    abstract public function getIterator();

    /**
     * @param callable $callback
     * @return \Generator
     */
    public function withView(callable $callback = null)
    {
        $iterator = $this->getIterator();

        if (is_callable($callback)) {
            foreach ($iterator as $key => $value) {
                yield from $callback($value, $key, $iterator);
            }
        } else {
            yield from $iterator;
        }
    }
}
