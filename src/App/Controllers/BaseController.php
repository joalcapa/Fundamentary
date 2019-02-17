<?php

namespace Joalcapa\Fundamentary\App\Controllers;

use Joalcapa\Fundamentary\Dir\Dir as Dir;

class BaseController {

    private $model;

    public function __construct($model = null) {
        $this->model = $model;
    }
    
    /**
     * Método REST, asociado al verbo http get, sin parametro requerido en la url.
     *
     * @param  \Fundamentary\Http\Interactions\Request\Request  $request
     * @return  array
     */
    public function index($request) {
        $model = Dir::apiModel($this->model);
        $data = $model::all();
        return $data;
    }
    
    /**
     * Método REST, asociado al verbo http get, con parametro requerido en la url.
     *
     * @param  \Fundamentary\Http\Interactions\Request\Request  $request
     * @return  array
     */
    public function show($request) {
        $model = Dir::apiModel($this->model);
        $data = $model::find($request->id);
        return $data;
    }
    
    /**
     * Método REST, asociado al verbo http post.
     *
     * @param  \Fundamentary\Http\Interactions\Request\Request  $request
     */
    public function store($request) {
        $model = Dir::apiModel($this->model);
        $model = new $model();

        foreach($model->getTuples() as $tuple)
            empty($request->$tuple) ? killer('400') : $model->$tuple = $request->$tuple;

        return [
            'data' => $model->save()
        ];
    }
    
    /**
     * Método REST, asociado al verbo http put.
     *
     * @param  \Fundamentary\Http\Interactions\Request\Request  $request
     */
    public function update($request) {
        
    }
    
    /**
     * Método REST, asociado al verbo http delete.
     *
     * @param  \Fundamentary\Http\Interactions\Request\Request  $request
     */
    public function destroy($request) {
        
    }
}