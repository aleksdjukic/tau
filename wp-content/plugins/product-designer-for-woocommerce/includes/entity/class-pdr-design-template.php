<?php

/**
 * Design Template.
 * */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'PDR_Design_Template' ) ) {

	/**
	 * PDR_Design_Template Class.
	 */
	class PDR_Design_Template extends PDR_Post {

		/**
		 * Post Type.
		 * 
		 * @var string
		 */
		protected $post_type = 'pdr_design_templates';

		/**
		 * Post Status.
		 * 
		 * @var string
		 */
		protected $post_status = 'publish';

		/**
		 * Name.
		 * 
		 * @var string
		 */
		protected $name;

		/**
		 * Content.
		 * 
		 * @var string
		 */
		protected $content;

		/**
		 * Created Date.
		 * 
		 * @var string
		 */
		protected $created_date;

		/**
		 * Meta data keys.
		 */
		protected $meta_data_keys = array(
			'pdr_price'                => '',
			'pdr_usage_count'          => '',
			'pdr_featured'             => '',
			'pdr_canvas_attachment_id' => ''
		);

		/**
		 * Prepare extra post data.
		 */
		protected function load_extra_postdata() {
			$this->created_date = $this->post->post_date_gmt;
			$this->name         = $this->post->post_title;
			$this->content      = $this->post->post_content;
		}

		/**
		 * Setters and Getters.
		 * */

		/**
		 * Set name.
		 */
		public function set_name( $value ) {
			$this->name = $value;
		}

		/**
		 * Set content.
		 */
		public function set_content( $value ) {
			$this->content = $value;
		}

		/**
		 * Set created date.
		 */
		public function set_created_date( $value ) {
			$this->created_date = $value;
		}

		/**
		 * Set Price.
		 * */
		public function set_price( $value ) {
			$this->set_prop( 'pdr_price', $value );
		}

		/**
		 * Set usage count.
		 */
		public function set_usage_count( $value ) {
			$this->set_prop( 'pdr_usage_count', $value );
		}

		/**
		 * Set featured.
		 */
		public function set_featured( $value ) {
			$this->set_prop( 'pdr_featured', $value );
		}

		/**
		 * Set canvas attachment ID.
		 */
		public function set_canvas_attachment_id( $value ) {
			$this->set_prop( 'pdr_canvas_attachment_id', $value );
		}

		/**
		 * Set name.
		 */
		public function get_name() {
			return $this->name;
		}

		/**
		 * Get content.
		 */
		public function get_content() {
			return $this->content;
		}

		/**
		 * Get created date.
		 */
		public function get_created_date() {
			return $this->created_date;
		}

		/**
		 * Get price.
		 */
		public function get_price() {
			return $this->get_prop( 'pdr_price' );
		}

		/**
		 * Get Usage count.
		 */
		public function get_usage_count() {
			return $this->get_prop( 'pdr_usage_count' );
		}

		/**
		 * Get featured.
		 */
		public function get_featured() {
			return $this->get_prop( 'pdr_featured' );
		}

		/**
		 * Get canvas attachment ID.
		 */
		public function get_canvas_attachment_id() {
			return $this->get_prop( 'pdr_canvas_attachment_id' );
		}

		/**
		 * Read the canvas object from file.
		 * 
		 * @return string
		 */
		public function read_canvas_from_file() {
			$canvas_attachment_id = $this->get_canvas_attachment_id();
			$canvas_object        = '';

			if ( is_numeric( $canvas_attachment_id ) ) {
				$attached_file = get_attached_file( $canvas_attachment_id );

				if ( is_file( $attached_file ) ) {
					$canvas_object = file_get_contents( $attached_file );
				}
			}

			return $canvas_object;
		}

	}

}

