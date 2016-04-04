<?php
/**
 * Created by PhpStorm.
 * User: malhal
 * Date: 04/04/2016
 * Time: 16:56
 */

namespace CloudKit;

abstract class Action{
    const NONE = 'NONE';
    const DELETE_SELF = 'DELETE_SELF';
}

class Reference
{
    private $recordName;
    private $action;

    public function __construct($recordName, $action){
        $this->recordName = $recordName;
        $this->action = $action;
    }

    public static function createFromServerArray($array){
        return new Reference($array['recordName'], $array['action']);
    }

    public function toServerArray(){
        $a = array();
        $a['recordName'] = $this->recordName;
        $a['action'] = $this->action;
        return $a;
    }
}