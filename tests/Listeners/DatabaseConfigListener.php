<?php

declare(strict_types=1);

namespace App\Tests\Listeners;

use App\Kernel;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestListenerDefaultImplementation;
use PHPUnit\Framework\TestSuite;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\ConsoleOutput;

class DatabaseConfigListener implements TestListener
{
    private $application;

    use TestListenerDefaultImplementation;

    public function startTestSuite(TestSuite $suite): void
    {
        if ('Project Test Suite' === $suite->getName()) {
//            $this->executeCommand('doctrine:database:drop --verbose --force --if-exists');
//            $this->executeCommand('doctrine:database:create --verbose');
//            $this->executeCommand('doctrine:migrations:migrate --quiet');
        }
    }

    private function executeCommand(string $command): void
    {
        if (null === $this->application) {
            $this->application = new Application(new Kernel(getenv('APP_ENV'), true));
            $this->application->setAutoExit(false);
        }
        $input = new StringInput($command);
        $input->setInteractive(false);
        $output = new ConsoleOutput();
        $this->application->run($input, $output);
    }
}
