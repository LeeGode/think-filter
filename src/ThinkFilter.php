<?php

/*
 * This file is part of the leegode/think-filter.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 *
 *
 */

namespace Leegode\ThinkFilter;

use think\facade\Config;

trait ThinkFilter
{
    protected $filterInstance;

    /**
     * query搜索器入口.
     *
     * @param null $filter
     *
     * @return mixed
     */
    public function filter(?array $input = [], array $allowFields = [], $filter = null)
    {
        if (null === $filter) {
            $filter = $this->getClass();
        }

        $this->filterInstance = new $filter($this, $input, $allowFields);

        return $this->filterInstance->apply();
    }

    /**
     * 获取过滤器类.
     *
     * @return mixed|string|null
     */
    private function getClass()
    {
        return method_exists($this, 'getFilterClass') ? $this->getFilterClass() : $this->getFilter();
    }

    /**
     * 获取过滤器.
     *
     * @return mixed|string|null
     */
    private function getFilter($filter = null)
    {
        if (null === $filter) {
            $filter = Config::get('filter.namespace', 'app\\filters\\').class_basename($this).'Filter';
            if (!class_exists($filter)) {
                $filter = Config::get('filter.base_filter', 'Leegode\\ThinkFilter\\BaseFilter');
            }
        }

        return $filter;
    }
}
