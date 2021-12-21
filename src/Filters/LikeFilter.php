<?php

namespace Leegode\ThinkFilter\Filters;

class LikeFilter extends Filter
{

    public function __invoke()
    {
        $this->query->where($this->name,'like',"%{$this->value}%");
    }
}