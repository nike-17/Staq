<?php

/* This file is part of the Supersoniq project.
 * Supersoniq is a free and unencumbered software released into the public domain.
 * For more information, please refer to <http://unlicense.org/>
 */

namespace Supersoniq;


/*************************************************************************
  STRING METHODS                   
 *************************************************************************/


// STARTS WITH & ENDS WITH FUNCTIONS
function starts_with( $hay, $needles ) {
	must_be_array( $needles );
	foreach( $needles as $needle ) {
		if ( substr( $hay, 0, strlen( $needle ) ) == $needle ) {
			return TRUE;
		}
	}
	return FALSE;
}

function ends_with( $hay, $needles ) {
	must_be_array( $needles );
	foreach( $needles as $needle ) {
		if ( substr( $hay, -strlen( $needle ) ) == $needle ) {
			return TRUE;
		}
	}
	return FALSE;
}

function i_starts_with( $hay, $needle ) {
	return starts_with( strtolower( $hay ), strtolower( $needle ) );
}

function i_ends_with( $hay, $needle ) {
	return ends_with( strtolower( $hay ), strtolower( $needle ) );
}

function must_starts_with( &$hay, $needle ) {
	if ( ! starts_with( $hay, $needle ) ) {
		$hay = $needle . $hay;
	}
}

function must_ends_with( &$hay, $needle ) {
	if ( ! ends_with( $hay, $needle ) ) {
		$hay .= $needle;
	}
}

function must_not_starts_with( &$hay, $needle ) {
	if ( starts_with( $hay, $needle ) ) {
		$hay = substr( $hay, strlen( $needle ) );
	}
}

function must_not_ends_with( &$hay, $needle ) {
	if ( ends_with( $hay, $needle ) ) {
		$hay = substr( $hay, 0, -strlen( $needle ) );
	}
}



// CONTAINS FUNCTIONS
function contains( $hay, $needle ) {
	// if ( ! empty( $needle ) ) {
		return ( strpos( $hay, $needle ) !== false );
	// }
}

function i_contains( $hay, $needle ) {
	return contains( strtolower( $hay ), strtolower( $needle ) );
}



// SUBSTRING FUNCTIONS
function cut_before( &$hay, $needles ) {
	$return = substr_before( $hay, $needles );
	$hay = substr( $hay, strlen( $return ) );
	return $return;
}

function substr_before( $hay, $needles ) {
	must_be_array( $needles );
	$return = $hay;
	foreach( $needles as $needle ) {
		if ( ! empty( $needle) && contains( $hay, $needle ) ) {
			$cut = substr( $hay, 0, strpos( $hay, $needle ) );
			if ( strlen( $cut ) < strlen ( $return ) ) {
				$return = $cut;
			}
		}
	}
	$hay = substr( $hay, strlen( $return ) );
	return $return;
}

function cut_before_last( &$hay, $needles ) {
	$return = substr_before_last( $hay, $needles );
	$hay = substr( $hay, strlen( $return ) );
	return $return;
}

function substr_before_last( $hay, $needles ) {
	must_be_array( $needles );
	$return = '';
	foreach( $needles as $needle ) {
		if ( ! empty( $needle) && contains( $hay, $needle ) ) {
			$cut = substr( $hay, 0, strrpos( $hay, $needle ) );
			if ( strlen( $cut ) > strlen ( $return ) ) {
				$return = $cut;
			}
		}
	}
	$hay = substr( $hay, strlen( $return ) );
	return $return;
}

function cut_after( &$hay, $needles ) {
	$return = substr_after( $hay, $needles );
	$hay = substr( $hay, 0, - strlen( $return ) );
	return $return;
}

function substr_after( $hay, $needles ) {
	must_be_array( $needles );
	$return = '';
	foreach( $needles as $needle ) {
		if ( ! empty( $needle) && contains( $hay, $needle ) ) {
			$cut = substr( $hay, strpos( $hay, $needle ) + strlen( $needle ) );
			if ( strlen( $cut ) > strlen ( $return ) ) {
				$return = $cut;
			}
		}
	}
	return $return;
}

function cut_after_last( &$hay, $needles ) {
	$return = substr_after_last( $hay, $needles );
	$hay = substr( $hay, 0, - strlen( $return ) );
	return $return;
}

function substr_after_last( $hay, $needles ) {
	must_be_array( $needles );
	$return = $hay;
	foreach( $needles as $needle ) {
		if ( ! empty( $needle) && contains( $hay, $needle ) ) {
			$cut = substr( $hay, strrpos( $hay, $needle ) + strlen( $needle ) );
			if ( strlen( $cut ) < strlen ( $return ) ) {
				$return = $cut;
			}
		}
	}
	return $return;
}



// RANDOM FUNCTIONS
function random( $length = 10 ) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$string = '';    
	for ( $i = 0; $i < $length; $i++ ) {
		$string .= $characters[ mt_rand( 0, strlen( $characters ) - 1 ) ];
	}
	return $string;
}

// PATH FUNCTIONS
function dirname( $path, $level = 1 ) {
	for ( $i = 0; $i < $level; $i++ ) {
		$path = \dirname( $path );
	}
	return $path;
}



/*************************************************************************
  ARRAY METHODS                   
 *************************************************************************/
function must_be_array( &$array ) {
	if ( ! is_array( $array ) ) {
		$array = [ $array ];
	}
}

function array_merge_unique( $array1, $array2 ) {
	return array_values( array_unique( array_merge( $array1, $array2 ) ) );
}



/*************************************************************************
  SUPERSONIQ METHODS                   
 *************************************************************************/
function format_to_namespace( $path ) {
	return str_replace( '/', '\\', $path );
}

function format_to_path( $namespace ) {
	return str_replace( '\\', '/', $namespace );
}



/*************************************************************************
  ACTION METHODS                   
 *************************************************************************/
function redirect( $url ) {
	header( 'HTTP/1.1 302 Moved Temporarily' );
	header( 'Location: ' . $url );
	die( );
}

function redirect_to_module_page( $module, $page, $parameters = [ ] ) {
	redirect( module_page_url( $module, $action, $parameters ) );
}

function module_page_url( $module, $page, $parameters = [ ] ) {
	return \Supersoniq::$BASE_URL . $this->module_page_route( $module, $page, $parameters );
}

function module_page_route( $module, $page, $parameters = [ ] ) {
	$module = \Supersoniq::$MODULES[ $module ];
	$route = $module->get_page_route( $page, $parameters );
	return $route;
}

function class_type_name( $object ) {
	return ( new \Supersoniq\Kernel\Internal\Class_Name )->by_object( $object )->name;;
}


