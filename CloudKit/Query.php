<?php

/**
 * Query.php
 * Copyright 2016-2021, Malcolm Hall, Timothy Oliver. All rights reserved.
 * Licensed under the MIT License. Please see the LICENSE file for the full license text.
 */

namespace CloudKit;

/**
 * Represents CKQuery, a query that describes the criteria to apply when searching for records in a database.
 * https://developer.apple.com/documentation/cloudkit/ckquery/
 */
class Query
{
    protected string $recordType;
    protected array $filters;
    protected array $sorters;

    protected array $operators = ['=' => 'EQUALS',
                                 '!=' => 'NOT_EQUALS',
                                 '<' => 'LESS_THAN',
                                 '<=' => 'LESS_THAN_OR_EQUALS',
                                 '>' => 'GREATER_THAN',
                                 '>=' => 'GREATER_THAN_OR_EQUALS'];

    public function __construct($recordType)
    {
        $this->recordType = $recordType;
    }

    public function getRecordType(): string
    {
        return $this->recordType;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }

    public function getSorters(): array
    {
        return $this->sorters;
    }

    protected function invalidOperatorAndValue($operator, $value): bool
    {
        $isOperator = in_array($operator, $this->operators);
        return ($isOperator && $operator != '=' && is_null($value));
    }

    public function filter($fieldName, $operator = null, $value = null): Query
    {
        if (func_num_args() == 2) {
            list($value, $operator) = array($operator, '=');
        } elseif ($this->invalidOperatorAndValue($operator, $value)) {
            throw new \InvalidArgumentException("Value must be provided.");
        }

        $this->filters[] = ['comparator' => $this->operators[$operator],
                            'fieldName' => $fieldName,
                            'fieldValue' => ['value' => $value]
                            ];
        return $this;
    }

    public function filterIn($fieldName, $values, $not = false): Query
    {
        $this->filters[] = ['comparator' => ($not ? 'NOT_IN' : 'IN'),
            'fieldName' => $fieldName,
            'fieldValue' => ['value' => $values]
        ];
        return $this;
    }

    public function filterNotIn($fieldName, $values): Query
    {
        return $this->filterIn($fieldName, $values, true);
    }

    public function filterNear()
    {
        //todo
        return $this;
    }

    public function filterContainsAllTokens($fieldName, $tokens, $any = false)
    {
        // todo
        return $this;
    }

    public function filterContainsAnyTokens($fieldName, $tokens)
    {
        return $this->filterContainsAllTokens($fieldName, $tokens, true);
    }

    public function filterListContains($fieldName, $values, $not = false, $any = false)
    {
        // todo
        return $this;
    }

    public function filterNotListContains($fieldName, $values)
    {
        return $this->filterListContains($fieldName, $values, true);
    }

    public function filterNotListContainsAny($fieldName, $values)
    {
        return $this->filterListContains($fieldName, $values, true, true);
    }

    public function filterBeginsWith($fieldName, $value, $not = false)
    {
        //todo
        return $this;
    }

    public function filterNotBeginsWith($fieldName, $value)
    {
        return $this->filterBeginsWith($fieldName, $value, true);
    }

    public function filterListMemberBeginsWith($fieldName, $value, $not)
    {
        //todo
        return $this;
    }

    public function filterNotListMemberBeginsWith($fieldName, $value)
    {
        return $this->filterListMemberBeginsWith($fieldName, $value, true);
    }

    public function filterListContainsAll($fieldName, $values, $not = false)
    {
        //todo
        return $this;
    }

    public function filterNotListContainsAll($fieldName, $value)
    {
        return $this->filterListContainsAll($fieldName, $value, true);
    }

    public function toServerArray()
    {
        $a = array();
        $a['recordType'] = $this->recordType;
        $a['filterBy'] = $this->filters;
        $a['sortBy'] = $this->sorters;
        return $a;
    }
}
