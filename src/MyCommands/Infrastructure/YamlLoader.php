<?php declare( strict_types = 1 );

namespace MyCommands\Infrastructure;

use Symfony\Component\Config\Exception\FileLocatorFileNotFoundException;
use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlLoader
 */
class YamlLoader extends FileLoader {
    /**
     * @inheritDoc
     */
    public function load( mixed $resource, string $type = null ): array {
        try {
            $resource = $this->getLocator()->locate( $resource );
        } catch ( FileLocatorFileNotFoundException ) {
            return [];
        }

        return Yaml::parse( file_get_contents( $resource ) );
    }

    /**
     * @inheritDoc
     */
    public function supports( mixed $resource, string $type = null ): bool {
        return is_string( $resource ) && 'yaml' === pathinfo(
                $resource,
                PATHINFO_EXTENSION
            );
    }
}