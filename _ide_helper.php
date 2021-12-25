<?php

namespace think\facade {

    use Leegode\ThinkFilter\ThinkFilter;

    class  Db {

        public static function filter(?array $input = [], $filter = null, $query = null)
        {
            /** @var ThinkFilter $instance */
            return $instance->filter($input = [], $filter = null, $query = null);
        }
    }


}

namespace  think {

    use Leegode\ThinkFilter\ThinkFilter;

    class Model{
        public static function filter(?array $input = [], $filter = null, $query = null)
        {

            /** @var ThinkFilter $instance */
            return $instance->filter($input = [], $filter = null, $query = null);
        }
    }
}