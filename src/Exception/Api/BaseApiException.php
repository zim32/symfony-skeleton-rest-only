<?php

namespace App\Exception\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BaseApiException extends HttpException
{
    public $statusCode; // HTTP status code
    public $errorCode; // internal error code
    public $reason;
    public $payload;

    public function __construct($payload = null, \Throwable $previous = null, array $headers = [])
    {
        parent::__construct($this->statusCode, $this->reason, $previous, $headers, $this->errorCode);

        $this->payload = $payload;
    }
}