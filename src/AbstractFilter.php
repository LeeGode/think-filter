<?php

namespace Leegode\ThinkFilter;



use think\db\Query;
use think\facade\Request;
use think\helper\Arr;
use think\helper\Str;

class AbstractFilter
{

    protected $query;

    protected $input;

    protected $ignoreFields=[];

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

    /**
     * 获取过滤器方法
     * @param $filed
     *
     * @return string
     */
    public function getFilterMethod($filed): string
    {
       return Str::camel($filed);
    }

    /**
     * 通过过滤参数执行过滤器
     */
    public function filterInput()
    {
        foreach ($this->input as $key=>$val){
            $method =$this->getFilterMethod($key);
            if(method_exists($this,$method)){
                $this->{$method}($val);
            }else{
                $this->filterByDefault();
            }
        }

    }



    public function filterByDefault()
    {


    }


    //sort
    //like
    //厂家过滤
    //%

    public function like($value)
    {
        $fileds = implode(',', $value);

     $this->query->whereLike(aa, $condition);
    }


    /**
     * 获取过滤器参数
     *
     * @param  array  $input
     *
     * @return array
     */
    public function getInput(array $input=[]): array
    {
        if(!$input){
             $input = Request::param('filter');
        }

      return  Arr::pluck($input, array_diff(array_keys($input),  $this->ignoreFields));

    }

}