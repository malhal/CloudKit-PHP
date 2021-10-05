<?php

/**
 * Reference.php
 * Copyright 2016-2021, Malcolm Hall, Timothy Oliver. All rights reserved.
 * Licensed under the MIT License. Please see the LICENSE file for the full license text.
 */

 // phpcs:disable PSR1.Classes.ClassDeclaration

namespace CloudKit;

/**
 * Constants that indicate the behavior when deleting a referenced record.
 */
abstract class Action
{
    /* A reference action that has no cascading behavior. */
    public const NONE = 'NONE';

    /* A reference action that cascades deletions. */
    public const DELETE_SELF = 'DELETE_SELF';
}

/**
 * Represents a CKReference, referring to another record object, allowing for many-to-one relationships.
 * https://developer.apple.com/documentation/cloudkit/ckreference
 */
class Reference
{
    private $recordName;
    private $action;

    public function __construct($recordName, $action)
    {
        $this->recordName = $recordName;
        $this->action = $action;
    }

    public static function createFromServerArray($array)
    {
        return new Reference($array['recordName'], $array['action']);
    }

    public function toServerArray()
    {
        $a = array();
        $a['recordName'] = $this->recordName;
        $a['action'] = $this->action;
        return $a;
    }
}
