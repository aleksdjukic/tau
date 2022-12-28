<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'PDR_Admin_Menu' ) ) :

	class PDR_Admin_Menu {

		public function __construct() {
			add_action( 'admin_menu', array( $this, 'create_menu' ) );
			add_action( 'init', array( $this, 'register_setting_options' ) );
			add_action( 'admin_head', array( $this, 'rearrange_menu_link_structure' ), 30 );
			add_filter( 'woocommerce_screen_ids', array( $this, 'add_custom_wc_screen_ids' ), 9, 1 );
			add_filter( 'parent_file', array( $this, 'set_current_menu' ) );
			add_filter( 'submenu_file', array( $this, 'set_sub_menu' ) );
			//hide view link
			$list_of_categories = array( 'pdr_clipart_cat', 'pdr_product_base_cat', 'pdr_design_template_cat', 'pdr_shapes_cat' );
			foreach ( $list_of_categories as $each_category ) {
				add_filter( $each_category . '_row_actions', array( $this, 'remove_row_actions' ), 10, 2 );
			}

			add_action( 'pre_get_posts', array( $this, 'hide_pdr_attachments_list_view' ) );
			add_filter( 'ajax_query_attachments_args', array( $this, 'hide_pdr_attachments_grid_view' ) );
		}

		/**
		 * Add the custom screen ids in WooCommerce.
		 * 
		 * @return array.
		 */
		public function add_custom_wc_screen_ids( $wc_screen_ids ) {
			$screen_ids = pdr_page_screen_ids();

			$newscreenids = get_current_screen();
			$screenid     = str_replace( 'edit-', '', $newscreenids->id );

			// Return if current page is not product designer page.
			if ( ! in_array( $screenid, $screen_ids ) ) {
				return $wc_screen_ids;
			}

			$wc_screen_ids[] = $screenid;

			return $wc_screen_ids;
		}

		public function create_menu() {
			add_menu_page( __( 'Product Designer for WooCommerce', 'product-designer-for-woocommerce' ), __( 'Product Designer', 'product-designer-for-woocommerce' ), 'manage_woocommerce', 'product-designer-for-woocommerce', null, 'dashicons-buddicons-topics', 50 );
			//          add_submenu_page( 'product-designer-for-woocommerce' , __( 'Dashboard' , 'product-designer-for-woocommerce' ) , __( 'Dashboard' , 'product-designer-for-woocommerce' ) , 'manage_woocommerce' , 'pdr_dashboard' , array( 'PDR_Admin_Dashboard' , 'dashboard' ) ) ;
			$array_of_submenus = array(
				__( 'Product Base Categories', 'product-designer-for-woocommerce' )    => 'edit-tags.php?taxonomy=pdr_product_base_cat&post_type=pdr_product_base',
				__( 'Design Template Categories', 'product-designer-for-woocommerce' ) => 'edit-tags.php?taxonomy=pdr_design_template_cat&post_type=pdr_design_templates',
				__( 'Cliparts Categories', 'product-designer-for-woocommerce' )        => 'edit-tags.php?taxonomy=pdr_clipart_cat&post_type=pdr_cliparts',
				__( 'Shapes Categories', 'product-designer-for-woocommerce' )          => 'edit-tags.php?taxonomy=pdr_shapes_cat&post_type=pdr_shapes' );

			foreach ( $array_of_submenus as $sub_menu => $each_menu ) {
				add_submenu_page( 'product-designer-for-woocommerce', $sub_menu, $sub_menu, 'manage_woocommerce', $each_menu, false );
			}
			$settings_page = add_submenu_page( 'product-designer-for-woocommerce', __( 'Settings', 'product-designer-for-woocommerce' ), __( 'Settings', 'product-designer-for-woocommerce' ), 'manage_woocommerce', 'pdr_settings', array( $this, 'settings_page' ) );
			add_action( 'load-' . $settings_page, array( $this, 'settings_page_init' ) );
		}

		/**
		 * Register the setting options.
		 * */
		public function register_setting_options() {
			// Include settings tabs.
			$tabs = PDR_Settings_Page::get_settings_tabs();

			if ( ! pdr_check_is_array( $tabs ) ) {
				return;
			}

			foreach ( $tabs as $tab ) {
				if ( ! is_object( $tab ) ) {
					continue;
				}

				// Register the settings.
				$tab->register_settings();
			}
		}

		/**
		 * Highlight the custom taxonomy menu 
		 */
		public function set_current_menu( $parent_file ) {

			global $submenu_file, $current_screen, $pagenow;
			$array_of_post_type = array( 'pdr_product_base', 'pdr_design_templates', 'pdr_cliparts', 'pdr_shapes' );
			if ( in_array( $current_screen->post_type, $array_of_post_type ) ) {

				if ( 'edit-tags.php' == $pagenow && isset( $_REQUEST[ 'taxonomy' ] ) && ! empty( $_REQUEST[ 'taxonomy' ] ) ) {
					$taxonomy = wc_clean( $_REQUEST[ 'taxonomy' ] );
					//$submenu_file = 'edit-tags.php?taxonomy=' . $taxonomy . '&post_type=' . $current_screen->post_type ;
				}

				$parent_file = 'product-designer-for-woocommerce';
			}

			return $parent_file;
		}

		/**
		 * Highlight the custom taxonomy menu 
		 */
		public function set_sub_menu( $submenu ) {

			global $submenu_file, $current_screen, $pagenow;
			$array_of_post_type = array( 'pdr_product_base', 'pdr_design_templates', 'pdr_cliparts', 'pdr_shapes' );
			if ( in_array( $current_screen->post_type, $array_of_post_type ) ) {

				if ( 'edit-tags.php' == $pagenow && isset( $_REQUEST[ 'taxonomy' ] ) && ! empty( $_REQUEST[ 'taxonomy' ] ) ) {
					$taxonomy = wc_clean( $_REQUEST[ 'taxonomy' ] );
					$submenu  = 'edit-tags.php?taxonomy=' . $taxonomy . '&post_type=' . $current_screen->post_type;
					return $submenu;
				}
			}

			return $submenu;
		}

		/**
		 * Display the Setting page.
		 */
		public function settings_page() {
			PDR_Settings_Page::output();
		}

		/**
		 * Settings page init.
		 * */
		public function settings_page_init() {

			global $current_tab, $current_section;

			// Include settings pages.
			$tabs = PDR_Settings_Page::get_settings_tabs();

			// Get the current tab.
			$current_tab = key( $tabs );
			$section     = array();
			if ( ! empty( $_GET[ 'tab' ] ) ) {
				$sanitize_current_tab = sanitize_title( wp_unslash( $_GET[ 'tab' ] ) ); // @codingStandardsIgnoreLine.
				if ( array_key_exists( $sanitize_current_tab, $tabs ) ) {
					$current_tab = $sanitize_current_tab;
				}
			}

			// Prepare the current section.
			$current_section = empty( $_REQUEST[ 'section' ] ) ? key( $section ) : sanitize_title( wp_unslash( $_REQUEST[ 'section' ] ) ); // @codingStandardsIgnoreLine.
			$current_section = empty( $current_section ) ? $current_tab : $current_section;

			if ( isset( $tabs[ $current_tab ] ) ) {
				$section = $tabs[ $current_tab ]->get_sections();

				// Add the settings.
				$tabs[ $current_tab ]->add_settings_fields( $current_section );
			}
		}

		/**
		 * Rearrange the Product designer menu order.
		 */
		public function rearrange_menu_link_structure() {
			global $submenu;
			// User does not have capabilites to see the submenu.
			if ( ! current_user_can( 'manage_woocommerce' ) || empty( $submenu[ 'product-designer-for-woocommerce' ] ) ) {
				return;
			}

			$dashboard_menu_key = null;
			foreach ( $submenu[ 'product-designer-for-woocommerce' ] as $submenu_key => $submenu_item ) {
				if ( 'pdr_dashboard' === $submenu_item[ 2 ] ) {
					$dashboard_menu_key = $submenu_key;
					break;
				}
			}

			if ( ! $dashboard_menu_key ) {
				return;
			}

			$menu = $submenu[ 'product-designer-for-woocommerce' ][ $dashboard_menu_key ];

			// Move menu item to top of array.
			unset( $submenu[ 'product-designer-for-woocommerce' ][ $dashboard_menu_key ] );
			array_unshift( $submenu[ 'product-designer-for-woocommerce' ], $menu );
		}

		/**
		 * Remove View Link in Custom Taxonomy
		 */
		public function remove_row_actions( $action, $tag ) {
			unset( $action[ 'view' ] );
			return $action;
		}

		/**
		 * Hide our attachments list wise.
		 * 
		 * @param WP_Query $query
		 */
		public function hide_pdr_attachments_list_view( $query ) {
			// Bail if this is not the admin area.
			if ( ! is_admin() ) {
				return;
			}

			// Bail if this is not the main query.
			if ( ! $query->is_main_query() ) {
				return;
			}

			// Only proceed if this the attachment upload screen.
			$screen = get_current_screen();
			if ( ! $screen || 'upload' !== $screen->id || 'attachment' !== $screen->post_type ) {
				return;
			}

			// Modify the query.
			$query->set( 'meta_query', array(
				array(
					'key'     => 'is_pdr',
					'compare' => 'NOT EXISTS',
				)
			) );
			return;
		}

		/**
		 * Hide our attachments grid wise.
		 * 
		 * @return array
		 */
		public function hide_pdr_attachments_grid_view( $args ) {
			// Bail if this is not the admin area.
			if ( ! is_admin() ) {
				return $args;
			}

			// Modify the query.
			$args[ 'meta_query' ] = array(
				array(
					'key'     => 'is_pdr',
					'compare' => 'NOT EXISTS',
				)
			);

			return $args;
		}

	}

	new PDR_Admin_Menu();

	
endif;
