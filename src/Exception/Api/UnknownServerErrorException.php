<?php

namespace App\Exception\Api;

class UnknownServerErrorException extends BaseApiException
{
    public $reason     = 'Unknown error';
    public $statusCode = 520;
    public $errorCode  = 520;
}