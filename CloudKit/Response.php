<?php

/**
 * Response.php
 * Copyright 2016-2021, Malcolm Hall, Timothy Oliver. All rights reserved.
 * Licensed under the MIT License. Please see the LICENSE file for the full license text.
 */

namespace CloudKit;

/**
 * A base class that parses the common elements in a response from CloudKit
 * to a request that was made from this library.
 */
class Response
{
    protected array $errors;

    public function __construct($array)
    {
        if (array_key_exists('serverErrorCode', $array)) {
            $this->errors = [new Error($array)];
        }
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
