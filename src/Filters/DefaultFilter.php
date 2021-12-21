<?php

namespace Leegode\ThinkFilter\Filters;

use think\facade\Config;

class DefaultFilter
{
    public $query;


    protected static $operatorMap=[
        '<',
        '<=',
        '>',
        '>=',
        '%',
        '+',
        '-',
    ];
    protected static $SORT_OPERATOR;
    protected static $LIKE_OPERATOR;
    protected static $ASC_OPERATOR;
    protected static $DESC_OPERATOR;



    public function __construct($query)
    {
        $this->query=$query;
        self::$SORT_OPERATOR=Config::get('think-filter.sort_operator','sort');
        self::$LIKE_OPERATOR=Config::get('think-filter.like_operator','%');
        self::$ASC_OPERATOR=Config::get('think-filter.asc_operator','+');
        self::$DESC_OPERATOR=Config::get('think-filter.desc_operator','-');
        array_replace()
    }

    public function handle($key,$val)
    {
        $this->resolveParam($key,$val);
    }

    protected function resolveParam($key,$val)
    {
        if(){

        }
        if (!is_array($param)) {
            return $param;
        }

        if (is_string($param)) {
            return explode(',', $param);
        }

        return [$param];
    }
}