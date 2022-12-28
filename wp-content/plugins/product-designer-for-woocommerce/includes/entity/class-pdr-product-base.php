<?php

/**
 * Product Base.
 * */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'PDR_Product_Base' ) ) {

	/**
	 * PDR_Product_Base Class.
	 */
	class PDR_Product_Base extends PDR_Post {

		/**
		 * Post Type.
		 * 
		 * @var string
		 */
		protected $post_type = 'pdr_product_base' ;

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
			'pdr_product_ids' => '' ,
			'pdr_description' => '' ,
			'pdr_image_segmentation' => '',
			'pdr_rules'       => '' ,
			'pdr_attributes'  => '' ,
						'pdr_font_selection' => '',
						'pdr_font_selection_type'=>'',
				) ;

		/**
		 * Prepare extra post data.
		 */
		protected function load_extra_postdata() {
			$this->created_date = $this->post->post_date_gmt ;
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
		 * Set product IDs.
		 * */
		public function set_product_ids( $value ) {
			$this->set_prop( 'pdr_product_ids' , $value ) ;
		}

		/**
		 * Set description.
		 */
		public function set_description( $value ) {
			$this->set_prop( 'pdr_description' , $value ) ;
		}

		/**
		 * Set rules.
		 */
		public function set_rules( $value ) {
			$this->set_prop( 'pdr_rules' , $value ) ;
		}

		/**
		 * Set attributes.
		 */
		public function set_attributes( $value ) {
			$this->set_prop( 'pdr_attributes' , $value ) ;
		}

		/**
		 * Get created date.
		 */
		public function get_created_date() {
			return $this->created_date ;
		}

		/**
		 * Get product IDs.
		 */
		public function get_product_ids() {
			return $this->get_prop( 'pdr_product_ids' ) ;
		}

		/**
		 * Get description.
		 */
		public function get_description() {
			return $this->get_prop( 'pdr_description' ) ;
		}

		/**
		 * Get rules.
		 */
		public function get_rules() {
			return $this->get_prop( 'pdr_rules' ) ;
		}

		/**
		 * Get attributes.
		 */
		public function get_attributes() {
			return $this->get_prop( 'pdr_attributes' ) ;
		}

	}

}

