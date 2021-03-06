<?php 


/*
|--------------------------------------------------------------------------
| GAULER MÁGICS
|--------------------------------------------------------------------------
|
| Aquí se establecen todos los métodos globales de ayuda.
|
*/


/**
 * Inicialización de las variables de entorno
 *
 */

try {
    $dotenv = Dotenv\Dotenv::create(REAL_PATH);
    $dotenv->load();
} catch(Exception $exception) {
    killer('500');
}

/**
 * Encriptación de datos en modo PASSWORD_BCRYPT.
 *
 * @param  string  $parameter
 * @return  $string
 */
function hashBCrypt($parameter) {
    return password_hash($parameter, PASSWORD_BCRYPT);
}

/**
 * Verificación de dato encriptado.
 *
 * @param  string  $parameter
 * @param  string  $parameterHash
 * @return  boolean
 */
function verifyBCrypt($parameter, $parameterHash) {
    return password_verify($parameter, $parameterHash);
}

/**
 * Persistencia de datos en archivos.
 *
 * @param  string  $route
 * @param  array  $data
 */
function writeAssetsBase64Decode($route, $data) {
    $tokens = explode('/', $route);
    $sizeTokens = count($tokens);
    
    for($i=0; $i<$sizeTokens && $sizeTokens>1 && $i<$sizeTokens-1; $i++) {
        $j=0; $routeAux = ASSETS_PATH;
        while($j<=$i) {
            $routeAux = $routeAux.$tokens[$j];
            $j++;
        }
        if(!is_dir($routeAux))
            mkdir($routeAux, 0777, true);
    }
    
    try {
        file_put_contents(ASSETS_PATH.$route, base64_decode($data));
    } catch(Exception $exception) {
        killer('507');
    }
}

/**
 * Recuperación de un archivo en array de bytes.
 *
 * @param  string  $route
 * @param  string  $typeData
 * @return array
 */
function streamingImage($route, $typeData) { 
    $route = ASSETS_PATH.$route;
    if(file_exists($route)) { 
        $byte_array = file_get_contents($route);
        $array = base64_encode($byte_array);
        $bytesImage = [
            'type data' => $typeData,
            'bytes' => $array
        ];
        return $bytesImage;    
    }
    killer('404');
}

/**
 * Método Gauler Mágic, que permite la 
 * ejecución de una con sentencia en el gestor de base de datos destino.
 *
 * @param  string  $query
 * @param  boolean  $isReturn
 * @return array
 */
function queryBuilder($query, $isReturn = true) {
    return Joalcapa\Fundamentary\Database\Kernel::queryBuilder($query, $isReturn);
}

/**
 * Método Gauler Mágic, aborta el proceso de Gauler
 * con una excepción, mediante un codigo de estado http.
 *
 * @param  string  $codeHttp
 */
function killer($codeHttp) {
    throw new Joalcapa\Fundamentary\Exception\Exceptions\KillerException('killer', $codeHttp);
}

/**
 * Método Gauler Mágic, aborta el proceso de autenticado en Gauler
 * con una excepción
 *
 * @param  string  $nameClassExceptionAuth
 */
function abortAuth($nameClassExceptionAuth) {
    switch($nameClassExceptionAuth) {
        case 'Firebase\JWT\SignatureInvalidException':
            throw new Joalcapa\Fundamentary\Exception\Exceptions\AuthSignatureInvalidException('AuthSignatureInvalidException', '401');
            break;
        case 'Firebase\JWT\ExpiredException':
            throw new Joalcapa\Fundamentary\Exception\Exceptions\AuthExpiredException('AuthExpiredException', '401');
            break;
        default:
            break;
    }
}

/**
 * Método Gauler Mágic, que permite obtener el 
 * valor de una variable de entorno, en caso
 * de no haberse declarado, retorna el parametro 
 * alternativo.
 *
 * @param  string  $environment
 * @param  string  $parameter
 * @return  string
 */
function env($environment, $parameter) {
    $env = getenv($environment);
    if(isset($env))
        return $env;
    return $parameter;
}


/*
|--------------------------------------------------------------------------
| CLASE GLOBAL AUTH SINGLETON
|--------------------------------------------------------------------------
|
| Aquí se guarda la información relevante al 
| usuario de forma global, gracias al patrón singleton.
|
*/

class Auth {
    
    private static $auth;
    
    public $id;
    public $email;
    public $name;

    private function __construct() {}
    
    public static function getAuth() {
        if (!self::$auth instanceof self)
            self::$auth = new self;
        return self::$auth;
    }
    
    public function __clone() { killer('500'); }
    
    public function __wakeup() { killer('500'); }
    
    public function init($id, $name, $email) {
        self::$auth->id = $id;
        self::$auth->name = $name;
        self::$auth->email = $email; 
    }
}


