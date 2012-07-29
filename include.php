<?php

/* This file is part of the Supersoniq project.
 * Supersoniq is a free and unencumbered software released into the public domain.
 * For more information, please refer to <http://unlicense.org/>
 */

session_start( );

// CONSTANTS
define( 'HTML_EOL', '<br>' . PHP_EOL ) ;
define( 'SUPERSONIQ_ROOT_PATH', dirname( dirname( __FILE__ ) ) . '/' );

// REQUIRE UTILS
$require_path = SUPERSONIQ_ROOT_PATH . 'Supersoniq/';
require_once( $require_path . 'utils.php' );

// REQUIRE KERNEL CLASSES
$require_path .= 'Kernel/internal/';
require_once( $require_path . 'Class_Name.php' );
require_once( $require_path . 'Autoloader.php' );
require_once( $require_path . 'Url.php' );
require_once( $require_path . 'Supersoniq.php' );

// REQUIRE KERNEL OVERRIDABLE OBJECT
$require_path = SUPERSONIQ_ROOT_PATH . 'Supersoniq/Kernel/object/';
require_once( $require_path . 'Settings.php' );
