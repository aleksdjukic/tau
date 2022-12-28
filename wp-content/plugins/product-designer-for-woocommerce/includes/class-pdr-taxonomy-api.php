<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit ;
}

if ( ! class_exists( 'PDR_Taxonomy_API' ) ) {

	class PDR_Taxonomy_API {

		private $category_id ;

		public function __construct( $category_id = 0 ) {
			$this->category_id = $category_id ;
		}

		public function get_category_product_base_ids() {
			$product_bases = array() ;

			$args = array(
				'numberposts' => -1 ,
				'post_type'   => 'pdr_product_base' ,
				'post_status' => 'publish' ,
				'fields'      => 'ids' ,
					) ;

			if ( $this->category_id > 0 ) {
				$args[ 'tax_query' ] = array(
					array(
						'taxonomy' => 'pdr_product_base_cat' ,
						'field'    => 'ID' ,
						'terms'    => $this->category_id
					)
						) ;
			}

			$product_base_ids = get_posts( $args ) ;

			if ( ! pdr_check_is_array( $product_base_ids ) ) {
				return $product_bases ;
			}

			foreach ( $product_base_ids as $product_base_id ) {
				$product_base_obj = pdr_get_product_base( $product_base_id ) ;

				if ( $product_base_obj ) {
					$url                               = get_permalink( get_option( 'pdr_product_designer_page_id' ) ) ;
					$url                               = esc_url_raw( add_query_arg( array( 'product_id' => $product_base_obj->get_product_ids() , 'pdr_id' => $product_base_id ) , $url ) ) ;
					$product_bases[ $product_base_id ] = array( 'title' => get_the_title( $product_base_id ) , 'url' => '#' , 'preview' => $product_base_obj->get_rules()[ 'general' ][ 'img_url' ], 'pdr_id' => $product_base_id ) ;
				}       
			}

			return $product_bases ;
		}

		public function get_category_cliparts_ids() {
			$data_cliparts = array() ;
			$args          = array(
				'numberposts' => -1 ,
				'post_type'   => 'pdr_cliparts' ,
				'post_status' => 'publish' ,
				'fields'      => 'ids' ,
					) ;
			$featured_args = array(
				'numberposts' => -1 ,
				'post_type'   => 'pdr_cliparts' ,
				'post_status' => 'publish' ,
				'fields'      => 'ids' ,
				'meta_query'  => array(
					array(
						'key'     => 'pdr_featured' ,
						'value'   => 'yes' ,
						'compare' => '=' ,
					)
				) ) ;


			if ( $this->category_id > 0 ) {
				$args[ 'tax_query' ]          = array(
					array(
						'taxonomy' => 'pdr_clipart_cat' ,
						'field'    => 'ID' ,
						'terms'    => $this->category_id
					)
						) ;
				$featured_args[ 'tax_query' ] = array(
					array(
						'taxonomy' => 'pdr_clipart_cat' ,
						'field'    => 'ID' ,
						'terms'    => $this->category_id
					)
						) ;
			}
			$featured_cliparts = get_posts( $featured_args ) ;
			$cliparts          = get_posts( $args ) ;
			$cliparts          = array_unique( array_filter( array_merge( $featured_cliparts , $cliparts ) ) ) ;

			if ( ! pdr_check_is_array( $cliparts ) ) {
				return $cliparts ;
			}

			foreach ( $cliparts as $each_clipart_id ) {
				$data_cliparts[] = pdr_get_clipart( $each_clipart_id ) ;
			}
			return $data_cliparts ;
		}

		public function get_category_shapes_ids() {
			$data_shapes = array() ;
			$args        = array(
				'numberposts' => -1 ,
				'post_type'   => 'pdr_shapes' ,
				'post_status' => 'publish' ,
				'fields'      => 'ids' ,
					) ;

			if ( $this->category_id > 0 ) {
				$args[ 'tax_query' ] = array(
					array(
						'taxonomy' => 'pdr_shapes_cat' ,
						'field'    => 'ID' ,
						'terms'    => $this->category_id
					)
						) ;
			}
			$shapes = get_posts( $args ) ;
			if ( ! pdr_check_is_array( $shapes ) ) {
				return $shapes ;
			}

			foreach ( $shapes as $each_shape_id ) {
				$data_shapes[] = pdr_get_shape( $each_shape_id ) ;
			}
			return $data_shapes ;
		}

		public function get_category_template_ids() {
			$data_templates = array() ;
			$args           = array(
				'numberposts' => -1 ,
				'post_type'   => 'pdr_design_templates' ,
				'post_status' => 'publish' ,
				'fields'      => 'ids' ,
					) ;

			$featured_args = array(
				'numberposts' => -1 ,
				'post_type'   => 'pdr_design_templates' ,
				'post_status' => 'publish' ,
				'fields'      => 'ids' ,
				'meta_query'  => array(
					array(
						'key'     => 'pdr_featured' ,
						'value'   => 'yes' ,
						'compare' => '=' ,
					)
				) ) ;

			if ( $this->category_id > 0 ) {
				$args[ 'tax_query' ]          = array(
					array(
						'taxonomy' => 'pdr_design_template_cat' ,
						'field'    => 'ID' ,
						'terms'    => $this->category_id
					)
						) ;
				$featured_args[ 'tax_query' ] = array(
					array(
						'taxonomy' => 'pdr_design_template_cat' ,
						'field'    => 'ID' ,
						'terms'    => $this->category_id
					)
						) ;
			}
			$featured_templates = get_posts( $featured_args ) ;
			$template_ids       = get_posts( $args ) ;
			$template_ids       = array_unique( array_filter( array_merge( $featured_templates , $template_ids ) ) ) ;
			if ( ! pdr_check_is_array( $template_ids ) ) {
				return $template_ids ;
			}

			foreach ( $template_ids as $each_template_id ) {
				$data_templates[] = pdr_get_design_template( $each_template_id ) ;
			}
			return $data_templates ;
		}

	}

}

