<?php

/* This file is part of the Supersoniq project.
 * Supersoniq is a free and unencumbered software released into the public domain.
 * For more information, please refer to <http://unlicense.org/>
 */

namespace Supersoniq\Kernel\Module;

abstract class __Base {



	/*************************************************************************
	  ATTRIBUTES                   
	 *************************************************************************/
	public $type;
	public $routes;
	public $settings;



	/*************************************************************************
	  GETTER                 
	 *************************************************************************/
	public function name( ) {
		return $this->type;
	}



	/*************************************************************************
	  CONSTRUCTOR                   
	 *************************************************************************/
	public function __construct( ) {
		$this->type = \Supersoniq\class_type_name( $this );
		$this->settings = $this->get_settings( );
		$this->routes   = $this->get_routes( );
	}

	protected function get_settings( ) {
		return ( new \Settings )->by_file_type( 'module', $this->type );
	}

	protected function get_pages( ) {
		$pages = $this->settings->get_list( 'pages' );
		if ( empty( $pages ) ) {
			$pages = array_diff( get_class_methods( $this ), get_class_methods( get_class( ) ) );
		}
		return $pages;
	}

	protected function get_routes( ) {
		$pages = $this->get_pages( );
		$routes = [ ];
		foreach ( $pages as $page ) {
			$route = $this->get_route( $page );
			if ( $route ) {
				$routes[ $page ] = $route;
			}
		}
		return $routes;
	}

	protected function get_route( $page ) {
		$route = $this->settings->get_array( [ 'routes_' . $page, 'routes' ] );
		if ( empty( $route ) ) {
			$route = ( new \Route )->from_string( '/' . \Supersoniq\format_to_path( strtolower( $this->type ) ) . '/' . $page );
		} else {
			$route = ( new \Route )->from_array( $route );
		}
		return $route;
	}



	/*************************************************************************
	  ROUTE METHODS                   
	 *************************************************************************/
	public function handle_route( $route ) {
		foreach ( $this->routes as $page => $handle ) {
			$parameters = $handle->handle( $route );
			if ( $parameters !== FALSE ) {
				return [ $page, $parameters ];
			}
		}
	}

	public function handle_exception( $exception ) {
		return FALSE;
	}

	public function call_page( $page, $parameters ) {
		if ( ! is_callable( [ $this, $page ] ) ) {
			throw new \Exception\Resource_Not_Found( 'Page "' . $this->type . ' > ' . $page . '" not defined' );
		}
		return call_user_func_array( [ $this, $page ], $parameters );
	}

	public function get_page_route( $page, $parameters = [ ] ) {
		if ( $this->is_route_page( $page ) ) {
			return $this->routes[ $page ]->to_string( $parameters );
		}
	}

	public function get_page_url( $page, $parameters = [ ] ) {
		if ( $this->is_route_page( $page ) ) {
			return \Supersoniq::$BASE_URL . $this->routes[ $page ]->to_string( $parameters );
		}
	}

	protected function is_route_page( $page ) {
		return ( isset( $this->routes[ $page ] ) && $this->routes[ $page ]->is_up( ) );
	}
}

