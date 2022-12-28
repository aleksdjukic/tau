<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit ;
}

if ( ! class_exists( 'PDR_My_Designs' ) ) {

	class PDR_My_Designs {

		public function __construct( $user_id = 0, $object = '', $screenshot = '' ) {
			$this->user_id = $user_id ;
			$this->object  = $object ;
			
		}

		public function insert_design() {
			$id = 0 ;
			if ( '' != $this->object && $this->user_id > 0 ) {
				$my_post = array(
					'post_title'   => 'Design_' . uniqid() ,
					'post_content' => $this->object ,
					'post_status'  => 'publish' ,
					'post_type'    => 'pdr_my_designs' ,
					'post_author'  => $this->user_id ,
						) ;
				$id      = wp_insert_post( $my_post ) ;
			}
			return $id ;
		}

		public function fetch_all_designs() {
			if ( $this->user_id > 0 ) {
				$my_designs = array() ;
				$args       = array(
					'post_type'      => 'pdr_my_designs' ,
					'post_status'    => 'publish' ,
					'posts_per_page' => -1 ,
					'author'         => $this->user_id ,
					'fields'         => 'ids' ,
						) ;

				$current_user_posts = get_posts( $args ) ;
				if ( ! pdr_check_is_array( $current_user_posts ) ) {
					return $current_user_posts ;
				}

				foreach ( $current_user_posts as $each_design_id ) {
					$product_base_id  = get_post_meta( $each_design_id , 'pdr_my_design_product_base_id' , true ) ;
					$preview          = get_post_meta( $each_design_id , 'pdr_my_design_preview' , true ) ;
					$design_date      = get_the_date( 'dS M Y' , $each_design_id ) ;
					//check if product base id exists
					$product_base_obj = get_post( $product_base_id ) ;
					if ( $product_base_obj ) {
						$product_base_obj              = pdr_get_product_base( $product_base_id ) ;
						$url                           = get_permalink( get_option( 'pdr_product_designer_page_id' ) ) ;
						$url                           = esc_url_raw( add_query_arg( array( 'product_id' => $product_base_obj->get_product_ids()[0] , 'pdr_id' => $product_base_id , 'my_design' => $each_design_id ) , $url ) ) ;
						$my_designs[ $each_design_id ] = array( 'title' => $design_date , 'url' => $url , 'preview' => $preview ) ;
					}
				}
				return $my_designs ;
			}
		}

		public function fetch_design( $design_id = 0 ) {
			
			$get_post = false ;
			if ( $design_id > 0 && $this->user_id > 0  ) {
				$array = array(
					'post_type'   => 'pdr_my_designs' ,
					'author'      => $this->user_id ,
					'fields'      => 'ids' ,
					'post_status'    => 'publish' ,
					'posts_per_page' => -1 ,
						) ;

				$get_posts = get_posts( $array ) ;
				if ( is_array( $get_posts ) && ! empty( $get_posts ) ) {
					$get_post = in_array( $design_id , $get_posts ) ? get_post( $design_id ) : false ;
				}
			}
			return $get_post ;
		}

		public function fetch_design_content( $design_id = 0 ) {
			$post_content = false ;
			if ( $design_id > 0 && $this->user_id > 0 ) {
				$get_post = $this->fetch_design( $design_id ) ;
				if ( $get_post ) {
					$post_content = $get_post->post_content ;
				}
			}
			return $post_content ;
		}

	}

}
