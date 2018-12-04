<?php

namespace Joalcapa\Fundamentary\Auth\Services;

use Auth;
use Joalcapa\Fundamentary\Dir\Dir as Dir;
use Joalcapa\Fundamentary\Database\Kernel as kernelDB;
use Joalcapa\Fundamentary\App\Models\UserModel as User;
use Joalcapa\Fundamentary\Auth\Provider\JWTProvider as JWT;
use Joalcapa\Fundamentary\Auth\Services\Service as Service;

class ServiceAuth implements Service {

    public static $userModel = 'Users';
    
    public static function validateAuth($request) {   
        $password = $request->input('password');
        $email = $request->input('email'); 
        
        if(!isset($password) || !isset($email))
            killer('511');
        
        $result = kernelDB::user(self::$userModel, $email, 'EMAIL');
        
        if(isset($result['password'])) 
            if(verifyBCrypt($password, $result['password'])) 
                return self::addCredentials($result);  
        
        killer('511');
    }
    
    public static function authenticate($request) {
        $config = require(Dir::config());
        $auth = $config['auth'];
        $token = $request->authorizationToken();
        
        $data = JWT::decodeCredentials($token, $auth);
        if(!isset($data->data->id) || !isset($data->data->password))
            killer('511');
        
        $result = kernelDB::verifyUser(self::$userModel, $data->data->id, $data->data->password);
        
        if(isset($result['id_rol'])) { 
            Auth::getAuth()->init($result['id'], $result['id_rol'], $result['nombre'], $result['email']); 
        } else
            killer('401');  
    }
    
    public static function resetPassword($request) { 
        $password = $request->input('password');
        $newPassword = $request->input('newPassword');
        $email = $request->input('email');
        
        $token = $request->authorizationToken();
        $config = require(Dir::config());
        $auth = $config['auth'];
        $data = JWT::decodeCredentials($token, $auth);
        
        if(!isset($data->data->id) || !isset($data->data->password))
            killer('511');
        
        if(!is_numeric($data->data->id))
            killer('401');
        
        $result = kernelDB::user(self::$userModel, $email, 'EMAIL');
        
        if(isset($result['password']))
            if(verifyBCrypt($password, $result['password'])) {
                kernelDB::updatePasswordUser(self::$userModel, hashBCrypt($newPassword), $result['id']);
                return self::addCredentials($result);
            }
        
        killer('401');
    }
    
    public static function addCredentials($result, $otherParameter = null) { 
        $user = new User(); 
        $user = $user->getTuples($result);
        if(file_exists(Dir::hypermedia())) { 
            $hypermedia = require(Dir::hypermedia()); 
            $cont = 0;
            
            foreach($hypermedia as $hyper) {
                $cont++;
                
                if($cont == $result['id_rol']) {
                    $arrayHypermedia = [];
                    foreach($hyper as $key => $value) {
                        if(!(strpos($value, '{{id_user}}') === false))
                            $value = str_replace('{{id_user}}', $result['id'], $value);
                        if(!(strpos($value, 'http:') === false))
                            $value = str_replace('http:', 'https:', $value);
                        if(strpos($value, 'http:') === false && strpos($value, 'https:') === false)
                            $value = 'https:'.$value;
                        $arrayHypermedia[$key] = $value;
                    }
                    
                    return [
                      'user' => $user,
                      'token' => JWT::credentialsGrant($user),
                      'hypermedia' => $arrayHypermedia
                    ];
                }
            }
        }
        
        return [
               'user' => $user,
               'token' => JWT::credentialsGrant($user)
        ];
    }
}
