<?php

/* This file is part of the Supersoniq project.
 * Supersoniq is a free and unencumbered software released into the public domain.
 * For more information, please refer to <http://unlicense.org/>
 */

namespace Supersoniq;

class Configuration {


	/*************************************************************************
	 ATTRIBUTES
	 *************************************************************************/
	private $data = array( );



	/*************************************************************************
	  CONSTRUCTOR                   
	 *************************************************************************/
	public function __construct( $file_name ) {
		$platform = SUPERSONIQ_PLATFORM;
		while ( $platform ) {
			$this->parse( $file_name . '.' . $platform );
			if ( contains( $platform, '.' ) ) {
				$platform = substr_before_last( $platform, '.' );
			} else {
				break;
			}
		}
		$this->parse( $file_name, TRUE );
	}



	/*************************************************************************
	  PUBLIC METHODS                   
	 *************************************************************************/
	public function get( $section, $property ) {
		if ( ! $this->has( $section, $property ) ) {
			return FALSE;
		}
		return $this->data[ $section ][ $property ];
	}
	public function has( $section, $property ) {
		return isset( $this->data[ $section ][ $property ] );
	}



	/*************************************************************************
	  PRIVATE METHODS                   
	 *************************************************************************/
	private function parse( $file_name, $required = FALSE ) {
		$source_file_path = SUPERSONIQ_ROOT_PATH . SUPERSONIQ_APPLICATION . '/configuration/' . $file_name . '.ini';
		if ( file_exists( $source_file_path ) ) {
			$this->register_data( parse_ini_file( $source_file_path, TRUE ) );
		} else if ( $required ) {
			throw new \Exception( 'Configuration source file "' . $source_file_path . '" does not exist' );
		}
	}
	private function register_data( $data ) {
		foreach ( $data as $section => $properties ) {
			if ( ! isset( $this->data[ $section ] ) ) {
				$this->data[ $section ] = $properties;
			} else {
				foreach ( $properties as $property => $value ) {
					if ( ! isset( $this->data[ $section ][ $property ] ) ) {
						$this->data[ $section ][ $property ] = $value;
					}
				}
			}
		}
	}
}
