<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'PDR_API' ) ) {

	class PDR_API {

		public function __construct( $pdr_id = '', $product_id = '' ) {
			$this->pdr_id     = $pdr_id;
			$this->product_id = $product_id;
		}

		public function get_cliparts() {
			$data_cliparts = array();
			$featured_args = array(
				'numberposts' => -1,
				'post_type'   => 'pdr_cliparts',
				'post_status' => 'publish',
				'fields'      => 'ids',
				'meta_query'  => array(
					array(
						'key'     => 'pdr_featured',
						'value'   => 'yes',
						'compare' => '=',
					)
				) );

			$args = array(
				'numberposts' => -1,
				'post_type'   => 'pdr_cliparts',
				'post_status' => 'publish',
				'fields'      => 'ids',
			);

			$featured_cliparts = get_posts( $featured_args );
			$cliparts          = get_posts( $args );
			$latest_cliparts   = array_unique( array_filter( array_merge( $featured_cliparts, $cliparts ) ) );

			if ( is_array( $latest_cliparts ) && ! empty( $latest_cliparts ) ) {
				//fetch featured image
				foreach ( $latest_cliparts as $each_clipart ) {

					$data_cliparts[] = pdr_get_clipart( $each_clipart );
				}
			}
			return $data_cliparts;
		}

		public function get_shapes() {
			$data_shapes = array();
			$args        = array(
				'numberposts' => -1,
				'post_type'   => 'pdr_shapes',
				'post_status' => 'publish',
				'fields'      => 'ids'
			);
			$get_shapes  = get_posts( $args );
			if ( is_array( $get_shapes ) && ! empty( $get_shapes ) ) {
				foreach ( $get_shapes as $each_shape ) {
					$data_shapes[] = pdr_get_shape( $each_shape );
				}
			}
			return $data_shapes;
		}

		public function get_templates() {
			$design_templates_data     = array();
			$args                      = array(
				'numberposts' => -1,
				'post_type'   => 'pdr_design_templates',
				'post_status' => 'publish',
				'fields'      => 'ids'
			);
			$featured_args             = array(
				'numberposts' => -1,
				'post_type'   => 'pdr_design_templates',
				'post_status' => 'publish',
				'fields'      => 'ids',
				'meta_query'  => array(
					array(
						'key'     => 'pdr_featured',
						'value'   => 'yes',
						'compare' => '=',
					)
				) );
			$featured_design_templates = get_posts( $featured_args );
			$design_templates          = get_posts( $args );
			$design_templates          = array_unique( array_filter( array_merge( $featured_design_templates, $design_templates ) ) );

			if ( is_array( $design_templates ) && ! empty( $design_templates ) ) {
				foreach ( $design_templates as $design_template ) {
					$design_templates_data[] = pdr_get_design_template( $design_template );
				}
			}

			return $design_templates_data;
		}

		public function product_base_data() {
			return get_post_meta( $this->pdr_id, 'pdr_rules', true );
		}

		public function get_fonts() {
			$get_default_fonts = $this->get_default_fonts();
			$get_google_fonts  = $this->get_google_fonts();
			$fonts             = array_merge( $get_default_fonts, $get_google_fonts );
			return $fonts;
		}

		private function get_selected_fonts_from_settings() {
			$get_font                = array();
			$get_font_selection_type = get_option( 'pdr_font_selection_type' );
			if ( '2' == $get_font_selection_type ) {
				$get_font = get_option( 'pdr_font_selection' );
			}
			return $get_font;
		}

		private function get_selected_fonts_from_base() {
			$get_font = array();
			$get_type = get_post_meta( $this->pdr_id, 'pdr_font_selection_type', true );
			if ( '2' == $get_type ) {
				$get_font = get_post_meta( $this->pdr_id, 'pdr_font_selection', true );
			}
			return $get_font;
		}

		private function get_selected_fonts() {
			$get_fonts = array();
			if ( $this->pdr_id ) {
				$get_fonts = $this->get_selected_fonts_from_base();
				if ( ! $get_fonts ) {
					$get_fonts = $this->get_selected_fonts_from_settings();
				}
			}
			return $get_fonts;
		}

		public function get_default_fonts() {
			$get_fonts           = $this->get_selected_fonts();
			/**
			 * Alter Default Font Families
			 *
			 * @since 2.8
			 */
			$default_font_family = apply_filters( 'pdr_default_font_families', array( 'Arial' => 'Arial', 'Courier New' => 'Courier New', 'Georgia' => 'Georgia', 'Times New Roman' => 'Times New Roman', 'Lucida Console' => 'Lucida Console', 'Verdana' => 'Verdana' ) );
			$default_font_family = is_array( $get_fonts ) && ! empty( $get_fonts ) ? array_intersect( $default_font_family, $get_fonts ) : $default_font_family;
			/**
			 * Alter Default and Custom Font Families
			 *
			 * @since 4.3
			 */
			return apply_filters( 'pdr_font_families', $default_font_family );
		}

		public function get_google_fonts() {
			$get_selected_fonts = $this->get_selected_fonts();
			$google_font_family = array();
			$google_fonts       = new PDR_Google_Fonts();
			$get_fonts          = $google_fonts->get_fonts();
			$decode             = json_decode( $get_fonts );
			if ( isset( $decode->items ) ) {
				$get_google_items = $decode->items;
				if ( is_array( $get_google_items ) && ! empty( $get_google_items ) ) {
					foreach ( $get_google_items as $gkey => $gobj ) {
						$google_font_family[ $gobj->family ] = $gobj->family;
					}
				}
			}
			$google_font_family = is_array( $get_selected_fonts ) && ! empty( $get_selected_fonts ) ? array_intersect( $google_font_family, $get_selected_fonts ) : $google_font_family;
			/**
			 * Alter google font families
			 *
			 * @since 2.8
			 */
			return apply_filters( 'pdr_google_font_families', $google_font_family );
		}

	}

}
