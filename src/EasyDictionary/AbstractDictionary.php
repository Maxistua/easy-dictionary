<?php

namespace EasyDictionary;

abstract class AbstractDictionary implements DictionaryInterface
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var DataProviderInterface
     */
    protected $dataProvider = null;

    /**
     * @var callable
     */
    public $view = null;

    public function __construct(string $name = '')
    {
        $this->setName($name);
    }

    /**
     * @param callable|null $view
     * @return $this
     */
    public function setDefaultView(callable $view = null)
    {
        $this->view = $view;

        return $this;
    }

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

    abstract protected function loadData();

    public function getData()
    {

    }

    /**
     * @return \Iterator
     */
    public function getIterator()
    {
        if (is_null($this->view)) {
            foreach ($this->getData() as $key => $item) {
                yield $key => $item;
            }
        } else {
            yield from $this->withView($this->view);
        }
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->getData());
    }

    /**
     * @param callable $callback
     * @return \Generator
     */
    public function withView(callable $callback = null)
    {
        if (is_callable($callback)) {
            yield from call_user_func_array($callback, $this->getData());
        } else {
            yield from $this->getIterator();
        }
    }
}
