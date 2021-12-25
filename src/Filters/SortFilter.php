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

class SortFilter extends Filter
{
    protected static $operatorMap = [
        '+' => 'ASC',
        '-' => 'DESC',
    ];

    public function __invoke()
    {
        $params = explode(',', $this->value);
        foreach ($params as $param) {
            [$operator,$value] = $this->getOrderOperator($param);

            $this->query->order($value, self::$operatorMap[$operator]);
        }
    }

    /**
     * 解析排序操作符.
     */
    protected function getOrderOperator(?string $param): array
    {
        if (array_key_exists($param[0], self::$operatorMap)) {
            return [$param[0], ltrim($param, $param[0])];
        }

        return ['+', $param];
    }
}
