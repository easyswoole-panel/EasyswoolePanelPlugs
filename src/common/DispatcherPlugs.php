<?php
/**
 * Created by PhpStorm.
 * User: Siam
 * Date: 2020/12/2 0002
 * Time: 21:55
 */

namespace Siam\Plugs\common;


use EasySwoole\Component\Singleton;
use EasySwoole\EasySwoole\Http\Dispatcher;
use Siam\Plugs\controller\BasePlugsController;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use EasySwoole\Http\Message\Status;

class DispatcherPlugs
{
    use Singleton;


    public function run(BasePlugsController $controllerObject, $actionName, Request $request, Response $response)
    {
        $response->withHeader('Content-type', 'application/json;charset=utf-8');
        try{
            $controllerObject->__hook($actionName,$request,$response);
        }catch (\Throwable $throwable){
            $this->hookThrowable($throwable,$request,$response);
        }
    }

    protected function hookThrowable(\Throwable $throwable,Request $request,Response $response)
    {
        $response->withStatus(Status::CODE_INTERNAL_SERVER_ERROR);
        $response->write(nl2br($throwable->getMessage()."\n".$throwable->getTraceAsString()));
    }
}