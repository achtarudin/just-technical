<?php

namespace App\Exceptions;

use Exception;

class ApiV1Exception extends Exception
{
    protected $data;

    public function __construct($message, $code, $data = [])
    {
        parent::__construct($message, $code);
        $this->data = $data;
    }

    public function getDataOption() {
        return $this->data;
    }

}
