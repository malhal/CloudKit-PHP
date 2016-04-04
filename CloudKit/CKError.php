<?php
/**
 * Created by PhpStorm.
 * User: malhal
 * Date: 04/04/2016
 * Time: 15:03
 */

namespace CloudKit;

abstract class ErrorCode{
    const NOT_FOUND = 'NOT_FOUND';
}

class CKError
{
    private $serverErrorCode;
    private $reason;
    private $recordName;
    private $uuid;
    private $retryAfter;

    public function __construct($array){
        foreach($array as $key => $value) {
            switch ($key) {
                case 'serverErrorCode':
                    $this->serverErrorCode = $value;
                    break;
                case 'reason':
                    $this->reason = $value;
                    break;
                case 'recordName':
                    $this->recordName = $value;
                    break;
                case 'uuid':
                    $this->uuid = $value;
                    break;
                case 'retryAfter':
                    $this->retryAfter = $value;
                    break;
            }
        }
    }

    public function getServerErrorCode(){
        return $this->serverErrorCode;
    }

    public function getReason(){
        return $this->reason;
    }

    public function getRecordName(){
        return $this->recordName;
    }

    public function getUuid(){
        return $this->uuid;
    }
}