<?php

use Joalcapa\Fundamentary\Dir\Dir as Dir;
use Joalcapa\Fundamentary\Http\Kernel as KernelHttp;

class Api {

    /**
     * Evaluación de la ruta en la api.
     *
     * @param  string  $route
     * @param  string  $controller
     * @param  function  $closure
     */
    public static function Route($route, $controller, $closure = null) {
        $route = strtolower($route);
        $controller = ucwords(strtolower($controller));
        $request = KernelHttp::request();
        if($request->getUrlApi() == $route)
            self::explorer($route, $controller, $closure, $request);
    }

    /**
     * Exploración de los recursos existentes de la ruta.
     *
     * @param  string  $route
     * @param  function  $closure
     * @param  \Fundamentary\Http\Request  $request
     */
    public static function explorer($route, $controller, $closure = null, $request) {
        $data = $request->getInteractionsRequest();
        $controller = explode('@', $controller);
        $methodController = $controller[1];
        $controller = $controller[0];

        if(file_exists(Dir::controller($controller))) {

            if($closure)
                $data = call_user_func_array(
                    $closure,
                    array('request' => $data)
                );

            if($data === $request->getInteractionsRequest()) {
                $routeController = Dir::apiControllers($controller);
                $ctr = new $routeController();

                if(!property_exists($ctr, $methodController)) killer('404');

                $data = $ctr->$methodController($data);
                KernelHttp::makeResponse('200', $data);
            }

        } else
            killer('401');
    }
}