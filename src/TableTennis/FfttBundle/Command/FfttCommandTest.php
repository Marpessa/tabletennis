<?php

namespace TableTennis\FfttBundle\Command;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use TableTennis\FfttBundle\Command\FfttCommand;

class FfttCommandTest
//extends \PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $kernel = new \AppKernel("test", true);
        $kernel->boot();

        // mockez le Kernel ou créez en un selon vos besoins
        $application = new Application($kernel);
        $application->add(new FfttCommand());

        $command = $application->find('cron:fftt');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array('command' => $command->getName()));

        $display = $commandTester->getDisplay();

        //$this->assertRegExp('/.../', $display);

        return $display;
    }
}