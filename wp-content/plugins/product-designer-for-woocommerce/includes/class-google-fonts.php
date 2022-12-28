<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit ;
}

if ( ! class_exists( 'PDR_Google_Fonts' ) ) {

	class PDR_Google_Fonts {

		private $api_key ;

		public function __construct() {
			$this->api_key = get_option( 'pdr_google_fonts_api_key' ) ; //api key for to fetch details
		}

		public function get_fonts() {
			$body = wp_cache_get( 'pdr_google_fonts' ) ;
			if ( ! $body ) {
				$response = wp_remote_request( 'https://www.googleapis.com/webfonts/v1/webfonts?key=' . $this->api_key , array( 'timeout' => 20 ) ) ;
				$body     = wp_remote_retrieve_body( $response ) ;
				wp_cache_set( 'pdr_google_fonts' , $body ) ;
			}
			return $body ;
		}

	}

}

