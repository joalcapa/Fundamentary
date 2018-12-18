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
        return REAL_PATH.'\\App\\Models\\'.$model.'Model.php';
    }
    
    /**
     * Directorio del controlador Rest.
     *
     * @param  string  $model
     * @return  string
     */
    public static function controller($model) {
        return REAL_PATH.'\\App\\Controllers\\'.$model.'Controller.php';
    }
    
    /**
     * Directorio del middleware Rest.
     *
     * @param  string  $model
     * @return  string
     */
    public static function middleware($model) {
        return REAL_PATH.'\\App\\Middlewares\\'.$model.'Middleware.php';
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
     * Directorio del controlador Rest.
     *
     * @param  string  $model
     * @return  string
     */
    public static function apiControllers($model) {
        return 'Gauler\\App\\Controllers\\'.$model.'Controller';
    }
    
    /**
     * Directorio del middleware Rest.
     *
     * @param  string  $model
     * @return  string
     */
    public static function apiMiddlewares($model) {
        return 'Gauler\\App\\Middlewares\\'.$model.'Middleware';
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
