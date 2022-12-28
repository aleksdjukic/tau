<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit ;
}

if ( ! class_exists( 'PDR_REST_API' ) ) {

	class PDR_REST_API {

		public $namespace = 'wc-pdr/v1' ;
		public $post_type = 'pdr' ;

		public function __construct() {
			add_action( 'rest_api_init' , array( $this , 'register_rest_route' ) ) ;
		}

		public function register_rest_route() {

			register_rest_route( $this->namespace , '/get_data/(?P<id>\d+)' , array(
				'methods'             => 'GET' ,
				'callback'            => array( $this , 'get_pdr_data' ) ,
				'permission_callback' => array( $this , 'check_create_permission' ) ,
			) ) ;
		}

		public function get_pdr_data( WP_REST_Request $request ) {
			$data = get_post( $request[ 'id' ] ) ;
			if ( ! $data ) {
				return new WP_Error( 'woocommerce_rest_cannot_view' , __( 'No Data found for your requested ID' , 'product-designer-woocommerce' ) , array( 'status' => '404' ) ) ;
			} elseif ( 'pdr' != $data->post_type ) {
				return new WP_Error( 'woocommerce_rest_cannot_view' , __( 'Requested ID is not for Product Designer' , 'product-designer-woocommerce' ) , array( 'status' => '404' ) ) ;
			}
			return rest_ensure_response( $this->format_pdr_data_schema( $data ) ) ;
		}

		public function format_pdr_data_schema( $response ) {
			$data    = array() ;
			$default = array(
				'ID'            => 'id' ,
				'post_date'     => 'date' ,
				'post_content'  => 'message' ,
				'post_modified' => 'last_modified_date' ,
				'post_status'   => 'status' ,
					) ;
			foreach ( $default as $each_field => $new_field ) {
				$data[ $new_field ] = $response->$each_field ;
			}

			$data[ 'image' ]     = get_the_post_thumbnail_url( $response->ID , 'full' ) ;
			$data[ 'meta_data' ] = get_post_meta( $response->ID ) ;

			return rest_ensure_response( $data ) ;
		}

		public function check_permission() {
			if ( ! wc_rest_check_post_permissions( $this->post_type , 'read' ) ) {
				return new WP_Error( 'woocommerce_rest_cannot_view' , __( 'Sorry, you cannot list resources.' , 'product-designer-for-woocommerce' ) , array( 'status' => rest_authorization_required_code() ) ) ;
			}
			return true ;
		}

	}

	new PDR_REST_API() ;
}
