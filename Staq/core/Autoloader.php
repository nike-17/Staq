<?php

/* This file is part of the Staq project, which is under MIT license */


namespace Staq;

class Autoloader {



	/*************************************************************************
	 ATTRIBUTES
	 *************************************************************************/
	protected $extensions = [ ];
	const DEFAULT_CLASS = '__Default';



	/*************************************************************************
	  INITIALIZATION             
	 *************************************************************************/
	public function __construct( $extensions ) {
		$this->extensions = $extensions;
	}



	/*************************************************************************
	  TOP-LEVEL AUTOLOAD
	 *************************************************************************/
	public function autoload( $class ) {
		if ( \Staq\Util\is_stack( $class ) ) {
			$this->load_stack_class( $class );
		} else if ( \Staq\Util\is_parent_stack( $class ) ) {
			$this->load_stack_parent_class( $class );
		}
	}


	/*************************************************************************
	  FILE CLASS MANAGEMENT             
	 *************************************************************************/
	protected function load_stack_class( $class ) {
		$stack_query = \Staq\Util\stack_query( $class );
		while( $stack_query ) {
			foreach( $this->extensions as $extension ) {
				if ( $real_class = $this->load_stack_extension_file( $stack_query, $extension ) ) {
					$this->create_alias_class( $class, $real_class );
					return TRUE;
				}
			}
			$stack_query = \Staq\Util\stack_query_pop( $stack_query );
		}

		$this->create_empty_class( $class );
	}
	/* "stack" is now a part of the namespace to : 
	 *   1. Separate stackable class files with others
	 *   2. Separate the extension namespace with the query namespace
	 *   3. There is no more burgers at my bakery 
	 */
	protected function load_stack_extension_file( $stack, $extension ) {
		$relative_path = $extension . '/stack/' . $this->string_namespace_to_path( $stack );
		$absolute_path = STAQ_ROOT_PATH . $relative_path . '.php';
		if ( is_file( $absolute_path ) ) {
			$real_class = $this->string_path_to_namespace( $relative_path );
			if ( ! $this->class_exists( $real_class ) ) {
				require_once( $absolute_path );
				$this->check_class_loaded( $real_class );
			}
			return $real_class;
		}
	}
	

	protected function load_stack_parent_class( $class ) {
		$query_extension = $this->string_namespace_to_path( \Staq\Util\stackable_extension( $class ) );
		$query = \Staq\Util\parent_stack_query( $class );
		$ready = FALSE;
		while( $query ) {
			foreach( $this->extensions as $extension ) {
				if ( $ready ) {
					if ( $real_class = $this->load_stack_extension_file( $query, $extension ) ) {
						$this->create_alias_class( $class, $real_class );
						return TRUE;
					}
				} else {
					if ( strtolower( $query_extension ) == strtolower( $extension ) ) {
						$ready = TRUE;
					}
				}
			}
			$query = \Staq\Util\stack_query_pop( $query );
			$ready = TRUE;
		}

		$this->create_empty_class( $class );
	}



	/*************************************************************************
	  CLASS DECLARATION             
	 *************************************************************************/
	protected function class_exists( $class ) {
		return ( class_exists( $class ) || interface_exists( $class ) );
	}
	protected function create_alias_class( $alias, $class ) {
		return $this->create_class( $alias, $class, interface_exists( $class ) );
	}
	protected function create_empty_class( $class ) {
		return $this->create_class( $class, NULL );
	}
	protected function create_class( $class, $base_class, $is_interface = FALSE ) {
		$namespace = \Staq\Util\string_substr_before_last( $class, '\\' );
		$name = \Staq\Util\string_substr_after_last( $class, '\\' );
		$code = '';
		if ( $namespace ) {
			$code = 'namespace ' . $namespace . ';' . PHP_EOL;
		}
		if ( $is_interface ) {
			$code .= 'interface';
		} else {
			$code .= 'class';
		}
		$code .= ' ' . $name . ' ';
		if ( $base_class ) {
			$code .= 'extends ' . $base_class . ' ';
		}
		$code .= '{ }' . PHP_EOL;
		// echo $code . HTML_EOL;
		eval( $code );
	}
	protected function check_class_loaded( $class ) {
		if ( ! $this->class_exists( $class ) ) {
			$classes = get_declared_classes( );
			$loaded_class = end( $classes );
			throw new \Stack\Exception\Wrong_Class_Definition( 'Wrong class definition: "' . $loaded_class . '" definition, but "' . $class . '" expected.' ); 
		}
	}



	/*************************************************************************
	  NAMESPACE FORMAT             
	 *************************************************************************/
	protected function string_path_to_namespace( $path, $absolute = TRUE ) {
		$namespace = implode( '\\', array_map( function( $a ) {
			return ucfirst( $a );
		}, explode( '/', $path ) ) );
		if ( $absolute ) $namespace = '\\' . $namespace;
		return $namespace;
	}
	protected function string_namespace_to_path( $namespace, $file = TRUE ) {
		if ( $file ) {
			$parts = explode( '\\', $namespace );
			if ( count( $parts ) == 1 ) {
				return $parts[ 0 ];
			}
			$class = array_pop( $parts );
			return strtolower( implode( '/', $parts ) ) . '/' . $class;
		}
		return str_replace( '\\', '/' , $namespace );
	}
}