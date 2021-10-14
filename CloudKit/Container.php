<?php

/**
 * Container.php
 * Copyright 2016-2021, Malcolm Hall, Timothy Oliver. All rights reserved.
 * Licensed under the MIT License. Please see the LICENSE file for the full license text.
 */

namespace CloudKit;

/**
 * Represents a CKContainer, the top-level object that represents a single CloudKit instance in iCloud.
 * Contains the public and private databases that store data on behalf of one or more applications
 * vended by the same developer account.
 * https://developer.apple.com/documentation/cloudkit/ckcontainer
 */

namespace CloudKit;

class Container
{
    private string $containerID;
    private string $keyID;
    private string $environment;
    private string $privatePEM;
    private Database $publicCloudDatabase;

    public function __construct($containerID, $keyID, $privatePEM, $environment)
    {
        $this->containerID = $containerID;
        $this->keyID = $keyID;
        $this->privatePEM = $privatePEM;
        $this->environment = $environment;
    }

    public function getKeyID(): string
    {
        return $this->keyID;
    }

    public function getEnvironment(): string
    {
        return $this->environment;
    }

    public function getPrivatePEM(): string
    {
        return $this->privatePEM;
    }

    public function getContainerID(): string
    {
        return $this->containerID;
    }

    public function getPublicCloudDatabase(): Database
    {
        if ($this->publicCloudDatabase) {
            $this->publicCloudDatabase = new Database($this, 'public');
        }
        return $this->publicCloudDatabase;
    }
}
