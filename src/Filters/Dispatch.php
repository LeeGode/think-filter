<?php

namespace Leegode\ThinkFilter\Filters;

use think\facade\Config;

class Dispatch
{
    public $query;


    protected static $operatorMap=[
        '<',
        '<=',
        '>',
        '>=',
        '%'=>LikeFilter::class,
    ];



    public function __construct($query)
    {
        $this->query=$query;
    }

    public function handle($key,$val)
    {
        $this->resolveParam($key,$val);
    }

    protected function dispachFilter($key,$val){
        //处理排序参数
        if($key==='sort'){
            (new SortFilter($this->query,$key,$val))();
        }else{
            [$operator,$value] = $this->resolveOperator($val);
            $filter = $this->getFilterClass($operator);
            (new  $filter($this->query,$key,$value))();
        }
    }

    /**
     * 获取默认过滤处理类
     * @param  string  $operator
     *
     * @return string
     */
    protected function getFilterClass(string $operator): string
    {

        return ($operator && self::$operatorMap[$operator]) ? self::$operatorMap[$operator] :DefaultFilter::class;
    }

    /**
     * 解析过滤参数操作符
     * @param  string  $val
     *
     * @return array
     */
    protected function resolveOperator( string  $val): array
    {
        if(array_key_exists($val[0], self::$operatorMap)){

            return [ $val[0],ltrim($val,$val[0])];
        }

        return  [null,$val];
    }
}