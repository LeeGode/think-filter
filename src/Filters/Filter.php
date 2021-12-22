<?php

namespace Leegode\ThinkFilter\Filters;

abstract class Filter
{
    protected $query;
    protected $name;
    protected $value;

    /**
     * @param $query
     * @param $name
     * @param $value
     */
    public function __construct($query, $name, $value)
    {
        $this->query = $query;
        $this->name = $name;
        $this->value = $value;
    }

    abstract public function __invoke();
}
