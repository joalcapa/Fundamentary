<?php

namespace Joalcapa\Fundamentary\Database\Drivers;

use Joalcapa\Fundamentary\Database\Drivers\DriverDB as DriverDB;
use Joalcapa\Elementary\Generics\TypeAttrQ as TypeAttrQ;

class Mysql implements DriverDB {
    
    private $mysqli;
    private $config;

    public function __construct($config) {
        $this->config = $config;
    }
    
    public function connect() {
        $this->mysqli = mysqli_connect(
            $this->config['host'],
            $this->config['user'],
            $this->config['password'],
            $this->config['db']
        );
        
        if (mysqli_connect_errno($this->mysqli))
            killer('500');
        
        mysqli_set_charset($this->mysqli, "utf8");
    }
    
    public function query($query, $isReturn = true) {
        if($isReturn) {
            $result =  mysqli_query($this->mysqli, $query);
            return mysqli_fetch_array($result, MYSQLI_ASSOC);
        }
        mysqli_query($this->mysqli, $query);
    }
    
    public function close() {
        mysqli_close();
    }  
    
    public function user($model, $parameter, $parameterString) {
        return $this->query(
            "SELECT * FROM ".mysqli_real_escape_string($this->mysqli, $model)
            ." WHERE ".mysqli_real_escape_string($this->mysqli, $parameterString)." = '".mysqli_real_escape_string($this->mysqli, $parameter)
            ."';");
    }
    
    public function verifyUser($model, $id, $password) {
        return $this->query(
            "SELECT * FROM ".mysqli_real_escape_string($this->mysqli, $model)
            ." WHERE ID = '".mysqli_real_escape_string($this->mysqli, $id)
            ."' AND PASSWORD = '".mysqli_real_escape_string($this->mysqli, $password)
            ."';"); 
    }
    
    public function updatePasswordUser($model, $password, $id) {
        $this->query(
            "UPDATE LOW_PRIORITY ".mysqli_real_escape_string($this->mysqli, $model)
            ." SET PASSWORD = '".mysqli_real_escape_string($this->mysqli, $password)
            ."' WHERE ID = '".mysqli_real_escape_string($this->mysqli, $id)
            ."'", false);
    }
    
    public function all($model) {
        $result =  mysqli_query($this->mysqli, "SELECT * FROM ".mysqli_real_escape_string($this->mysqli, $model)." LIMIT 0, 30");
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    
    public function where($model, $where) {
        $query = ' WHERE ';
        $isAdd = false;
        foreach ($where as $item) {
            if ($isAdd)
                $query = $this->prepareQuery($query, $item);
            $query = $this->writeQuery($query, $item);
            $isAdd = true;
        }

        $result =  mysqli_query($this->mysqli, "SELECT * FROM ".mysqli_real_escape_string($this->mysqli, $model).$query); 
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }

    public function writeQuery($query, $itemQuery) {
        $isAdd = false;
        if(is_array($itemQuery)) {
            $query = $query . '( ';
            $query = $query . $itemQuery['query'];
            $query = $query . ')';
        } else
            $query = $query . $itemQuery;

        return $query;
    }

    public function prepareQuery($query, $item) {
        if(is_array($item))
            switch($item['type']) {
                case 'OR':
                    $query = $query . ' OR ';
                    break;
                default:
                    break;
            }
        else
            $query = $query . ' AND ';

        return $query;
    }

    public function or($where) {
        $query = '';
        $isAdd = false;
        foreach ($where as $item) {
            if ($isAdd)
                $query = $this->prepareQuery($query, $item);
            $query = $this->writeQuery($query, $item);
            $isAdd = true;
        }

        return [
            "type" => "OR",
            "query" => $query
        ];
    }
    
    public function typeWhere($typeWhere, $attribute, $value = null) { 
        switch($typeWhere) {
            case 'NOT_NULL':
                return $attribute.' IS NOT NULL ';
            case 'NOT_EQUALS':
                return $attribute." != '".mysqli_real_escape_string($this->mysqli, $value)."' ";
            case 'EQUALS':
                return $attribute." = '".mysqli_real_escape_string($this->mysqli, $value)."' ";
            default:
                break;
        }
    }
    
    public function update($data, $tuples, $model, $id) { 
        $isAdd = false;
        $query = 'UPDATE LOW_PRIORITY '.mysqli_real_escape_string($this->mysqli, $model).' SET ';
        
        foreach($tuples as $tuple) { 
            if($isAdd && isset($data->$tuple))
                $query = $query.', ';
            if(isset($data->$tuple)) {
                $isAdd = true;
                $query = $query.$tuple."='".mysqli_real_escape_string($this->mysqli, $data->$tuple)."'";
            }
        }
            
        $query = $query." WHERE ID='".$id."'";
        $this->query($query, false);
        return $this->find($model, $id);
    }
    
    public function save($data, $tuples, $model) { 
        $isAdd = false;
        $query = 'INSERT INTO '.mysqli_real_escape_string($this->mysqli, $model).' (';
        
        foreach($tuples as $tuple) { 
            if($isAdd && isset($data->$tuple))
                $query = $query.', ';
            if(isset($data->$tuple)) {
                $isAdd = true;
                $query = $query.$tuple;
            }
        }
        
        $query = $query.') VALUES (';
        $isAdd = false;
        
        foreach($tuples as $tuple) { 
            if($isAdd && isset($data->$tuple))
                $query = $query.', ';
            if(isset($data->$tuple)) {
                $isAdd = true; 
                $query = $query."'".mysqli_real_escape_string($this->mysqli, $data->$tuple)."'";
            }
        }
          
        $query = $query.')';
        $this->query($query, false);

        $id = mysqli_insert_id($this->mysqli);
        return $this->find($model, $id);
    }
    
    public function find($model, $id) {
        $data = $this->query(
            "SELECT * FROM ".mysqli_real_escape_string($this->mysqli, $model)
            ." WHERE id='".mysqli_real_escape_string($this->mysqli, $id)
            ."' LIMIT 1"
        );

        if(empty($data))
            $data = [];

        return $data;
    }
    
    public function destroy($model, $id) {
        $this->query(
            "DELETE FROM ".mysqli_real_escape_string($this->mysqli, $model)
            ." WHERE id='".mysqli_real_escape_string($this->mysqli, $id)."'",
            false
        );
    }

    public function createOrReplaceTable($model, $attributes) {
        $this->query(
            'CREATE TABLE '. $model .' (
            id INT NOT NULL AUTO_INCREMENT,'
            . $this->prepareAttributesForCreateTable($attributes) .',
            created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            update_date TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id));',
            false
        );
    }

    public function prepareAttributesForCreateTable($attributes) {
        $str = '';
        $isFirts = true;
        foreach ($attributes as $key => $value) {

            switch($value) {
                case TypeAttrQ::STRING:
                    $value = 'VARCHAR(500)';
                    break;
                case TypeAttrQ::INTEGER:
                    $value = 'INT';
                    break;
                default:
                    return;
            }

            $strAttribute = $key.' '.$value;
            $isFirts == false ? $strAttribute = ', '.$strAttribute : $isFirts = false;
            $str .= $strAttribute;
        }

        return $str;
    }
}