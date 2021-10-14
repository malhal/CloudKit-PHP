<?php

/**
 * RecordResponse.php
 * Copyright 2016-2021, Malcolm Hall, Timothy Oliver. All rights reserved.
 * Licensed under the MIT License. Please see the LICENSE file for the full license text.
 */

namespace CloudKit;

/**
 * A response to a CloudKit records request.
 */
class RecordsResponse extends Response
{
    private array $records;

    public function __construct($array)
    {
        parent::__construct($array);
        if (array_key_exists('records', $array)) {
            foreach ($array['records'] as $r) {
                if (array_key_exists('serverErrorCode', $r)) {
                    $this->errors[] = new Error($r);
                } else {
                    $this->records[] = Record::createFromServerArray($r);
                }
            }
        }
    }

    public function getRecords()
    {
        return $this->records;
    }
}
