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

class Dispatch
{
    public $query;

    protected static $operatorMap = [
        '<' => DefaultFilter::class,
        '<=' => DefaultFilter::class,
        '>' => DefaultFilter::class,
        '>=' => DefaultFilter::class,
        '%' => LikeFilter::class,
    ];

    public function __construct($query)
    {
        $this->query = $query;
    }

    /**
     * 执行入口.
     *
     * @param $key
     * @param $val
     */
    public function handle($key, $val): void
    {
        $this->dispatchFilter($key, $val);
    }

    /**
     * 分发处理.
     *
     * @param $key
     * @param $val
     */
    protected function dispatchFilter($key, $val): void
    {
        //处理排序参数
        if ('sort' === $key) {
            (new SortFilter($this->query, $key, $val))();
        } else {
            [$operator,$value] = $this->resolveOperator($val);
            $filter = $this->getFilterClass($operator);
            (new $filter($this->query, $key, $value, $operator))();
        }
    }

    /**
     * 获取默认过滤处理类.
     */
    protected function getFilterClass(?string $operator): string
    {
        return ($operator && self::$operatorMap[$operator]) ? self::$operatorMap[$operator] : DefaultFilter::class;
    }

    /**
     * 解析过滤参数操作符.
     */
    protected function resolveOperator(string $val): array
    {
         $valArr = explode(',', $val);
         if(count($valArr) > 1){
             $operator = array_shift($valArr);
             $value = implode(',', $valArr);
         }else{
             $operator=null;
             $value=$val;
         }
        if (array_key_exists($operator, self::$operatorMap)) {
            return [$operator,$value];
        }

        return [null, $val];
    }
}
