<?php

namespace Leegode\ThinkFilter;



use Leegode\ThinkFilter\Exceptions\InvalidArgumentException;
use Leegode\ThinkFilter\Exceptions\SceneNotFoundException;
use Leegode\ThinkFilter\Filters\dispatch;
use think\db\Query;
use think\facade\Request;
use think\helper\Arr;
use think\helper\Str;

class AbstractFilter
{

    protected $query;

    protected $input;

    protected $ignoreFields=[];

    public function __construct( Query $query,  $input=[]){
        $this->query = $query;
        $this->input = $input;
    }

    /**
     * 执行过滤器方法
     * @return Query
     * @throws InvalidArgumentException
     * @throws SceneNotFoundException
     */
    public function apply(): Query
    {
        if(method_exists($this,'init')){
            $this->init();
        }
        if(is_string($this)){
            $this->filterScene();
        }else{
            $this->filterInput();
        }

        return $this->query;

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
            if($this->isEmptyInput($val)){
                continue;
            }
            $method =$this->getFilterMethod($key);
            if(method_exists($this,$method)){
                $this->{$method}($val);
            }else{
                $this->filterByDefault($key,$val);
            }
        }

    }

    /**
     * 通过场景值执行过滤器
     * @throws InvalidArgumentException
     * @throws SceneNotFoundException
     */
    public function filterScene()
    {
        $method =$this->getSceneMethod($this->input);
        if(!method_exists($this,$method)){
            throw new SceneNotFoundException('未找到场景方法：'.$method);
        }

        $this->{$method}();
    }



    public function filterByDefault($key,$val)
    {
        (new dispatch($this->query))->handle($key,$val);
    }


    //sort
    //like
    //场景过滤
    //%


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

    /**
     * 获取场景值方法
     * @param $scene
     * @return string
     * @throws InvalidArgumentException
     */
    public function getSceneMethod($scene): string
    {
        if(!$scene){
            throw new InvalidArgumentException('场景值参数不能为空');
        }

        return 'scene'.Str::studly($scene);
    }

    /**
     * 输入值是为空
     * @param $value
     * @return bool
     */
    protected function isEmptyInput($value): bool
    {

        return $value !== '' && $value !== null && ! (is_array($value) && empty($value));
    }

}