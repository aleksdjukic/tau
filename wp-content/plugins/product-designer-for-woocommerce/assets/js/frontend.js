/* global pdr_product_params, PDR_ClientDB */

jQuery( function( $ ) {
    'use strict';

    var frontend = {
        checkoutTimer : false,
        $checkout_form : $( 'form.checkout' ),
        init : function() {
            $( document ).on( 'click', '.pdr_customize_button', this.forceGuest );

            if ( PDR_ClientDB.isSupported ) {
                this.mayBeClearDesignedFromCart();
                this.$checkout_form.on( 'checkout_place_order', this.saveDesignFromClientDB );
            }
        },
        forceGuest : function( e ) {
            if ( '1' !== pdr_product_params.force_guest || '0' !== pdr_product_params.user_id ) {
                return true;
            }

            e.preventDefault();
            $.alertable.alert( pdr_product_params.force_guest_alert_message, { html : true } );
            return false;
        },
        mayBeClearDesignedFromCart : function() {
            if ( ! pdr_product_params.is_cart_empty ) {
                return false;
            }

            let clientdb = new PDR_ClientDB( 'pdr', 1, { name : 'cart', keyOptions : { keyPath : 'itemKey', autoIncrement : false } } );

            $( document ).on( 'pdr_clientdb_ready_handler', function() {
                clientdb.deleteValues( 'cart' );
            } );
        },
        saveDesignFromClientDB : function( e ) {
            clearTimeout( frontend.checkoutTimer );
            var $form = $( this );

            if ( $form.is( '.pdr-checkout-processed' ) ) {
                return true;
            }

            frontend.block( $form );

            let clientdb = new PDR_ClientDB( 'pdr', 1, { name : 'cart', keyOptions : { keyPath : 'itemKey', autoIncrement : false } } ),
                    designedCarts = { };

            $( document ).on( 'pdr_clientdb_ready_handler', function() {
                clientdb
                        .getValues( 'cart' )
                        .openCursor()
                        .onsuccess = function( e ) {
                            let cursor = e.target.result;
                            if ( cursor ) {
                                designedCarts[cursor.key] = cursor.value;
                                cursor.continue();
                            }
                        };
            } );

            frontend.checkoutTimer = setTimeout( function() {
                $.ajax( {
                    type : 'POST',
                    url : pdr_product_params.ajaxurl,
                    dataType : 'json',
                    data : {
                        action : 'pdr_save_design_from_clientdb',
                        pdr_security : pdr_product_params.save_design_from_clientdb_nonce,
                        designedCarts : designedCarts
                    },
                    success : function( response ) {
                        frontend.unblock( $form );
                        $form.addClass( 'pdr-checkout-processed' ).submit();
                    }
                } );
            }, 1000 );
            return false;
        },
        is_blocked : function( id ) {
            return $( id ).is( '.processing' ) || $( id ).parents( '.processing' ).length;
        },
        block : function( id ) {
            if ( ! frontend.is_blocked( id ) ) {
                $( id ).addClass( 'processing' ).block( {
                    message : null,
                    overlayCSS : {
                        background : '#fff',
                        opacity : 0.6
                    }
                } );
            }
        },
        unblock : function( id ) {
            $( id ).removeClass( 'processing' ).unblock( );
        }
    };

    frontend.init();
} );
