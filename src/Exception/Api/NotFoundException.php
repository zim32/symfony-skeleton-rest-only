<?php

namespace App\Exception\Api;

class NotFoundException extends BaseApiException
{
    public $reason     = 'Item not found';
    public $statusCode = 404;
    public $errorCode  = 404;
}