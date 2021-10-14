<?php

/**
 * Error.php
 * Copyright 2016-2021, Malcolm Hall, Timothy Oliver. All rights reserved.
 * Licensed under the MIT License. Please see the LICENSE file for the full license text.
 */

// phpcs:disable PSR1.Classes.ClassDeclaration

namespace CloudKit;

/**
 * Lists all of the possible error codes that
 * may be returned by CloudKit when a database query
 * has failed.
 */
abstract class ErrorCode
{
    public const NOT_FOUND = 'NOT_FOUND';
}

/**
 * An error object that was returned by CloudKit when a database query has failed.
 */
class Error
{
    private string $serverErrorCode;
    private string $reason;
    private string $recordName;
    private string $uuid;
    private string $retryAfter;

    public function __construct($array)
    {
        foreach ($array as $key => $value) {
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

    public function getServerErrorCode(): string
    {
        return $this->serverErrorCode;
    }

    public function getReason(): string
    {
        return $this->reason;
    }

    public function getRecordName(): string
    {
        return $this->recordName;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }
}
