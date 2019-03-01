<?php

use Joalcapa\Fundamentary\Dir\Dir as Dir;
use Joalcapa\Fundamentary\Http\Kernel as KernelHttp;

class Middleware {
    
    public static function Meet($middleware, $closure) {
        $middleware = ucwords(strtolower($middleware));
        file_exists(Dir::middleware($middleware)) ? require_once(Dir::middleware($middleware)) : killer('401');

        $routeMiddleware = Dir::ApiMiddleware($middleware);
        $middleware = new $routeMiddleware();

        if(!method_exists($middleware, 'middle')) killer('401');

        $request = KernelHttp::request();
        $middleware->middle($request->getInteractionsRequest(), $closure);
    }
}
