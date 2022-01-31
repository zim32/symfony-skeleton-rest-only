<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function boot()
    {
        if (!defined('API_SCHEMA_URL') && array_key_exists('API_SCHEMA_URL', $_ENV)) {
            define('API_SCHEMA_URL', $_ENV['API_SCHEMA_URL']);
        }

        if (!defined('API_ENDPOINT_URL') && array_key_exists('API_ENDPOINT_URL', $_ENV)) {
            define('API_ENDPOINT_URL', $_ENV['API_ENDPOINT_URL']);
        }

        parent::boot();
    }


}
