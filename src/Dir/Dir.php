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
        return REAL_PATH.'\\Api\\rest.php';
    }
    
    /**
     * Directorio del archivo que contiene los datos de hypermedia de usuario.
     *
     * @return  string
     */
    public static function hypermedia() {
        return REAL_PATH.'\\Api\\hypermedia.php';
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
