<?php
/**
 * Plugin Name: WooCommerce CRIF Payment Gateway
 * Description: This is a WooCommerce CRIF Payment Gateway
 * Author: Pier Serta
 * Version: 1.6.3
 * Textdomain: woocrifpaymentgateway
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
define ('WOOCRIFPAY',dirname(__FILE__));


if ( ! class_exists( 'WC_Crif' ) ) :

class WC_Crif {
	
	
	protected static $instance = null;
	
	function __construct(){
		// Load plugin text domain.
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		
		
		if ( class_exists( 'WC_Payment_Gateway' ) ){
			require_once(WOOCRIFPAY.'/inc/crif-payment-api.php');
			require_once(WOOCRIFPAY.'/inc/class.WC_Gateway_CRIF.php');
		}
		
		// Make sure WooCommerce is active
		
		add_filter( 'woocommerce_payment_gateways', array( $this, 'add_gateway' ) );
		add_action( 'admin_notices', array($this, 'woocrifpaymentgateway_admin_notice__error') );
	
	}
	/**
	 * Return an instance of this class.
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
	
	function add_gateway($methods){
		global $woocommerce;
		$session = WC()->session;
			//var_dump(rest_api_loaded());
			
			
	if(!is_admin()  && $session ){
		if((strpos($GLOBALS['wp']->query_vars['rest_route'], 'wc-analytics') == false)){
		
		$order_awaiting_payment = WC()->session->get( 'order_awaiting_payment', 0 );
		$already_sent = (WC()->session->get( 'crif_request_sent', 0 ) == 0) ? NULL : WC()->session->get( 'crif_request_sent', 0 );

			
			if(($already_sent === $order_awaiting_payment) ) 
			return $methods;



			if($woocommerce->customer->get_billing_country()){
				if($woocommerce->customer->get_billing_country() == 'LI')
				$methods[] = 'WC_Gateway_CRIF';
				if($woocommerce->customer->get_billing_country() == 'CH')
				$methods[] = 'WC_Gateway_CRIF';
				if($woocommerce->customer->get_billing_country() == 'DE')
				$methods[] = 'WC_Gateway_CRIF';
				if($woocommerce->customer->get_billing_country() == 'AT')
				$methods[] = 'WC_Gateway_CRIF';
							

				return $methods;
			}
		}
	}
			
		$methods[] = 'WC_Gateway_CRIF';
		return $methods;
		
	}
	public static function get_templates_path() {
		return plugin_dir_path( __FILE__ ) . '/templates/';
	}
	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain() {
		
//		print_r(WC()->session );
		load_plugin_textdomain( 'woocrifpaymentgateway', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}
	
	function woocrifpaymentgateway_admin_notice__error() {
		
		if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	
	
		$class = 'notice notice-error';
		$message = __( 'Irks! This is a WooCommerce Extension, Please activate WooCommerce first.', 'woocrifpaymentgateway' );
	
		printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message ); 
		}
		else
		return;
	}
	
} // class
add_action( 'plugins_loaded', array( 'WC_Crif', 'get_instance' ), 11 );
endif;


add_action('woocommerce_after_checkout_validation','woocommerce_after_checkout_validationCBF',1,2);
function woocommerce_after_checkout_validationCBF($data, $errors){
	global $wpdb;
	$last_order_id_obj = $wpdb->get_results("SELECT ID FROM $wpdb->posts WHERE post_type='shop_order' AND post_status='wc-pending' ORDER BY ID DESC LIMIT 1");
	
	if(count($errors->errors) >= 1 && $_POST['payment_method'] == 'crif-payment-method')		
		wp_delete_post($last_order_id_obj[0]->ID, true);
		
	
} // function woocommerce_after_checkout_validationCBF($data, $errors) $wpdb->insert_id