<?php

/* This file is part of the Staq project, which is under MIT license */


namespace Staq\Core\View\Stack;

class View extends \Pixel418\Iniliq\ArrayObject {



	/*************************************************************************
	  ATTRIBUTES              
	 *************************************************************************/
	public $var;
	public static $setting = [
		'twig.cache' => 'off'
	];
	protected $twig;



	/*************************************************************************
	  CONSTRUCTOR METHODS              
	 *************************************************************************/
	public function __construct( ) {
		\Twig_Autoloader::register( );
		$loader = new \Twig_Loader_Filesystem( \Staq\Application::get_extensions( 'template' ) );
		$settings = ( new \Stack\Setting )->parse( $this );
		$params = [ ];
		if ( $settings->get_as_boolean( 'twig.cache' ) ) {
			if ( $cache_path = \Staq\Application::get_path( 'cache/twig', TRUE ) ) {
				$params[ 'cache' ] = $cache_path;
			}
		}
		$this->twig = new \Twig_Environment( $loader, $params );
		$this[ 'template' ] = 'index.html';
		$this[ 'base_uri' ] = \UString::end_with( \Staq\Application::get_base_uri( ), '/' );
	}



	/*************************************************************************
	  PUBLIC METHODS              
	 *************************************************************************/
	public function render( ) {
		$template = $this->loadTemplate( );
		return $template->render( $this->getArrayCopy( ) );
	}
	public function loadTemplate( ) {
		$template = $this[ 'template' ];
		while ( TRUE ) {
			if ( \Staq\Application::get_file_path( 'template/' . $template ) ) {
				break;
			} 
			if ( \UString::has( $template, '/' ) ) {
				$template = \UString::substr_before_last( $template, '/' ) . '.html';
			} else {
				$template = 'index.html';
				break;
			}
		}
		return $this->twig->loadTemplate( $template );
	}
}
