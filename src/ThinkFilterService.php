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

use think\facade\Config;
use think\Service;

class ThinkFilterService extends Service
{
    public function boot()
    {
        $databaseConfig = Config::get('database');
        Config::set(data_set($databaseConfig, 'connections.mysql.query', Query::class), 'database');
    }
}
