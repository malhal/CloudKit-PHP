<?php
/**
 * Created by PhpStorm.
 * User: malhal
 * Date: 04/04/2016
 * Time: 14:19
 */

namespace CloudKit;

class RecordsResponse extends Response
{
    private $records;

    public function __construct($array){
        parent::__construct($array);
        if(array_key_exists('records', $array)) {
            foreach($array['records'] as $r){
                if(array_key_exists('serverErrorCode', $r)){
                    $this->errors[] = new CKError($r);
                }else {
                    $this->records[] = Record::createFromServerArray($r);
                }
            }
        }
    }

    public function getRecords(){
        return $this->records;
    }
}