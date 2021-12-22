<?php

namespace Leegode\ThinkFilter;

use think\facade\Config;

trait ThinkFilter
{
    protected $filterInstance;
    /**
     * 模型搜索器入口
     * @param $query
     * @param  array|string  $input
     * @param $filter
     */
    public function scopeFilter($query,  $input=[],$filter=null)
    {
        if ($filter === null) {
            $filter = $this->getClass();
        }
        $filterInstance = new $filter($query, $input);
        $this->filterInstance =$filterInstance;

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
                $filter =  Config::get('filter.base_filter', 'Leegode\\ThinkFilter\\BaseFilter');
            }
        }

        return $filter;
    }

}