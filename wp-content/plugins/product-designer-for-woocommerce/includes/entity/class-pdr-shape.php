<?php

/**
 * Shape.
 * */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'PDR_Shape' ) ) {

	/**
	 * PDR_Shape Class.
	 */
	class PDR_Shape extends PDR_Post {

		/**
		 * Post Type.
		 * 
		 * @var string
		 */
		protected $post_type = 'pdr_shapes' ;

		/**
		 * Post Status.
		 * 
		 * @var string
		 */
		protected $post_status = 'publish' ;

		/**
		 * Content.
		 * 
		 * @var string
		 */
		protected $content ;

		/**
		 * Name.
		 * 
		 * @var string
		 */
		protected $name ;

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
				) ;

		/**
		 * Prepare extra post data.
		 */
		protected function load_extra_postdata() {
			$this->created_date = $this->post->post_date_gmt ;
			$this->content      = $this->post->post_content ;
			$this->name         = $this->post->post_title ;
		}

		/**
		 * Setters and Getters.
		 * */

		/**
		 * Set name.
		 */
		public function set_name( $value ) {
			$this->name = $value ;
		}

		/**
		 * Set content.
		 */
		public function set_content( $value ) {
			$this->content = $value ;
		}

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
		 * Set name.
		 */
		public function get_name() {
			return $this->name ;
		}

		/**
		 * Set content.
		 */
		public function get_content() {
			return $this->content ;
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

	}

}

