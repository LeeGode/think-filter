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

class Dispatch
{
    public $query;

    protected static $operatorMap = [
        '<'=>DefaultFilter::class,
        '<='=>DefaultFilter::class,
        '>'=>DefaultFilter::class,
        '>='=>DefaultFilter::class,
        '%' => LikeFilter::class,
    ];

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function handle($key, $val)
    {
        $this->dispachFilter($key, $val);
    }

    protected function dispachFilter($key, $val)
    {
        //处理排序参数
        if ('sort' === $key) {
            (new SortFilter($this->query, $key, $val))();
        } else {
            [$operator,$value] = $this->resolveOperator($val);
            $filter = $this->getFilterClass($operator);
            (new $filter($this->query, $key, $value,$operator))();
        }
    }

    /**
     * 获取默认过滤处理类.
     *
     * @param string $operator
     */
    protected function getFilterClass($operator): string
    {
        return ($operator && self::$operatorMap[$operator]) ? self::$operatorMap[$operator] : DefaultFilter::class;
    }

    /**
     * 解析过滤参数操作符.
     */
    protected function resolveOperator(string $val): array
    {
        if (array_key_exists($val[0], self::$operatorMap)) {
            return [$val[0], ltrim($val, $val[0])];
        }

        return [null, $val];
    }
}
