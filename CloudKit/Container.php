<?php
/**
 * Created by PhpStorm.
 * User: malhal
 * Date: 04/04/2016
 * Time: 13:26
 */

namespace CloudKit;

class Container
{
    private $containerID;
    private $keyID;
    private $environment;
    private $privatePEM;
    private $publicCloudDatabase;

    public function __construct($containerID, $keyID, $privatePEM, $environment) {
        $this->containerID = $containerID;
        $this->keyID = $keyID;
        $this->privatePEM = $privatePEM;
        $this->environment = $environment;
    }

    public function getKeyID(){
        return $this->keyID;
    }

    public function getEnvironment(){
        return $this->environment;
    }

    public function getPrivatePEM(){
        return $this->privatePEM;
    }

    public function getContainerID(){
        return $this->containerID;
    }

    public function getPublicCloudDatabase(){
        if(!$this->publicCloudDatabase){
            $this->publicCloudDatabase = new Database($this, 'public');
        }
        return $this->publicCloudDatabase;
    }
}