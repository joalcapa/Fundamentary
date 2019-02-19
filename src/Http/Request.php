<?php

namespace Joalcapa\Fundamentary\Http;

use Joalcapa\Fundamentary\Http\Interactions\Request\Request as InteractionsRequest;

class Request {
    
    private $id;
    private $url;
    private $inputs;
    private $idRol;
    private $model;
    private $login;
    private $resetPassword;
    private $relationalModel;
    private $requiredParameter;
    private $relationalParameter;
    private $interactionsRequest;
    private $rootUrl;
    
    public function __construct() {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
        header("Expires: Tue, 01 Jul 2001 06:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Cache-Control: no-cache, must-revalidate");
        header_remove('x-powered-by');
        
        if($_SERVER['REQUEST_METHOD'] == "OPTIONS") 
            die();
           
        $this->login = false;
        $this->resetPassword = false;
        $this->model = $this->requiredParameter = $this->relationalModel = $this->relationalParameter = null;   
        $this->prepareURL();
        
        $json = file_get_contents('php://input');
        if(isset($json)) {
            $this->inputs = json_decode($json);
            $this->interactionsRequest = new InteractionsRequest($this->inputs, $this->relationalParameter, $this->requiredParameter, $this->relationalModel);
        }
    }
    
    /**
     * Preparación de las variables necesarias para el procedimiento REST.
     */
    private function prepareURL() {
        $this->url = $_SERVER['REQUEST_URI'];
        $this->rootUrl = false;

        if($this->url == '/api/auth' || $this->url == '/api/auth/' )
            $this->login = true;

        if($this->url == '/api/auth/reset-password' || $this->url == '/api/auth/reset-password/' )
            $this->resetPassword = true;

        if(strpos($this->url, '?')) 
            strstr($this->url, '?', true);

        if(strpos($this->url, 'api/') !== false)
            $strSearch = 'api/';
        else {
            if (strpos($this->url, 'api') !== false)
                $strSearch = 'api';
            else
                $this->rootUrl = true;
        }

        if($this->login || $this->resetPassword) return;
        if(empty($strSearch)) return;

        $tokens2 = explode($strSearch, $this->url);

        if($tokens2[1] != '') {
            $tokens = explode("/", $tokens2[1]);
            switch(count($tokens)) {
                case 1:
                    $this->model = ucwords(strtolower($tokens[0]));
                    break;
                case 2:
                    $this->model = ucwords(strtolower($tokens[0]));
                    $this->requiredParameter = $tokens[1];
                    break;
                case 3:
                    $this->relationalModel = ucwords(strtolower($tokens[0]));
                    $this->relationalParameter = $tokens[1];
                    $this->model = ucwords(strtolower($tokens[2]));
                    break;
                default:
                    $this->relationalModel = ucwords(strtolower($tokens[0]));
                    $this->relationalParameter = $tokens[1];
                    $this->model = ucwords(strtolower($tokens[2]));
                    $this->requiredParameter = $tokens[3];
                    break;
            }
        } else
            $this->rootUrl = true;
    }
    
    /**
     * Asignación del rol del usuario autenticado.
     *
     * @param  int  $id
     */
    public function setIdRol($idRol) {
        $this->idRol = $idRol;
    }
    
    /**
     * Asignación del id del usuario autenticado.
     *
     * @param  int  $id
     */
    public function setId($id) {
        $this->id = $id;
    }
    
    /**
     * Retorno del rol del usuario autenticado.
     *
     * @return  int
     */
    public function getIdRol() {
        return $this->idRol;
    }
    
    /**
     * Retorno de asignación al reseteo de password.
     *
     * @return  boolean
     */
    public function isResetPassword() {
        return $this->resetPassword;
    }
    
    /**
     * Retorno de asignación al login.
     *
     * @return  boolean
     */
    public function isLogin() {
        return $this->login;
    }
    
    /**
     * Retorno del cuerpo de Request.
     *
     * @return  array
     */
    public function bodyRequest() {
        $json = file_get_contents('php://input');
        $data = json_decode($json);

        $requestWithBodyRequest = $this->getInteractionsRequest();
        foreach ($data as $key => $value)
            $requestWithBodyRequest->$key = $value;

        return $requestWithBodyRequest;
    }
    
    /**
     * Retorno de la variable enviada por el cliente.
     *
     * @return  string
     */
    public function input($input) {
        if(isset($this->inputs->$input)) 
            return $this->inputs->$input;
        return null;
    }
    
    /**
     * Retorno del id del recurso presente en la URL jerarquizada.
     *
     * @return  int
     */
    public function getRequiredParameter() {
        if(isset($this->requiredParameter))
            return $this->requiredParameter;
        return null;
    }
    
    /**
     * Retorno del id de la relacion presente en la URL jerarquizada.
     *
     * @return  int
     */
    public function getRelationalParameter() {
        if(isset($this->relationalParameter))
            return $this->relationalParameter;
        return null;
    }
    
    /**
     * Retorno del método http.
     *
     * @return  string
     */
    public function method() {
        return $_SERVER['REQUEST_METHOD'];
    }
    
    /**
     * Retorno del modelo Rest.
     *
     * @return  string
     */
    public function getModel() {
        return $this->model;
    }
    
    /**
     * Retorno del token de autenticación presente en el header authorization.
     *
     * @return  string
     */
    public function authorizationToken() {
        $headers = apache_request_headers();
        foreach ($headers as $header => $value) 
            if($header == 'Authorization') {
                $tokens = explode(" ", $value);
                if($tokens[0] != 'Bearer' || empty($tokens[1]))
                    killer('511');
                return $tokens[1];
            }
        killer('401');
    }
    
    /**
     * Retorno del objeto request que interacciona con el modeloRest.
     *
     * @return  Fundamentary\Http\Interactions\Request\Request
     */
    public function getInteractionsRequest() {
        if(isset($this->interactionsRequest))
            return $this->interactionsRequest;
        killer('500');
    }

    /**
     * Retorno de ruta ajena a la api
     *
     * @return boolean
     */
    public function isRootUrl() {
        return $this->rootUrl;
    }
}
