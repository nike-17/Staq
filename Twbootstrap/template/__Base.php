<?php

/* This file is part of the Supersoniq project.
 * Supersoniq is a free and unencumbered software released into the public domain.
 * For more information, please refer to <http://unlicense.org/>
 */

namespace Supersoniq\Twbootstrap\Template;

class __Base extends \__Auto\Template\__Base {



	/*************************************************************************
	  CONSTRUCTOR                   
	 *************************************************************************/
	public function __construct( ) {
		parent::__construct( );
		if ( $this->is_module_template( ) ) {
			$this->set_parent( ( new \View )->by_layout( 'simple' ) );
		}
	}

}
