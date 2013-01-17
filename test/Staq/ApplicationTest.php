<?php

namespace Test\Staq;

require_once( __DIR__ . '/../../vendor/autoload.php' );

class Application_Test extends \PHPUnit_Framework_TestCase {

	// Without custom application
	public function test_without_custom_setting( ) {
		$app = \Staq\Application::create( );
		return ( $app->get_extensions( 'name' ) == [ 'Staq\App\Starter', 'Staq\Core\Router', 'Staq\Core\Ground' ] );
	}
}