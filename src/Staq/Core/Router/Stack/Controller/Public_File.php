<?php

/* This file is part of the Staq project, which is under MIT license */


namespace Staq\Core\Router\Stack\Controller;

class PublicFile extends PublicFile\__Parent {



	/*************************************************************************
	 ATTRIBUTES
	 *************************************************************************/
	public static $setting = [
		'route.action.uri' => '/*',
	];



	/*************************************************************************
	  ACTION METHODS           
	 *************************************************************************/
	public function action( ) {
		$path = \Staq\Application::get_current_uri( );
		print_r( \Staq\Application::get_extension( '/public' . $path ) );
	}



	/*************************************************************************
	  PRIVATE METHODS				   
	 *************************************************************************/
	protected function render_static_file( $file_path ) {
		$content_type = $this->get_content_type( $file_path );
		$resource     = fopen( $file_path, 'rb' );
		$cache_time   = $this->get_public_file_cache_time( );
		$control      = ( $cache_time > 0 ) ? 'public' : 'private'; 
		header( 'Pragma: public' );
		header( 'Content-Type: ' . $content_type . '; charset: UTF-8' );
		header( 'Content-Length: ' . filesize( $file_path ) );
		header( 'Cache-Control: max-age=' . ( $cache_time - time( ) ) . ', pre-check=' . ( $cache_time - time( ) ) . ', ' . $control, true );
		header( 'Expires: ' . gmdate( 'D, d M Y H:i:s \G\M\T', $cache_time ), true );
		fpassthru( $resource );
	}

	protected function get_content_type( $file_path ) {
		$extension = \UString::substr_after_last( $file_path, '.' );
		if ( in_array( $extension, [ 'html', 'css' ] ) ) {
			$content_type = 'text/' . $extension;
		} else if ( $extension === 'js' ) {
			$content_type = 'text/javascript';
		} else if ( $extension === 'ico' ) {
			$content_type = 'image/png';
		} else {
			$finfo        = finfo_open( FILEINFO_MIME_TYPE );
			$content_type = finfo_file( $finfo, $file_path );
			finfo_close( $finfo );
		}
		return $content_type;
	}    

	protected function get_public_file_cache_time( ) {
		$setting = ( new \Stack\Setting )->parse( 'application' );
			->by_file( 'application' )
		$public_file_cache = $setting[ 'cache.public_file_cache' ];
		if ( ! $public_file_cache_time = strtotime( $public_file_cache ) ) {
			$public_file_cache_time = strtotime( '+1 hour' );
		}
		return $public_file_cache_time;
	}

}

?>