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
        return $model::all();
    }
    
    /**
     * Método REST, asociado al verbo http get, con parametro requerido en la url.
     *
     * @param  \Fundamentary\Http\Interactions\Request\Request  $request
     * @return  array
     */
    public function show($request) {
        $model = Dir::apiModel($this->model);
        return $model::find($request->id);
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

        return $model->save();
    }
    
    /**
     * Método REST, asociado al verbo http put.
     *
     * @param  \Fundamentary\Http\Interactions\Request\Request  $request
     */
    public function update($request) {
        $model = Dir::apiModel($this->model);
        $modelFind = $model::find($request->id);

        foreach($modelFind->getTuples() as $tuple)
            if(!empty($request->$tuple))
                $modelFind->$tuple = $request->$tuple;

        return $modelFind->update();
    }
    
    /**
     * Método REST, asociado al verbo http delete.
     *
     * @param  \Fundamentary\Http\Interactions\Request\Request  $request
     */
    public function destroy($request) {
        $model = Dir::apiModel($this->model);
        $model::destroy($request->id);
    }
}