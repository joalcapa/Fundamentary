<?php

namespace Joalcapa\Fundamentary\App\Models;

use Joalcapa\Fundamentary\Dir\Dir as Dir;
use Joalcapa\Fundamentary\Http\Kernel as kernelHttp;
use Joalcapa\Fundamentary\Database\Kernel as KernelDB;

class BaseModel {

    /**
     * Constructor ORM, que permite guardar el objeto relacional, perteneciente al modelo Rest en la base de datos destino.
     *
     * @param  array  $request
     */
    public function __construct($request = null) {
        if($request) {
            foreach($this->tuples as $tuple)
                if(!empty($request->$tuple))
                    $this->$tuple = $request->$tuple;
                else
                    killer('400');
            $this->save();
        }
    }
    
    /**
     * Método ORM, que permite guardar el objeto relacional, perteneciente al modelo Rest en la base de datos destino.
     */
    public function save() { 
        $array = KernelDB::save($this, $this->tuples, static::$model);
        $routeModel = Dir::apiModel(static::$model);
        $this->id = $array['id'];
    }
    
    /**
     * Método ORM, que permite actualizar el objeto relacional, perteneciente al modelo Rest ó al modelo en la base de datos destino.
     *
     * @param  int  $id
     */
    public function update($id = null) {
        if($id)
            $array = KernelDB::update($this, $this->tuples, static::$model, $id);
        else
            $array = KernelDB::update($this, $this->tuples, static::$model, kernelHttp::request()->getRequiredParameter());

        self::updateThis($this, $array);
    }

    /**
     * Método ORM, que permite actualizar el objeto relacional, perteneciente al modelo Rest ó al modelo en la base de datos destino.
     *
     * @param  array  $request
     */
    public static function updateRequest($request) {
        $routeModel = Dir::apiModel(static::$model);
        $heroe = $routeModel::find($request->id);

        foreach($heroe->getTuples() as $tuple)
            if(!empty($request->$tuple))
                $heroe->$tuple = $request->$tuple;

        $heroe->update();
        return $heroe;
    }
    
    /**
     * Método ORM, que permite obtener una colección de objetos, pertenecientes al modelo Rest en la base de datos destino.
     *
     * @return  array
     */
    public static function all() {
        return KernelDB::all(static::$model);
    }
    
    /**
     * Método ORM, que permite obtener un objeto, perteneciente al modelo Rest en la base de datos destino.
     * 
     * @param  int  $id
     * @return  array
     */
    public static function find($id) { 
        $array = KernelDB::find(static::$model, $id);
        $routeModel = Dir::apiModel(static::$model);
        return self::arrayToModel($routeModel, $array);
    }
    
    /**
     * Método ORM, que permite obtener un objeto o colección de objetos, de acuerdo a las condiciones
     * ingresadas como un array asociativo, perteneciente al modelo Rest en la base de datos destino.
     *
     * @param  array  $where
     * @return  array
     */
    public static function where($where) { 
        return KernelDB::where(static::$model, $where);
    }
    
    /**
     * Método ORM, que permite eliminar un objeto, perteneciente al modelo Rest en la base de datos destino.
     *
     * @param  int  $id
     */
    public static function destroy($id) {
        KernelDB::destroy(static::$model, $id);
    }
    
    /**
     * Método ORM, que permite obtener la syntaxis del gestor de base de datos destino.
     *
     * @param  string  $attribute
     * @return  string
     */
    public static function NOT_NULL($attribute) {
        return KernelDB::TypeWhere('NOT_NULL', $attribute);
    }
    
    /**
     * Método ORM, que permite obtener la syntaxis del gestor de base de datos destino.
     *
     * @param  string  $attribute
     * @param  string  $value
     * @return  string
     */
    public static function NOT_EQUALS($attribute, $value) {
        return KernelDB::TypeWhere('NOT_EQUALS', $attribute, $value);
    }
    
    /**
     * Método ORM, que permite obtener la syntaxis del gestor de base de datos destino.
     *
     * @param  string  $attribute
     * @param  string  $value
     * @return  string
     */
    public static function EQUALS($attribute, $value) {
        return KernelDB::TypeWhere('EQUALS', $attribute, $value);
    }

    /**
     * Método que retorna los atributos del modelo
     *
     * @return  array
     */
    public function getTuples() {
        return $this->tuples;
    }

    public static function arrayToModel($routeModel, $array) {
        $model = new $routeModel();
        $model->id = $array['id'];
        foreach ($model->tuples as $tuple)
            $model->$tuple = $array[$tuple];
        return $model;
    }

    public static function updateThis($model, $array) {
        $model->id = $array['id'];
        foreach ($model->tuples as $tuple)
            $model->$tuple = $array[$tuple];
    }
}