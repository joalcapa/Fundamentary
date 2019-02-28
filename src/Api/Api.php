<?php

namespace Joalcapa\Fundamentary\Api;

use Joalcapa\Fundamentary\Dir\Dir as Dir;

class Api {

    /**
     * Inicialización del método model, del proceso RestFull.
     *
     */
    public static function apply() {
        require(__DIR__.'/Handlers/Api.php');
        file_exists(Dir::api()) ? require_once(Dir::api()) : killer('500');
    }
}