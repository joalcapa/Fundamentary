<?php

namespace Joalcapa\Fundamentary\App;

use Joalcapa\Fundamentary\Dir\Dir as Dir;
use Joalcapa\Fundamentary\Api\Api as Api;
use Joalcapa\Fundamentary\Auth\Auth as Auth;
use Joalcapa\Fundamentary\Rest\Rest as Rest;
use Joalcapa\Fundamentary\Database\Kernel as KernelDB;
use Joalcapa\Fundamentary\Exception\Exceptions\KillerException as KillerException;
use Joalcapa\Fundamentary\Exception\Exceptions\AuthExpiredException as AuthExpiredException;

class Application {
    
    private $kernels = [];
    
    /**
     * Definición del directorio real e inicialización del kernel de base de datos.
     *
     * @param  string  $path
     */
    public function __construct($path) {
        define("REAL_PATH", $path);
        define("ASSETS_PATH", Dir::assets()); 
     //   error_reporting(0);
    }
    
    /**
     * Agregado de los kernel necesarios para el funcionamiento de la api Rest.
     *
     * @param  string  $classKernel
     */
    public function addKernelSingleton($classKernel) {
        $this->kernels[$classKernel] = $classKernel::getKernel();
    }
    
    /**
     * Proceso de enviado de la respuesta de la api Rest.
     */
    public function sendResponse() {
        $kernelHttp = $this->kernels[Dir::kernelHttp()];
        $kernelHttp::send();
    }
    
    /**
     * Punto de inicio de la api Rest, proceso de autenticación, busqueda del modelo y generación
     * de la respuesta de la api.
     */
    public function init() {
        try {
            require_once (REAL_PATH . '\\vendor\\joalcapa\\fundamentary\\src\\Magics.php');
            require(__DIR__.'/Middlewares/Middleware.php');
            KernelDB::getKernel();

            $kernelHttp = $this->kernels[Dir::kernelHttp()];

            $request = $kernelHttp::request();
            if($request->isRootUrl())
                killer('1');

            $data = Auth::initAuth($request);
            $data ? $this->kernels[Dir::kernelHttp()]->makeResponse('200', $data) : $this->apiRest();

            if(!$this->kernels[Dir::kernelHttp()]->response())
                $this->kernels[Dir::kernelHttp()]->makeResponse('404');

        } catch(KillerException $exception) {
            $this->kernels[Dir::kernelException()]->handlerException($exception, $this);
        } catch(AuthExpiredException $exception) {
            $this->kernels[Dir::kernelException()]->handlerException($exception, $this);
        }
    }
    
    /**
     * Preparación del objeto response http, cuyo objetivo es el envío de la respuesta de la api Rest.
     *
     * @param  array  $data
     */
    public function makeResponse($data) {
        $this->kernels[Dir::kernelHttp()]->makeResponse($data);
    }

    public function apiRest() {
        Rest::Apply();
        Api::Apply();
    }
}
