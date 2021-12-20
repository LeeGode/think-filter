<?php

namespace Leegode\ThinkFilter;

use think\facade\Config;

trait Filter
{
    /**
     * 模型搜索器入口
     * @param $query
     * @param  array  $input
     * @param $filter
     */
    public function scopeFilter($query, array $input=[],$filter=null)
    {
        if ($filter === null) {
            $filter = $this->getClass();
        }
        $filterInstance = new $filter($query, $input);

        return $filterInstance->apply();

    }

    private function getClass()
    {
        return method_exists($this, 'getFilterClass') ? $this->getFilterClass() : $this->getFilter();
    }


    private function getFilter($filter = null)
    {
        if ($filter === null) {
            $filter = Config::get('filter.namespace', 'app\\filters\\').class_basename($this).'Filter';
            if(!class_exists($filter)){
                $filter =  Config::get('filter.base_filter', 'Leegode\\thinkFilter\\BaseFilter');
            }
        }

        return $filter;
    }

}