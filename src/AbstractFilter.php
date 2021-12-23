<?php

/*
 * This file is part of the leegode/think-filter.
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 *
 *
 */

namespace Leegode\ThinkFilter;

use Leegode\ThinkFilter\Exceptions\InvalidArgumentException;
use Leegode\ThinkFilter\Exceptions\SceneNotFoundException;
use Leegode\ThinkFilter\Filters\Dispatch;
use think\db\Query;
use think\facade\Request;
use think\helper\Str;

class AbstractFilter
{
    protected $query;

    protected $input;

    protected $ignoreFields = [];

    public function __construct(Query $query, $input = [])
    {
        $this->query = $query;
        $this->input = $input;
    }

    /**
     * 执行过滤器方法.
     *
     * @throws InvalidArgumentException
     * @throws SceneNotFoundException
     */
    public function apply(): Query
    {
        if (method_exists($this, 'init')) {
            $this->init();
        }
        if (is_string($this)) {
            $this->filterScene();
        } else {
            $this->filterInput();
        }

        return $this->query;
    }

    /**
     * 获取过滤器方法.
     *
     * @param $filed
     */
    public function getFilterMethod($filed): string
    {
        return Str::camel($filed);
    }

    /**
     * 通过过滤参数执行过滤器.
     */
    public function filterInput(): void
    {
        $this->input = $this->getInput($this->input);
        foreach ($this->input as $key => $val) {
            $method = $this->getFilterMethod($key);
            if (method_exists($this, $method)) {
                $this->{$method}($val);
            } else {
                $this->filterByDefault($key, $val);
            }
        }
    }

    /**
     * 通过场景值执行过滤器.
     *
     * @throws InvalidArgumentException
     * @throws SceneNotFoundException
     */
    public function filterScene(): void
    {
        $method = $this->getSceneMethod($this->input);
        if (!method_exists($this, $method)) {
            throw new SceneNotFoundException('未找到场景方法：'.$method);
        }

        $this->{$method}();
    }

    /**
     * 默认处理.
     *
     * @param $key
     * @param $val
     */
    public function filterByDefault($key, $val): void
    {
        (new Dispatch($this->query))->handle($key, $val);
    }

    /**
     * 获取过滤器参数.
     */
    public function getInput(array $input = []): array
    {
        if (!$input) {
            $input = Request::param('filter', []);
        }
        foreach ($input as $key => $val) {
            if ($this->isEmptyInput($val)) {
                continue;
            }
            if (in_array($key, $this->ignoreFields)) {
                unset($input,$key);
            }
        }

        return $input;
    }

    /**
     * 获取场景值方法.
     *
     * @param $scene
     *
     * @throws InvalidArgumentException
     */
    public function getSceneMethod($scene): string
    {
        if (!$scene) {
            throw new InvalidArgumentException('场景值参数不能为空');
        }

        return 'scene'.Str::studly($scene);
    }

    /**
     * 输入值是为空.
     *
     * @param $value
     */
    protected function isEmptyInput($value): bool
    {
        return '' !== $value && null !== $value && !(is_array($value) && empty($value));
    }

    public function __call($method, $args)
    {
        $res = call_user_func_array([$this->query, $method], $args);

        return $res instanceof Query ? $this : $res;
    }
}
