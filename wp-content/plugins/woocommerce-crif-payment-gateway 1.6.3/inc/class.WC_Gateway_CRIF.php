<?php
/**
 * CRIF Payment Gateway
 *
 * Provides an CRIF Payment Gateway.
 * We load it later to ensure WC is loaded first since we're extending it.
 *
 * @class       WC_Gateway_CRIF
 * @extends     WC_Payment_Gateway
 * @version     1.0.0
 * @package     WooCommerce/Classes/Payment
 * @author      Pier Serta
 */

class WC_Gateway_CRIF extends WC_Payment_Gateway {

        /**
		 * Constructor for the gateway.
		 */
		public function __construct() {

			
			$this->id                   = 'crif-payment-method';
			$this->icon                 = apply_filters( 'wc_crif_payment_icon', false );
			$this->has_fields           = false;
			$this->method_title         = __( 'Open Invoice', 'woocrifpaymentgateway' );
			$this->method_description   = __( 'Open Invoice.', 'woocrifpaymentgateway' );
		//	$this->view_transaction_url = '';
	
			// Load the form fields.
			$this->init_form_fields();
	
			// Load the settings.
			$this->init_settings();
	
			// Define user set variables.
			$this->icon			  = "";
			$this->title          = $this->get_option( 'title' );
			$this->description    = $this->get_option( 'description' );
			$this->debug          = $this->get_option( 'debug' );
			$this->endPoint       = 'https://services.crif-online.ch/CrifSS/CrifSoapServiceV1';
			$this->username		  = $this->get_option( 'username' );
			$this->password       = $this->get_option( 'password' );
			$this->MAJOR_VERSION  = $this->get_option( 'MAJOR_VERSION' );
			$this->MINOR_VERSION  = $this->get_option( 'MINOR_VERSION' );
			$this->ReportType     = $this->get_option( 'ReportType' );
			$this->instructions =   $this->get_option( 'instructions', $this->description );
			
	
			// Active logs.
			if ( 'yes' == $this->debug ) {
				$this->log = new WC_Logger();
			}
			
			//global $woocommerce;
			//print_r(WC()->customer->get_billing_country());
			// Set the API.
			$this->api = new SolvencyChecker( $this->endPoint, $this->username, $this->password, $this->MAJOR_VERSION, $this->MINOR_VERSION, $this->ReportType );
	
			// Actions.
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
			add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );
			add_action( 'woocommerce_email_after_order_table', array( $this, 'email_instructions' ), 10, 3 );
			add_filter( 'woocommerce_checkout_fields',array($this, 'custom_override_checkout_fields') ,1);
		}
	
		/**
		 * Admin page.
		 */
		public function admin_options() {
			/**
			 * Admin options screen.
			 *
			 * @package /Admin/Settings
			 */
			
			if ( ! defined( 'ABSPATH' ) ) {
				exit;
			}
			
			?>
			
			<h3><?php echo esc_html( $this->method_title ); ?></h3>
			
			
			<?php echo wpautop( $this->method_description ); ?>
			
			<table class="form-table">
				<?php $this->generate_settings_html(); ?>
			</table> <?php

		}
	
		/**
		 * Check if the gateway is available to take payments.
		 *
		 * @return bool
		 */
		public function is_available() {
			return parent::is_available() && ! empty( $this->username ) && ! empty( $this->password ) && $this->ReportType;
		}
	
		/**
		 * Settings fields.
		 */
		public function init_form_fields() {
			$this->form_fields = array(
				'enabled' => array(
					'title'   => __( 'Enable/Disable', 'woocrifpaymentgateway' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable CRIF Payment Gateway', 'woocrifpaymentgateway' ),
					'default' => 'no',
				),
				'title' => array(
					'title'       => __( 'Title', 'woocrifpaymentgateway' ),
					'type'        => 'text',
					'description' => __( 'This controls the title which the user sees during checkout.', 'woocrifpaymentgateway' ),
					'desc_tip'    => true,
					'default'     => __( 'Open Invoice', 'woocrifpaymentgateway' ),
				),
				'description' => array(
					'title'       => __( 'Description', 'woocrifpaymentgateway' ),
					'type'        => 'textarea',
					'description' => __( 'This controls the description which the user sees during checkout.', 'woocrifpaymentgateway' ),
					'desc_tip'    => true,
					'default'     => __( 'Pay with Open Invoice', 'woocrifpaymentgateway' ),
				),
				'instructions' => array(
					'title'       => __( 'Instructions', 'woocrifpaymentgateway' ),
					'type'        => 'textarea',
					'description' => __( 'This controls the Instructions which the user sees after checkout.', 'woocrifpaymentgateway' ),
					'desc_tip'    => true,
					'default'     => __( 'Your Open Invoice is sent', 'woocrifpaymentgateway' ),
				),
				'integration' => array(
					'title'       => __( 'Integration Settings', 'woocrifpaymentgateway' ),
					'type'        => 'title',
					'description' => ''
				),
				'username' => array(
					'title'             => __( 'crif-online.ch Username', 'woocrifpaymentgateway' ),
					'type'              => 'text',
					'description'       => sprintf( __( 'Please enter your crif-online.ch password', 'woocrifpaymentgateway' ) . '</a>' ),
					'default'           => '',
					'custom_attributes' => array(
						'required' => 'required',
					),
				),
				'password' => array(
					'title'             => __( 'crif-online.ch Password', 'woocrifpaymentgateway' ),
					'type'              => 'text',
					'description'       => sprintf( __( 'Please enter your crif-online.ch password', 'woocrifpaymentgateway' ) . '</a>' ),
					'default'           => '',
					'custom_attributes' => array(
						'required' => 'required',
					),
				),
				'MAJOR_VERSION' => array(
					'title'             => __( 'Enter Major Version', 'woocrifpaymentgateway' ),
					'type'              => 'text',
					'description'       => sprintf( __( 'Do not change it if you have no idea.', 'woocrifpaymentgateway' ) . '</a>' ),
					'default'           => '1',
					'custom_attributes' => array(
						'required' => 'required',
					),
				),
				'MINOR_VERSION' => array(
					'title'             => __( 'Enter Minor Version', 'woocrifpaymentgateway' ),
					'type'              => 'text',
					'description'       => sprintf( __( 'Do not change it if you have not idea', 'woocrifpaymentgateway' ) . '</a>' ),
					'default'           => 5,
					'custom_attributes' => array(
						'required' => 'required',
					),
				),
				'ReportType' => array(
					'title'             => __( 'Enter Report Type', 'woocrifpaymentgateway' ),
					'type'              => 'text',
					'description'       => sprintf( __( 'Do not change it if you have no idea', 'woocrifpaymentgateway' ) . '</a>' ),
					'default'           => 'QUICK_CHECK_CONSUMER',
					'custom_attributes' => array(
						'required' => 'required',
					),
				),
				'testing' => array(
					'title'       => __( 'Gateway Testing', 'woocrifpaymentgateway' ),
					'type'        => 'title',
					'description' => '',
				),
				'debug' => array(
					'title'       => __( 'Debug Log', 'woocrifpaymentgateway' ),
					'type'        => 'checkbox',
					'label'       => __( 'Enable logging', 'woocrifpaymentgateway' ),
					'default'     => 'no',
					'description' => sprintf( __( 'Log CRIF events, such as API requests. You can check the log in %s', 'woocrifpaymentgateway' ), '<a href="' . esc_url( admin_url( 'admin.php?page=wc-status&tab=logs&log_file=' . esc_attr( $this->id ) . '-' . sanitize_file_name( wp_hash( $this->id ) ) . '.log' ) ) . '">' . __( 'System Status &gt; Logs', 'woocrifpaymentgateway' ) . '</a>' ),
				),
			);
		}
	
		/**
		 * Process the payment.
		 *
		 * @param int $order_id Order ID.
		 *
		 * @return array Redirect data.
		 */
		public function process_payment( $order_id ) {
			

			global $woocommerce;
			$order = new WC_Order( $order_id );
			$customer = $woocommerce->customer;	
			$order_awaiting_payment = WC()->session->get( 'order_awaiting_payment', 0 );
			
			// Do not send request if it already checked			
			$already_sent = (WC()->session->get( 'crif_request_sent', 0 ) == 0) ? NULL : WC()->session->get( 'crif_request_sent', 0 );
			//echo $already_sent;
			if($already_sent === $order_awaiting_payment) {

			wc_add_notice( __('Payment error: You cannot use Open Invoice, Choose another method'), 'woocrifpaymentgateway' );
			return;
			}
			
			update_post_meta($order_id,'_billing_street',$_POST['_billing_street']);
			update_post_meta($order_id,'_billing_street_number',$_POST['_billing_street_number']);
			
		//	update_post_meta($order_id,'_customer_dob',$_POST['yob'].'-'.$_POST['mob'].'-'.$_POST['dob']);
			$order_meta = get_post_meta($order_id);
			
			// Check Solvency
			$address = array(  
				  "firstname" => $order_meta['_billing_first_name'][0]
				, "name" => $order_meta["_billing_last_name"][0]
				, "street" => $order_meta['_billing_address_1'][0]
				, "houseNumber" => "" //$order_meta['_billing_street_number'][0]
				, "zip" => $order_meta["_billing_postcode"][0]
				, "city" => $order_meta["_billing_city"][0]
				, "country" => $order_meta["_billing_country"][0]
				, "birthdate" => ''//$order_meta["_customer_dob"][0] //"1974-11-11"
				);
			$referenceNumber = $order_id;
			if($this->api->isSolvent($address, $referenceNumber)){
			// Payment complete
			
			$order->payment_complete();	
			return array(
				'result' => 'success',
				'redirect' => $this->get_return_url( $order )
			);
			}
			else {
			wc_add_notice( __('Kauf auf Rechnung abgelehnt. Bitte wählen Sie eine andere Zahlungsart.','woocrifpaymentgateway'), 'error' );
			return;	
			}
			
		}
		
		/*
		Kauf auf Rechnung abgelehnt. Bitte wählen Sie eine andere Zahlungsart.
		Achat sur facture refusé. Veuillez choisir un autre mode de paiement.
		Acquisto su fattura rifiutato. Per favore scegliere un altro metodo di pagamento.
		*/
		
		
		public function custom_override_checkout_fields($fields){
			
			// billing fields
		//	unset($fields['billing']['billing_address_1']);
		//	unset($fields['billing']['billing_address_2']);
			
			
			
			/*$fields['billing']['billing_address_1'] = array
                (
                    'label' => 'Address',
                    'placeholder' => 'Street address',
                    'required' => 1,
                    'class' => array
                        (
                            0 => 'form-row-wide',
                            1 => 'address-field'
                        ),

                    'autocomplete' => 'address-line1'
                );*/
			
			/*$fields['billing']['_billing_street'] = array
			('
					type'=>'text', 
					'label'=>__('Street Name','woocrifpaymentgateway'), 
					'placeholder'=>'Hardstrasse',
					'class' => array
                        (
                            0 => 'form-row-first',
                        ),
					'required'=>1, 
					'order'=>11, 
					'custom'=>1, 
					'show_in_email' => 1, 
					'show_in_order' => 1
					);
			$fields['billing']['_billing_street_number'] = array
			('
					type'=>'text', 
					'label'=>__('Street No.','woocrifpaymentgateway'), 
					'placeholder'=>'72',
					'class' => array
                        (
                            0 => 'form-row-last',
                        ),
					'required'=>1, 
					'order'=>12, 
					'custom'=>1, 
					'show_in_email' => 1, 
					'show_in_order' => 1
					);*/
			
			
           /* $fields['billing']['billing_address_2'] = array
                (
                    'placeholder' => 'House Number',
                    'class' => array
                        (
                            0 => 'form-row-wide',
                            1 => 'address-field'
                        ),

                    'required' => '',
                    'autocomplete' => 'address-line2'
                );*/
			
			// shipping fields
			
		//	unset($fields['shipping']['shipping_address_1']);
		//	unset($fields['shipping']['shipping_address_2']);
			
			
			
		/*	$fields['shipping']['shipping_address_1'] = array
                (
                    'label' => 'Address',
                    'placeholder' => 'Street address',
                    'required' => 1,
                    'class' => array
                        (
                            0 => 'form-row-wide',
                            1 => 'address-field'
                        ),

                    'autocomplete' => 'address-line1'
                );*/
			
			/*$fields['shipping']['_shipping_street'] = array
			('
					type'=>'text', 
					'label'=>__('Street Name','woocrifpaymentgateway'), 
					'placeholder'=>'Hardstrasse',
					'class' => array
                        (
                            0 => 'form-row-first',
                        ),
					'required'=>1, 
					'order'=>11, 
					'custom'=>1, 
					'show_in_email' => 1, 
					'show_in_order' => 1
					);
			$fields['shipping']['_shipping_street_number'] = array
			('
					type'=>'text', 
					'label'=>__('Street No.','woocrifpaymentgateway'), 
					'placeholder'=>'72',
					'class' => array
                        (
                            0 => 'form-row-last',
                        ),
					'required'=>1, 
					'order'=>11, 
					'custom'=>1, 
					'show_in_email' => 1, 
					'show_in_order' => 1
					);*/
			
			
            /*$fields['shipping']['shipping_address_2'] = array
                (
                    'placeholder' => 'House Number',
                    'class' => array
                        (
                            0 => 'form-row-wide',
                            1 => 'address-field'
                        ),

                    'required' => '',
                    'autocomplete' => 'address-line2'
                );*/
			
//			print_r($fields);	
			return $fields;
			
			
		}
		
		/**
		 * Payment fields.
		 *
		 * @return string
		 */
		public function payment_fields() {
			if ( $description = $this->get_description() ) {
				echo sprintf( __('%s','woocrifpaymentgateway'), wpautop( wptexturize( $description ) ));
			}
	
	//		wc_get_template('checkout-fields.php',array(),'woocommerce/crif-payment/',WC_Crif::get_templates_path());
		}
		public function validate_fields(){
			
			//print_r($data);
		//	if (isset($_POST['dob']) && !empty($_POST['dob']) )
		//	return true;
		//	wc_add_notice( __('Birthdate Field is required', 'woothemes') , 'error' );
		//	return false;
			
		}
		/**
		 * Thank You page message.
		 *
		 * @param  int    $order_id Order ID.
		 *
		 * @return string
		 */
		 
		public function thankyou_page( $order_id ) {
			/*if ( $this->instructions ) {
				echo sprintf( __('%s','woocrifpaymentgateway'), wpautop( wptexturize( $this->instructions ) ));
			}
			*/
			
		}
	
		/**
		 * Add content to the WC emails.
		 *
		 * @param  object $order         Order object.
		 * @param  bool   $sent_to_admin Send to admin.
		 * @param  bool   $plain_text    Plain text or HTML.
		 *
		 * @return string                Payment instructions.
		 */
		public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
			if ( $this->instructions && ! $sent_to_admin && $this->id === $order->payment_method && $order->has_status( 'on-hold' ) ) {
				echo sprintf( __('%s','woocrifpaymentgateway'), wpautop( wptexturize( $this->instructions ) )) . PHP_EOL;
			}
		}
	
		/**
		 * IPN handler.
		 */
	//	public function ipn_handler() {
	//		$this->api->ipn_handler();
	//	}
    } // end \WC_Gateway_Offline class
$initilize = new WC_Gateway_CRIF();