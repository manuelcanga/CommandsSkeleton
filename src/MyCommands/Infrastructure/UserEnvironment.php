<?php declare( strict_types = 1 );

namespace MyCommands\Infrastructure;

/**
 * Class UserEnvironment. Information about current local environment of user.
 */
class UserEnvironment {
    private array $metadata = [];

    /**
     * UserEnvironment construct.
     *
     * @param array $metadata
     *
     * @return void
     */
    public function __construct( array $metadata = [] ) {
        $this->metadata = $metadata;
    }

    /**
     * Retrieve current project directory
     *
     * @return string
     */
    public function getEnvironmentDir(): string {
        return $this->metadata['CURRENT_DIR'] ?? '';
    }
}