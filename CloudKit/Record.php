<?php

/**
 * Record.php
 * Copyright 2016-2021, Malcolm Hall, Timothy Oliver. All rights reserved.
 * Licensed under the MIT License. Please see the LICENSE file for the full license text.
 */

namespace CloudKit;

use DateTime;
use ReflectionClass;

/**
 * Represents a CKRecord, a collection of key-value pairs that stores user data.
 * https://developer.apple.com/documentation/cloudkit/ckrecord
 */
class Record
{
    private string $recordType;
    private string $recordName;
    private string $recordChangeTag;
    private array $fields = array();
    private DateTime $createdAt;
    private DateTime $createdBy;
    private DateTime $modifiedAt;
    private string $modifiedBy;
    private string $deleted;
    private $zoneID;
    private $changedFields;
    private $exists = false;

    public function __construct($recordType, $recordName = null, $zoneID = null)
    {
        $this->recordType = $recordType;
        $this->recordName = $recordName;
        $this->zoneID = $zoneID;
    }

    public static function createFromServerArray($array)
    {
        $r = new Record($array['recordType']);
        $r->exists = true;
        foreach ($array as $key => $value) {
            switch ($key) {
                case 'recordName':
                    $r->recordName = $value;
                    break;
                case 'recordChangeTag':
                    $r->recordChangeTag = $value;
                    break;
                case 'deleted':
                    $r->deleted = $value;
                    break;
                case 'created':
                    $r->createdBy = $value['userRecordName'];
                    $r->createdAt = (new DateTime())->setTimestamp($value['timestamp'] / 1000);
                    break;
                case 'modified':
                    $r->modifiedBy = $value['userRecordName'];
                    $r->modifiedAt = (new DateTime())->setTimestamp($value['timestamp'] / 1000);
                    break;
                case 'fields':
                    foreach ($value as $fieldKey => $fieldValue) {
                        $ft = $fieldValue['type'];
                        $fv = $fieldValue['value'];
                        // convert the value where necessary.
                        switch ($ft) {
                            case 'REFERENCE':
                                $fv = Reference::createFromServerArray($fv);
                                break;
                            case 'LOCATION':
                                $fv = Location::createFromServerArray($fv);
                                break;
                            default:
                                break;
                        }
                        $r->fields[$fieldKey] = $fv;
                    }
                    break;
                default:
                    break;
            }
        }
        return $r;
    }

    public function getRecordName()
    {
        return $this->recordName;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getField($key)
    {
        return $this->fields[$key];
    }

    public function changedFields()
    {
        return $this->changedFields;
    }

    public function setField($key, $value)
    {
        $this->fields[$key] = $value;
        // store that this key was changed.
        if (!$this->changedFields || !in_array($key, $this->changedFields)) {
            $this->changedFields[] = $key;
        }
    }

    public function toServerArray()
    {
        $a = array();
        $a['recordName'] = $this->recordName;
        if ($this->exists) {
            // We only need to include the change tag if this record came from the server.
            $a['recordChangeTag'] = $this->recordChangeTag;
        } else {
            // We only need to include the type if this is a new record.
            $a['recordType'] = $this->recordType;
        }

        $fields = array();
        foreach ($this->fields as $key => $value) {
            $ft = null;
            $fv = null;

            if (is_object($value)) {
                $className = (new ReflectionClass($value))->getShortName();
                switch ($className) {
                    case 'Location':
                    case 'Reference':
                        $ft = strtoupper($className);
                        $fv = $value->toServerArray();
                        break;
                    case 'DateTime':
                        $ft = 'TIMESTAMP';
                        $fv = $value->getTimestamp() * 1000;
                        break;
                    default:
                        throw new \InvalidArgumentException("Invalid class $className for field $key in record.");
                        break;
                }
            } else {
                $fv = $value;
                switch (gettype($value)) {
                    case 'integer':
                        $ft = 'NUMBER_INT64';
                        break;
                    case 'double':
                        $ft = 'NUMBER_DOUBLE';
                        break;
                    case 'string':
                        $ft = 'STRING';
                        break;
                    default:
                        break;
                }
            }
            $fields[$key] = ['type' => $ft, 'value' => $fv];
        }

        if (count($fields)) {
            $a['fields'] = $fields;
        }

        return $a;
    }
}
