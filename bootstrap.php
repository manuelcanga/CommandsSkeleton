<?php declare( strict_types = 1 );

namespace MyCommands;

// Basic constants
define( __NAMESPACE__ . '\APP_PATH', __DIR__ );

const COMMANDS_NAMESPACE = __NAMESPACE__ . '\Command\\';
const COMMANDS_PATH      = APP_PATH . '/src/MyCommands/Command';

require_once APP_PATH . '/vendor/autoload.php';
