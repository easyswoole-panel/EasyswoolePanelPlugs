<?php

namespace Siam\Plugs\common\utils;

use EasySwoole\Component\MultiEvent;
use EasySwoole\Component\Singleton;

class PlugsHook extends MultiEvent
{
    use Singleton;
}