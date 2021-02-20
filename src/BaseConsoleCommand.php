<?php

namespace App;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class BaseConsoleCommand extends Command
{
    /**
     * @var InputInterface
     */
    protected $input;

    /**
     * @var OutputInterface
     */
    protected $output;

    /**
     * @var InputArgument
     */
    protected $stdIn;

    /**
     * @var OutputInterface;
     */
    protected $stdOut;

    /**
     * @var OutputInterface
     */
    protected $stdErr;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // for backward compatibility
        $this->input  = $input;
        $this->output = $output;

        $this->stdIn  = $input;
        $this->stdOut = $output;

        if ($output instanceof ConsoleOutputInterface) {
            $this->stdErr = $output->getErrorOutput();
        }
    }

    protected function writeStdOut(string $message)
    {
        if ($this->stdOut->isQuiet()) {
            return;
        }

        $this->stdOut->writeln($message);
    }

    protected function writeStdErr(string $message)
    {
        $this->stdErr->writeln($message);
    }

}