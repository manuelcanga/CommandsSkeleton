<?php
declare( strict_types=1 );

namespace MyCommands\Infrastructure;

use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

/**
 * Class MyCommandsCommand. Abstraction for app commands. This can be used as dependencies container.
 */
class AppCommand extends Command
{
    /**
     * A basic factory for MyCommands commands.
     *
     * @var AppFactory
     */
    protected AppFactory $app;
    /**
     * A basic shortcut to input terminal.
     *
     * @var InputInterface
     */
    protected InputInterface $input;
    /**
     * A basic shortcut to output terminal.
     *
     * @var OutputInterface
     */
    protected OutputInterface $output;
    /**
     * A basic shortcut to terminal questions.
     *
     * @var \Framework\TerminalQuestions
     */
    protected TerminalQuestions $questions;

    /**
     * Initialize command.
     *
     * @param AppFactory $app
     *
     * @return void
     * @throws \Exception
     */
    public function __construct()
    {
        $this->app = new AppFactory();

        parent::__construct();
    }

    /**
     * Add App Factory.
     *
     * @param AppFactory $app
     *
     * @return void
     */
    public function addApp( AppFactory $app )
    {
        $this->app = $app;
    }

    /**
     * Common middleware for commands.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws Exception
     */
    public function run( InputInterface $input, OutputInterface $output ): int
    {
        $this->input = $input;
        $this->output = $output;
        $this->questions = $this->app->getTerminalQuestions( $input, $output, $this->getHelperSet() );

        try {
            return parent::run( $input, $output );
        } catch ( Throwable $error ) {
            $output->writeln( $error->getMessage() );

            return self::FAILURE;
        } finally {
            $this->app->getStore()->save();
        }
    }

    /**
     * Clear terminal screen.
     *
     * @return void
     */
    protected function clearScreen()
    {
        $this->output->write( sprintf( "\033\143" ) );
    }
}