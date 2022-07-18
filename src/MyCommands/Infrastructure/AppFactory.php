<?php declare( strict_types = 1 );

namespace MyCommands\Infrastructure;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use const MyCommands\APP_PATH;

/**
 * App Factory.
 */
class AppFactory {
    /**
     * PATH to yaml config files.
     */
    public const CONFIG_PATH = APP_PATH . '/config';
    private array $env_data;

    /**
     * App arguments
     *
     * @param array $env_data
     */
    public function __construct( array $env_data = [] ) {
        $this->env_data = $env_data;
    }

    /**
     * Retrieve local repo project.
     *
     * @return UserEnvironment
     */
    public function getUserEnvironment(): UserEnvironment {
        return new UserEnvironment( $this->env_data );
    }

    /**
     * Retrieve an instance of Session store.
     *
     * @return SessionStore
     */
    public function getStore(): SessionStore {
        return SessionStore::persistence();
    }

    /**
     * Retrieve an instance of PHPMailer.
     *
     * @return \PHPMailer\PHPMailer\PHPMailer
     * @throws \PHPMailer\PHPMailer\Exception
     */
    public function newEmail( string $to_add ): PHPMailer {
        $email_settings = $this->getConfig()->getNotifications()['smtp'] ?? [];

        $email             = new PHPMailer();
        $email->SMTPDebug  = SMTP::DEBUG_OFF;
        $email->Host       = $email_settings['host'];
        $email->Port       = $email_settings['port'];
        $email->Username   = $email_settings['user'];
        $email->Password   = $email_settings['pass'];
        $email->SMTPAuth   = (bool) $email_settings['auth'];
        $email->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $email->CharSet    = 'UTF-8';
        $email->setFrom( $email_settings['from_email'], $email_settings['from_name'] );
        $email->addAddress( "$to_add" );
        $email->isHTML( true );
        $email->isSMTP();

        return $email;
    }

    /**
     * Retrieve a Config loader in order to retrieve app config.
     *
     * @param string $config_path
     * @param bool   $cache_is_used Allow to specific if cache is used or not.
     *
     * @return AppConfig
     */
    public function getConfig( string $config_path = self::CONFIG_PATH, bool $cache_is_used = true ): AppConfig {
        if ( ! file_exists( $config_path ) ) {
            throw new \LogicException( "Not config directory[<error>{$config_path}</error>] was found" );
        }

        $file_locator = new FileLocator( [ $config_path ] );
        $loader       = new YamlLoader( $file_locator );

        return new AppConfig( $loader, $cache_is_used );
    }

    /**
     * Helper constructor.
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \Symfony\Component\Console\Helper\HelperSet       $helper_set
     *
     * @return TerminalQuestions
     */
    public function getTerminalQuestions( InputInterface $input, OutputInterface $output, ?HelperSet $helper_set ): TerminalQuestions {
        // Helper_set can be null in tests.
        $helper_set = $helper_set ?: new HelperSet( [] );

        return new TerminalQuestions( $input, $output, $helper_set );
    }

    /**
     * Instance a class using arguments.
     *
     * @param string $class_name
     * @param mixed  ...$arguments
     *
     * @return object instance of required class
     */
    public function instance( string $class_name, ...$arguments ): object {
        if ( 0 === count( $arguments ) ) {
            return new $class_name();
        }

        return new $class_name( ...$arguments );
    }
}