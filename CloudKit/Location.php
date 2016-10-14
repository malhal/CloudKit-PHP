<?php
/**
 * Created by PhpStorm.
 * User: malhal
 * Date: 04/04/2016
 * Time: 17:22
 */

namespace CloudKit;
use DateTime;

class Location
{
    public $latitude;
    public $longitude;
    public $horizontalAccuracy;
    public $verticalAccuracy;
    public $altitude;
    public $speed;
    public $course;
    public $timestamp;

    public function __construct($latitude, $longitude){
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public static function createFromServerArray($array){
        $a = new Location($array['latitude'], $array['longitude']);
        foreach($array as $key => $value){
            switch($key){
                case 'horizontalAccuracy':
                    $a->horizontalAccuracy = $value;
                    break;
                case 'verticalAccuracy':
                    $a->verticalAccuracy = $value;
                    break;
                case 'altitude':
                    $a->altitude = $value;
                    break;
                case 'speed':
                    $a->speed = $value;
                    break;
                case 'course':
                    $a->course = $value;
                    break;
                case 'timestamp':
                    $a->timestamp = (new DateTime())->setTimestamp($value / 1000);
                    break;
            }
        }
        return $a;
    }

    public function toServerArray(){
        $a = array();
        $a['latitude'] = $this->latitude;
        $a['longitude'] = $this->longitude;
        return $a;
    }
}
