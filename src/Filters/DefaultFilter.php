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

class DefaultFilter extends Filter
{
    public function __invoke()
    {
        $this->query->where($this->name, $this->value);
    }
}
