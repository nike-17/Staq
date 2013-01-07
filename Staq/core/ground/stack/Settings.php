<?php

/* This file is part of the Staq project, which is under MIT license */


namespace Staq\Core\Ground\Stack;

class Settings {


	/*************************************************************************
	 ATTRIBUTES
	 *************************************************************************/
	public $datas = [ ];
	public static $cache_parsed_file = [ ];



	/*************************************************************************
	  CONSTRUCTOR                 
	 *************************************************************************/
	public function __construct( $full_settings_file_name ) {
		$settings_file_paths = $this->get_file_paths( $full_settings_file_name );
		$this->datas = $this->parse_settings_files( $settings_file_paths );
	}



	/*************************************************************************
	  ACCESSOR METHODS                   
	 *************************************************************************/
	public function has( $section, $property ) {
		foreach ( $this->datas as $data ) {
			if ( isset( $data[ $section ][ $property ] ) ) {
				return TRUE;
			}
		}
		return FALSE;
	}

	public function get( $section, $property, $default = NULL ) {
		foreach ( $this->datas as $data ) {
			if ( isset( $data[ $section ][ $property ] ) ) {
				return $data[ $section ][ $property ];
			}
		}
		return $default;
	}

	public function get_list( $property, $initial = [ ] ) {
		$disabled = [ ];
		$enabled = $initial;
		foreach ( $this->datas as $data ) {
			if ( isset( $data[ $property ][ 'disabled' ] ) ) {
				$new = $data[ $property ][ 'disabled' ];
				$disabled = \Staq\Util\array_merge_unique( $disabled, $new );
			}
			if ( isset( $data[ $property ][ 'enabled' ] ) ) {
				$new = array_diff( $data[ $property ][ 'enabled' ], $disabled );
				$enabled  = \Staq\Util\array_merge_unique( $enabled, $new );
			}
		}
		return $enabled;
	}

	public function get_array( $section, $property = NULL ) {
		if ( ! is_null( $property ) ) {
			return $this->get_section_property_array( $section, $property );
		}
		return $this->get_section( $section );
	}

	private function get_section( $sections ) {
		$array = [ ];
		\Staq\Util\must_be_array( $sections );
		foreach( $sections as $section ) {
			foreach ( $this->datas as $data ) {
				if ( isset( $data[ $section ] ) ) {
					foreach ( $data[ $section ] as $name => $value ) {
						if ( ! isset( $array[ $name ] ) ) {
							$array[ $name ] = $value;
						}
					}
				}
			}
		}
		return $array;
	}

	private function get_section_property_array( $section, $property ) {
		$array = [ ];
		foreach ( $this->datas as $data ) {
			if ( isset( $data[ $section ][ $property ] ) ) {
				$elements = $data[ $section ][ $property ];
				\Staq\Util\must_be_array( $elements );
				$array = array_merge( $array, $elements );
			}
		}
		return $array;
	}



	/*************************************************************************
	  PROTECTED METHODS                 
	 *************************************************************************/
	protected function get_file_paths( $full_settings_file_name ) {
		$file_names = $this->get_file_names( $full_settings_file_name );
		$platform_name = \Staq\Application::get_platform( );
		$file_paths = [ ];
		foreach ( \Staq\Application::get_extensions( ) as $extension ) {
			foreach( $file_names as $file_name ) {
				if ( $platform_name ) {
					$file_name .= '.' . $platform_name;
				}
				while ( $file_name ) {
					$file_paths[ ] = $extension . '/setting/' . $file_name . '.ini';
					$file_name = \Staq\Util\string_substr_before_last( $file_name, '.' );
				}
			}
		}
		return $file_paths;
	}
	protected function get_file_names( $file_name ) {
		$file_names = [ ];
		do {
			$file_names[ ] = $file_name;
			$file_name = \Staq\Util\string_substr_before_last( $file_name, '/' );
		} while( ! empty( $file_name ) );
		return $file_names;
	}
	protected function parse_settings_files( $file_paths ) {
		$datas = [ ];
		foreach ( $file_paths as $file_path ) {
			if ( isset( self::$cache_parsed_file[ $file_path ] ) ) {
				$datas[ $file_path ] = self::$cache_parsed_file[ $file_path ];
			} else {
				$absolute_file_path = STAQ_ROOT_PATH . $file_path;
				if ( is_file( $absolute_file_path ) ) {
					$datas[ $file_path ] = parse_ini_file( $absolute_file_path, TRUE );
				} else {
					$datas[ $file_path ] = [ ];
				}
				self::$cache_parsed_file[ $file_path ] = $datas[ $file_path ];
			}
		}
		return $datas;
	}
}