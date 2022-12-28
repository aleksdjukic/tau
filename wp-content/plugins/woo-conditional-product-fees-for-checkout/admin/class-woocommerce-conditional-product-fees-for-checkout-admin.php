<?php

// phpcs:ignore
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.multidots.com
 * @package    Woocommerce_Conditional_Product_Fees_For_Checkout_Pro
 * @subpackage Woocommerce_Conditional_Product_Fees_For_Checkout_Pro/admin
 * @since      1.0.0
 * @author     Multidots <inquiry@multidots.in>
 */
class Woocommerce_Conditional_Product_Fees_For_Checkout_Pro_Admin
{
    const  wcpfc_post_type = 'wc_conditional_fee' ;
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private  $version ;
    /**
     * Initialize the class and set its properties.
     *
     * @param string $plugin_name
     * @param string $version
     *
     * @since    1.0.0
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles( $hook )
    {
        
        if ( strpos( $hook, 'dotstore-plugins_page_wcpf' ) !== false ) {
            wp_enqueue_style(
                $this->plugin_name . 'select2-min',
                plugin_dir_url( __FILE__ ) . 'css/select2.min.css',
                array(),
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . '-jquery-ui-css',
                plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . '-timepicker-min-css',
                plugin_dir_url( __FILE__ ) . 'css/jquery.timepicker.min.css',
                $this->version,
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . 'font-awesome',
                plugin_dir_url( __FILE__ ) . 'css/font-awesome.min.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . '-webkit-css',
                plugin_dir_url( __FILE__ ) . 'css/webkit.css',
                array(),
                $this->version,
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . 'main-style',
                plugin_dir_url( __FILE__ ) . 'css/style.css',
                array(),
                'all'
            );
            wp_enqueue_style(
                $this->plugin_name . 'media-css',
                plugin_dir_url( __FILE__ ) . 'css/media.css',
                array(),
                'all'
            );
        }
    
    }
    
    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts( $hook )
    {
        global  $wp ;
        wp_enqueue_style( 'wp-jquery-ui-dialog' );
        wp_enqueue_script( 'jquery-ui-accordion' );
        
        if ( strpos( $hook, 'dotstore-plugins_page_wcpf' ) !== false ) {
            wp_enqueue_script(
                $this->plugin_name . '-select2-full-min',
                plugin_dir_url( __FILE__ ) . 'js/select2.full.min.js',
                array( 'jquery', 'jquery-ui-datepicker' ),
                $this->version,
                false
            );
            wp_enqueue_script(
                $this->plugin_name . '-chart-js',
                plugin_dir_url( __FILE__ ) . 'js/chart.js',
                array( 'jquery' ),
                $this->version,
                false
            );
            wp_enqueue_script(
                $this->plugin_name,
                plugin_dir_url( __FILE__ ) . 'js/woocommerce-conditional-product-fees-for-checkout-admin.js',
                array(
                'jquery',
                'jquery-ui-dialog',
                'jquery-ui-accordion',
                'jquery-ui-sortable',
                'select2'
            ),
                $this->version,
                false
            );
            wp_enqueue_script( 'jquery-tiptip' );
            wp_enqueue_script( 'jquery-blockui' );
            wp_enqueue_script(
                $this->plugin_name . '-tablesorter-js',
                plugin_dir_url( __FILE__ ) . 'js/jquery.tablesorter.js',
                array( 'jquery' ),
                $this->version,
                false
            );
            wp_enqueue_script(
                $this->plugin_name . '-timepicker-js',
                plugin_dir_url( __FILE__ ) . 'js/jquery.timepicker.js',
                array( 'jquery' ),
                $this->version,
                false
            );
            wp_localize_script( $this->plugin_name, 'coditional_vars', array(
                'ajaxurl'                        => admin_url( 'admin-ajax.php' ),
                'ajax_icon'                      => esc_url( plugin_dir_url( __FILE__ ) . '/images/ajax-loader.gif' ),
                'plugin_url'                     => plugin_dir_url( __FILE__ ),
                'dsm_ajax_nonce'                 => wp_create_nonce( 'dsm_nonce' ),
                'disable_fees_ajax_nonce'        => wp_create_nonce( 'disable_fees_nonce' ),
                'country'                        => esc_html__( 'Country', 'woocommerce-conditional-product-fees-for-checkout' ),
                'city'                           => esc_html__( 'City', 'woocommerce-conditional-product-fees-for-checkout' ),
                'cart_contains_product'          => esc_html__( 'Cart contains product', 'woocommerce-conditional-product-fees-for-checkout' ),
                'cart_contains_variable_product' => esc_html__( 'Cart contains variable product', 'woocommerce-conditional-product-fees-for-checkout' ),
                'cart_contains_category_product' => esc_html__( 'Cart contains category\'s product', 'woocommerce-conditional-product-fees-for-checkout' ),
                'cart_contains_tag_product'      => esc_html__( 'Cart contains tag\'s product', 'woocommerce-conditional-product-fees-for-checkout' ),
                'cart_contains_product_qty'      => esc_html__( 'Cart contains product\'s product', 'woocommerce-conditional-product-fees-for-checkout' ),
                'city_msg'                       => esc_html__( 'Make sure enter each city name in one line.', 'woocommerce-conditional-product-fees-for-checkout' ),
                'user'                           => esc_html__( 'User', 'woocommerce-conditional-product-fees-for-checkout' ),
                'cart_subtotal_before_discount'  => esc_html__( 'Cart Subtotal (Before Discount)', 'woocommerce-conditional-product-fees-for-checkout' ),
                'quantity'                       => esc_html__( 'Quantity', 'woocommerce-conditional-product-fees-for-checkout' ),
                'equal_to'                       => esc_html__( 'Equal to ( = )', 'woocommerce-conditional-product-fees-for-checkout' ),
                'not_equal_to'                   => esc_html__( 'Not Equal to ( != )', 'woocommerce-conditional-product-fees-for-checkout' ),
                'less_or_equal_to'               => esc_html__( 'Less or Equal to ( <= )', 'woocommerce-conditional-product-fees-for-checkout' ),
                'less_than'                      => esc_html__( 'Less then ( < )', 'woocommerce-conditional-product-fees-for-checkout' ),
                'greater_or_equal_to'            => esc_html__( 'greater or Equal to ( >= )', 'woocommerce-conditional-product-fees-for-checkout' ),
                'greater_than'                   => esc_html__( 'greater then ( > )', 'woocommerce-conditional-product-fees-for-checkout' ),
                'validation_length1'             => esc_html__( 'Please enter 3 or more characters', 'woocommerce-conditional-product-fees-for-checkout' ),
                'select_category'                => esc_html__( 'Select Category', 'woocommerce-conditional-product-fees-for-checkout' ),
                'delete'                         => esc_html__( 'Delete', 'woocommerce-conditional-product-fees-for-checkout' ),
                'validation_length2'             => esc_html__( 'Please enter', 'woocommerce-conditional-product-fees-for-checkout' ),
                'validation_length3'             => esc_html__( 'or more characters', 'woocommerce-conditional-product-fees-for-checkout' ),
                'location_specific'              => esc_html__( 'Location Specific', 'woocommerce-conditional-product-fees-for-checkout' ),
                'product_specific'               => esc_html__( 'Product Specific', 'woocommerce-conditional-product-fees-for-checkout' ),
                'shipping_specific'              => esc_html__( 'Shipping Specific', 'woocommerce-conditional-product-fees-for-checkout' ),
                'user_specific'                  => esc_html__( 'User Specific', 'woocommerce-conditional-product-fees-for-checkout' ),
                'cart_specific'                  => esc_html__( 'Cart Specific', 'woocommerce-conditional-product-fees-for-checkout' ),
                'payment_specific'               => esc_html__( 'Payment Specific', 'woocommerce-conditional-product-fees-for-checkout' ),
                'ajax_redirect_after'            => esc_url( admin_url( 'admin.php?page=wcpfc-pro-list' ) ),
                'success_msg1'                   => esc_html__( 'Fees order has been saved successfully', 'woocommerce-conditional-product-fees-for-checkout' ),
                'success_msg2'                   => esc_html__( 'Your settings has been saved successfully. Reload in moment.', 'woocommerce-conditional-product-fees-for-checkout' ),
                'warning_msg1'                   => sprintf( __( '<p><b style="color: red;">Note: </b>If entered price is more than total fee price than Message looks like: <b>Fee Name: Curreny Symbole like($) -60.00 Price </b> and if fee minus price is more than total price than it will set Total Price to Zero(0).</p>', 'woocommerce-conditional-product-fees-for-checkout' ) ),
                'select_chk'                     => esc_html__( 'Please select at least one checkbox', 'woocommerce-conditional-product-fees-for-checkout' ),
                'change_status'                  => esc_html__( 'Are You Sure You Want To Change The Status?', 'woocommerce-conditional-product-fees-for-checkout' ),
                'select_atleast_one_checkbox'    => esc_html__( 'Please select at least one checkbox', 'woocommerce-conditional-product-fees-for-checkout' ),
                'delete_confirmation_msg'        => esc_html__( 'Are You Sure You Want to Delete?', 'woocommerce-conditional-product-fees-for-checkout' ),
                'note'                           => esc_html__( 'Note: ', 'woocommerce-conditional-product-fees-for-checkout' ),
                'click_here'                     => esc_html__( 'Click Here', 'woocommerce-conditional-product-fees-for-checkout' ),
                'doc_url'                        => "https://docs.thedotstore.com/category/191-premium-plugin-settings",
                'list_page_url'                  => add_query_arg( array(
                'page' => 'wcpfc-pro-list',
            ), admin_url( 'admin.php' ) ),
                'currency_symbol'                => esc_attr( get_woocommerce_currency_symbol() ),
            ) );
        }
    
    }
    
    /**
     * Register Admin menu pages.
     *
     * @since    1.0.0
     */
    public function wcpfc_pro_admin_menu_pages()
    {
        if ( empty($GLOBALS['admin_page_hooks']['dots_store']) ) {
            add_menu_page(
                'DotStore Plugins',
                __( 'DotStore Plugins', 'woocommerce-conditional-product-fees-for-checkout' ),
                'null',
                'dots_store',
                array( $this, 'wcpfc-pro-list' ),
                WCPFC_PRO_PLUGIN_URL . 'admin/images/menu-icon.png',
                25
            );
        }
        add_submenu_page(
            'dots_store',
            'Get Started',
            'Get Started',
            'manage_options',
            'wcpfc-pro-get-started',
            array( $this, 'wcpfc_pro_get_started_page' )
        );
        add_submenu_page(
            'dots_store',
            'Introduction',
            'Introduction',
            'manage_options',
            'wcpfc-pro-information',
            array( $this, 'wcpfc_pro_information_page' )
        );
        add_submenu_page(
            'dots_store',
            'WooCommerce Conditional Product Fees for Checkout',
            __( 'WooCommerce Conditional Product Fees for Checkout', 'woocommerce-conditional-product-fees-for-checkout' ),
            'manage_options',
            'wcpfc-pro-list',
            array( $this, 'wcpfc_pro_fee_list_page' )
        );
        add_submenu_page(
            'dots_store',
            'Add New',
            'Add New',
            'manage_options',
            'wcpfc-pro-add-new',
            array( $this, 'wcpfc_pro_add_new_fee_page' )
        );
        add_submenu_page(
            'dots_store',
            'Edit Fee',
            'Edit Fee',
            'manage_options',
            'wcpfc-pro-edit-fee',
            array( $this, 'wcpfc_pro_edit_fee_page' )
        );
        add_submenu_page(
            'dots_store',
            'Premium Version',
            'Premium Version',
            'manage_options',
            'wcpfc-premium',
            array( $this, 'premium_version_wcpfc_page' )
        );
    }
    
    /**
     * Register Admin information page output.
     *
     * @since    1.0.0
     */
    public function wcpfc_pro_information_page()
    {
        require_once plugin_dir_path( __FILE__ ) . '/partials/wcpfc-pro-information-page.php';
    }
    
    /**
     * Register Admin fee list page output.
     *
     * @since    1.0.0
     */
    public function wcpfc_pro_fee_list_page()
    {
        require_once plugin_dir_path( __FILE__ ) . '/partials/wcpfc_pro_list-page.php';
        $wcpfc_rule_lising_obj = new WCPFC_Rule_Listing_Page();
        $wcpfc_rule_lising_obj->wcpfc_sj_output();
    }
    
    /**
     * Register Admin add new fee condition page output.
     *
     * @since    1.0.0
     */
    public function wcpfc_pro_add_new_fee_page()
    {
        require_once plugin_dir_path( __FILE__ ) . '/partials/wcpfc-pro-add-new-page.php';
    }
    
    /**
     * Register Admin edit fee condition page output.
     *
     * @since    1.0.0
     */
    public function wcpfc_pro_edit_fee_page()
    {
        require_once plugin_dir_path( __FILE__ ) . '/partials/wcpfc-pro-add-new-page.php';
    }
    
    /**
     * Register Admin get started page output.
     *
     */
    public function wcpfc_pro_get_started_page()
    {
        require_once plugin_dir_path( __FILE__ ) . '/partials/wcpfc-pro-get-started-page.php';
    }
    
    /**
     * Premium version info page
     *
     */
    public function premium_version_wcpfc_page()
    {
        require_once plugin_dir_path( __FILE__ ) . '/partials/wcpfc-premium-version-page.php';
    }
    
    /**
     * Get meta value by meta key.
     *
     * @param string $value
     *
     * @return bool if field is empty otherwise return string
     * @since 1.0.0
     *
     */
    function wcpfc_pro_fee_settings_get_meta( $value )
    {
        global  $post ;
        $field = get_post_meta( $post->ID, $value, true );
        
        if ( !empty($field) ) {
            return ( is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) ) );
        } else {
            return false;
        }
    
    }
    
    /**
     * Save fees data
     *
     * @param array $post
     *
     * @return false if post data will empty other wise it will redirect to list of fess page.
     * @since 1.0.0
     *
     */
    function wcpfc_pro_fees_conditions_save( $post )
    {
        global  $sitepress ;
        if ( empty($post) ) {
            return false;
        }
        $post_type = filter_input( INPUT_POST, 'post_type', FILTER_SANITIZE_STRING );
        $wcpfc_pro_conditions_save = filter_input( INPUT_POST, 'wcpfc_pro_fees_conditions_save', FILTER_SANITIZE_STRING );
        
        if ( isset( $wcpfc_pro_conditions_save, $post_type ) && wp_verify_nonce( sanitize_text_field( $wcpfc_pro_conditions_save ), 'wcpfc_pro_fees_conditions_save_action' ) && self::wcpfc_post_type === $post_type ) {
            delete_transient( "get_all_fees" );
            $method_id = filter_input( INPUT_POST, 'fee_post_id', FILTER_SANITIZE_NUMBER_INT );
            $get_fee_settings_product_fee_title = filter_input( INPUT_POST, 'fee_settings_product_fee_title', FILTER_SANITIZE_STRING );
            $get_fee_settings_product_cost = filter_input( INPUT_POST, 'fee_settings_product_cost', FILTER_SANITIZE_STRING );
            $get_fee_settings_select_fee_type = filter_input( INPUT_POST, 'fee_settings_select_fee_type', FILTER_SANITIZE_STRING );
            $get_fee_settings_start_date = filter_input( INPUT_POST, 'fee_settings_start_date', FILTER_SANITIZE_STRING );
            $get_fee_settings_end_date = filter_input( INPUT_POST, 'fee_settings_end_date', FILTER_SANITIZE_STRING );
            $get_fee_settings_status = filter_input( INPUT_POST, 'fee_settings_status', FILTER_SANITIZE_STRING );
            $get_fee_settings_select_taxable = filter_input( INPUT_POST, 'fee_settings_select_taxable', FILTER_SANITIZE_STRING );
            $get_fee_show_on_checkout_only = filter_input( INPUT_POST, 'fee_show_on_checkout_only', FILTER_SANITIZE_STRING );
            $get_fees_on_cart_total = filter_input( INPUT_POST, 'fees_on_cart_total', FILTER_SANITIZE_STRING );
            $get_ds_time_from = filter_input( INPUT_POST, 'ds_time_from', FILTER_SANITIZE_STRING );
            $get_ds_time_to = filter_input( INPUT_POST, 'ds_time_to', FILTER_SANITIZE_STRING );
            $get_ds_select_day_of_week = filter_input(
                INPUT_POST,
                'ds_select_day_of_week',
                FILTER_SANITIZE_STRING,
                FILTER_REQUIRE_ARRAY
            );
            $fee_settings_product_fee_title = ( isset( $get_fee_settings_product_fee_title ) ? sanitize_text_field( $get_fee_settings_product_fee_title ) : '' );
            $fee_settings_product_cost = ( isset( $get_fee_settings_product_cost ) ? sanitize_text_field( $get_fee_settings_product_cost ) : '' );
            $fee_settings_select_fee_type = ( isset( $get_fee_settings_select_fee_type ) ? sanitize_text_field( $get_fee_settings_select_fee_type ) : '' );
            $fee_settings_start_date = ( isset( $get_fee_settings_start_date ) ? sanitize_text_field( $get_fee_settings_start_date ) : '' );
            $fee_settings_end_date = ( isset( $get_fee_settings_end_date ) ? sanitize_text_field( $get_fee_settings_end_date ) : '' );
            $fee_settings_status = ( isset( $get_fee_settings_status ) ? sanitize_text_field( $get_fee_settings_status ) : 'off' );
            $fee_settings_select_taxable = ( isset( $get_fee_settings_select_taxable ) ? sanitize_text_field( $get_fee_settings_select_taxable ) : '' );
            $fee_show_on_checkout_only = ( isset( $get_fee_show_on_checkout_only ) ? sanitize_text_field( $get_fee_show_on_checkout_only ) : '' );
            $fees_on_cart_total = ( isset( $get_fees_on_cart_total ) ? sanitize_text_field( $get_fees_on_cart_total ) : '' );
            $ds_time_from = ( isset( $get_ds_time_from ) ? sanitize_text_field( $get_ds_time_from ) : '' );
            $ds_time_to = ( isset( $get_ds_time_to ) ? sanitize_text_field( $get_ds_time_to ) : '' );
            $ds_select_day_of_week = ( isset( $get_ds_select_day_of_week ) ? array_map( 'sanitize_text_field', $get_ds_select_day_of_week ) : array() );
            $get_condition_key = filter_input(
                INPUT_POST,
                'condition_key',
                FILTER_SANITIZE_STRING,
                FILTER_REQUIRE_ARRAY
            );
            $fees = filter_input(
                INPUT_POST,
                'fees',
                FILTER_SANITIZE_STRING,
                FILTER_REQUIRE_ARRAY
            );
            
            if ( isset( $fee_settings_status ) && !empty($fee_settings_status) && "on" === $fee_settings_status ) {
                $post_status = 'publish';
            } else {
                $post_status = 'draft';
            }
            
            
            if ( '' === $method_id ) {
                $fee_post = array(
                    'post_title'  => wp_strip_all_tags( $fee_settings_product_fee_title ),
                    'post_status' => $post_status,
                    'post_type'   => self::wcpfc_post_type,
                );
                $post_id = wp_insert_post( $fee_post );
            } else {
                $fee_post = array(
                    'ID'          => sanitize_text_field( $method_id ),
                    'post_title'  => wp_strip_all_tags( $fee_settings_product_fee_title ),
                    'post_status' => $post_status,
                    'post_type'   => self::wcpfc_post_type,
                );
                $post_id = wp_update_post( $fee_post );
            }
            
            if ( '' !== $post_id && 0 !== $post_id ) {
                
                if ( $post_id > 0 ) {
                    $feesArray = array();
                    $conditionsValuesArray = array();
                    $condition_key = ( isset( $get_condition_key ) ? $get_condition_key : array() );
                    $fees_conditions = $fees['product_fees_conditions_condition'];
                    $conditions_is = $fees['product_fees_conditions_is'];
                    $conditions_values = ( isset( $fees['product_fees_conditions_values'] ) && !empty($fees['product_fees_conditions_values']) ? $fees['product_fees_conditions_values'] : array() );
                    $size = count( $fees_conditions );
                    foreach ( array_keys( $condition_key ) as $key ) {
                        if ( !array_key_exists( $key, $conditions_values ) ) {
                            $conditions_values[$key] = array();
                        }
                    }
                    uksort( $conditions_values, 'strnatcmp' );
                    foreach ( $conditions_values as $v ) {
                        $conditionsValuesArray[] = $v;
                    }
                    for ( $i = 0 ;  $i < $size ;  $i++ ) {
                        $feesArray[] = array(
                            'product_fees_conditions_condition' => $fees_conditions[$i],
                            'product_fees_conditions_is'        => $conditions_is[$i],
                            'product_fees_conditions_values'    => $conditionsValuesArray[$i],
                        );
                    }
                    update_post_meta( $post_id, 'fee_settings_product_cost', $fee_settings_product_cost );
                    update_post_meta( $post_id, 'fee_settings_select_fee_type', $fee_settings_select_fee_type );
                    update_post_meta( $post_id, 'fee_settings_start_date', $fee_settings_start_date );
                    update_post_meta( $post_id, 'fee_settings_end_date', $fee_settings_end_date );
                    update_post_meta( $post_id, 'fee_settings_status', $fee_settings_status );
                    update_post_meta( $post_id, 'fee_settings_select_taxable', $fee_settings_select_taxable );
                    update_post_meta( $post_id, 'fee_show_on_checkout_only', $fee_show_on_checkout_only );
                    update_post_meta( $post_id, 'fees_on_cart_total', $fees_on_cart_total );
                    update_post_meta( $post_id, 'ds_time_from', $ds_time_from );
                    update_post_meta( $post_id, 'ds_time_to', $ds_time_to );
                    update_post_meta( $post_id, 'ds_select_day_of_week', $ds_select_day_of_week );
                    update_post_meta( $post_id, 'product_fees_metabox', $feesArray );
                    if ( !empty($sitepress) ) {
                        do_action(
                            'wpml_register_single_string',
                            'woocommerce-conditional-product-fees-for-checkout',
                            sanitize_text_field( $post['fee_settings_product_fee_title'] ),
                            sanitize_text_field( $post['fee_settings_product_fee_title'] )
                        );
                    }
                } else {
                    echo  '<div class="updated error"><p>' . esc_html__( 'Error saving Fees.', 'woocommerce-conditional-product-fees-for-checkout' ) . '</p></div>' ;
                    return false;
                }
            
            }
            
            if ( is_network_admin() ) {
                $admin_url = admin_url( 'admin.php' );
            } else {
                $admin_url = admin_url( 'admin.php' );
            }
            
            wp_safe_redirect( add_query_arg( array(
                'page'    => 'wcpfc-pro-list',
                'success' => 'true',
            ), $admin_url ) );
            exit;
        }
    
    }
    
    /**
     * It will display notification message
     *
     * @since 1.0.0
     */
    function wcpfc_pro_notifications()
    {
        $page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_SPECIAL_CHARS );
        $success = filter_input( INPUT_GET, 'success', FILTER_SANITIZE_SPECIAL_CHARS );
        $delete = filter_input( INPUT_GET, 'delete', FILTER_SANITIZE_STRING );
        
        if ( isset( $page, $success ) && $page === ' wcpfc-pro-list' && $success === 'true' ) {
            ?>
			<div class="updated notice is-dismissible">
				<p><?php 
            esc_html_e( 'Fee rule has been successfully saved', 'woocommerce-conditional-product-fees-for-checkout' );
            ?></p>
			</div>
			<?php 
        } else {
            
            if ( isset( $page, $delete ) && $page === 'wcpfc-pro-list' && $delete === 'true' ) {
                ?>
			<div class="updated notice is-dismissible">
				<p><?php 
                esc_html_e( 'Fee rule has been successfully deleted', 'woocommerce-conditional-product-fees-for-checkout' );
                ?></p>
			</div>
			<?php 
            }
        
        }
    
    }
    
    /**
     * Get meta data of conditional fee
     *
     * @param string $value
     *
     * @return bool if $field is empty otherwise it will return string
     * @since 1.0.0
     *
     */
    function wcpfc_pro_product_fees_conditions_get_meta( $value )
    {
        global  $post ;
        $field = get_post_meta( $post->ID, $value, true );
        
        if ( isset( $field ) && !empty($field) ) {
            return ( is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) ) );
        } else {
            return false;
        }
    
    }
    
    /**
     * Display rule Like: country list, state list, zone list, city, postcode, product, category etc.
     *
     * @since 1.0.0
     */
    public function wcpfc_pro_product_fees_conditions_values_ajax()
    {
        $html = '';
        
        if ( check_ajax_referer( 'wcpfc_pro_product_fees_conditions_values_ajax_action', 'wcpfc_pro_product_fees_conditions_values_ajax' ) ) {
            $get_condition = filter_input( INPUT_GET, 'condition', FILTER_SANITIZE_STRING );
            $get_count = filter_input( INPUT_GET, 'count', FILTER_SANITIZE_NUMBER_INT );
            $posts_per_page = filter_input( INPUT_GET, 'posts_per_page', FILTER_VALIDATE_INT );
            $offset = filter_input( INPUT_GET, 'offset', FILTER_VALIDATE_INT );
            $condition = ( isset( $get_condition ) ? sanitize_text_field( $get_condition ) : '' );
            $count = ( isset( $get_count ) ? sanitize_text_field( $get_count ) : '' );
            $posts_per_page = ( isset( $posts_per_page ) ? sanitize_text_field( $posts_per_page ) : '' );
            $offset = ( isset( $offset ) ? sanitize_text_field( $offset ) : '' );
            $html = '';
            
            if ( 'country' === $condition ) {
                $html .= wp_json_encode( $this->wcpfc_pro_get_country_list( $count, [], true ) );
            } elseif ( 'city' === $condition ) {
                $html .= 'textarea';
            } elseif ( 'product' === $condition ) {
                $html .= wp_json_encode( $this->wcpfc_pro_get_product_list(
                    $count,
                    [],
                    '',
                    true
                ) );
            } elseif ( 'variableproduct' === $condition ) {
                $html .= wp_json_encode( $this->wcpfc_pro_get_varible_product_list(
                    $count,
                    [],
                    '',
                    true
                ) );
            } elseif ( 'category' === $condition ) {
                $html .= wp_json_encode( $this->wcpfc_pro_get_category_list( $count, [], true ) );
            } elseif ( 'tag' === $condition ) {
                $html .= wp_json_encode( $this->wcpfc_pro_get_tag_list( $count, [], true ) );
            } elseif ( 'product_qty' === $condition ) {
                $html .= 'input';
            } elseif ( 'user' === $condition ) {
                $html .= wp_json_encode( $this->wcpfc_pro_get_user_list( $count, [], true ) );
            } elseif ( 'cart_total' === $condition ) {
                $html .= 'input';
            } elseif ( 'quantity' === $condition ) {
                $html .= 'input';
            }
        
        }
        
        echo  wp_kses( $html, Woocommerce_Conditional_Product_Fees_For_Checkout_Pro::allowed_html_tags() ) ;
        wp_die();
        // this is required to terminate immediately and return a proper response
    }
    
    /**
     * Function for select country list
     *
     * @param string $count
     * @param array  $selected
     * @param bool   $json
     *
     * @return string or array $html
     * @since 1.0.0
     *
     */
    public function wcpfc_pro_get_country_list( $count = '', $selected = array(), $json = false )
    {
        $countries_obj = new WC_Countries();
        $getCountries = $countries_obj->__get( 'countries' );
        if ( $json ) {
            return $this->wcpfc_pro_convert_array_to_json( $getCountries );
        }
        $html = '<select name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="wcpfc_select product_fees_conditions_values multiselect2 product_fees_conditions_values_country" multiple="multiple">';
        if ( !empty($getCountries) ) {
            foreach ( $getCountries as $code => $country ) {
                $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $code, $selected, true ) ? 'selected=selected' : '' );
                $html .= '<option value="' . esc_attr( $code ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html( $country ) . '</option>';
            }
        }
        $html .= '</select>';
        return $html;
    }
    
    /**
     * Function for select category list
     *
     * @param string $count
     * @param array  $selected
     * @param string $action
     * @param bool   $json
     *
     * @return string or array $html
     * @since 1.0.0
     *
     */
    public function wcpfc_pro_get_product_list(
        $count = '',
        $selected = array(),
        $action = '',
        $json = false
    )
    {
        global  $sitepress ;
        $default_lang = $this->wcpfc_pro_get_default_langugae_with_sitpress();
        $post_in = '';
        
        if ( 'edit' === $action ) {
            $post_in = $selected;
            $posts_per_page = -1;
        } else {
            $post_in = '';
            $posts_per_page = 10;
        }
        
        $product_args = array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'orderby'        => 'ID',
            'order'          => 'ASC',
            'post__in'       => $post_in,
            'posts_per_page' => $posts_per_page,
        );
        $get_all_products = new WP_Query( $product_args );
        $html = '<select id="product-filter-' . esc_attr( $count ) . '" rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="wcpfc_select product_fees_conditions_values multiselect2 product_fees_conditions_values_product" multiple="multiple">';
        if ( isset( $get_all_products->posts ) && !empty($get_all_products->posts) ) {
            foreach ( $get_all_products->posts as $get_all_product ) {
                $_product = wc_get_product( $get_all_product->ID );
                
                if ( $_product->is_type( 'simple' ) ) {
                    
                    if ( !empty($sitepress) ) {
                        $new_product_id = apply_filters(
                            'wpml_object_id',
                            $get_all_product->ID,
                            'product',
                            true,
                            $default_lang
                        );
                    } else {
                        $new_product_id = $get_all_product->ID;
                    }
                    
                    $selected = array_map( 'intval', $selected );
                    $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $new_product_id, $selected, true ) ? 'selected=selected' : '' );
                    if ( $selectedVal !== '' ) {
                        $html .= '<option value="' . esc_attr( $new_product_id ) . '" ' . esc_attr( $selectedVal ) . '>' . '#' . esc_html( $new_product_id ) . ' - ' . esc_html( get_the_title( $new_product_id ) ) . '</option>';
                    }
                }
            
            }
        }
        $html .= '</select>';
        if ( $json ) {
            return [];
        }
        return $html;
    }
    
    /**
     * Function for select product variable list
     *
     * @param string $count
     * @param array  $selected
     * @param string $action
     * @param bool   $json
     *
     * @return string or array $html
     * @since 1.0.0
     *
     */
    public function wcpfc_pro_get_varible_product_list(
        $count = '',
        $selected = array(),
        $action = '',
        $json = false
    )
    {
        global  $sitepress ;
        $default_lang = $this->wcpfc_pro_get_default_langugae_with_sitpress();
        
        if ( 'edit' === $action ) {
            $post_in = $selected;
            $get_varible_product_list_count = -1;
        } else {
            $post_in = '';
            $get_varible_product_list_count = 10;
        }
        
        $product_args = array(
            'post_type'      => 'product_variation',
            'post_status'    => 'publish',
            'orderby'        => 'ID',
            'order'          => 'ASC',
            'posts_per_page' => $get_varible_product_list_count,
            'post__in'       => $post_in,
        );
        $get_all_products = new WP_Query( $product_args );
        $html = '<select id="var-product-filter-' . esc_attr( $count ) . '" rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="wcpfc_select product_fees_conditions_values multiselect2 product_fees_conditions_values_var_product" multiple="multiple">';
        if ( isset( $get_all_products->posts ) && !empty($get_all_products->posts) ) {
            foreach ( $get_all_products->posts as $get_all_product ) {
                $_product = wc_get_product( $get_all_product->ID );
                // if ( ! ( $_product->is_virtual( 'yes' ) ) ) {
                
                if ( !empty($sitepress) ) {
                    $new_product_id = apply_filters(
                        'wpml_object_id',
                        $get_all_product->ID,
                        'product',
                        true,
                        $default_lang
                    );
                } else {
                    $new_product_id = $get_all_product->ID;
                }
                
                $selected = array_map( 'intval', $selected );
                $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $new_product_id, $selected, true ) ? 'selected=selected' : '' );
                if ( '' !== $selectedVal ) {
                    $html .= '<option value="' . esc_attr( $new_product_id ) . '" ' . esc_attr( $selectedVal ) . '>' . '#' . esc_html( $new_product_id ) . ' - ' . esc_html( get_the_title( $new_product_id ) ) . '</option>';
                }
                // }
            }
        }
        $html .= '</select>';
        if ( $json ) {
            return [];
        }
        return $html;
    }
    
    /**
     * Function for select cat list
     *
     * @param string $count
     * @param array  $selected
     * @param bool   $json
     *
     * @return string or array $html
     * @since 1.0.0
     *
     */
    public function wcpfc_pro_get_category_list( $count = '', $selected = array(), $json = false )
    {
        global  $sitepress ;
        $default_lang = $this->wcpfc_pro_get_default_langugae_with_sitpress();
        $filter_categories = [];
        $args = array(
            'taxonomy'     => 'product_cat',
            'orderby'      => 'name',
            'hierarchical' => true,
            'hide_empty'   => false,
        );
        $get_all_categories = get_terms( 'product_cat', $args );
        $html = '<select rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="wcpfc_select product_fees_conditions_values multiselect2" multiple="multiple">';
        if ( isset( $get_all_categories ) && !empty($get_all_categories) ) {
            foreach ( $get_all_categories as $get_all_category ) {
                
                if ( $get_all_category ) {
                    
                    if ( !empty($sitepress) ) {
                        $new_cat_id = apply_filters(
                            'wpml_object_id',
                            $get_all_category->term_id,
                            'product_cat',
                            true,
                            $default_lang
                        );
                    } else {
                        $new_cat_id = $get_all_category->term_id;
                    }
                    
                    $selected = array_map( 'intval', $selected );
                    $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $new_cat_id, $selected, true ) ? 'selected=selected' : '' );
                    $category = get_term_by( 'id', $new_cat_id, 'product_cat' );
                    $parent_category = get_term_by( 'id', $category->parent, 'product_cat' );
                    
                    if ( $category->parent > 0 ) {
                        $html .= '<option value=' . esc_attr( $category->term_id ) . ' ' . esc_attr( $selectedVal ) . '>' . '#' . esc_html( $parent_category->name ) . '->' . esc_html( $category->name ) . '</option>';
                        $filter_categories[$category->term_id] = '#' . $parent_category->name . '->' . $category->name;
                    } else {
                        $html .= '<option value=' . esc_attr( $category->term_id ) . ' ' . esc_attr( $selectedVal ) . '>' . esc_html( $category->name ) . '</option>';
                        $filter_categories[$category->term_id] = $category->name;
                    }
                
                }
            
            }
        }
        $html .= '</select>';
        if ( $json ) {
            return $this->wcpfc_pro_convert_array_to_json( $filter_categories );
        }
        return $html;
    }
    
    /**
     * Function for select tag list
     *
     * @param string $count
     * @param array  $selected
     * @param bool   $json
     *
     * @return string or array $html
     * @since 1.0.0
     *
     */
    public function wcpfc_pro_get_tag_list( $count = '', $selected = array(), $json = false )
    {
        global  $sitepress ;
        $default_lang = $this->wcpfc_pro_get_default_langugae_with_sitpress();
        $filter_tags = [];
        $args = array(
            'taxonomy'     => 'product_cat',
            'orderby'      => 'name',
            'hierarchical' => true,
            'hide_empty'   => false,
        );
        $get_all_tags = get_terms( 'product_tag', $args );
        $html = '<select rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="wcpfc_select product_fees_conditions_values multiselect2" multiple="multiple">';
        if ( isset( $get_all_tags ) && !empty($get_all_tags) ) {
            foreach ( $get_all_tags as $get_all_tag ) {
                
                if ( $get_all_tag ) {
                    
                    if ( !empty($sitepress) ) {
                        $new_tag_id = apply_filters(
                            'wpml_object_id',
                            $get_all_tag->term_id,
                            'product_tag',
                            true,
                            $default_lang
                        );
                    } else {
                        $new_tag_id = $get_all_tag->term_id;
                    }
                    
                    $selected = array_map( 'intval', $selected );
                    $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $new_tag_id, $selected, true ) ? 'selected=selected' : '' );
                    $tag = get_term_by( 'id', $new_tag_id, 'product_tag' );
                    $html .= '<option value="' . esc_attr( $tag->term_id ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html( $tag->name ) . '</option>';
                    $filter_tags[$tag->term_id] = $tag->name;
                }
            
            }
        }
        $html .= '</select>';
        if ( $json ) {
            return $this->wcpfc_pro_convert_array_to_json( $filter_tags );
        }
        return $html;
    }
    
    /**
     * Function for select user list
     *
     * @param string $count
     * @param array  $selected
     * @param bool   $json
     *
     * @return string or array $html
     * @since 1.0.0
     *
     */
    public function wcpfc_pro_get_user_list( $count = '', $selected = array(), $json = false )
    {
        $filter_users = [];
        $get_all_users = get_users();
        $html = '<select rel-id="' . esc_attr( $count ) . '" name="fees[product_fees_conditions_values][value_' . esc_attr( $count ) . '][]" class="wcpfc_select product_fees_conditions_values multiselect2" multiple="multiple">';
        if ( isset( $get_all_users ) && !empty($get_all_users) ) {
            foreach ( $get_all_users as $get_all_user ) {
                $selected = array_map( 'intval', $selected );
                $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( (int) $get_all_user->data->ID, $selected, true ) ? 'selected=selected' : '' );
                $html .= '<option value="' . esc_attr( $get_all_user->data->ID ) . '" ' . esc_attr( $selectedVal ) . '>' . esc_html( $get_all_user->data->user_login ) . '</option>';
                $filter_users[$get_all_user->data->ID] = $get_all_user->data->user_login;
            }
        }
        $html .= '</select>';
        if ( $json ) {
            return $this->wcpfc_pro_convert_array_to_json( $filter_users );
        }
        return $html;
    }
    
    /**
     * Function for multiple delete fees
     *
     * @since 1.0.0
     */
    public function wcpfc_pro_wc_multiple_delete_conditional_fee()
    {
        check_ajax_referer( 'dsm_nonce', 'nonce' );
        $result = 0;
        $get_allVals = filter_input(
            INPUT_GET,
            'allVals',
            FILTER_SANITIZE_NUMBER_INT,
            FILTER_REQUIRE_ARRAY
        );
        $allVals = ( !empty($get_allVals) ? array_map( 'sanitize_text_field', wp_unslash( $get_allVals ) ) : array() );
        if ( !empty($allVals) ) {
            foreach ( $allVals as $val ) {
                wp_delete_post( $val );
                $result = 1;
            }
        }
        
        if ( 1 === $result ) {
            $html = esc_html__( 'Selected fees rule has been deleted successfully.', 'woocommerce-conditional-product-fees-for-checkout' );
            delete_transient( 'get_top_ten_fees' );
            delete_transient( 'get_all_fees' );
            delete_transient( 'get_all_dashboard_fees' );
        } else {
            $html = esc_html__( 'Something went wrong', 'woocommerce-conditional-product-fees-for-checkout' );
        }
        
        echo  esc_html( $html ) ;
        wp_die();
    }
    
    /**
     * Function for multiple delete fees
     *
     * @since 1.0.0
     */
    public function wcpfc_reset_fee_cache()
    {
        check_ajax_referer( 'dsm_nonce', 'nonce' );
        $html = esc_html__( 'Somethng went wrong!', 'woocommerce-conditional-product-fees-for-checkout' );
        if ( delete_transient( 'get_top_ten_fees' ) && delete_transient( 'get_all_fees' ) && delete_transient( 'get_all_dashboard_fees' ) && delete_transient( 'get_total_revenue' ) && delete_transient( 'get_total_yearly_revenue' ) && delete_transient( 'get_total_last_month_revenue' ) && delete_transient( 'get_total_this_month_revenue' ) && delete_transient( 'get_total_yesterday_revenue' ) && delete_transient( 'get_total_today_revenue' ) ) {
            $html = esc_html__( 'Fees data has been updated successfully.', 'woocommerce-conditional-product-fees-for-checkout' );
        }
        echo  esc_html( $html ) ;
        wp_die();
    }
    
    /**
     * Function for multiple disable fees
     *
     * @since 1.0.0
     */
    public function wcpfc_pro_multiple_disable_conditional_fee()
    {
        check_ajax_referer( 'disable_fees_nonce', 'nonce' );
        $result = 0;
        $get_allVals = filter_input(
            INPUT_GET,
            'allVals',
            FILTER_SANITIZE_NUMBER_INT,
            FILTER_REQUIRE_ARRAY
        );
        $get_do_action = filter_input( INPUT_GET, 'do_action', FILTER_SANITIZE_STRING );
        $allVals = ( !empty($get_allVals) ? array_map( 'sanitize_text_field', wp_unslash( $get_allVals ) ) : array() );
        $do_action = ( isset( $get_do_action ) ? sanitize_text_field( $get_do_action ) : '' );
        if ( !empty($allVals) ) {
            foreach ( $allVals as $val ) {
                
                if ( $do_action === 'disable-conditional-fee' ) {
                    $post_args = array(
                        'ID'          => $val,
                        'post_status' => 'draft',
                        'post_type'   => self::wcpfc_post_type,
                    );
                    wp_update_post( $post_args );
                    update_post_meta( $val, 'fee_settings_status', 'off' );
                } else {
                    
                    if ( $do_action === 'enable-conditional-fee' ) {
                        $post_args = array(
                            'ID'          => $val,
                            'post_status' => 'publish',
                            'post_type'   => self::wcpfc_post_type,
                        );
                        wp_update_post( $post_args );
                        update_post_meta( $val, 'fee_settings_status', 'on' );
                    }
                
                }
                
                $result = 1;
            }
        }
        
        if ( 1 === $result ) {
            $html = esc_html__( "Fees status has been changed successfully.", 'woocommerce-conditional-product-fees-for-checkout' );
        } else {
            $html = esc_html__( "Something went wrong", 'woocommerce-conditional-product-fees-for-checkout' );
        }
        
        echo  esc_html( $html ) ;
        wp_die();
    }
    
    /**
     * Function for reset transient after fee delete
     *
     * @since 3.7.0
     */
    public function wcpfc_clear_fee_cache( $post_id )
    {
        
        if ( self::wcpfc_post_type === get_post_type( $post_id ) ) {
            delete_transient( 'get_top_ten_fees' );
            delete_transient( 'get_all_fees' );
            delete_transient( 'get_all_dashboard_fees' );
        }
    
    }
    
    /**
     * Function for date wise fee with revenue
     *
     * @since 3.7.0
     */
    public function get_fee_data_from_date_range( $start_date, $end_date, $all = '' )
    {
        if ( '' === $all && (empty($start_date) || empty($end_date)) ) {
            return 0;
        }
        global  $sitepress ;
        $filter_arr = array(
            "limit"   => -1,
            "orderby" => "date",
            "return"  => "ids",
            'status'  => array( 'wc-processing', 'wc-completed' ),
        );
        if ( empty($all) ) {
            $filter_arr["date_created"] = $start_date . "..." . $end_date;
        }
        $orders = wc_get_orders( $filter_arr );
        $fee_array = array();
        if ( !empty($orders) ) {
            foreach ( $orders as $order_id ) {
                $order = wc_get_order( $order_id );
                $order_fees = $order->get_meta( '_wcpfc_fee_summary' );
                
                if ( !empty($order_fees) ) {
                    foreach ( $order_fees as $order_fee ) {
                        $fee_revenue = 0;
                        
                        if ( !empty($sitepress) ) {
                            $fee_id = apply_filters(
                                'wpml_object_id',
                                $order_fee->id,
                                'product',
                                true,
                                $default_lang
                            );
                        } else {
                            $fee_id = $order_fee->id;
                        }
                        
                        $fee_id = ( !empty($fee_id) ? $fee_id : 0 );
                        
                        if ( $fee_id > 0 ) {
                            $fee_amount = ( !empty($order_fee->total) ? $order_fee->total : 0 );
                            if ( !empty($order_fee->taxable) && $order_fee->taxable ) {
                                $fee_amount += ( $order_fee->tax > 0 ? $order_fee->tax : 0 );
                            }
                            $fee_revenue += $fee_amount;
                            
                            if ( $fee_revenue > 0 && array_key_exists( $fee_id, $fee_array ) ) {
                                $fee_array[$fee_id] += $fee_revenue;
                            } else {
                                $fee_array[$fee_id] = $fee_revenue;
                            }
                        
                        }
                    
                    }
                } else {
                    if ( !empty($order->get_fees()) ) {
                        foreach ( $order->get_fees() as $fee_id => $fee ) {
                            $fee_revenue = 0;
                            $fee_post = get_page_by_title( $fee['name'], OBJECT, 'wc_conditional_fee' );
                            $fee_id = ( !empty($fee_post) ? $fee_post->ID : 0 );
                            if ( !empty($sitepress) ) {
                                $fee_id = apply_filters(
                                    'wpml_object_id',
                                    $fee_id,
                                    'product',
                                    true,
                                    $default_lang
                                );
                            }
                            //$fee_id 0 will consider as other custom fees.
                            if ( $fee['line_total'] > 0 ) {
                                $fee_revenue += $fee['line_total'];
                            }
                            if ( $fee['line_tax'] > 0 ) {
                                $fee_revenue += $fee['line_tax'];
                            }
                            
                            if ( $fee_revenue >= 0 && array_key_exists( $fee_id, $fee_array ) ) {
                                $fee_array[$fee_id] += $fee_revenue;
                            } else {
                                $fee_array[$fee_id] = $fee_revenue;
                            }
                        
                        }
                    }
                }
            
            }
        }
        return $fee_array;
    }
    
    /**
     * Function color generator in RGB from random number
     *
     * @since 3.7.0
     */
    public function colorGenerator( $num = 10 )
    {
        $hash = md5( 'color' . $num );
        // modify 'color' to get a different palette
        return 'rgb(' . hexdec( substr( $hash, 0, 2 ) ) . ', ' . hexdec( substr( $hash, 2, 2 ) ) . ', ' . hexdec( substr( $hash, 4, 2 ) ) . ')';
    }
    
    /**
     * Redirect page after plugin activation
     *
     * @uses  wcpfc_pro_register_post_type
     *
     * @since 1.0.0
     */
    public function wcpfc_pro_welcome_conditional_fee_screen_do_activation_redirect()
    {
        $this->wcpfc_pro_register_post_type();
        // if no activation redirect
        if ( !get_transient( '_welcome_screen_wcpfc_pro_mode_activation_redirect_data' ) ) {
            return;
        }
        // Delete the redirect transient
        delete_transient( '_welcome_screen_wcpfc_pro_mode_activation_redirect_data' );
        // if activating from network, or bulk
        $activate_multi = filter_input( INPUT_GET, 'activate-multi', FILTER_SANITIZE_SPECIAL_CHARS );
        if ( is_network_admin() || isset( $activate_multi ) ) {
            return;
        }
        // Redirect to extra cost welcome  page
        wp_safe_redirect( add_query_arg( array(
            'page' => 'wcpfc-pro-list',
        ), admin_url( 'admin.php' ) ) );
        exit;
    }
    
    /**
     * Register post type
     *
     * @since    1.0.0
     */
    public function wcpfc_pro_register_post_type()
    {
        register_post_type( self::wcpfc_post_type, array(
            'labels' => array(
            'name'          => __( 'Advance Conditional Fees', 'woocommerce-conditional-product-fees-for-checkout' ),
            'singular_name' => __( 'Advance Conditional Fees', 'woocommerce-conditional-product-fees-for-checkout' ),
        ),
        ) );
    }
    
    /**
     * Remove submenu from admin section
     *
     * @since 1.0.0
     */
    public function wcpfc_pro_remove_admin_submenus()
    {
        remove_submenu_page( 'dots_store', 'wcpfc-pro-information' );
        remove_submenu_page( 'dots_store', 'wcpfc-pro-add-new' );
        remove_submenu_page( 'dots_store', 'wcpfc-pro-edit-fee' );
        remove_submenu_page( 'dots_store', 'wcpfc-pro-get-started' );
        remove_submenu_page( 'dots_store', 'wcpfc-premium' );
    }
    
    /**
     * When create fees based on product then all product will display using ajax
     *
     * @since 1.0.0
     *
     */
    public function wcpfc_pro_product_fees_conditions_values_product_ajax()
    {
        global  $sitepress ;
        $default_lang = $this->wcpfc_pro_get_default_langugae_with_sitpress();
        $json = true;
        $filter_product_list = [];
        $request_value = filter_input( INPUT_GET, 'value', FILTER_SANITIZE_STRING );
        $posts_per_page = filter_input( INPUT_GET, 'posts_per_page', FILTER_VALIDATE_INT );
        $offset = filter_input( INPUT_GET, 'offset', FILTER_VALIDATE_INT );
        $post_value = ( isset( $request_value ) ? sanitize_text_field( $request_value ) : '' );
        $posts_per_page = ( isset( $posts_per_page ) ? sanitize_text_field( $posts_per_page ) : '' );
        $offset = ( isset( $offset ) ? sanitize_text_field( $offset ) : '' );
        $baselang_product_ids = array();
        function wcpfc_posts_where( $where, $wp_query )
        {
            global  $wpdb ;
            $search_term = $wp_query->get( 'search_pro_title' );
            
            if ( isset( $search_term ) ) {
                $search_term_like = $wpdb->esc_like( $search_term );
                $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $search_term_like ) . '%\'';
            }
            
            return $where;
        }
        
        $product_args = array(
            'post_type'        => 'product',
            'posts_per_page'   => 900,
            'search_pro_title' => $post_value,
            'post_status'      => 'publish',
            'orderby'          => 'title',
            'order'            => 'ASC',
        );
        add_filter(
            'posts_where',
            'wcpfc_posts_where',
            10,
            2
        );
        $wp_query = new WP_Query( $product_args );
        remove_filter(
            'posts_where',
            'wcpfc_posts_where',
            10,
            2
        );
        $get_all_products = $wp_query->posts;
        if ( isset( $get_all_products ) && !empty($get_all_products) ) {
            foreach ( $get_all_products as $get_all_product ) {
                $_product = wc_get_product( $get_all_product->ID );
                
                if ( $_product->is_type( 'simple' ) ) {
                    
                    if ( !empty($sitepress) ) {
                        $defaultlang_product_id = apply_filters(
                            'wpml_object_id',
                            $get_all_product->ID,
                            'product',
                            true,
                            $default_lang
                        );
                    } else {
                        $defaultlang_product_id = $get_all_product->ID;
                    }
                    
                    $baselang_product_ids[] = $defaultlang_product_id;
                }
            
            }
        }
        $html = '';
        if ( isset( $baselang_product_ids ) && !empty($baselang_product_ids) ) {
            foreach ( $baselang_product_ids as $baselang_product_id ) {
                $html .= '<option value="' . $baselang_product_id . '">' . '#' . $baselang_product_id . ' - ' . get_the_title( $baselang_product_id ) . '</option>';
                $filter_product_list[] = array( $baselang_product_id, get_the_title( $baselang_product_id ) );
            }
        }
        
        if ( $json ) {
            echo  wp_json_encode( $filter_product_list ) ;
            wp_die();
        }
        
        echo  wp_kses( $html, Woocommerce_Conditional_Product_Fees_For_Checkout_Pro::allowed_html_tags() ) ;
        wp_die();
    }
    
    /**
     * When create fees based on advance pricing rule and add rule based onm product qty then all
     * product will display using ajax
     *
     * @since 1.0.0
     *
     */
    public function wcpfc_pro_simple_and_variation_product_list_ajax()
    {
        global  $sitepress ;
        $default_lang = $this->wcpfc_pro_get_default_langugae_with_sitpress();
        $json = true;
        $filter_product_list = [];
        $request_value = filter_input( INPUT_GET, 'value', FILTER_SANITIZE_STRING );
        // $posts_per_page                 = filter_input( INPUT_GET, 'posts_per_page', FILTER_VALIDATE_INT );
        // $offset                         = filter_input( INPUT_GET, 'offset', FILTER_VALIDATE_INT );
        $post_value = ( isset( $request_value ) ? sanitize_text_field( $request_value ) : '' );
        // $posts_per_page                 = isset( $posts_per_page ) ? sanitize_text_field( $posts_per_page ) : '';
        // $offset                         = isset( $offset ) ? sanitize_text_field( $offset ) : '';
        $baselang_simple_product_ids = array();
        $baselang_variation_product_ids = array();
        function wcpfc_posts_where( $where, $wp_query )
        {
            global  $wpdb ;
            $search_term = $wp_query->get( 'search_pro_title' );
            
            if ( !empty($search_term) ) {
                $search_term_like = $wpdb->esc_like( $search_term );
                $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $search_term_like ) . '%\'';
            }
            
            return $where;
        }
        
        $product_args = array(
            'post_type'        => 'product',
            'posts_per_page'   => -1,
            'search_pro_title' => $post_value,
            'post_status'      => 'publish',
            'orderby'          => 'title',
            'order'            => 'ASC',
        );
        add_filter(
            'posts_where',
            'wcpfc_posts_where',
            10,
            2
        );
        $get_wp_query = new WP_Query( $product_args );
        remove_filter(
            'posts_where',
            'wcpfc_posts_where',
            10,
            2
        );
        $get_all_products = $get_wp_query->posts;
        if ( isset( $get_all_products ) && !empty($get_all_products) ) {
            foreach ( $get_all_products as $get_all_product ) {
                $_product = wc_get_product( $get_all_product->ID );
                
                if ( $_product->is_type( 'variable' ) ) {
                    $variations = $_product->get_available_variations();
                    foreach ( $variations as $value ) {
                        
                        if ( !empty($sitepress) ) {
                            $defaultlang_variation_product_id = apply_filters(
                                'wpml_object_id',
                                $value['variation_id'],
                                'product',
                                true,
                                $default_lang
                            );
                        } else {
                            $defaultlang_variation_product_id = $value['variation_id'];
                        }
                        
                        $baselang_variation_product_ids[] = $defaultlang_variation_product_id;
                    }
                }
                
                
                if ( $_product->is_type( 'simple' ) ) {
                    
                    if ( !empty($sitepress) ) {
                        $defaultlang_simple_product_id = apply_filters(
                            'wpml_object_id',
                            $get_all_product->ID,
                            'product',
                            true,
                            $default_lang
                        );
                    } else {
                        $defaultlang_simple_product_id = $get_all_product->ID;
                    }
                    
                    $baselang_simple_product_ids[] = $defaultlang_simple_product_id;
                }
            
            }
        }
        $baselang_product_ids = array_merge( $baselang_variation_product_ids, $baselang_simple_product_ids );
        $html = '';
        if ( isset( $baselang_product_ids ) && !empty($baselang_product_ids) ) {
            foreach ( $baselang_product_ids as $baselang_product_id ) {
                $html .= '<option value="' . $baselang_product_id . '">' . '#' . $baselang_product_id . ' - ' . get_the_title( $baselang_product_id ) . '</option>';
                $filter_product_list[] = array( $baselang_product_id, '#' . $baselang_product_id . ' - ' . get_the_title( $baselang_product_id ) );
            }
        }
        
        if ( $json ) {
            echo  wp_json_encode( $filter_product_list ) ;
            wp_die();
        }
        
        echo  wp_kses( $html, Woocommerce_Conditional_Product_Fees_For_Checkout_Pro::allowed_html_tags() ) ;
        wp_die();
    }
    
    /**
     * Add link to plugin section
     *
     * @param mixed $links
     *
     * @return mixed $links
     *
     * @since 1.0.0
     *
     */
    function wcpfc_pro_product_fees_conditions_setting_link( $links )
    {
        $links[] = '<a href="' . admin_url( 'admin.php?page=wcpfc-pro-get-started' ) . '">' . __( 'Settings', 'woocommerce-conditional-product-fees-for-checkout' ) . '</a>';
        return $links;
    }
    
    /**
     * Sorting fess in list section
     *
     * @since 1.0.0
     */
    function wcpfc_pro_conditional_fee_sorting()
    {
        check_ajax_referer( 'sorting_conditional_fee_action', 'sorting_conditional_fee' );
        global  $sitepress, $wpdb ;
        
        if ( !empty($sitepress) ) {
            $default_lang = $sitepress->get_default_language();
        } else {
            $get_site_language = get_bloginfo( 'language' );
            
            if ( false !== strpos( $get_site_language, '-' ) ) {
                $get_site_language_explode = explode( '-', $get_site_language );
                $default_lang = $get_site_language_explode[0];
            } else {
                $default_lang = $get_site_language;
            }
        
        }
        
        $post_type = self::wcpfc_post_type;
        $getPaged = filter_input( INPUT_POST, 'paged', FILTER_SANITIZE_NUMBER_INT );
        $getListingArray = filter_input(
            INPUT_POST,
            'listing',
            FILTER_SANITIZE_NUMBER_INT,
            FILTER_REQUIRE_ARRAY
        );
        $paged = ( !empty($getPaged) ? $getPaged : 1 );
        $listinbgArray = ( !empty($getListingArray) ? array_map( 'intval', wp_unslash( $getListingArray ) ) : array() );
        $results = $wpdb->get_results( $wpdb->prepare( "SELECT ID \n                FROM {$wpdb->posts} \n                WHERE post_type = %s AND post_status IN ('publish', 'draft') \n                ORDER BY menu_order, post_date \n                DESC", $post_type ) );
        //Create the list of ID's
        $objects_ids = array();
        foreach ( $results as $result ) {
            $objects_ids[] = (int) $result->ID;
        }
        //Here we switch order
        $per_page = ( get_option( 'chk_fees_per_page' ) ? get_option( 'chk_fees_per_page' ) : 10 );
        $edit_start_at = $paged * $per_page - $per_page;
        $index = 0;
        for ( $i = $edit_start_at ;  $i < $edit_start_at + $per_page ;  $i++ ) {
            if ( !isset( $objects_ids[$i] ) ) {
                break;
            }
            $objects_ids[$i] = (int) $listinbgArray[$index];
            $index++;
        }
        //Update the menu_order within database
        foreach ( $objects_ids as $menu_order => $id ) {
            $data = array(
                'menu_order' => $menu_order,
            );
            $wpdb->update( $wpdb->posts, $data, array(
                'ID' => $id,
            ) );
            clean_post_cache( $id );
        }
        wp_send_json_success( array(
            'message' => esc_html__( 'Fee rules has been updated.', 'woocommerce-conditional-product-fees-for-checkout' ),
        ) );
    }
    
    /**
     * Ajax response of product wc product variable
     *
     * @since 1.0.0
     */
    public function wcpfc_pro_product_fees_conditions_varible_values_product_ajax()
    {
        global  $sitepress ;
        $default_lang = $this->wcpfc_pro_get_default_langugae_with_sitpress();
        $json = true;
        $filter_variable_product_list = [];
        $request_value = filter_input( INPUT_GET, 'value', FILTER_SANITIZE_STRING );
        // $posts_per_page               = filter_input( INPUT_GET, 'posts_per_page', FILTER_VALIDATE_INT );
        // $offset                       = filter_input( INPUT_GET, 'offset', FILTER_VALIDATE_INT );
        $post_value = ( isset( $request_value ) ? sanitize_text_field( $request_value ) : '' );
        // $posts_per_page               = isset( $posts_per_page ) ? sanitize_text_field( $posts_per_page ) : '';
        // $offset                       = isset( $offset ) ? sanitize_text_field( $offset ) : '';
        $baselang_product_ids = array();
        function wcpfc_posts_wheres( $where, $wp_query )
        {
            global  $wpdb ;
            $search_term = $wp_query->get( 'search_pro_title' );
            
            if ( isset( $search_term ) ) {
                $search_term_like = $wpdb->esc_like( $search_term );
                $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $search_term_like ) . '%\'';
            }
            
            return $where;
        }
        
        $product_args = array(
            'post_type'        => 'product',
            'posts_per_page'   => 900,
            'search_pro_title' => $post_value,
            'post_status'      => array( 'publish', 'private' ),
            'orderby'          => 'title',
            'order'            => 'ASC',
        );
        add_filter(
            'posts_where',
            'wcpfc_posts_wheres',
            10,
            2
        );
        $get_all_products = new WP_Query( $product_args );
        remove_filter(
            'posts_where',
            'wcpfc_posts_wheres',
            10,
            2
        );
        if ( !empty($get_all_products) ) {
            foreach ( $get_all_products->posts as $get_all_product ) {
                $_product = wc_get_product( $get_all_product->ID );
                
                if ( $_product->is_type( 'variable' ) ) {
                    $variations = $_product->get_available_variations();
                    foreach ( $variations as $value ) {
                        
                        if ( !empty($sitepress) ) {
                            $defaultlang_product_id = apply_filters(
                                'wpml_object_id',
                                $value['variation_id'],
                                'product',
                                true,
                                $default_lang
                            );
                        } else {
                            $defaultlang_product_id = $value['variation_id'];
                        }
                        
                        $baselang_product_ids[] = $defaultlang_product_id;
                    }
                }
            
            }
        }
        $html = '';
        if ( isset( $baselang_product_ids ) && !empty($baselang_product_ids) ) {
            foreach ( $baselang_product_ids as $baselang_product_id ) {
                $html .= '<option value="' . $baselang_product_id . '">' . '#' . $baselang_product_id . ' - ' . get_the_title( $baselang_product_id ) . '</option>';
                $filter_variable_product_list[] = array( $baselang_product_id, '#' . $baselang_product_id . ' - ' . get_the_title( $baselang_product_id ) );
            }
        }
        
        if ( $json ) {
            echo  wp_json_encode( $filter_variable_product_list ) ;
            wp_die();
        }
        
        echo  wp_kses( $html, Woocommerce_Conditional_Product_Fees_For_Checkout_Pro::allowed_html_tags() ) ;
        wp_die();
    }
    
    /**
     * Admin footer review
     *
     * @since 1.0.0
     */
    public function wcpfc_pro_admin_footer_review()
    {
        $url = '';
        $url = esc_url( 'https://wordpress.org/plugins/woo-conditional-product-fees-for-checkout/#reviews' );
        $html = sprintf(
            '%s<strong>%s</strong>%s<a href=%s target="_blank">%s</a>',
            esc_html__( 'If you like ', 'woocommerce-conditional-product-fees-for-checkout' ),
            esc_html__( 'Installing WooCommerce Extra Fees Plugin ', 'woocommerce-conditional-product-fees-for-checkout' ),
            esc_html__( 'plugin, please leave us &#9733;&#9733;&#9733;&#9733;&#9733; ratings on ', 'woocommerce-conditional-product-fees-for-checkout' ),
            $url,
            esc_html__( 'DotStore', 'woocommerce-conditional-product-fees-for-checkout' )
        );
        echo  wp_kses_post( $html ) ;
    }
    
    /**
     * Convert array to json
     *
     * @param array $arr
     *
     * @return array $filter_data
     * @since 1.0.0
     *
     */
    public function wcpfc_pro_convert_array_to_json( $arr )
    {
        $filter_data = [];
        foreach ( $arr as $key => $value ) {
            $option = [];
            $option['name'] = $value;
            $option['attributes']['value'] = $key;
            $filter_data[] = $option;
        }
        return $filter_data;
    }
    
    /**
     * Get product list in advance pricing rules section
     *
     * @param string $count
     * @param array  $selected
     *
     * @return mixed $html
     * @since 1.0.0
     *
     */
    public function wcpfc_pro_get_product_options( $count = '', $selected = array() )
    {
        global  $sitepress ;
        $default_lang = $this->wcpfc_pro_get_default_langugae_with_sitpress();
        $all_selected_product_ids = array();
        if ( !empty($selected) && is_array( $selected ) ) {
            foreach ( $selected as $product_id ) {
                $_product = wc_get_product( $product_id );
                
                if ( 'product_variation' === $_product->post_type ) {
                    $all_selected_product_ids[] = $_product->get_parent_id();
                    //parent_id;
                } else {
                    $all_selected_product_ids[] = $product_id;
                }
            
            }
        }
        $all_selected_product_count = 900;
        $get_all_products = new WP_Query( array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => $all_selected_product_count,
            'post__in'       => $all_selected_product_ids,
        ) );
        $baselang_variation_product_ids = array();
        $defaultlang_simple_product_ids = array();
        $html = '';
        if ( isset( $get_all_products->posts ) && !empty($get_all_products->posts) ) {
            foreach ( $get_all_products->posts as $get_all_product ) {
                $_product = wc_get_product( $get_all_product->ID );
                
                if ( $_product->is_type( 'variable' ) ) {
                    $variations = $_product->get_available_variations();
                    foreach ( $variations as $value ) {
                        
                        if ( !empty($sitepress) ) {
                            $defaultlang_variation_product_id = apply_filters(
                                'wpml_object_id',
                                $value['variation_id'],
                                'product',
                                true,
                                $default_lang
                            );
                        } else {
                            $defaultlang_variation_product_id = $value['variation_id'];
                        }
                        
                        $baselang_variation_product_ids[] = $defaultlang_variation_product_id;
                    }
                }
                
                
                if ( $_product->is_type( 'simple' ) ) {
                    
                    if ( !empty($sitepress) ) {
                        $defaultlang_simple_product_id = apply_filters(
                            'wpml_object_id',
                            $get_all_product->ID,
                            'product',
                            true,
                            $default_lang
                        );
                    } else {
                        $defaultlang_simple_product_id = $get_all_product->ID;
                    }
                    
                    $defaultlang_simple_product_ids[] = $defaultlang_simple_product_id;
                }
            
            }
        }
        $baselang_product_ids = array_merge( $baselang_variation_product_ids, $defaultlang_simple_product_ids );
        if ( isset( $baselang_product_ids ) && !empty($baselang_product_ids) ) {
            foreach ( $baselang_product_ids as $baselang_product_id ) {
                $selected = array_map( 'intval', $selected );
                $selectedVal = ( is_array( $selected ) && !empty($selected) && in_array( $baselang_product_id, $selected, true ) ? 'selected=selected' : '' );
                if ( '' !== $selectedVal ) {
                    $html .= '<option value="' . $baselang_product_id . '" ' . $selectedVal . '>' . '#' . $baselang_product_id . ' - ' . get_the_title( $baselang_product_id ) . '</option>';
                }
            }
        }
        return $html;
    }
    
    /**
     * Change fees status in list section
     *
     * @since 1.0.0
     */
    public function wcpfc_pro_change_status_from_list_section()
    {
        $get_current_fees_id = filter_input( INPUT_GET, 'current_fees_id', FILTER_SANITIZE_NUMBER_INT );
        $get_current_value = filter_input( INPUT_GET, 'current_value', FILTER_SANITIZE_STRING );
        
        if ( !isset( $get_current_fees_id ) ) {
            echo  '<strong>' . esc_html__( 'Something went wrong', 'woocommerce-conditional-product-fees-for-checkout' ) . '</strong>' ;
            wp_die();
        }
        
        $post_id = ( isset( $get_current_fees_id ) ? absint( $get_current_fees_id ) : '' );
        $current_value = ( isset( $get_current_value ) ? sanitize_text_field( $get_current_value ) : '' );
        
        if ( 'true' === $current_value ) {
            $post_args = array(
                'ID'          => $post_id,
                'post_status' => 'publish',
                'post_type'   => self::wcpfc_post_type,
            );
            $post_update = wp_update_post( $post_args );
            update_post_meta( $post_id, 'fee_settings_status', 'on' );
        } else {
            $post_args = array(
                'ID'          => $post_id,
                'post_status' => 'draft',
                'post_type'   => self::wcpfc_post_type,
            );
            $post_update = wp_update_post( $post_args );
            update_post_meta( $post_id, 'fee_settings_status', 'off' );
        }
        
        
        if ( !empty($post_update) ) {
            echo  esc_html__( 'Fees status has been changed successfully.', 'woocommerce-conditional-product-fees-for-checkout' ) ;
            delete_transient( 'get_top_ten_fees' );
            delete_transient( 'get_all_fees' );
            delete_transient( 'get_all_dashboard_fees' );
        } else {
            echo  esc_html__( 'Something went wrong', 'woocommerce-conditional-product-fees-for-checkout' ) ;
        }
        
        wp_die();
    }
    
    /**
     * Save master settings data
     *
     * @since 1.0.0
     */
    public function wcpfc_pro_save_master_settings()
    {
        $get_chk_enable_logging = filter_input( INPUT_GET, 'chk_enable_logging', FILTER_SANITIZE_STRING );
        $chk_enable_coupon_fee = filter_input( INPUT_GET, 'chk_enable_coupon_fee', FILTER_SANITIZE_STRING );
        $chk_enable_custom_fun = filter_input( INPUT_GET, 'chk_enable_custom_fun', FILTER_SANITIZE_STRING );
        $chk_enable_all_fee_tax = filter_input( INPUT_GET, 'chk_enable_all_fee_tax', FILTER_SANITIZE_STRING );
        $chk_enable_all_fee_tooltip = filter_input( INPUT_GET, 'chk_enable_all_fee_tooltip', FILTER_SANITIZE_STRING );
        $chk_enable_all_fee_tooltip_text = filter_input( INPUT_GET, 'chk_enable_all_fee_tooltip_text', FILTER_SANITIZE_STRING );
        $chk_fees_per_page = filter_input( INPUT_GET, 'chk_fees_per_page', FILTER_SANITIZE_STRING );
        if ( isset( $get_chk_enable_logging ) && !empty($get_chk_enable_logging) ) {
            update_option( 'chk_enable_logging', $get_chk_enable_logging );
        }
        if ( isset( $chk_enable_coupon_fee ) && !empty($chk_enable_coupon_fee) ) {
            update_option( 'chk_enable_coupon_fee', $chk_enable_coupon_fee );
        }
        if ( isset( $chk_enable_custom_fun ) && !empty($chk_enable_custom_fun) ) {
            update_option( 'chk_enable_custom_fun', $chk_enable_custom_fun );
        }
        if ( isset( $chk_enable_all_fee_tax ) && !empty($chk_enable_all_fee_tax) ) {
            update_option( 'chk_enable_all_fee_tax', $chk_enable_all_fee_tax );
        }
        if ( isset( $chk_enable_all_fee_tooltip ) && !empty($chk_enable_all_fee_tooltip) ) {
            update_option( 'chk_enable_all_fee_tooltip', $chk_enable_all_fee_tooltip );
        }
        
        if ( isset( $chk_enable_all_fee_tooltip_text ) && !empty($chk_enable_all_fee_tooltip_text) ) {
            $chk_enable_all_fee_tooltip_text = substr( sanitize_text_field( $chk_enable_all_fee_tooltip_text ), 0, 25 );
            update_option( 'chk_enable_all_fee_tooltip_text', $chk_enable_all_fee_tooltip_text );
        }
        
        if ( isset( $chk_fees_per_page ) && !empty($chk_fees_per_page) ) {
            update_option( 'chk_fees_per_page', $chk_fees_per_page );
        }
        wp_die();
    }
    
    /**
     * Save fees order in fees list section
     *
     * @since 1.0.0
     */
    public function wcpfc_pro_sm_sort_order()
    {
        $get_smOrderArray = filter_input(
            INPUT_GET,
            'smOrderArray',
            FILTER_SANITIZE_NUMBER_INT,
            FILTER_REQUIRE_ARRAY
        );
        $smOrderArray = ( !empty($get_smOrderArray) ? array_map( 'sanitize_text_field', wp_unslash( $get_smOrderArray ) ) : '' );
        
        if ( isset( $smOrderArray ) && !empty($smOrderArray) ) {
            update_option( 'sm_sortable_order', $smOrderArray );
            delete_transient( 'get_all_fees' );
        }
        
        wp_die();
    }
    
    /**
     * Get default site language
     *
     * @return string $default_lang
     *
     * @since  1.0.0
     *
     */
    public function wcpfc_pro_get_default_langugae_with_sitpress()
    {
        global  $sitepress ;
        
        if ( !empty($sitepress) ) {
            $default_lang = $sitepress->get_current_language();
        } else {
            $default_lang = $this->wcpfc_pro_get_current_site_language();
        }
        
        return $default_lang;
    }
    
    /**
     * Get current site langugae
     *
     * @return string $default_lang
     * @since 1.0.0
     *
     */
    public function wcpfc_pro_get_current_site_language()
    {
        $get_site_language = get_bloginfo( "language" );
        
        if ( false !== strpos( $get_site_language, '-' ) ) {
            $get_site_language_explode = explode( '-', $get_site_language );
            $default_lang = $get_site_language_explode[0];
        } else {
            $default_lang = $get_site_language;
        }
        
        return $default_lang;
    }
    
    /**
     * Fetch slug based on id
     *
     * @since    3.6.1
     */
    public function wcpfc_pro_fetch_slug( $id_array, $condition )
    {
        $return_array = array();
        if ( !empty($id_array) ) {
            foreach ( $id_array as $key => $ids ) {
                
                if ( 'product' === $condition || 'variableproduct' === $condition || 'cpp' === $condition ) {
                    $get_posts = get_post( $ids );
                    if ( !empty($get_posts) ) {
                        $return_array[] = $get_posts->post_name;
                    }
                } elseif ( 'category' === $condition || 'cpc' === $condition ) {
                    $term = get_term( $ids, 'product_cat' );
                    if ( $term ) {
                        $return_array[] = $term->slug;
                    }
                } elseif ( 'tag' === $condition ) {
                    $tag = get_term( $ids, 'product_tag' );
                    if ( $tag ) {
                        $return_array[] = $tag->slug;
                    }
                } elseif ( 'shipping_class' === $condition ) {
                    $shipping_class = get_term( $key, 'product_shipping_class' );
                    if ( $shipping_class ) {
                        $return_array[$shipping_class->slug] = $ids;
                    }
                } elseif ( 'cpsc' === $condition ) {
                    $return_array[] = $ids;
                } elseif ( 'cpp' === $condition ) {
                    $cpp_posts = get_post( $ids );
                    if ( !empty($cpp_posts) ) {
                        $return_array[] = $cpp_posts->post_name;
                    }
                } else {
                    $return_array[] = $ids;
                }
            
            }
        }
        return $return_array;
    }
    
    /**
     * Fetch id based on slug
     *
     * @since    3.6.1
     */
    public function wcpfc_pro_fetch_id( $slug_array, $condition )
    {
        $return_array = array();
        if ( !empty($slug_array) ) {
            foreach ( $slug_array as $key => $slugs ) {
                
                if ( 'product' === $condition ) {
                    $post = get_page_by_path( $slugs, OBJECT, 'product' );
                    $id = $post->ID;
                    $return_array[] = $id;
                } elseif ( 'variableproduct' === $condition ) {
                    $args = array(
                        'post_type' => 'product_variation',
                        'fields'    => 'ids',
                        'name'      => $slugs,
                    );
                    $variable_posts = get_posts( $args );
                    if ( !empty($variable_posts) ) {
                        foreach ( $variable_posts as $val ) {
                            $return_array[] = $val;
                        }
                    }
                } elseif ( 'category' === $condition || 'cpc' === $condition ) {
                    $term = get_term_by( 'slug', $slugs, 'product_cat' );
                    if ( $term ) {
                        $return_array[] = $term->term_id;
                    }
                } elseif ( 'tag' === $condition ) {
                    $term_tag = get_term_by( 'slug', $slugs, 'product_tag' );
                    if ( $term_tag ) {
                        $return_array[] = $term_tag->term_id;
                    }
                } elseif ( 'shipping_class' === $condition || 'cpsc' === $condition ) {
                    $term_tag = get_term_by( 'slug', $key, 'product_shipping_class' );
                    if ( $term_tag ) {
                        $return_array[$term_tag->term_id] = $slugs;
                    }
                } elseif ( 'cpp' === $condition ) {
                    $args = array(
                        'post_type' => array( 'product_variation', 'product' ),
                        'name'      => $slugs,
                    );
                    $variable_posts = get_posts( $args );
                    if ( !empty($variable_posts) ) {
                        foreach ( $variable_posts as $val ) {
                            $return_array[] = $val->ID;
                        }
                    }
                } else {
                    $return_array[] = $slugs;
                }
            
            }
        }
        return $return_array;
    }
    
    /**
     * Plugins URL
     *
     * @since     3.1
     */
    public function wcpfc_pro_plugins_url(
        $id,
        $page,
        $tab,
        $action,
        $nonce
    )
    {
        $query_args = array();
        if ( '' !== $page ) {
            $query_args['page'] = $page;
        }
        if ( '' !== $tab ) {
            $query_args['tab'] = $tab;
        }
        if ( '' !== $action ) {
            $query_args['action'] = $action;
        }
        if ( '' !== $id ) {
            $query_args['id'] = $id;
        }
        if ( '' !== $nonce ) {
            $query_args['_wpnonce'] = wp_create_nonce( 'wcpfcnonce' );
        }
        return esc_url( add_query_arg( $query_args, admin_url( 'admin.php' ) ) );
    }
    
    /**
     * Create a menu for plugin.
     *
     * @param string $current current page.
     *
     * @since     3.1
     */
    public function wcpfc_pro_menus( $current = 'wcpfc-pro-list' )
    {
        $wcpfc_action = filter_input( INPUT_GET, 'action', FILTER_SANITIZE_STRING );
        $wcpfc_wpnonce = filter_input( INPUT_GET, '_wpnonce', FILTER_SANITIZE_STRING );
        $wpfp_menus = array(
            'main_menu' => array(
            'pro_menu'  => array(
            'wcpfc-pro-dashboard'     => array(
            'menu_title' => __( 'Dashboard', 'woocommerce-conditional-product-fees-for-checkout' ),
            'menu_slug'  => 'wcpfc-pro-dashboard',
            'menu_url'   => $this->wcpfc_pro_plugins_url(
            '',
            'wcpfc-pro-dashboard',
            '',
            '',
            ''
        ),
        ),
            'wcpfc-pro-list'          => array(
            'menu_title' => __( 'Manage Product Fees', 'woocommerce-conditional-product-fees-for-checkout' ),
            'menu_slug'  => 'wcpfc-pro-list',
            'menu_url'   => $this->wcpfc_pro_plugins_url(
            '',
            'wcpfc-pro-list',
            '',
            '',
            ''
        ),
        ),
            'wcpfc-pro-import-export' => array(
            'menu_title' => __( 'Import / Export', 'woocommerce-conditional-product-fees-for-checkout' ),
            'menu_slug'  => 'wcpfc-pro-import-export',
            'menu_url'   => $this->wcpfc_pro_plugins_url(
            '',
            'wcpfc-pro-import-export',
            '',
            '',
            ''
        ),
        ),
            'wcpfc-pro-get-started'   => array(
            'menu_title' => __( 'About Plugin', 'woocommerce-conditional-product-fees-for-checkout' ),
            'menu_slug'  => 'wcpfc-pro-get-started',
            'menu_url'   => $this->wcpfc_pro_plugins_url(
            '',
            'wcpfc-pro-get-started',
            '',
            '',
            ''
        ),
            'sub_menu'   => array(
            'wcpfc-pro-get-started' => array(
            'menu_title' => __( 'Getting Started', 'woocommerce-conditional-product-fees-for-checkout' ),
            'menu_slug'  => 'wcpfc-pro-get-started',
            'menu_url'   => $this->wcpfc_pro_plugins_url(
            '',
            'wcpfc-pro-get-started',
            '',
            '',
            ''
        ),
        ),
            'wcpfc-pro-information' => array(
            'menu_title' => __( 'Quick info', 'woocommerce-conditional-product-fees-for-checkout' ),
            'menu_slug'  => 'wcpfc-pro-information',
            'menu_url'   => $this->wcpfc_pro_plugins_url(
            '',
            'wcpfc-pro-information',
            '',
            '',
            ''
        ),
        ),
        ),
        ),
            'dotstore'                => array(
            'menu_title' => __( 'Dotstore', 'woocommerce-conditional-product-fees-for-checkout' ),
            'menu_slug'  => 'dotstore',
            'menu_url'   => 'javascript:void(0)',
            'sub_menu'   => array(
            'woocommerce-plugins' => array(
            'menu_title' => __( 'WooCommerce Plugins', 'woocommerce-conditional-product-fees-for-checkout' ),
            'menu_slug'  => 'woocommerce-plugins',
            'menu_url'   => esc_url( 'https://www.thedotstore.com/woocommerce-plugins/' ),
        ),
            'wordpress-plugins'   => array(
            'menu_title' => __( 'Wordpress Plugins', 'woocommerce-conditional-product-fees-for-checkout' ),
            'menu_slug'  => 'wordpress-plugins',
            'menu_url'   => esc_url( 'https://www.thedotstore.com/wordpress-plugins/' ),
        ),
            'contact-support'     => array(
            'menu_title' => __( 'Contact Support', 'woocommerce-conditional-product-fees-for-checkout' ),
            'menu_slug'  => 'contact-support',
            'menu_url'   => esc_url( 'https://www.thedotstore.com/support/' ),
        ),
        ),
        ),
        ),
            'free_menu' => array(
            'wcpfc-pro-list'        => array(
            'menu_title' => __( 'Manage Product Fees', 'woocommerce-conditional-product-fees-for-checkout' ),
            'menu_slug'  => 'wcpfc-pro-list',
            'menu_url'   => $this->wcpfc_pro_plugins_url(
            '',
            'wcpfc-pro-list',
            '',
            '',
            ''
        ),
        ),
            'wcpfc-pro-get-started' => array(
            'menu_title' => __( 'About Plugin', 'woocommerce-conditional-product-fees-for-checkout' ),
            'menu_slug'  => 'wcpfc-pro-get-started',
            'menu_url'   => $this->wcpfc_pro_plugins_url(
            '',
            'wcpfc-pro-get-started',
            '',
            '',
            ''
        ),
            'sub_menu'   => array(
            'wcpfc-pro-get-started' => array(
            'menu_title' => __( 'Getting Started', 'woocommerce-conditional-product-fees-for-checkout' ),
            'menu_slug'  => 'wcpfc-pro-get-started',
            'menu_url'   => $this->wcpfc_pro_plugins_url(
            '',
            'wcpfc-pro-get-started',
            '',
            '',
            ''
        ),
        ),
            'wcpfc-pro-information' => array(
            'menu_title' => __( 'Quick info', 'woocommerce-conditional-product-fees-for-checkout' ),
            'menu_slug'  => 'wcpfc-pro-information',
            'menu_url'   => $this->wcpfc_pro_plugins_url(
            '',
            'wcpfc-pro-information',
            '',
            '',
            ''
        ),
        ),
        ),
        ),
            'wcpfc-premium'         => array(
            'menu_title' => __( 'Premium Version', 'woocommerce-conditional-product-fees-for-checkout' ),
            'menu_slug'  => 'wcpfc-premium',
            'menu_url'   => $this->wcpfc_pro_plugins_url(
            '',
            'wcpfc-premium',
            '',
            '',
            ''
        ),
        ),
            'dotstore'              => array(
            'menu_title' => __( 'Dotstore', 'woocommerce-conditional-product-fees-for-checkout' ),
            'menu_slug'  => 'dotstore',
            'menu_url'   => 'javascript:void(0)',
            'sub_menu'   => array(
            'woocommerce-plugins' => array(
            'menu_title' => __( 'WooCommerce Plugins', 'woocommerce-conditional-product-fees-for-checkout' ),
            'menu_slug'  => 'woocommerce-plugins',
            'menu_url'   => esc_url( 'https://www.thedotstore.com/woocommerce-plugins/' ),
        ),
            'wordpress-plugins'   => array(
            'menu_title' => __( 'Wordpress Plugins', 'woocommerce-conditional-product-fees-for-checkout' ),
            'menu_slug'  => 'wordpress-plugins',
            'menu_url'   => esc_url( 'https://www.thedotstore.com/wordpress-plugins/' ),
        ),
            'contact-support'     => array(
            'menu_title' => __( 'Contact Support', 'woocommerce-conditional-product-fees-for-checkout' ),
            'menu_slug'  => 'contact-support',
            'menu_url'   => esc_url( 'https://www.thedotstore.com/support/' ),
        ),
        ),
        ),
        ),
        ),
        );
        ?>
		<div class="dots-menu-main">
			<nav>
				<ul>
					<?php 
        $main_current = $current;
        $sub_current = $current;
        foreach ( $wpfp_menus['main_menu'] as $main_menu_slug => $main_wpfp_menu ) {
            if ( 'free_menu' === $main_menu_slug ) {
                foreach ( $main_wpfp_menu as $menu_slug => $wpfp_menu ) {
                    if ( 'wcpfc-pro-information' === $main_current ) {
                        $main_current = 'wcpfc-pro-get-started';
                    }
                    $class = ( $menu_slug === $main_current ? 'active' : '' );
                    ?>
									<li>
										<a class="dotstore_plugin <?php 
                    echo  esc_attr( $class ) ;
                    ?>"
										   href="<?php 
                    echo  esc_url( $wpfp_menu['menu_url'] ) ;
                    ?>">
											<?php 
                    esc_html_e( $wpfp_menu['menu_title'], 'woocommerce-conditional-product-fees-for-checkout' );
                    ?>
										</a>
										<?php 
                    
                    if ( isset( $wpfp_menu['sub_menu'] ) && !empty($wpfp_menu['sub_menu']) ) {
                        ?>
											<ul class="sub-menu">
												<?php 
                        foreach ( $wpfp_menu['sub_menu'] as $sub_menu_slug => $wpfp_sub_menu ) {
                            $sub_class = ( $sub_menu_slug === $sub_current ? 'active' : '' );
                            ?>

													<li>
														<a class="dotstore_plugin <?php 
                            echo  esc_attr( $sub_class ) ;
                            ?>"
														   href="<?php 
                            echo  esc_url( $wpfp_sub_menu['menu_url'] ) ;
                            ?>">
															<?php 
                            esc_html_e( $wpfp_sub_menu['menu_title'], 'woocommerce-conditional-product-fees-for-checkout' );
                            ?>
														</a>
													</li>
												<?php 
                        }
                        ?>
											</ul>
										<?php 
                    }
                    
                    ?>
									</li>
									<?php 
                }
            }
        }
        ?>
				</ul>
			</nav>
		</div>
		<?php 
    }
    
    /**
     * Display message in admin side
     *
     * @param string $message
     * @param string $tab
     *
     * @return bool
     * @since 1.0.0
     *
     */
    public function wcpfc_updated_message( $message, $validation_msg )
    {
        if ( empty($message) ) {
            return false;
        }
        
        if ( 'created' === $message ) {
            $updated_message = esc_html__( "Fee rule has been created.", 'woocommerce-conditional-product-fees-for-checkout' );
        } elseif ( 'saved' === $message ) {
            $updated_message = esc_html__( "Fee rule has been updated.", 'woocommerce-conditional-product-fees-for-checkout' );
        } elseif ( 'deleted' === $message ) {
            $updated_message = esc_html__( "Fee rule has been deleted.", 'woocommerce-conditional-product-fees-for-checkout' );
        } elseif ( 'duplicated' === $message ) {
            $updated_message = esc_html__( "Fee rule has been duplicated.", 'woocommerce-conditional-product-fees-for-checkout' );
        } elseif ( 'disabled' === $message ) {
            $updated_message = esc_html__( "Fee rule has been disabled.", 'woocommerce-conditional-product-fees-for-checkout' );
        } elseif ( 'enabled' === $message ) {
            $updated_message = esc_html__( "Fee rule has been enabled.", 'woocommerce-conditional-product-fees-for-checkout' );
        }
        
        
        if ( 'failed' === $message ) {
            $failed_messsage = esc_html__( "There was an error with saving data.", 'woocommerce-conditional-product-fees-for-checkout' );
        } elseif ( 'nonce_check' === $message ) {
            $failed_messsage = esc_html__( "There was an error with security check.", 'woocommerce-conditional-product-fees-for-checkout' );
        }
        
        if ( 'validated' === $message ) {
            $validated_messsage = esc_html( $validation_msg );
        }
        
        if ( !empty($updated_message) ) {
            echo  sprintf( '<div id="message" class="notice notice-success is-dismissible"><p>%s</p></div>', esc_html( $updated_message ) ) ;
            return false;
        }
        
        
        if ( !empty($failed_messsage) ) {
            echo  sprintf( '<div id="message" class="notice notice-error is-dismissible"><p>%s</p></div>', esc_html( $failed_messsage ) ) ;
            return false;
        }
        
        
        if ( !empty($validated_messsage) ) {
            echo  sprintf( '<div id="message" class="notice notice-error is-dismissible"><p>%s</p></div>', esc_html( $validated_messsage ) ) ;
            return false;
        }
    
    }

}