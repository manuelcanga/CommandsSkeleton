<?php declare( strict_types = 1 );

namespace MyCommands\Tests\Commands;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use MyCommands\Command\EchoNameCommand;

/**
 * Class EchoNameTest
 * Execute this test with:
 * `my_commands tests`  or my_commands tests /app/tests/Commands/EchoNameTest.php
 */
class EchoNameTest extends KernelTestCase {
    /**
     * Test EchoNameCommand is exit successful
     *
     * @return void
     */
    public function testExecute(): void {
        $commandTester = new CommandTester( new EchoNameCommand( ) );
        $commandTester->execute( [ 'name' => 'pepito' ] );

        // This exit successful
        $commandTester->assertCommandIsSuccessful();
    }

    /**
     * Test EchoNameCommand display 'Hey, pepito!. Nice to meet you!\n' when 'pepito' is passed as name.
     *
     * @return void
     */
    public function testEchoMessageIsOK(): void {
        $commandTester = new CommandTester( new EchoNameCommand() );
        $commandTester->execute( [ 'name' => 'pepito' ] );

        $output = $commandTester->getDisplay();

        $this->assertSame( "Hey, pepito!. Nice to meet you!\n", $output );
    }
}