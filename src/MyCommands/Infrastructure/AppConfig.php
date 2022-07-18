<?php
declare( strict_types=1 );

namespace MyCommands\Infrastructure;

use Exception;
use Symfony\Component\Config\Loader\FileLoader;

/**
 * Class AppConfig
 */
class AppConfig
{
    private const CONFIG_FILE_EXTENSION = '.yaml';
    private const USER_CONFIG = 'user';
    private const USER_CONFIG_TEMPLATE = 'templates/user-dist';
    private FileLoader $loader;
    private bool $cache_is_used;

    /**
     * AppConfig constructor.
     *
     * @param FileLoader $file_loader
     * @param bool       $cache_is_used Allow to specific if cache is used or not.
     *
     * @return void
     */
    public function __construct( FileLoader $file_loader, bool $cache_is_used = true )
    {
        $this->loader = $file_loader;
        $this->cache_is_used = $cache_is_used;
    }
    
    /**
     * Retrieve specific config
     *
     * @return array
     * @throws Exception
     */
    public function getSpecificConfig(): array
    {
        static $specific_config;

        if( isset( $specific_config ) && $this->cache_is_used ) {
            return $specific_config;
        }

        return $specific_config = $this->getConfig( 'specific_config' );
    }

    /**
     * Retrieve email notifications config.
     *
     * @return array
     * @throws \Exception
     */
    public function getNotifications(): array
    {
        return $this->getConfig( 'notifications' );
    }

    /**
     * Retrieve config property related to a config.
     *
     * @param string $config_property
     *
     * @return array
     * @throws Exception
     */
    private function getConfig( string $config_property ): array
    {
        $config = ( $this->getUser()[ $config_property ] ?? [] ); // Custom config for current user.
        $config += $this->getFromConfigFile( 'app/' . $config_property ); //Common config for all users
        $config += ( $this->getFromConfigFile( self::USER_CONFIG_TEMPLATE )[ $config_property ] ?? [] ); // Default user config

        return $config ?: [];
    }

    /**
     * Retrieve information from a config file.
     *
     * @param string $filename Filename of config file.
     *
     * @return array
     * @throws Exception
     */
    public function getFromConfigFile( string $filename ): array
    {
        $filename = str_replace( self::CONFIG_FILE_EXTENSION, '', $filename );

        return $this->loader->load( $filename . self::CONFIG_FILE_EXTENSION ) ?: [];
    }

    /**
     * Retrieve user config.
     *
     * @return array<string, string>
     * @throws Exception
     */
    public function getUser(): array
    {
        static $user;

        if( isset( $user ) && $this->cache_is_used ) {
            return $user;
        }

        $user = $this->getFromConfigFile( self::USER_CONFIG );
        $user_template = $this->getFromConfigFile( self::USER_CONFIG_TEMPLATE );

        return array_replace( $user_template, $user );
    }
}