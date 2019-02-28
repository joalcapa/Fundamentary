<?php 

namespace Joalcapa\Fundamentary\Dir;

class Dir {
    
    /**
     * Directorio de configuración.
     *
     * @return  string
     */
    public static function config() {
        return REAL_PATH.'\\Config\\config.php';
    }
    
    /**
     * Directorio de archivos assets.
     *
     * @return  string
     */
    public static function assets() {
        return REAL_PATH.'\\assets\\';
    }
    
    /**
     * Directorio del modelo Rest.
     *
     * @param  string  $model
     * @return  string
     */
    public static function model($model) {
        return REAL_PATH.'\\Api\\Models\\'.$model.'Model.php';
    }
    
    /**
     * Directorio del controlador Rest.
     *
     * @param  string  $model
     * @return  string
     */
    public static function controller($model) {
        return REAL_PATH.'\\Api\\Controllers\\'.$model.'Controller.php';
    }
    
    /**
     * Directorio del middleware Rest.
     *
     * @param  string  $model
     * @return  string
     */
    public static function middleware($model) {
        return REAL_PATH.'\\Api\\Middlewares\\'.$model.'Middleware.php';
    }
    
    /**
     * Directorio del archivo Rest, definición de modelos.
     *
     * @return  string
     */
    public static function rest() {
        return REAL_PATH.'\\Routes\\rest.php';
    }

    /**
     * Directorio del archivo Api, definición de rutas.
     *
     * @return  string
     */
    public static function api() {
        return REAL_PATH.'\\Routes\\api.php';
    }

    /**
     * Directorio del modelo Rest.
     *
     * @param  string  $model
     * @return  string
     */
    public static function apiModel($model) {
        return 'Gauler\\Api\\Models\\'.$model.'Model';
    }
    
    /**
     * Directorio del controlador Rest.
     *
     * @param  string  $model
     * @return  string
     */
    public static function apiControllers($model) {
        return 'Gauler\\Api\\Controllers\\'.$model.'Controller';
    }

    /**
     * Directorio del controlador Rest.
     *
     * @param  string  $model
     * @return  string
     */
    public static function baseControllers() {
        return 'Joalcapa\\Fundamentary\\App\\Controllers\\BaseController';
    }
    
    /**
     * Directorio del middleware Rest.
     *
     * @param  string  $model
     * @return  string
     */
    public static function apiMiddlewares($model) {
        return 'Gauler\\Api\\Middlewares\\'.$model.'Middleware';
    }
    
    /**
     * Directorio del driver del gestor de base de datos.
     *
     * @param  string  $nameDriver
     * @return  string
     */
    public static function driverDatabase($nameDriver) {
        return 'Joalcapa\\Fundamentary\\Database\\Drivers\\'.$nameDriver;
    }
    
    /**
     * Directorio del kernel de excepciones.
     *
     * @return  string
     */
    public static function kernelException() {
        return 'Joalcapa\\Fundamentary\\Exception\\Kernel';
    }
    
    /**
     * Directorio del kernel del protocolo http.
     *
     * @return  string
     */
    public static function kernelHttp() {
        return 'Joalcapa\\Fundamentary\\Http\\Kernel';
    }
}
