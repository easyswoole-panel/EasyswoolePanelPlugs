<?php

use Siam\Plugs\controller\Plugs;

return [
    '/api/plugs/get_list' => [['GET','POST'], [new Plugs, 'get_list']],
    '/api/plugs/install'  => [['GET','POST'], [new Plugs, 'install']],
    '/api/plugs/update'   => [['GET','POST'], [new Plugs, 'update']],
    '/api/plugs/remove'   => [['GET','POST'], [new Plugs, 'remove']],
];