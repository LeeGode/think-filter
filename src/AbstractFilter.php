<?php

namespace Leegode\ThinkFilter;



use think\db\Query;
use think\facade\Request;

class AbstractFilter
{

    protected $query;

    protected $input;
    public function __construct( Query $query, array  $input=[]){
        $this->query = $query;
        $this->input = $this->getInput($input);
    }

    public function apply()
    {
        if(method_exists($this,'init')){
            $this->init();
        }

    }
    //sort
    //like
    //厂家过滤

    public function like($value)
    {
        $fileds = implode(',', $value);

     $this->query->whereLike(aa, $condition);
    }
    /**
     * 获取过滤参数
     * @param  array  $input
     *
     * @return array|mixed
     */
    public function getInput(array $input=[])
    {
        if(!$input){
             $input = Request::param('filter');
        }

        return $input;
    }
}