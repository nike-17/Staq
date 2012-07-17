<?php

/* This file is part of the Supersoniq project.
 * Supersoniq is a free and unencumbered software released into the public domain.
 * For more information, please refer to <http://unlicense.org/>
 */

namespace Supersoniq;

class Url {


	/*************************************************************************
	  ATTRIBUTES                   
	 *************************************************************************/
        public $host;
	public $port;
	public $uri;


	/*************************************************************************
	  CONSTRUCTOR                   
	 *************************************************************************/
	public function from( $mixed ) {
		if ( is_object( $mixed ) ) {
			return $mixed;
		}
		if ( is_array( $mixed ) ) {
			return $this->from_array( $mixed );
		}
		if ( is_string( $mixed ) ) {
			return $this->from_string( $mixed );
		}
	}
	public function from_array( $array ) {
		$return = array( );
		foreach( $array as $item ) {
			$return[ ] = $this->from( $item );
		}
		return $return;
	}
	public function from_string( $string ) {
		$return = new $this;
		if ( \Supersoniq\starts_with( $string, array( 'http://', 'https://', '//' ) ) ) {
			$string = \Supersoniq\cut_after( $string, '//' );
			$return->host = \Supersoniq\cut_before( $string, array( '/', ':' ) );
			$return->port = 80;
		}
		if ( \Supersoniq\starts_with( $string, array( ':' ) ) ) {
			$return->port = \Supersoniq\cut_before( $string, '/' );
		}
		$return->uri = $string;
		return $return;
	}
	public function by_server( ) {
		$return = new $this;		
		$return->host = $_SERVER[ 'SERVER_NAME' ];
		$return->port = $_SERVER[ 'SERVER_PORT'];
		$return->uri = \Supersoniq\substr_before( $_SERVER[ 'REQUEST_URI' ], '?' );
		return $return;
	}


	/*************************************************************************
	  PUBLIC METHODS                   
	 *************************************************************************/
	public function to_string( $url ) {
		$url = 'http://' . $this->host;
		if ( $this->port != '80' ) {
			$url .= ':' . $this->port;
		}
		$url .= $this->uri;
		return $url;
	}
	public function match( $url ) {
		return ( 
			( is_null( $this->host ) || $this->host === $url->host ) &&
			( is_null( $this->port ) || $this->port === $url->port ) &&
			( is_null( $this->uri  ) || \Supersoniq\starts_with( $url->uri, $this->uri )  )
		);
	}
	public function diff( $url ) {
		if ( is_object( $url ) ) {
			if ( isset( $url->host ) ) {
				unset( $url->host );
			}
			if ( isset( $url->port ) ) {
				unset( $url->port );
			}
			if ( ! is_null( $url->uri ) ) {
				$this->uri = \Supersoniq\substr_after( $this->uri, $url->uri );
				$this->uri = \Supersoniq\must_starts_with( $this->uri, '/' );
			}
		}
		return $this;
	}
}
