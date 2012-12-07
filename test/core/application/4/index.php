<?php

$staq_path = substr( __DIR__, 0, strrpos( __DIR__, '/Staq/' ) + 5 );
require_once( $staq_path . '/util/tests.php' );
include_once( $staq_path . '/include.php' );

// CONTEXT
$path = substr( __DIR__, strrpos( __DIR__, '/Staq/' ) + 1 );
$app = new \Staq\Application( $path );

// TEST COLLECTION
$case = new \Staq\Util\Test_Case( 'Extends project without configuration', [
	'Extensions' => function( ) use ( $app, $path ) {
		return ( $app->get_extensions( ) == [ $path, 'Staq/unexisting', 'Staq/starter', 'Staq/view', 'Staq/ground' ] );
	}
] );

// RESULT
echo $case->to_html( );
return $case;