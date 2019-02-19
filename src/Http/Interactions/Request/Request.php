<?php

namespace Joalcapa\Fundamentary\Http\Interactions\Request;

class Request {
    
    public $id;
    public $idRelational;
    public $relationalModel;
    
    private $inputs;
    
    public function __construct($inputs, $idRelational, $id, $relationalModel = null) {
        $this->id = $id;
        $this->inputs = $inputs;
        $this->idRelational = $idRelational;
        $this->relationalModel = $relationalModel;
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
}