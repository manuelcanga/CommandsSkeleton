<?php declare( strict_types = 1 );

namespace MyCommands\Command;

use MyCommands\Infrastructure\AppCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class EchoNameCommand
 * @execute this command with:
 *          `my_commands debug:echo_name pepito` or
 *          `docker exec -ti my_commands debug:echo_name pepito`
 */
#[AsCommand(
    name: 'debug:echo_name',
    description: 'This is a silly template command which echo a name.',
)]
final class EchoNameCommand extends AppCommand {
    /**
     * In this method setup command, description, and its parameters
     *
     * @return void
     */
    protected function configure(): void {
        $this->addArgument( 'name', InputArgument::REQUIRED, 'Name must be passed' );
    }

    /**
     * Here all logic happens
     *
     * @return integer
     */
    protected function execute( InputInterface $input, OutputInterface $output ): int {
        $name = $input->getArgument( 'name' );

        $output->writeln( "Hey, {$name}!. Nice to meet you!" );

        return self::SUCCESS;
    }
}