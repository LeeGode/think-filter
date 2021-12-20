<?php

namespace Leegode\thinkFilter;

use think\facade\Config;

trait Filter
{
    protected $filterInstance;
    /**
     * 模型搜索器入口
     * @param $query
     * @param  array  $input
     * @param $filter
     */
    public function scopeFilter($query, array $input=[],$filter=null)
    {
        if ($filter === null) {
            $filter = $this->getFilterClass();
        }
        $filterInstance = new $filter($query, $input);
        $this->filterInstance =$filterInstance;

        return $filterInstance->apply();

    }

    /**
     * 设置过滤器场景值
     * @param $scene
     */
    public function scene($scene)
    {

        $this->filterInstance->setScene($scene);
        return $this;
    }

    private function getFilterClass()
    {
        return method_exists($this, 'modelFilter') ? $this->modelFilter() : $this->provideFilter();
    }


    private function provideFilter($filter = null)
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