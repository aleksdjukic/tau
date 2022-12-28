<?php

/**
 * Order.
 * */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'PDR_Order' ) ) {

	/**
	 * PDR_Order Class.
	 */
	class PDR_Order extends PDR_Post {

		/**
		 * Post Type.
		 * 
		 * @var string
		 */
		protected $post_type = 'pdr_orders' ;

		/**
		 * Post Status.
		 * 
		 * @var string
		 */
		protected $post_status = 'publish' ;

		/**
		 * Product ID.
		 * 
		 * @var int
		 */
		protected $product_id ;

		/**
		 * Image URL.
		 * 
		 * @var string
		 */
		protected $image_url ;

		/**
		 * Created Date.
		 * 
		 * @var string
		 */
		protected $created_date ;

		/**
		 * Product.
		 * 
		 * @var object
		 */
		protected $product ;

		/**
		 * WC Order.
		 * 
		 * @var object
		 */
		protected $order ;

		/**
		 * Meta data keys.
		 */
		protected $meta_data_keys = array(
			'pdr_wc_order_id'     => '' ,
			'pdr_user_id'         => '' ,
			'pdr_canvas_data'     => '' ,
			'pdr_product_base'    => array() ,
			'pdr_product_base_id' => '' ,
			'pdr_shapes'          => '' ,
			'pdr_cliparts'        => '' ,
			'pdr_text'            => '' ,
			'pdr_quantity'        => '' ,
			'pdr_product_price'   => '' ,
			'pdr_user_name'       => '' ,
			'pdr_user_email'      => '' ,
			'pdr_data'            => '' ,
			'pdr_img_url'         => '' ,
			'pdr_item_id'         => '' ,
				) ;

		/**
		 * Prepare extra post data.
		 */
		protected function load_extra_postdata() {
			$this->product_id   = $this->post->post_parent ;
			$this->image_url    = $this->post->post_content ;
			$this->created_date = $this->post->post_date_gmt ;
		}

		/**
		 * Get the order.
		 * 
		 * @return object
		 */
		public function get_wc_order() {
			if ( isset( $this->wc_order ) ) {
				return $this->wc_order ;
			}

			$this->wc_order = wc_get_order( $this->get_wc_order_id() ) ;

			return $this->wc_order ;
		}

		/**
		 * Get the product.
		 * 
		 * @return object/bool
		 */
		public function get_product() {

			if ( isset( $this->product ) ) {
				return $this->product ;
			}

			$this->product = wc_get_product( $this->get_product_id() ) ;

			return $this->product ;
		}

		/**
		 * Setters and Getters.
		 * */

		/**
		 * Set Product ID.
		 */
		public function set_product_id( $value ) {
			$this->product_id = $value ;
		}

		/**
		 * Set Image URL.
		 */
		public function set_image_url( $value ) {
			$this->image_url = $value ;
		}

		/**
		 * Set created date.
		 */
		public function set_created_date( $value ) {
			$this->created_date = $value ;
		}

		/**
		 * Set WC Order ID.
		 * */
		public function set_wc_order_id( $value ) {
			$this->set_prop( 'pdr_wc_order_id' , $value ) ;
		}

		/**
		 * Set User ID.
		 */
		public function set_user_id( $value ) {
			$this->set_prop( 'pdr_user_id' , $value ) ;
		}

		/**
		 * Set Product base.
		 */
		public function set_product_base( $value ) {
			$this->set_prop( 'pdr_product_base' , $value ) ;
		}

		/**
		 * Set Product base ID.
		 */
		public function set_product_base_id( $value ) {
			$this->set_prop( 'pdr_product_base_id' , $value ) ;
		}

		/**
		 * Set shapes.
		 */
		public function set_shapes( $value ) {
			$this->set_prop( 'pdr_shapes' , $value ) ;
		}

		/**
		 * Set Cliparts.
		 */
		public function set_cliparts( $value ) {
			$this->set_prop( 'pdr_cliparts' , $value ) ;
		}

		/**
		 * Set Text.
		 */
		public function set_text( $value ) {
			$this->set_prop( 'pdr_text' , $value ) ;
		}

		/**
		 * Set Quantity.
		 */
		public function set_quantity( $value ) {
			$this->set_prop( 'pdr_quantity' , $value ) ;
		}

		/**
		 * Set Product Price.
		 */
		public function set_product_price( $value ) {
			$this->set_prop( 'pdr_product_price' , $value ) ;
		}

		/**
		 * Set Canvas data.
		 */
		public function set_canvas_data( $value ) {
			$this->set_prop( 'pdr_canvas_data' , $value ) ;
		}

		/**
		 * Set User Name.
		 */
		public function set_user_name( $value ) {
			$this->set_prop( 'pdr_user_name' , $value ) ;
		}

		/**
		 * Set User Email.
		 */
		public function set_user_email( $value ) {
			$this->set_prop( 'pdr_user_email' , $value ) ;
		}

		/**
		 * Get Product ID.
		 */
		public function get_product_id() {
			return $this->product_id ;
		}

		/**
		 * Get Image URL.
		 */
		public function get_image_url() {
			$image_url_file = $this->get_prop( 'pdr_img_url' ) ;
			$file_api       = new PDR_File( '' , $image_url_file ) ;
			$image_data     = $file_api->retrieve() ;
			return $image_data ;
		}

		/**
		 * Get created date.
		 */
		public function get_created_date() {
			return $this->created_date ;
		}

		/**
		 * Get WC Order ID.
		 */
		public function get_wc_order_id() {
			return $this->get_prop( 'pdr_wc_order_id' ) ;
		}
		
		/**
		 * Get Item ID
		 */
		public function get_wc_item_id() {
			return $this->get_prop( 'pdr_item_id' ) ;
		}
		

		/**
		 * Get User ID.
		 */
		public function get_user_id() {
			return $this->get_prop( 'pdr_user_id' ) ;
		}

		/**
		 * Get Product Base.
		 */
		public function get_product_base() {
			return $this->get_prop( 'pdr_product_base' ) ;
		}

		/**
		 * Get Product base ID.
		 */
		public function get_product_base_id() {
			return $this->get_prop( 'pdr_product_base_id' ) ;
		}

		/**
		 * Get shapes.
		 */
		public function get_shapes() {
			return $this->get_prop( 'pdr_shapes' ) ;
		}

		/**
		 * Get Cliparts.
		 */
		public function get_cliparts() {
			return $this->get_prop( 'pdr_cliparts' ) ;
		}

		/**
		 * Get Text.
		 */
		public function get_text() {
			return $this->get_prop( 'pdr_text' ) ;
		}

		/**
		 * Get Quantity.
		 */
		public function get_quantity() {
			return $this->get_prop( 'pdr_quantity' ) ;
		}

		/**
		 * Get Product price.
		 */
		public function get_product_price() {
			return $this->get_prop( 'pdr_product_price' ) ;
		}

		/**
		 * Get canvas data.
		 * */
		public function get_canvas_data() {
			return $this->get_prop( 'pdr_canvas_data' ) ;
		}

		/**
		 * Get User Name.
		 */
		public function get_user_name() {
			return $this->get_prop( 'pdr_user_name' ) ;
		}

		/**
		 * Get User Email.
		 */
		public function get_user_email() {
			return $this->get_prop( 'pdr_user_email' ) ;
		}

	}

}

