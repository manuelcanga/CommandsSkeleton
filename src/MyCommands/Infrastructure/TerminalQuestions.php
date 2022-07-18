<?php declare( strict_types = 1 );

namespace MyCommands\Infrastructure;

use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TerminalQuestions
 */
class TerminalQuestions {
    private InputInterface $input;
    private OutputInterface $output;
    private HelperSet $helperSet;

    /**
     * Helper constructor.
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \Symfony\Component\Console\Helper\HelperSet       $helper_set
     *
     * @return void
     */
    public function __construct( InputInterface $input, OutputInterface $output, HelperSet $helper_set ) {
        $this->input     = $input;
        $this->output    = $output;
        $this->helperSet = $helper_set;
    }

    /**
     * Gets a helper instance by name.
     *
     * @throws LogicException           if no HelperSet is defined
     * @throws InvalidArgumentException if the helper is not defined
     */
    public function getHelper( string $name ): mixed {
        if ( null === $this->helperSet ) {
            throw new LogicException( sprintf( 'Cannot retrieve helper "%s" because there is no HelperSet defined. Did you forget to add your command to the application or to set the application on the command using the setApplication() method? You can also set the HelperSet directly using the setHelperSet() method.', $name ) );
        }

        return $this->helperSet->get( $name );
    }

}