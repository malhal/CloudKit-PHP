<?php

/**
 * Location.php
 * Copyright 2016-2021, Malcolm Hall, Timothy Oliver. All rights reserved.
 * Licensed under the MIT License. Please see the LICENSE file for the full license text.
 */

namespace CloudKit;

use DateTime;

/**
 * Represents a CLLocation, used to encode and decode GPS location coordinates to a CloudKit record.
 * https://developer.apple.com/documentation/corelocation/cllocation
 */
class Location
{
    public float $latitude;
    public float $longitude;
    public string $horizontalAccuracy;
    public string $verticalAccuracy;
    public string $altitude;
    public string $speed;
    public string $course;
    public DateTime $timestamp;

    public function __construct($latitude, $longitude)
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }

    public static function createFromServerArray($array)
    {
        $a = new Location($array['latitude'], $array['longitude']);
        foreach ($array as $key => $value) {
            switch ($key) {
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

    public function toServerArray()
    {
        $a = array();
        $a['latitude'] = $this->latitude;
        $a['longitude'] = $this->longitude;
        return $a;
    }
}
