<?php

namespace App;


interface CircularReferableInterface
{

    public function representCircularDependency($format, $context);

}