/* global pdr_admin_params, ajaxurl */

jQuery( function( $ ) {
    'use strict';

    var PDR_Admin = {
        init : function( ) {

            //Add the product base new panel.
            $( document ).on( 'click', '.pdr-add-new-panel', this.add_product_base_panel );
            //Remove the product base panel.
            $( document ).on( 'click', '.pdr-remove-panel', this.remove_product_base_panel );
            //Tabbed product base panel.
            $( document ).on( 'pdr-init-tabbed-panels', this.tabbed_product_base_panels ).trigger( 'pdr-init-tabbed-panels' );
            // Trigger select2 after variations loaded. 
            //            $( document ).on( 'woocommerce_variations_loaded' , this.variations_loaded ) ;
            // Trigger select2 after variations added. 
            //            $( document ).on( 'woocommerce_variations_added' , this.variations_loaded ) ;
            // User Restrictions.
            $( document ).on( 'click', '#pdr_user_restrictions', this.toggle_user_restrictions );
            // File Upload.
            $( document ).on( 'change', '.pdr-design-template-upload', this.prepare_image );
            // Enable Modules.
            $( document ).on( 'click', '.pdr-modules-enable-btn', this.enable_modules );
            // Add Attributes 
            $( document ).on( 'click', '.pdr-add-new-attributes', this.add_attributes );
            $( document ).on( 'click', '.pdr-remove-attributes', this.remove_attributes );
            $( document ).on( 'pdr-init-tabbed-attributes', this.tabbed_attributes ).trigger( 'pdr-init-tabbed-attributes' );
            $( document ).on( 'pdr-init-attribute-field', this.initialize_attribute_field ).trigger( 'pdr-init-attribute-field' );

            // Add Attribute color
            $( document ).on( 'click', '.pdr-add-attribute-color', this.attribute_color_popup );
            // Edit Attribute color
            $( document ).on( 'click', '.pdr-attribute-edit-color', this.attribute_color_popup );
            // Remove Attribute color
            $( document ).on( 'click', '.pdr-attribute-remove-color', this.remove_attribute_color );
            // Attribute color type
            $( document ).on( 'change', '.pdr-attributes-type', this.toggle_attribute_color_type );

            //theme change
            $( document ).on( 'change', '#pdr_theme_selection', this.theme_selection );
            var get_current_theme = $( '#pdr_theme_selection' ).val();
            this.visibility_theme_selection( get_current_theme );
            this.trigger_on_page_load();
            $( '.pdr_font_selection' ).select2();

            //visibility for all fonts or selected fonts
            $( document ).on( 'change', '#pdr_font_selection_type', this.show_hide_font );
            var get_type = $( '#pdr_font_selection_type' ).val();
            this.visibility_font_selection( get_type );

            //product base product selection
            $( document ).on( 'change', '#pdr_font_selection_type', this.show_hide_font_base );
            var get_font_type = $( '#pdr_font_selection_type' ).val();
            this.visibility_font_selection( get_font_type, 'base' );

        }, trigger_on_page_load : function( ) {
            // User Restrictions.
            this.user_restrictions( '#pdr_user_restrictions' );
        }, variations_loaded : function( ) {
            $( document.body ).trigger( 'pdr-enhanced-init' );
        }, toggle_user_restrictions : function( event ) {
            event.preventDefault( );
            var $this = $( event.currentTarget );
            PDR_Admin.user_restrictions( $this );
        }, toggle_attribute_color_type : function( event ) {
            event.preventDefault( );
            var $this = $( event.currentTarget );

            PDR_Admin.attribute_color_type( $this );
        }, attribute_color_type : function( $this ) {

            var panel = $( $this ).closest( '.pdr-attributes-panel-content' );
            panel.find( '.pdr-attribute-color-field' ).closest( '.pdr-form-field' ).hide();

            switch ( $( $this ).val() ) {
                case 'product_color':
                    panel.find( '.pdr-product-base-attribute-color' ).closest( '.pdr-form-field' ).show();
                    panel.find( '.pdr-product-base-attribute-price' ).closest( '.pdr-form-field' ).show();
                    panel.find( '.pdr-product-base-attribute-price' ).closest( '.pdr-form-field' ).hide();
                    break;
                case 'text':
                case 'textarea':
                    panel.find( '.pdr-product-base-attribute-label' ).closest( '.pdr-form-field' ).show();
                    panel.find( '.pdr-product-base-attribute-price' ).closest( '.pdr-form-field' ).show();
                    break;
                case 'select':
                case 'checkbox':
                case 'radio':
                    panel.find( '.pdr-product-base-attribute-options' ).closest( '.pdr-form-field' ).show();
                    panel.find( '.pdr-product-base-attribute-price' ).closest( '.pdr-form-field' ).hide();
                    break;
            }
        }, user_restrictions : function( $this ) {
            $( '.pdr_user_restrictions' ).closest( 'tr' ).hide();

            switch ( $( $this ).val() ) {
                case '2':
                    $( '#pdr_include_users' ).closest( 'tr' ).show();
                    break;
                case '3':
                    $( '#pdr_exclude_users' ).closest( 'tr' ).show();
                    break;
                case '4':
                    $( '#pdr_include_user_roles' ).closest( 'tr' ).show();
                    break;
                case '5':
                    $( '#pdr_exclude_user_roles' ).closest( 'tr' ).show();
                    break;
            }
        }, add_product_base_panel : function( event ) {
            event.preventDefault( );

            var $this = $( event.currentTarget ),
                    panels_wrapper = $( $this ).closest( '.pdr-product-base-data-panels-wrapper' );

            PDR_Admin.block( panels_wrapper );

            var data = ( {
                action : 'pdr_add_product_base_panel',
                pdr_security : pdr_admin_params.panel_nonce,
            } );

            $.post( ajaxurl, data, function( res ) {
                if ( true === res.success ) {
                    panels_wrapper.find( '.pdr-product-base-data-tabs' ).append( res.data.name );
                    panels_wrapper.find( '.pdr-product-base-data-panel-wrapper' ).append( res.data.content );

                    // Trigger click last tab.
                    $( document ).trigger( 'pdr-init-tabbed-panels' );
                    $( '.pdr-product-base-data-tab-link:last' ).trigger( 'click' );
                } else {
                    PDR_Admin.pdr_alert( res.data.error );
                }

                PDR_Admin.unblock( panels_wrapper );
            } );
        }, remove_product_base_panel : function( event ) {
            event.preventDefault( );

            var $this = $( event.currentTarget ),
                    key = $( $this ).data( 'key' ),
                    panel_wrapper = $( $this ).closest( '.pdr-product-base-panel-content' );

            panel_wrapper.remove();
            $( '.pdr-product-base-tab-' + key ).remove();

            $( '.pdr-product-base-data-tab-link:first' ).trigger( 'click' );
        }, initialize_attribute_field : function( ) {

            $( '.pdr-attributes-type' ).each( function() {
                PDR_Admin.attribute_color_type( this );
            } );
        }, tabbed_product_base_panels : function( ) {

            // trigger the clicked link.
            $( '.pdr-product-base-data-tab-link' ).on( 'click', function( event ) {
                event.preventDefault();
                var $this = $( event.currentTarget ),
                        panels_wrapper = $( $this ).closest( '.pdr-product-base-data-panels-wrapper' );

                $( '.pdr-product-base-data-tab', panels_wrapper ).removeClass( 'active' );
                $( $this ).parent().addClass( 'active' );

                $( 'div.pdr-product-base-panel-content', panels_wrapper ).hide();
                $( $( $this ).attr( 'href' ) ).show();
            } );

            // Trigger the first link.
            $( 'div.pdr-product-base-data-panels-wrapper' ).each( function() {
                $( this ).find( '.pdr-product-base-data-tab' ).eq( 0 ).find( 'a' ).click();
            } );
        },
        add_attributes : function( event ) {
            event.preventDefault( );

            var $this = $( event.currentTarget ),
                    panels_wrapper = $( $this ).closest( '.pdr-attributes-data-panels-wrapper' );
            PDR_Admin.block( panels_wrapper );

            var data = ( {
                action : 'pdr_add_attributes',
                pdr_security : pdr_admin_params.panel_nonce,
            } );

            $.post( ajaxurl, data, function( res ) {
                if ( true === res.success ) {
                    panels_wrapper.find( '.pdr-attributes-data-tabs' ).append( res.data.name );
                    panels_wrapper.find( '.pdr-attributes-data-panel-wrapper' ).append( res.data.content );

                    // Trigger click last tab.
                    $( document ).trigger( 'pdr-init-tabbed-attributes' );
                    $( '.pdr-attributes-data-tab-link:last' ).trigger( 'click' );
                    $( document ).trigger( 'pdr-init-attribute-field' );
                } else {
                    PDR_Admin.pdr_alert( res.data.error );
                }

                PDR_Admin.unblock( panels_wrapper );
            } );
        },
        remove_attributes : function( event ) {
            event.preventDefault( );

            var $this = $( event.currentTarget ),
                    key = $( $this ).data( 'key' ),
                    panel_wrapper = $( $this ).closest( '.pdr-attributes-panel-content' );

            panel_wrapper.remove();
            $( '.pdr-attributes-tab-' + key ).remove();

            $( '.pdr-attributes-data-tab-link:first' ).trigger( 'click' );
        },
        tabbed_attributes : function( ) {

            // trigger the clicked link.
            $( '.pdr-attributes-data-tab-link' ).on( 'click', function( event ) {
                event.preventDefault();
                var $this = $( event.currentTarget ),
                        panels_wrapper = $( $this ).closest( '.pdr-attributes-data-panels-wrapper' );

                $( '.pdr-attributes-data-tab', panels_wrapper ).removeClass( 'active' );
                $( $this ).parent().addClass( 'active' );

                $( 'div.pdr-attributes-panel-content', panels_wrapper ).hide();
                $( $( $this ).attr( 'href' ) ).show();
            } );

            // Trigger the first link.
            $( 'div.pdr-attributes-data-panels-wrapper' ).each( function() {
                $( this ).find( '.pdr-attributes-data-tab' ).eq( 0 ).find( 'a' ).click();
            } );
        }, attribute_color_popup : function( event ) {

            var _this = $( event.currentTarget ),
                    color_wrapper = _this.closest( '.pdr-attribute-color-wrapper' ),
                    render = function() {
                        var data = {
                            action : 'pdr_attribute_color_popup',
                            pdr_security : pdr_admin_params.panel_nonce,
                            data : _this.data(),
                        };

                        $.post( ajaxurl, data, function( res ) {
                            if ( true === res.success ) {
                                $( res.data.content ).appendTo( 'body' );
                                add_event();
                            } else {
                                PDR_Admin.pdr_alert( res.data.error );
                            }
                        }
                        );

                    },
                    add_event = function() {
                        var ele = $( '.pdr-attribute-color-popup-wrapper' );

                        ele.on( 'click', '.pdr-attribute-save-color', save_attribute_color );
                        ele.on( 'click', '.pdr-attribute-update-color', update_color_fields );
                        ele.on( 'change', '.pdr-color-picker', update_attribute_color );
                        ele.on( 'click', '.pdr-attribute-color-close-popup', close_popup );

                    },
                    update_attribute_color = function( event ) {

                        var $this = $( event.currentTarget ),
                                body = $( $this ).closest( '.pdr-attribute-color-popup-body' );

                        body.find( '.pdr-color-value' ).val( $this.val() );
                    },
                    update_color_fields = function( event ) {
                        event.preventDefault( );

                        var $this = $( event.currentTarget ),
                                body = $( $this ).closest( '.pdr-attribute-color-popup-body' ),
                                color_lists = color_wrapper.find( '.pdr-attribute-color-lists' ),
                                color_name = body.find( '.pdr-color-name' ).val(),
                                color_value = body.find( '.pdr-color-value' ).val(),
                                color_price = body.find( '.pdr-color-price' ).val();



                        if ( '' == color_name ) {
                            color_name = color_value;
                        }
                        var list = '<li class="pdr-atrribute-color" style="background:' + color_value + '" title="' + color_name + '">\n\
                                <span class="dashicons dashicons-dismiss pdr-attribute-remove-color"></span>\n\
                                <span data-price="' + color_price + '" data-color="' + color_value + '" data-colortitle="' + color_name + '" class="dashicons dashicons-edit pdr-attribute-edit-color"></span>\n\
                                <input type="hidden" name="pdr_attributes[' + color_lists.data( 'key' ) + '][product_color_price][' + color_value + ']" value="' + color_price + '"><input type="hidden" name="pdr_attributes[' + color_lists.data( 'key' ) + '][product_color][' + color_value + ']" value="' + color_name + '">\n\
                                </li>';
                        _this.parent().remove();
                        color_lists.append( list );
                        $( '.pdr-attribute-color-popup-wrapper' ).remove();
                    },
                    save_attribute_color = function( event ) {
                        event.preventDefault( );

                        var $this = $( event.currentTarget ),
                                body = $( $this ).closest( '.pdr-attribute-color-popup-body' ),
                                color_lists = color_wrapper.find( '.pdr-attribute-color-lists' ),
                                color_name = body.find( '.pdr-color-name' ).val(),
                                color_value = body.find( '.pdr-color-value' ).val(),
                                color_price = body.find( '.pdr-color-price' ).val();

                        if ( '' == color_name ) {
                            color_name = color_value;
                        }

                        var list = '<li class="pdr-atrribute-color" style="background:' + color_value + '" title="' + color_name + '">\n\
                                <span class="dashicons dashicons-dismiss pdr-attribute-remove-color"></span>\n\
                                <input type="hidden" name="pdr_attributes[' + color_lists.data( 'key' ) + '][product_color_price][' + color_value + ']" value="' + color_price + '"><input type="hidden" name="pdr_attributes[' + color_lists.data( 'key' ) + '][product_color][' + color_value + ']" value="' + color_name + '">\n\
                                </li>';

                        color_lists.append( list );
                        $( '.pdr-attribute-color-popup-wrapper' ).remove();
                    },
                    close_popup = function( event ) {
                        event.preventDefault( );
                        var $this = $( event.currentTarget );

                        $( $this ).closest( '.pdr-attribute-color-popup-wrapper' ).remove();
                    };

            event.preventDefault();
            render();
            return;

        }, remove_attribute_color : function( event ) {
            event.preventDefault( );
            var $this = $( event.currentTarget );

            $( $this ).closest( 'li' ).remove();
        },
        edit_attribute_color : function( event ) {
            var data = {
                action : 'pdr_edit_attribute_color_popup',
                pdr_security : pdr_admin_params.panel_nonce,
                data : $( this ).data(),
            };

            $.post( ajaxurl, data, function( res ) {
                if ( true === res.success ) {
                    $( res.data.content ).appendTo( 'body' );
                    add_event();
                } else {
                    PDR_Admin.pdr_alert( res.data.error );
                }
            }
            );
            return false;
        },
        prepare_image : function( e ) {
            e.preventDefault();

            var reader = new FileReader();
            reader.file = this.files[0];

            if ( 'text/plain' !== reader.file.type && 'image/png' !== reader.file.type && 'image/jpeg' !== reader.file.type && 'image/jpg' !== reader.file.type ) {
                PDR_Admin.pdr_alert( pdr_admin_params.design_file_upload_error );
                return false;
            }

            reader.onload = function( e ) {
                var uploadedData,
                        content = $( '.pdr-design-template-content' ),
                        canvas = $( '.pdr-design-template-canvas' ),
                        preview = $( '.pdr-design-template-preview' );

                if ( 'text/plain' === reader.file.type ) {
                    uploadedData = JSON.parse( atob( e.target.result.split( 'base64,' )[1] ) );
                } else {
                    uploadedData = reader.result;
                }

                if ( uploadedData.dataUrl ) {
                    preview.attr( 'src', uploadedData.dataUrl );
                    content.val( uploadedData.dataUrl );
                    canvas.val( JSON.stringify( uploadedData.canvasObject ) );
                } else {
                    preview.attr( 'src', uploadedData );
                    content.val( uploadedData );
                    canvas.val( '' );
                }
            };

            reader.readAsDataURL( reader.file );
        },
        enable_modules : function( event ) {
            event.preventDefault( );
            var $this = $( event.currentTarget );

            PDR_Admin.block( $this );

            var data = ( {
                action : 'pdr_enable_modules',
                key : $this.data( 'key' ),
                action_type : $this.data( 'action' ),
                pdr_security : pdr_admin_params.module_nonce,
            } );

            $.post( ajaxurl, data, function( res ) {
                if ( true === res.success ) {
                    $this.data( 'action', res.data.action );
                    $this.html( res.data.label );
                } else {
                    PDR_Admin.pdr_alert( res.data.error );
                }

                PDR_Admin.unblock( $this );
            } );

        },
        theme_selection : function() {
            PDR_Admin.visibility_theme_selection( $( this ).val() );
        },
        visibility_theme_selection : function( value ) {
            let a = [ "pdr_theme_header_bg_color", "pdr_theme_body_bg_color", "pdr_theme_footer_bg_color", "pdr_theme_button_bg_color", "pdr_theme_button_font_color", "pdr_theme_font_color" ];
            if ( 'custom' === value ) {
                while ( a.length ) {
                    $( '#' + a.shift() ).parent().parent().show();
                }
            } else {
                while ( a.length ) {
                    $( '#' + a.shift() ).parent().parent().hide();
                }
            }
        },
        show_hide_font : function() {
            PDR_Admin.visibility_font_selection( $( this ).val() );
        },
        show_hide_font_base : function() {
            PDR_Admin.visibility_font_selection( $( this ).val(), 'base' );
        },
        visibility_font_selection : function( value, base = 'settings' ) {
            let a = [ "pdr_font_selection" ];
            if ( '2' === value ) {
                while ( a.length ) {
                    if ( base === 'base' ) {
                        $( '#' + a.shift() + '_base' ).parent().show();
                    } else {
                        $( '#' + a.shift() ).parent().parent().show();
                    }
                }
            } else {
                while ( a.length ) {
                    if ( base === 'base' ) {
                        $( '#' + a.shift() + '_base' ).parent().hide();
                    } else {
                        $( '#' + a.shift() ).parent().parent().hide();
                    }
                }
        }
        },
//        base_visibility_font_selection: function (value) {
//            let a = ["pdr_font_selection"];
//            if ('2' === value) {
//                while(a.length) {
//                    $('#'+a.shift()).parent().parent().hide
//                }
//            }
//        },
        block : function( id ) {
            if ( ! PDR_Admin.is_blocked( id ) ) {
                $( id ).addClass( 'processing' ).block( {
                    message : null,
                    overlayCSS : {
                        background : '#fff',
                        opacity : 0.7
                    }
                } );
            }
        }
        , unblock : function( id ) {
            $( id ).removeClass( 'processing' ).unblock( );
        }, is_blocked : function( id ) {
            return $( id ).is( '.processing' ) || $( id ).parents( '.processing' ).length;
        }, pdr_alert : function( msg ) {
            $.alertable.alert( msg )
        }

    };
    PDR_Admin.init( );
} );
