<?php

/**
 * Clipart.
 * */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'PDR_Clipart' ) ) {

	/**
	 * PDR_Clipart Class.
	 */
	class PDR_Clipart extends PDR_Post {

		/**
		 * Post Type.
		 * 
		 * @var string
		 */
		protected $post_type = 'pdr_cliparts' ;

		/**
		 * Post Status.
		 * 
		 * @var string
		 */
		protected $post_status = 'publish' ;

		/**
		 * Created Date.
		 * 
		 * @var string
		 */
		protected $created_date ;

		/**
		 * Meta data keys.
		 */
		protected $meta_data_keys = array(
			'pdr_price'       => '' ,
			'pdr_usage_count' => '' ,
			'pdr_featured'    => '' ,
				) ;

		/**
		 * Prepare extra post data.
		 */
		protected function load_extra_postdata() {
			$this->created_date = $this->post->post_date_gmt ;
		}

		/**
		 * Get the thumbnail URL.
		 */
		public function get_thumbnail_url() {
			return get_the_post_thumbnail_url( $this->get_post() ) ;
		}

		/**
		 * Get the thumbnail id.
		 */
		public function get_thumbnail_id() {
			return get_post_thumbnail_id( $this->get_post() ) ;
		}

		/**
		 * Setters and Getters.
		 * */

		/**
		 * Set created date.
		 */
		public function set_created_date( $value ) {
			$this->created_date = $value ;
		}

		/**
		 * Set Price.
		 * */
		public function set_price( $value ) {
			$this->set_prop( 'pdr_price' , $value ) ;
		}

		/**
		 * Set usage count.
		 */
		public function set_usage_count( $value ) {
			$this->set_prop( 'pdr_usage_count' , $value ) ;
		}

		/**
		 * Get created date.
		 */
		public function get_created_date() {
			return $this->created_date ;
		}

		/**
		 * Get price.
		 */
		public function get_price() {
			return $this->get_prop( 'pdr_price' ) ;
		}

		/**
		 * Get Usage count.
		 */
		public function get_usage_count() {
			return $this->get_prop( 'pdr_usage_count' ) ;
		}

		/**
		 * Get featured.
		 */
		public function get_featured() {
			return $this->get_prop( 'pdr_featured' ) ;
		}

	}

}

