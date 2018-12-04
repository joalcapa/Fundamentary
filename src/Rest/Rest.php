<?php

namespace Joalcapa\Fundamentary\Rest;

use Auth;
use Joalcapa\Fundamentary\Dir\Dir as Dir;

class Rest {
    
    /**
     * Inicialización del método model, del proceso RestFull.
     *
     */
    public static function apply() { 
        require(__DIR__.'/RestFull/RestFull.php');
        file_exists(Dir::rest()) ? require_once(Dir::rest()) : killer('500');
    }  
}
