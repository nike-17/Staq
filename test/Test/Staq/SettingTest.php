<?php

namespace Test\Staq;

require_once( __DIR__ . '/../../../vendor/autoload.php' );

class SettingTest extends \PHPUnit_Framework_TestCase {



	/*************************************************************************
	 ATTRIBUTES
	 *************************************************************************/
	public $project_namespace = 'Test\\Staq\\Project\\Setting';



	/*************************************************************************
	  TEST METHODS             
	 *************************************************************************/
	public function test_unexisting_setting( ) {
		$app = \Staq\Application::create( $this->project_namespace );
		$setting = ( new \Stack\Setting )->parse( 'test' );
		$this->assertEquals( 'a_value', $setting[ 'test.a_setting' ] );
	}

	public function test_existing_setting__existing_key( ) {
		$app = \Staq\Application::create( $this->project_namespace );
		$setting = ( new \Stack\Setting )->parse( 'application' );
		$this->assertEquals( 0, $setting[ 'error.display_errors' ] );
	}

	public function test_existing_setting__custom_key( ) {
		$app = \Staq\Application::create( $this->project_namespace );
		$setting = ( new \Stack\Setting )->parse( 'application' );
		$this->assertEquals( 'a_value', $setting[ 'error.a_setting' ] );
	}

	public function test_existing_setting__inherit_key__extension( ) {
		$app = \Staq\Application::create( $this->project_namespace );
		$setting = ( new \Stack\Setting )->parse( 'application' );
		$this->assertEquals( 'E_STRICT', $setting[ 'error.error_reporting' ] );
	}

	public function test_existing_setting__inherit_key__platform( ) {
		$app = \Staq\Application::create( $this->project_namespace, '/', 'local' );
		$setting = ( new \Stack\Setting )->parse( 'application' );
		$this->assertEquals( 1, $setting[ 'error.display_errors' ] );
	}

	public function test_existing_setting__merged_key__platform( ) {
		$app = \Staq\Application::create( $this->project_namespace, '/', 'local' );
		$setting = ( new \Stack\Setting )->parse( 'test' );
		$this->assertEquals( [ 'a_value', 'more_value' ], $setting[ 'test.a_setting' ] );
	}

	public function test_stack_setting_file( ) {
		$app = \Staq\Application::create( $this->project_namespace );
		$stack = new \Stack\Controller;
		$setting = ( new \Stack\Setting )->parse( $stack );
		$this->assertEquals( 'empty', $setting[ 'view.layout' ] );
	}

	public function test_stack_setting_file__complex( ) {
		$app = \Staq\Application::create( $this->project_namespace );
		$stack = new \Stack\Controller\Unexisting;
		$setting = ( new \Stack\Setting )->parse( $stack );
		$this->assertEquals( 'bootstrap', $setting[ 'view.layout' ] );
	}

	public function test_stack_setting_file__inherit_class_name( ) {
		$app = \Staq\Application::create( $this->project_namespace );
		$stack = new \Stack\Controller\Unexisting;
		$setting = ( new \Stack\Setting )->parse( $stack );
		$this->assertEquals( 'coco', $setting[ 'view.title' ] );
	}

	public function test_stack_setting_attribute( ) {
		$app = \Staq\Application::create( $this->project_namespace );
		$stack = new \Stack\Setting\Coco;
		$setting = ( new \Stack\Setting )->parse( $stack );
		$this->assertEquals( [ 'one' ], $setting[ 'data.value.list' ] );
	}

	public function test_stack_setting_attribute__complex( ) {
		$app = \Staq\Application::create( $this->project_namespace );
		$stack = new \Stack\Setting\Coco\Des;
		$setting = ( new \Stack\Setting )->parse( $stack );
		$this->assertEquals( [ 'one', 'two' ], $setting[ 'data.value.list' ] );
	}

	public function test_stack_setting_attribute__overrided_file( ) {
		$app = \Staq\Application::create( $this->project_namespace );
		$stack = new \Stack\Setting\Coco\Des\Bois;
		$setting = ( new \Stack\Setting )->parse( $stack );
		$this->assertEquals( [ 'three', 'four' ], $setting[ 'data.value.list' ] );
	}
}