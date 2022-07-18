<?php declare( strict_types = 1 );

namespace MyCommands\Infrastructure;

use const MyCommands\APP_PATH;

/**
 * Class SessionStore
 */
class SessionStore {
    private const DEFAULLT_SESSION_STORE_FILE = APP_PATH . '/var/cache/session.json';
    private array $store;
    private string $session_store_file;

    /**
     * SessionStore constructor.
     */
    public function __construct( array $store = [] ) {
        $this->store = $store;
    }

    /**
     * Start a session or retrieving one started session.
     *
     * @param bool   $force_new_instance Useful for tests.
     * @param string $session_store_file Useful for tests.
     *
     * @return SessionStore
     */
    public static function persistence( bool $force_new_instance = false, string $session_store_file = self::DEFAULLT_SESSION_STORE_FILE ): SessionStore {
        static $instance;

        if ( ! $force_new_instance && isset( $instance ) ) {
            return $instance;
        }

        $instance                             = new self();
        $instance->session_store_file         = $session_store_file;
        $instance->store                      = $instance->load();

        return $instance;
    }

    /**
     * Retrieve a session data.
     *
     * @param string $field
     *
     * @return mixed
     */
    public function get( string $field ) {
        if ( \array_key_exists( $field, $this->store ) ) {
            return $this->store[ $field ];
        }

        return null;
    }

    /**
     * Retrieve all session values.
     *
     * @return array
     */
    public function get_all(): array {
        return $this->store;
    }

    /**
     * Set a value.
     *
     * @param string $field
     * @param mixed  $value
     *
     * @return void
     */
    public function set( string $field, $value ): void {
        $this->store[ $field ] = $value;
    }

    /**
     * Unset a value.
     *
     * @param string $field
     *
     * @return void
     */
    public function unset( string $field ): void {
        unset( $this->store[ $field ] );
    }

    /**
     * Reset session.
     *
     * @return void
     */
    public function reset(): void {
        $this->store = [];
    }

    /**
     * Restore session data.
     *
     * @return array
     */
    public function load(): array {
        if ( ! file_exists( $this->session_store_file ) ) {
            return [];
        }

        $data_in_file = file_get_contents( $this->session_store_file ) ?: [];

        return json_decode( $data_in_file, associative: true );
    }

    /**
     * Save session data to file.
     *
     * @return void
     */
    public function save(): void {
        file_put_contents( $this->session_store_file, json_encode( $this->store, JSON_PRETTY_PRINT ) );
    }
}