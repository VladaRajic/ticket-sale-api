<?php

namespace App\Exceptions;

class ApiException extends \Exception
{
    public int $apiCode = 0;
    public function __construct(
        $message = "",
        $code = 0,
        int $apiCode = 0,
    ) {
        parent::__construct($message, $code);
        $this->apiCode = $apiCode;
    }
}
