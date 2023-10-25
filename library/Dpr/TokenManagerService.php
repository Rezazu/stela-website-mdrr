<?php

use Ahc\Jwt\JWT;

class Dpr_TokenManagerService
{
    public function __construct()
    {
        // get jwt from config app
        $this->config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        // 8 jam
        $this->maxAge = 28800;
        // Instantiate with key, algo, maxAge and leeway.
        $this->jwt = new JWT($this->config->JWT->KEY, 'HS256', $this->maxAge, 10);
    }

    public function encode($payload)
    {
        return $this->jwt->encode($payload);
    }

    public function decode($token)
    {
        try {
            return $this->jwt->decode($token);
        } catch (Exception $e) {
            throw $e;
        }
    }
}
