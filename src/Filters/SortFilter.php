<?php

namespace Leegode\ThinkFilter\Filters;

class SortFilter extends Filter
{
    protected static $operatorMap = [
        '+'=> 'ASC',
        '-'=> 'DESC',
    ];

    public function __invoke()
    {
        $params = explode(',', $this->value);
        foreach ($params as $param) {
            [$operator,$value] = $this->getOperator($param);

            $this->query->order($value, self::$operatorMap[$operator]);
        }
    }

    /**
     * 解析排序操作符.
     *
     * @param $param
     *
     * @return array
     */
    private function getOperator($param): array
    {
        if (array_key_exists($param[0], self::$operatorMap)) {
            return [$param[0], ltrim($param, $param[0])];
        }

        return  ['+', $param];
    }
}
