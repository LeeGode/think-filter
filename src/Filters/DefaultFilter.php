<?php

namespace Leegode\ThinkFilter\Filters;

class DefaultFilter extends Filter
{

    public function __invoke()
    {
        $this->query->where($this->name,$this->value);
    }
}