<?php

/*
 * This file is part of theleegode/think-filter.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 *
 *
 */

namespace Leegode\ThinkFilter\Filters;

abstract class Filter
{
    protected $query;
    protected $name;
    protected $value;
    protected $operator;

    /**
     * @param $query
     * @param $name
     * @param $value
     * @param  null  $operator
     */
    public function __construct($query, $name, $value,$operator=null)
    {
        $this->query = $query;
        $this->name = $name;
        $this->value = $value;
        $this->operator = $operator;

    }
    protected function getOperator()
    {
        return $this->operator;
    }

    abstract public function __invoke();
}
