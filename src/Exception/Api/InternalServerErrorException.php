<?php

namespace App\Exception\Api;

class InternalServerErrorException extends BaseApiException
{
    public $statusCode = 520;
    public $errorCode  = 520;

    public function __construct(string $reason, $payload = null, \Throwable $previous = null, array $headers = [])
    {
        parent::__construct($payload, $previous, $headers);

        $this->reason = $reason;
    }

}