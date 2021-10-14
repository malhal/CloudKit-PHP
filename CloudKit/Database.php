<?php

/**
 * Database.php
 * Copyright 2016-2021, Malcolm Hall, Timothy Oliver. All rights reserved.
 * Licensed under the MIT License. Please see the LICENSE file for the full license text.
 */

namespace CloudKit;

/**
 * Represents a CKDatabase, a collection of record zones and subscriptions.
 * Databases can be either public, and accessible by all users, or private, and
 * accessible by only a single iCloud account.
 * https://developer.apple.com/documentation/cloudkit/ckdatabase
 */
class Database
{
    private Container $container;
    private string $type;

    public function __construct(Container $container, $type)
    {
        $this->container = $container;
        $this->type = $type;
    }

    public function fetchRecords($recordNames, $options = array())
    {
        $records = array();
        foreach ($recordNames as $recordName) {
            $records[] = ['recordName' => $recordName];
        }
        $postArray = array_merge(['records' => $records], $options);

        $r = $this->request('lookup', $postArray);
        return new RecordsResponse($r);
    }

    public function performQuery(Query $query, $options = array())
    {
        $postArray = array_merge(['query' => $query->toServerArray()], $options);
        $r = $this->request('query', $postArray);
        $response = new QueryResponse($r);
        return $response;
    }

    public function saveRecords($records)
    {
        $operations = array();
        foreach ($records as $record) {
            $recordArray = $record->toServerArray();
            $operationType = null;
            if (array_key_exists('recordChangeTag', $recordArray)) {
                $operationType = 'update';
            } else {
                $operationType = 'create';
            }
            $operationArray = ['record' => $recordArray, 'operationType' => $operationType];
            $operations[] = $operationArray;
        }
        $postArray = ['operations' => $operations];
        $r = $this->request('modify', $postArray);
        return new RecordsResponse($r);
    }

    public function request($requestType, $postArray)
    {
        // Constants
        $KEY_ID                 = $this->container->getKeyID();
        $CONTAINER              = $this->container->getContainerID();
        $PRIVATE_PEM_LOCATION   = $this->container->getPrivatePEM(); // Store this file outside the public folder!
        $Environment = $this->container->getEnvironment();
        $result = null;

         // Config
        $url = 'https://api.apple-cloudkit.com/database/1/' . $CONTAINER . '/'
                                                            . $Environment . '/'
                                                            . $this->type . '/records/'
                                                            . $requestType;
        $body = json_encode($postArray);

        // Set cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // Create signature
        date_default_timezone_set('UTC');
        $explode_date = explode('+', date("c", time()));
        $time = $explode_date[0] . 'Z';
        $signature = $time . ":" . base64_encode(hash("sha256", $body, true)) . ":" . explode('cloudkit.com', $url)[1];

        // Get private key
        $pkeyid = openssl_pkey_get_private("file://" . $PRIVATE_PEM_LOCATION);

        // Sign signature with private key
        if (openssl_sign($signature, $signed_signature, $pkeyid, "sha256WithRSAEncryption")) {
            // Set headers
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                [
                    "Content-Type: text/plain",
                    "X-Apple-CloudKit-Request-KeyID: " . $KEY_ID,
                    "X-Apple-CloudKit-Request-ISO8601Date: " . $time,
                    "X-Apple-CloudKit-Request-SignatureV1: " . base64_encode($signed_signature),
                ]
            );

            // Set body
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

            // Send the request & save response to $resp
            $resp = curl_exec($ch);
            if ($resp === false) {
                die('Error: "' . curl_error($ch) . '" - Code: ' . curl_errno($ch));
            } else {
                //echo $resp;
                $result = json_decode($resp, true);
            }
            curl_close($ch);
        } else {
            while ($msg = openssl_error_string()) {
                echo $msg . "<br />\n";
            }
        }
        return $result;
    }
}
