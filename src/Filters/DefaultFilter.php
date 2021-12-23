<?php

/*
 * This file is part of the leegode/think-filter.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 *
 *
 */

namespace Leegode\ThinkFilter\Filters;

class DefaultFilter extends Filter
{
    public function __invoke()
    {
        $operator = $this->getOperator() ?: '=';
        if (is_array($this->value)) {
            $operator = $this->getOperator() ?: 'in';
        }

        $this->query->where($this->name, $operator, $this->value);
    }
}
