<?php

namespace App\Component\VarDumper;

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

class VarDumperService
{
    /**
     * @var VarCloner
     */
    private $cloner;

    /**
     * @var CliDumper
     */
    private $dumper;

    public function __construct()
    {
        $this->cloner = new VarCloner();
        $this->dumper = new CliDumper();
    }

    /**
     * @param $value
     * @return null|string
     */
    public function dump($value)
    {
        return $this->dumper->dump($this->cloner->cloneVar($value), true);
    }
}