<?php

namespace App\Exception\Api;

use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends BaseApiException
{
    public $reason     = 'Validation error';
    public $statusCode = 400;
    public $errorCode  = 400;

    /**
     * ValidationException constructor.
     * @param ConstraintViolationListInterface|ConstraintViolation[]|array $errors
     * @param \Throwable|null $previous
     * @param array $headers
     */
    public function __construct($errors, \Throwable $previous = null, array $headers = [])
    {
        $payload = [];

        if ($errors instanceof ConstraintViolationListInterface) {
            foreach ($errors as $error) {
                if (!array_key_exists($error->getPropertyPath(), $payload)) {
                    $payload[$error->getPropertyPath()] = [];
                }

                $payload[$error->getPropertyPath()][] = $error->getMessage();
            }
        } else {
            $payload = $errors;
        }

        parent::__construct($payload, $previous, $headers);
    }

}
