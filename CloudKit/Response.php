<?php
/**
 * Created by PhpStorm.
 * User: malhal
 * Date: 04/04/2016
 * Time: 14:17
 */

namespace CloudKit;

class Response
{
    protected $errors;

    public function __construct($array){
        if(array_key_exists('serverErrorCode', $array)){
            $this->errors = [ new CKError($array) ];
        }
    }

    function hasErrors(){
        return count($this->errors) > 0;
    }

    public function getErrors(){
        return $this->errors;
    }
}