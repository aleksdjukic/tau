/* global pdr_frontend, fabric, PDFJS, accounting, WebFont, jspdf, watermark, Blitz, PDR_ClientDB */

'use strict';

jQuery( window ).on( 'load', function( ) {

    var product_designer = {
        init : function( ) {
            $( document ).on( 'click', '.quantity-action', this.qty ); //qty
            $( document ).on( 'click', '.color-preview', this.setbg ); //set background color to canvas
            $( document ).on( 'click', '.pdr-clear-bg', this.clearbg );
            $( document ).on( 'click', '#pdr-image-file', this.force_guest_user ); //Force guest user
            $( document ).on( 'change', '#pdr-image-file', this.image_upload ); //image upload to canvas
            $( document ).on( 'change', '#pdr-text-mask-image-file', this.text_mask_image );
            $( document ).on( 'click', '.clipart-thumbnail-image', this.add_cliparts ); //add cliparts
            $( document ).on( 'click', '.pdr-template-image', this.addDesignTemplates ); //add cliparts
            $( document ).on( 'click', '.pdr-shape', this.add_shapes ); //add shapes
            $( document ).on( 'click', '#pdr-object-delete-selected', this.delete_object ); //delete object from canvas
            $( document ).on( 'click', '#pdr-object-send-to-front', this.send_to_front ); //object send it to front
            $( document ).on( 'click', '#pdr-object-send-to-back', this.send_to_back ); // object send it to back

            // Image Filters
            $( document ).on( 'click', '.pdr-object-filters img', this.imageFilterSelected );
            $( document ).on( 'input change', '.pdr-object-filter-range', this.imageFilterRangeChanged );
            $( document ).on( 'click', '.pdr-object-filter-range-reset', this.resetImageFilterRange );

            //copy and paste object
            $( document ).on( 'click', '#pdr-object-copy-selected', this.copy );
            $( document ).on( 'click', '#pdr-object-paste-selected', this.paste );

            $( document ).on( 'click', '.pdr-cart-button', this.addToCart );
            $( document ).on( 'click', '.pdr-single-product-button', this.addToCart );
            $( document ).on( 'click', '.pdr-export-file', this.export_file );
            $( document ).on( 'click', '.pdr-export-template', this.exportTemplate );
            $( document ).on( 'click', '.pdr-download-action', this.download_image );
            $( document ).on( 'click', '.pdr-download-pdf-action', this.download_as_pdf );
            $( document ).on( 'click', '#canvas2png', this.canvas2png );
            $( document ).on( 'change', '#pdr-switch-view-select', this.switch_view );
            $( document ).on( 'click', '#pdr-text-add-new', this.add_text );
            $( document ).on( 'click', '.pdr-gfont-family-list', this.set_google_fonts );
            $( document ).on( 'click', '.pdr-apply-qr-action', this.apply_qr );

            $( '#pdr-object-filters' ).popup( {
                popup : $( '.pdr-object-filters-view' ),
                hoverable : true,
                boundary : $( 'body' ),
                lastResort : true,
                on : 'click',
                hideOnScroll : false
            } );

            $( '.pdr-text-font-btn' ).popup( {
                popup : $( '.pdr-custom-btn-content' ),
                hoverable : true,
                on : 'click',
                hideOnScroll : false
            } );

            $( '.pdr-object-btn' ).popup( {
                popup : $( '.pdr-object-tools-popup' ),
                hoverable : true,
                on : 'click',
                hideOnScroll : false
            } );

            $( '.pdr-print' ).popup( {
                popup : $( '.pdr-print-data.flowing.popup' ),
                hoverable : true,
                on : 'click',
                hideOnScroll : false
            } );

            $( '.pdr-text-edit-tools' ).popup( {
                popup : '.pdr-text-edit-tools-popup',
                hoverable : true,
                on : 'click',
                hideOnScroll : false
            } );

            $( '.pdr-text-mask-tools' ).popup( {
                popup : '.pdr-text-mask-tools-popup',
                hoverable : true,
                on : 'click',
                hideOnScroll : false
            } );

            $( '.pdr-qr-generation' ).popup( {
                popup : '.pdr-qr-generation-tools-popup',
                hoverable : true,
                on : 'click',
                hideOnScroll : false
            } );

            $( '.pdr-base-view.dropdown' ).dropdown( {
                onChange : function( value, text, choice ) {
                    //do further action here
                    $( '.ui .search' ).blur();
                }
            } );

            $( '.ui.pdr-checkbox-field.checkbox' ).checkbox( {
                onChecked : function( ) {
                    $( document ).trigger( 'pdr_calculate_price' );
                },
                onUnchecked : function( ) {
                    $( document ).trigger( 'pdr_calculate_price' );
                }
            } );

            $( '.ui.pdr-radio-field.checkbox' ).checkbox( {
                onChange : function( ) {
                    var get_price = $( this ).attr( 'data-price-radio' );
                    var get_key = $( this ).attr( 'data-uniqid' );
                    $( document ).trigger( 'pdr_calculate_price' );
                }

            } );

            /* Form Validation and Initialization*/
            var form_validation = JSON.parse( pdr_frontend.attributes_validation );
            $( '.pdr-product-details-form' ).form( {
                fields : form_validation
            } );

            if ( ! pdr_frontend.is_mobile ) {
                $( '.ui.sticky' ).sticky( {
                    context : '.pdr-content-section'
                } );
            }

            $( '.tabular.menu  .item' ).click( function( ) {
                $( '.pdr_vertical_content' ).addClass( 'hidden' );
                $( '.tabular.menu  .item' ).removeClass( 'active' );
                $( this ).addClass( 'active' );
                $( this ).transition( 'pulse' );
                var content = $( this ).attr( 'data-content' );
                $( '.' + content ).removeClass( 'hidden' );
            } );

            $( '.ui.pdr-font-families.search.dropdown' ).dropdown( {
                onChange : function( value, text, choice ) {
                    $( '.ui .search' ).blur();
                    var get_data = choice.attr( 'data-remote-font' );
                    if ( 'google' === get_data ) {
                        product_designer.set_google_fonts( value );
                    } else if('custom' === get_data){
                        product_designer.set_custom_fonts( value );
                    }else {
                        product_designer.set_default_fonts( value );
                    }
                }
            } );

            $( '.ui.pdr-cliparts-category' ).dropdown( {
                selectOnKeydown : false,
                onChange : function( value, text, choice ) {
                    $( '.ui .search' ).blur();
                    product_designer.fetch_cliparts( value );

                }
            } );

            $( '.ui.pdr-shapes-category' ).dropdown( {
                onChange : function( value, text, choice ) {
                    $( '.ui .search' ).blur();
                    product_designer.fetch_shapes( value );

                }
            } );

            $( '.ui.pdr-templates-category' ).dropdown( {
                onChange : function( value, text, choice ) {
                    $( '.ui .search' ).blur();
                    product_designer.fetch_templates( value );

                }
            } );

            //Print Functionality tooltip functionality
            $( '.pdr-print-functionality .item' ).click( function( ) {
                $( '.pdr-print-functionality .item' ).removeClass( 'active' );
                $( this ).addClass( 'active' );
                var get_tab = $( this ).attr( 'data-tab' );
                $( '.pdr-print-data .ui.tab' ).removeClass( 'active' );
                $( '.pdr-print-data .ui.tab[data-tab=' + get_tab + ']' ).addClass( 'active' );
            } );

            //select paper size and calculation in print
            $( 'select.pdr-attributes-select.dropdown' ).dropdown( {
                clearable : true,
                onChange : function( value, text, choice ) {
                    if ( value ) {
                        var dynamic_price = choice.parent( ).parent( ).children( 'select' ).children( "[value='" + value + "']" ).attr( 'data-price' );
                        choice.parent( ).parent( ).children( 'select' ).attr( 'data-price', dynamic_price ); //add price here
                    }
                    $( document ).trigger( 'pdr_calculate_price' );
                }
            } );

            $( 'select.dropdown' ).dropdown( );
            $( '.pdr-print-paper-size' ).dropdown( {
                onChange : function( value, text, choice ) {
                    var measure_data = $( '.pdr-print-paper-measure' ).dropdown( 'get value' );
                    product_designer.set_print_input_data( value, measure_data );
                },
            } );

            $( '.pdr-print-paper-measure' ).dropdown( {
                onChange : function( value, text, choice ) {
                    var print_size_data = $( '.pdr-print-paper-size' ).dropdown( 'get value' );
                    product_designer.set_print_input_data( print_size_data, value );
                },
            } );

            $( document ).on( 'click', '.pdr-font-family-list', this.set_default_font_family );
            $( document ).on( 'click', '.pdr-font-weight', this.set_font_weight );
            $( "#pdr-number-field" ).bind( 'keyup mouseup', function( e ) {
                product_designer.set_font_size( e );
            } );
            $( ".pdr-input-fields" ).bind( 'keyup mouseup', function( e ) {
                $( document ).trigger( 'pdr_calculate_price' );
            } );
            $( document ).on( 'click', '.pdr-print-action', this.print );
            //save design
            $( document ).on( 'click', '.pdr-save-design', this.save_design );
            $( document ).on( 'click', '.pdr-my-design-data', this.set_design_data );
            //delete my design
            $( document ).on( 'click', '.pdr-my-design-delete', this.delete_my_design );

            $( document ).on( 'change', '#pdr-color-field', this.set_font_color );
            $( document ).on( 'change', '#pdr-text-bg-field', this.set_bg_color );

            $( document ).on( 'change', '#pdr-shape-color-field', this.set_shape_fill_color );
            $( document ).on( 'change', '#pdr-shape-stroke-color-field', this.set_shape_stroke_color );

            $( document ).on( 'pdr_calculate_price', this.calculatePrice );
            $( document ).on( 'click', '.pdr-align-text', this.align_text );
            $( document ).on( 'click', '.pdr-position-text', this.position_text );
            $( document ).on( 'click', '.pdr-transform-text', this.text_transform );
            $( document ).on( 'click', '.pdr-text-style', this.font_style );
            $( document ).on( 'click', '.pdf_preview_img', this.apply_pdf_image );
            this.canvas_event( );
            $( document ).on( 'click', 'a.nav__link', function() {
                var sectionId = $( this ).attr( 'href' ).replace( "#", '' );
                $( '.active-link' ).removeClass( 'active-link' );
                document.querySelector( '.nav__menu a' ).classList.remove( 'active-link' );
                document.querySelector( '.nav__menu a[href*=' + sectionId + ']' ).classList.add( 'active-link' );
                //show or hide the section as per the selection
                $( 'section.pdr-container' ).addClass( 'hidden' );
                $( 'section#' + sectionId ).removeClass( 'hidden' );

                return false;
            } );
        },
        initialize_canvas : function( ) {
            var canvas = window._canvas = new fabric.Canvas( 'c', {

                controlsAboveOverlay : true,
                centeredScaling : true,
                allowTouchScrolling : true
            } );

            $( document.body ).trigger( 'pdr_initialized_canvas', canvas );
            return canvas;
        },
        qty : function( event ) {
            var action_type = jQuery( this ).attr( 'data-type' );
            var min_value = jQuery( "#quantity" ).attr( 'min' );
            var max_value = jQuery( "#quantity" ).attr( 'max' );
            if ( "minus" === action_type ) {
                var quantity = parseInt( $( '#quantity' ).val( ) );
                var updated_min_value = ( quantity - 1 );
                updated_min_value = updated_min_value >= min_value ? updated_min_value : min_value;
                $( '#quantity' ).val( updated_min_value );
            } else {
                //addition
                var quantity = parseInt( $( '#quantity' ).val( ) );
                var updated_max_value = ( quantity + 1 );
                updated_max_value = updated_max_value <= max_value ? updated_max_value : max_value;
                $( '#quantity' ).val( updated_max_value );
            }

            $( document ).trigger( 'pdr_calculate_price' );
            return  false;
        },
        setbg : function( event ) {
            var get_current_color = $( this ).attr( 'data-color' );
            var get_current_color_price = $( this ).attr( 'data-price' );
            product_designer._setbg( get_current_color, get_current_color_price );
        },
        _setbg : function( get_current_color, get_current_color_price, is_undo_redo = '' ) {
            $( '.canvas-container' ).css( "background", get_current_color );
            master_data['pdr_extra'] = { bg : get_current_color, price : get_current_color_price };

            $( '#pdr-color-preview-selection' ).val( get_current_color );
            $( '.pdr-clear-bg' ).show();
            $( '#pdr-color-preview-selection' ).attr( 'data-price', get_current_color_price );

            var view_checker = $( '#pdr-switch-view-select' ).val( );
            image_data[view_checker] = product_designer.add_bg_to_canvas( '', false );
            object_data ++;
            if ( is_undo_redo == '2' ) {
                overlayimgdata['pdr_extra'] = { bg : get_current_color, price : get_current_color_price };
                product_designer.updateCanvasState( overlayimgdata );
            }
            $( document ).trigger( 'pdr_calculate_price' );
            product_designer.mobile_menu_active();

        },
        clearbg : function( event ) {
            var get_current_color = '';
            product_designer._clearbg( get_current_color );
            return false;
        },
        _clearbg : function( get_current_color, is_undo_redo = '' ) {
            $( '.canvas-container' ).css( "background", get_current_color );
            master_data['pdr_extra'] = { bg : get_current_color, price : 0 };
            var view_checker = $( '#pdr-switch-view-select' ).val( );
            image_data[view_checker] = product_designer.add_bg_to_canvas( get_current_color, false );
            $( '#pdr-color-preview-selection' ).val( get_current_color );
            $( '.pdr-clear-bg' ).hide();
            object_data --;
            if ( is_undo_redo == '2' ) {
                overlayimgdata['pdr_extra'] = { bg : get_current_color, price : 0 };
                product_designer.updateCanvasState( overlayimgdata );
            }
            $( document ).trigger( 'pdr_calculate_price' );
        },
        set_print_input_data : function( size = "a10", measure = "mm" ) {
            var width = pageSizes[size]['portrait'][measure].width;
            var height = pageSizes[size]['portrait'][measure].height;
            $( '#pdr-print-data-width' ).val( width );
            $( '#pdr-print-data-height' ).val( height );
        },
        force_guest_user : function( e ) {
            if ( '0' == pdr_frontend.user_id && '1' == pdr_frontend.image_upload_force_user ) {
                product_designer.pdr_alert( pdr_frontend.image_guest_alert_msg );
                return false;
            }
        },
        upload_pdf : function( fileReader ) {
            product_designer.pdr_block( $( 'body' ) );
            window.PDFJS = window.pdfjsLib;
            PDFJS.getDocument( fileReader.result ).promise.then( function( pdf ) {
                for ( var i = 1; i <= pdf.numPages; i ++ ) {
                    pdf.getPage( i ).then( function( page ) {
                        var scale = 1.5;
                        var viewport = page.getViewport( { scale } );
                        var tempcanvas = document.createElement( 'canvas' );
                        var tempcontext = tempcanvas.getContext( '2d' );
                        tempcanvas.height = viewport.height;
                        tempcanvas.width = viewport.width;
                        var renderContext = {
                            canvasContext : tempcontext,
                            viewport : viewport
                        };
                        var renderTask = page.render( renderContext );
                        renderTask.promise.then( function() {
                            var image = "<div class='pdf_preview_sub'><img class='pdf_preview_img' src='" + tempcanvas.toDataURL() + "' /></div>";
                            jQuery( '.pdf_preview' ).append( image );
                        } );
                    } );

                    if ( i === pdf.numPages ) {
                        product_designer.pdr_unblock( $( 'body' ) );
                    }
                }
            } );
        },
        validate_image_upload : function( file ) {
            // Validate image extensions.
            if ( JSON.stringify( pdr_frontend.image_valid_ext ) != '[]' ) {
                var ext = file.name.substring( file.name.lastIndexOf( '.' ) + 1 ).toLowerCase();
                if ( '-1' == pdr_frontend.image_valid_ext.indexOf( ext ) ) {
                    product_designer.pdr_alert( pdr_frontend.image_ext_error_msg );
                    return false;
                }
            }

            // Validate image size.
            if ( 0 !== pdr_frontend.image_min_size && 0 !== pdr_frontend.image_max_size ) {
                var size = Math.round( ( file.size / 1024 ) );
                if ( size < pdr_frontend.image_min_size ) {
                    product_designer.pdr_alert( pdr_frontend.image_min_size_error_msg.replace( '%s', pdr_frontend.image_min_size ) );
                    return false;
                }
                if ( size > pdr_frontend.image_max_size ) {
                    product_designer.pdr_alert( pdr_frontend.image_max_size_error_msg.replace( '%s', pdr_frontend.image_max_size ) );
                    return false;
                }
            }

            // Validate image dimesions.
            if ( '' != pdr_frontend.image_min_dimension || '' != pdr_frontend.image_max_dimension ) {
                var _URL = window.URL || window.webkitURL;
                var img = new Image();
                img.src = _URL.createObjectURL( file );

                img.onload = function() {
                    var imgwidth = this.width;
                    var imgheight = this.height;

                    if ( imgwidth < pdr_frontend.image_min_dimension[0] || imgheight < pdr_frontend.image_min_dimension[1] ) {
                        product_designer.pdr_alert( pdr_frontend.image_min_dimension_error_msg.replace( '%s', pdr_frontend.image_min_dimension.join( 'x' ) ) );
                        return false;
                    }
                    if ( imgwidth > pdr_frontend.image_max_dimension[0] || imgheight > pdr_frontend.image_max_dimension[1] ) {
                        product_designer.pdr_alert( pdr_frontend.image_max_dimension_error_msg.replace( '%s', pdr_frontend.image_max_dimension.join( 'x' ) ) );
                        return false;
                    }

                    _URL.revokeObjectURL();
                };
            }

            return true;
        },
        apply_pdf_image : function( e ) {
            var pdf_image = $( this );
            var parent_wrapper = $( '#pdr-image-file' );
            fabric.Image.fromURL( pdf_image.attr( 'src' ), function( img ) {
                img['pdr_property'] = {
                    type : 'image_upload',
                    price : parseFloat( parent_wrapper.data( 'price' ) ) || 0
                };
                img.toObject = ( function( toObject ) {
                    return function( propertiesToInclude ) {
                        return fabric.util.object.extend( toObject.call( this, propertiesToInclude ), {
                            pdr_property : {
                                type : 'image_upload',
                                price : parseFloat( parent_wrapper.data( 'price' ) ) || 0
                            }
                        } );
                    };
                } )( img.toObject );
                product_designer.fit_obj( img );
                canvas.add( img ).setActiveObject( img ).renderAll( );
                $( document ).trigger( 'pdr_calculate_price' );
            } );
        },
        image_upload : function( e ) {
            var image = $( this );
            var file = e.target.files[0];

            if ( ! product_designer.validate_image_upload( file ) ) {
                return false;
            }

            var reader = new FileReader( );
            reader.onload = function( f ) {
                var extension = file.name.split( '.' ).pop().toLowerCase();
                if ( extension === 'pdf' ) {
                    //if it is pdf perform the following
                    product_designer.upload_pdf( reader );
                } else {
                    var data = f.target.result;
                    console.log( data );
                    fabric.Image.fromURL( data, function( img ) {
                        product_designer.fit_obj( img );
                        img['pdr_property'] = {
                            type : 'clipart',
                            price : parseFloat( image.data( 'price' ) ) || 0
                        };
                        img.toObject = ( function( toObject ) {
                            return function( propertiesToInclude ) {
                                return fabric.util.object.extend( toObject.call( this, propertiesToInclude ), {
                                    pdr_property : {
                                        type : 'image_upload',
                                        price : parseFloat( image.data( 'price' ) ) || 0
                                    }

                                } );
                            };
                        } )( img.toObject );
                        canvas.add( img ).setActiveObject( img ).renderAll( );
                        product_designer.mobile_menu_active();
                    } );
                }
            };
            reader.readAsDataURL( file );
        },
        text_mask_image : function( e ) {
            console.log( textbox );
            var image = $( this );
            var file = e.target.files[0];
            console.log( file );
            if ( ! product_designer.validate_image_upload( file ) ) {
                return false;
            }

            var reader = new FileReader( );
            var padding = 0;

            reader.onload = function( f ) {
                var data = f.target.result;
                fabric.Image.fromURL( data, function( img ) {
                    patternSourceCanvas = new fabric.StaticCanvas( );
                    patternSourceCanvas.add( img );
                    patternSourceCanvas.renderAll( );
                    pattern = new fabric.Pattern( {
                        source : patternSourceCanvas.getElement( ),
                        repeat : 'repeat',
                    } );
                    canvas.getActiveObject( ).cleanStyle( 'fill' );
                    canvas.getActiveObject().set( 'fill', pattern );

                    canvas.requestRenderAll( );
                } );

            },
                    reader.readAsDataURL( file );
        },
        delete_object : function( event ) {
            var activeObject = canvas.getActiveObject( );
            if ( activeObject ) {
                canvas.remove( activeObject );
            }
            $( document ).trigger( 'pdr_calculate_price' );
        },
        send_to_front : function( event ) {
            var activeObject = canvas.getActiveObject( );
            if ( activeObject ) {
                activeObject.bringToFront( );
            }
        },
        send_to_back : function( event ) {
            var activeObject = canvas.getActiveObject( );
            if ( activeObject ) {
                activeObject.sendToBack( );
            }
        },
        imageFilterSelected : function( event ) {
            var activeObject = canvas.getActiveObject(),
                    selected_filter = $( event.currentTarget ).data( 'filter' );

            if ( activeObject ) {
                $( this ).closest( '.row' ).find( 'img' ).each( function() {
                    $( this ).removeClass( 'pdr-object-filter-selected' );
                } );

                $( this ).addClass( 'pdr-object-filter-selected' );

                if ( 'Sharpen' === selected_filter ) {
                    activeObject.filters[0] = new fabric.Image.filters.Convolute( {
                        matrix : [ 0, - 1, 0,
                            - 1, 5, - 1,
                            0, - 1, 0 ]
                    } );
                } else if ( 'Emboss' === selected_filter ) {
                    activeObject.filters[0] = new fabric.Image.filters.Convolute( {
                        matrix : [ 1, 1, 1,
                            1, 0.7, - 1,
                            - 1, - 1, - 1 ]
                    } );
                } else if ( 'Original' !== selected_filter ) {
                    activeObject.filters[0] = new fabric.Image.filters[selected_filter]();
                } else {
                    activeObject.filters[0] = false;
                }

                activeObject.applyFilters();
                var view_checker = $( '#pdr-switch-view-select' ).val( );
                image_data[view_checker] = product_designer.add_bg_to_canvas( '', false );
                canvas.renderAll();
            }
        },
        imageFilterRangeChanged : function( event ) {
            var activeObject = canvas.getActiveObject(),
                    active_range_value,
                    $this = $( event.currentTarget ),
                    changed_filter = $this.data( 'filter' ),
                    changed_filter_index = parseInt( $this.data( 'index' ) ),
                    changed_range_value = parseFloat( $this.val() ),
                    output = parseInt( ( changed_range_value * 1000 ) / 10 );

            if ( 0 !== output ) {
                $this.closest( '.row' ).find( '.pdr-object-filter-range-reset' ).closest( 'div' ).show();
            } else {
                $this.closest( '.row' ).find( '.pdr-object-filter-range-reset' ).closest( 'div' ).hide();
            }

            $this.closest( '.row' ).find( '.pdr-object-filter-range-output' ).text( output );

            if ( activeObject ) {
                active_range_value = 'object' === typeof activeObject.filters[changed_filter_index] ? parseFloat( activeObject.filters[changed_filter_index][changed_filter.toLowerCase()] ) : 0;

                if ( changed_range_value !== active_range_value ) {
                    activeObject.filters[changed_filter_index] = new fabric.Image.filters[changed_filter]( {
                        [changed_filter.toLowerCase()] : [ changed_range_value ]
                    } );

                    activeObject.applyFilters();
                    var view_checker = $( '#pdr-switch-view-select' ).val( );
                    image_data[view_checker] = product_designer.add_bg_to_canvas( '', false );
                    canvas.renderAll();
                }
            }
        },
        resetImageFilterRange : function( event ) {
            $( event.currentTarget ).closest( '.row' ).find( '.pdr-object-filter-range' ).val( 0 ).change();
        },
        fit_obj : function( obj ) {
            var view_checker = $( '#pdr-switch-view-select' ).val( );
            var get_current_color = $( '#pdr-color-preview-selection' ).val( );
            image_data[view_checker] = product_designer.add_bg_to_canvas( '', false );
            var obj_area = product_designer.original_area( view_checker );
            obj.set( {
                left : obj_area.x,
                top : obj_area.y,
            } );
            obj.scaleToHeight( obj_area.h );
            obj.scaleToWidth( obj_area.w );
            is_edited = true;
            return obj;
        },
        add_cliparts : function( event ) {
            clipart = $( this );
            fabric.Image.fromURL( clipart.attr( 'src' ), function( img ) {
                img['pdr_property'] = {
                    type : 'clipart',
                    price : parseFloat( clipart.data( 'price' ) ) || 0
                };
                img.toObject = ( function( toObject ) {
                    return function( propertiesToInclude ) {
                        return fabric.util.object.extend( toObject.call( this, propertiesToInclude ), {
                            pdr_property : {
                                type : 'clipart',
                                price : parseFloat( clipart.data( 'price' ) ) || 0
                            }
                        } );
                    };
                } )( img.toObject );
                product_designer.fit_obj( img );
                canvas.add( img ).setActiveObject( img ).renderAll( );
                $( document ).trigger( 'pdr_calculate_price' );
                product_designer.mobile_menu_active();
            } );
        },
        addDesignTemplates : function( e ) {
            var template = $( this );

            count = Object.keys( rules ).length;
            get_keys = Object.keys( rules );

            if ( 'yes' === template.data( 'has-canvas' ) ) {
                $( '.pdr-canvas-area' ).dimmer( 'add content', $( '#pdr_pre_loader' ) ).dimmer( 'show' );

                $.ajax( {
                    type : 'POST',
                    url : pdr_frontend.ajaxurl,
                    dataType : 'json',
                    data : {
                        action : 'pdr_get_canvas_object',
                        pdr_security : pdr_frontend.get_canvas_object_nonce,
                        template_id : template.data( 'id' )
                    },
                    success : function( response ) {
                        master_data = JSON.parse( response.canvas );

                        for ( var view in master_data ) {
                            master_data[view] = JSON.parse( master_data[view] );

                            if ( 'undefined' !== typeof canvas.toJSON().backgroundImage ) {
                                if ( 'undefined' !== typeof master_data[view].backgroundImage ) {
                                    master_data[view].backgroundImage.src = canvas.toJSON().backgroundImage.src;
                                } else {
                                    master_data[view].overlayImage.src = canvas.toJSON().backgroundImage.src;
                                }
                            } else {
                                if ( 'undefined' !== typeof master_data[view].backgroundImage ) {
                                    master_data[view].backgroundImage.src = canvas.toJSON().overlayImage.src;
                                } else {
                                    master_data[view].overlayImage.src = canvas.toJSON().overlayImage.src;
                                }
                            }

                            $.each( master_data[view].objects, function( i, object ) {
                                object['pdr_property'] = {
                                    type : 'templates',
                                    price : parseFloat( template.data( 'price' ) ) || 0
                                };
                            } );

                            master_data[view] = JSON.stringify( master_data[view] );
                        }

                        product_designer.loadCurrentView();
                        pdr_fetch_all_views( count );
                    }
                } );
            } else {
                fabric.Image.fromURL( template.attr( 'src' ), function( img ) {
                    img['pdr_property'] = {
                        type : 'clipart',
                        price : parseFloat( template.data( 'price' ) ) || 0
                    };
                    img.toObject = ( function( toObject ) {
                        return function( propertiesToInclude ) {
                            return fabric.util.object.extend( toObject.call( this, propertiesToInclude ), {
                                pdr_property : {
                                    type : 'clipart',
                                    price : parseFloat( template.data( 'price' ) ) || 0
                                }
                            } );
                        };
                    } )( img.toObject );
                    product_designer.fit_obj( img );
                    canvas.add( img ).setActiveObject( img ).renderAll( );
                    $( document ).trigger( 'pdr_calculate_price' );
                    product_designer.mobile_menu_active();
                } );
            }
        },
        add_shapes : function( event ) {
            var shape = $( this );
            var getsvg = $( this ).html( );
            fabric.loadSVGFromString( getsvg, function( objects, options ) {
                objs = fabric.util.groupSVGElements( objects, options );
                product_designer.fit_obj( objs );
                objs['pdr_property'] = {
                    type : 'shape',
                    price : parseFloat( shape.data( 'price' ) ) || 0
                };
                objs.toObject = ( function( toObject ) {
                    return function( propertiesToInclude ) {
                        return fabric.util.object.extend( toObject.call( this, propertiesToInclude ), {
                            pdr_property : {
                                type : 'shape',
                                price : parseFloat( shape.data( 'price' ) ) || 0
                            }
                        } );
                    };
                } )( objs.toObject );
                canvas.add( objs ).setActiveObject( objs );
            } );
            canvas.renderAll( );
            $( document ).trigger( 'pdr_calculate_price' );
            product_designer.mobile_menu_active();
        },
        add_color_to_shapes : function() {
            if ( objs && objs._objects ) {
                for ( var i = 0; i < objs._objects.length; i ++ ) {
                    objs._objects[i].set( {
                        fill : '#cccccc',
                        stroke : "#cccccc"
                    } );
                }
            } else {
                objs.set( {
                    fill : '#cccccc',
                    stroke : "#cccccc"
                } );
            }
        },
        add_text : function( event ) {
            var get_text = $( '#pdr-textarea' ).val( );
            var view_checker = $( '#pdr-switch-view-select' ).val( );
            var text_price = product_designer.calculate_text_price( pdr_frontend.text_char_fee, get_text, pdr_frontend.text_char_count );
            var obj_area = product_designer.original_area( view_checker );
            textbox = new fabric.Textbox( get_text, {
                height : obj_area.h,
                left : obj_area.x,
                top : obj_area.y+ ( obj_area.h / 3 ),
                plitByGrapheme : true,
                fixedWidth : obj_area.w,
            } );

            while ( textbox.textLines.length > 1 ) {
                textbox.set( { width : textbox.getScaledWidth() + 1 } );

            }
            if ( textbox.width > obj_area.w ) {
                textbox.fontSize *= textbox.fixedWidth / ( textbox.width + 1 );
                textbox.width = textbox.fixedWidth;
            }
            textbox['pdr_property'] = {
                type : 'texts',
                price : text_price
            };

            textbox.toObject = ( function( toObject ) {
                return function() {

                    return fabric.util.object.extend( toObject.call( this ), {
                        pdr_property : {
                            type : 'texts',
                            price : text_price
                        }
                    } );
                };
            } )( textbox.toObject );

            canvas.add( textbox ).setActiveObject( textbox );
            canvas.renderAll();
            $( document ).trigger( 'pdr_calculate_price' );
            product_designer.mobile_menu_active();
        },
        calculate_text_price : function( price, string, count ) {
            var length = string.length;
            if ( length < 0 || price < 0 ) {
                return 0;
            }

            return ( ( price / count ) * length );
        },
        align_text : function( event ) {
            var align_position = $( this ).data( 'alignposition' );
            canvas.getActiveObject().textAlign = align_position;
            canvas.requestRenderAll( );
            product_designer.updateCanvasStatewithView();
        },
        position_text : function( event ) {
            var position_type = $( this ).data( 'alignposition' );
            var active = canvas.getActiveObject();
            $( this ).toggleClass( 'active' );
            var output = $( this ).hasClass( 'active' ) ? position_type : "normal";
            if ( ! active )
                return;
            active.setSelectionStyles( {
                fontSize : undefined,
                deltaY : undefined,
            } );
            if ( output == 'subscript' ) {
                active.setSubscript();
                canvas.requestRenderAll();
            } else if ( output == 'superscript' ) {
                active.setSuperscript();
                canvas.requestRenderAll();
            } else {
                active.setSelectionStyles( {
                    fontSize : undefined,
                    deltaY : undefined,
                } );
                $( '.pdr-position-text' ).removeClass( 'active' );
                canvas.requestRenderAll();
                product_designer.updateCanvasStatewithView();
            }
        },
        text_transform : function( event ) {
            var text_transform = $( this ).data( 'alignposition' );
            var active = canvas.getActiveObject();
            if ( ! active )
                return;
            var obj_text = active.text;

            if ( text_transform == 'upper' ) {
                active.text = obj_text.toUpperCase();

                canvas.requestRenderAll();

            } else if ( text_transform == 'lower' ) {
                active.text = obj_text.toLowerCase();
                canvas.requestRenderAll();
            }
            product_designer.updateCanvasStatewithView();
        },
        font_style : function( event ) {
            var font_style = $( this ).data( 'textstyle' );
            $( this ).toggleClass( 'active' );
            var output = $( this ).hasClass( 'active' ) ? font_style : "normal";
            var is_active = $( this ).hasClass( 'active' ) ? true : false;
            canvas.getActiveObject().set( "dirty", true );
            var get_count = canvas.getActiveObject().getSelectionStyles().length;
            if ( "bold" === font_style ) {

                if ( get_count > 0 ) {
                    canvas.getActiveObject().setSelectionStyles( { fontWeight : output } );
                } else {
                    canvas.getActiveObject().set( "fontWeight", output );
                }
            } else if ( "underline" === font_style ) {
                output = $( this ).hasClass( 'active' ) ? true : false;
                if ( get_count > 0 ) {
                    canvas.getActiveObject().setSelectionStyles( { underline : output } );
                } else {
                    canvas.getActiveObject().set( "underline", output );
                }
            } else if ( "italic" === font_style ) {
                if ( get_count > 0 ) {
                    canvas.getActiveObject().setSelectionStyles( { fontStyle : output } );
                } else {
                    canvas.getActiveObject().set( "fontStyle", output );
                }
            } else if ( "overline" === font_style ) {
                if ( get_count > 0 ) {
                    canvas.getActiveObject().setSelectionStyles( { overline : is_active } );
                } else {
                    canvas.getActiveObject().set( "overline", is_active );
                }
            } else if ( "linethrough" === font_style ) {
                if ( get_count > 0 ) {
                    canvas.getActiveObject().setSelectionStyles( { linethrough : is_active } );
                } else {
                    canvas.getActiveObject().set( "linethrough", is_active );
                }
            }
            canvas.renderAll( );
            product_designer.updateCanvasStatewithView();
        },
        calculateObjectPrice : function() {
            var product_price = 0,
                    template_price = 0,
                    objects = canvas.getObjects(),
                    key = $( '#pdr-switch-view-select' ).val();

            for ( var i in objects ) {
                if ( 'undefined' !== typeof objects[i].pdr_property ) {
                    if ( 'templates' === objects[i].pdr_property.type ) {
                        template_price = parseFloat( objects[i].pdr_property.price ) || 0;
                    } else {
                        product_price += parseFloat( objects[i].pdr_property.price ) || 0;
                    }
                }
            }

            price_data[key] = product_price + template_price;
            return price_data;
        },
        is_data_empty : function( obj ) {
            for ( var prop in obj ) {
                if ( obj.hasOwnProperty( prop ) ) {
                    return false;
                }
            }
            return JSON.stringify( obj ) === JSON.stringify( { } );
        },
        calculate_all_view_price : function() {
            var product_price = 0;
            if ( ! product_designer.is_data_empty( price_data ) ) {
                //if data present
                for ( var i in price_data ) {
                    if ( price_data[i] ) {
                        product_price += parseFloat( price_data[i] ) || 0;
                    }
                }
            }
            return product_price;
        },
        calculatePrice : function() {
            var current_view = $( '#pdr-switch-view-select' ).val(),
                    form_object = $( '.pdr-product-details-form' ).form( 'get values' ),
                    product_price = parseFloat( $( '#pdr_original_product_price' ).val() ) || 0,
                    total_price = 0;

            // Get the price from objects.
            product_designer.calculateObjectPrice();
            product_price += parseFloat( product_designer.calculate_all_view_price() ) || 0;
            product_price += parseFloat( product_designer.get_form_price( form_object ) );
            total_price = parseInt( $( '#quantity' ).val() ) * ( product_price );
            master_data[current_view] = JSON.stringify( canvas.toJSON() );

            $( '#pdr_total_price' ).val( total_price );
            $( '#pdr_product_price' ).val( total_price );
            $( '.pdr-product-total-price' ).html( product_designer.formatPrice( total_price ) );
        },
        formatPrice : function( price ) {
            return accounting.formatMoney( price, {
                symbol : pdr_frontend.currency_symbol,
                decimal : pdr_frontend.currency_decimal_sep,
                thousand : pdr_frontend.currency_thousand_sep,
                precision : pdr_frontend.currency_num_decimals,
                format : pdr_frontend.currency_format
            } );
        },
        fetch_object_count : function() {
            var count = Object.keys( rules ).length;
            var data = Object.keys( rules );
            var master_count = 0;
            for ( var c = 0; c < count; c ++ ) {
                if ( master_data[data[c]] ) {
                    master_count += JSON.parse( master_data[data[c]] ).objects.length;
                }
            }
            return master_count;
        },
        addToCart : function( e ) {
            e.preventDefault();

            if ( true !== $( '.pdr-product-details-form' ).form( 'validate form' ) ) {
                product_designer.pdr_alert( pdr_frontend.no_attributes_error );
                return false;
            }

            if ( 0 === product_designer.fetch_object_count() ) {
                product_designer.pdr_alert( 'Do some customization in order to add this product to cart' );
                return false;
            }

            var object_details = product_designer.prepare_object_details(),
                    data_action = 'session' === $( this ).data( 'action' ) ? 'pdr_add_to_session' : 'pdr_add_to_cart';

            window.onbeforeunload = null;
            product_designer.pdr_block( $( 'body' ) );

            $.ajax( {
                type : 'POST',
                url : pdr_frontend.ajaxurl,
                dataType : 'json',
                data : {
                    action : data_action,
                    pdr_security : pdr_frontend.add_to_cart_nonce,
                    product_id : $( '#pdr_product_id' ).val( ),
                    cart_item : $( "#pdr_cart_item" ).val( ),
                    quantity : $( '#quantity' ).val( ),
                    price : $( '#pdr_product_price' ).val( ),
                    product_base : $( '#pdr_product_base' ).val( ),
                    shapes : object_details.shape,
                    cliparts : object_details.clipart,
                    text : object_details.text,
                    bg : $( '#pdr-color-preview-selection' ).val( ),
                    image_url : JSON.stringify( image_data ),
                    canvas_data : JSON.stringify( master_data ),
                    file_name : pdr_frontend.file_name,
                    data : JSON.stringify( $( '.pdr-product-details-form' ).form( 'get values' ) ),
                    store_data_in : PDR_ClientDB.isSupported ? 'client-side' : 'server-side'
                },
                success : function( response ) {
                    if ( true === response.success ) {
                        $( '#pdr-file-name' ).val( response.data.canvas_filename );

                        if ( PDR_ClientDB.isSupported ) {
                            let clientdb = new PDR_ClientDB( 'pdr', 1, { name : 'cart', keyOptions : { keyPath : 'itemKey', autoIncrement : false } } );

                            $( document ).on( 'pdr_clientdb_ready_handler', function() {
                                clientdb
                                        .updateValue( 'cart', {
                                            itemKey : response.data.item_key,
                                            canvasFname : response.data.canvas_filename,
                                            imageUrlFname : response.data.image_url_filename,
                                            canvas : JSON.stringify( master_data ),
                                            imageUrl : JSON.stringify( image_data )
                                        } );
                            } );
                        }

                        if ( 'pdr_add_to_session' === data_action ) {
                            pdr_frontend.product_added_msg = pdr_frontend.product_added_to_session;
                            window.location.href = response.data.url;
                        } else {
                            $.alertable.alert( pdr_frontend.product_added_msg ).always( function() {
                                window.location.href = response.data.url;
                            } );
                        }
                    } else {
                        product_designer.pdr_alert( response.data.error );
                    }

                    product_designer.pdr_unblock( $( 'body' ) );
                }
            } );
        },
        get_form_price : function( obj ) {
            var price = 0;
            for ( var j in obj ) {
                if ( obj[j] ) {
                    var attr = $( '[name ="' + j + '"]' );

                    if ( typeof attr !== typeof undefined && attr !== false && attr ) {
                        var check_attr = attr;
                        attr = $( '[name ="' + j + '"]' ).attr( 'data-price' );
                        if ( check_attr.attr( 'type' ) == 'radio' ) {
                            attr = $( '[name ="' + j + '"][value="' + obj[j] + '"]' ).attr( 'data-price' );
                        }

                        if ( typeof attr !== typeof undefined && attr !== false && attr ) {
                            price += parseFloat( attr );
                        }
                    }
                }
            }
            return price;
        },
        set_form_data : function( ) {
            var form_data = pdr_frontend.form_data;
            if ( form_data != '' ) {

                $( '.pdr-product-details-form' ).form( 'set values', form_data );
                for ( var j in form_data ) {

                    if ( form_data[j] ) {
                        var attr = $( '[name ="' + j + '"]' );
                        if ( typeof attr !== typeof undefined && attr !== false && attr ) {
                            if ( attr.attr( 'type' ) == 'checkbox' ) {
                                attr.prop( 'checked', true );
                            }
                        }

                    }
                }
            }
        },
        save_design : function( event ) {
            event.preventDefault( );
            var get_current_color = $( '#pdr-color-preview-selection' ).val( );
            var dataUrl = product_designer.add_bg_to_canvas( get_current_color, true );

            var data = ( {
                action : 'pdr_save_design',
                product_id : $( '#pdr_product_id' ).val( ),
                product_base : $( '#pdr_product_base' ).val( ),
                canvas_data : JSON.stringify( master_data ),
                user_id : pdr_frontend.user_id,
                screenshot : dataUrl,
                pdr_security : pdr_frontend.save_design_nonce
            } );
            product_designer.pdr_block( $( 'body' ) );
            $.post( pdr_frontend.ajaxurl, data, function( res ) {
                if ( true === res.success ) {
                    product_designer.pdr_alert( pdr_frontend.design_saved_msg );
                } else {
                    product_designer.pdr_alert( res.data.error );
                }
                product_designer.pdr_unblock( $( 'body' ) );
            } );
        },
        delete_my_design : function( event ) {
            event.preventDefault( );
            var current_obj = $( this );
            var delete_id = $( this ).attr( 'data-delete-id' );
            var get_current_color = $( '#pdr-color-preview-selection' ).val( );
            var dataUrl = product_designer.add_bg_to_canvas( get_current_color, false );
            $( '#pdr-switch-view-select' ).trigger( 'change' );
            product_designer.pdr_block( $( 'body' ) );
            var data = ( {
                action : 'pdr_delete_design',
                design_id : $( this ).attr( 'data-delete-id' ),
                user_id : pdr_frontend.user_id,
                pdr_security : pdr_frontend.save_design_nonce
            } );
            $.post( pdr_frontend.ajaxurl, data, function( res ) {
                if ( true === res.success ) {
                    $( '#' + delete_id ).remove();
                    $( '.pdr-my-design' ).trigger( 'click' );
                } else {
                    product_designer.pdr_alert( res.data.error );
                }
                product_designer.pdr_unblock( $( 'body' ) );
            } );
        },
        fetch_product_bases : function( cat = 0 ) {
            product_designer.pdr_block( $( 'body' ) );
            var data = ( {
                action : 'pdr_get_category_products',
                tax_id : cat,
                pdr_security : pdr_frontend.popup_product_base
            } );
            $.post( pdr_frontend.ajaxurl, data, function( res ) {
                if ( true === res.success ) {
                    if ( res.data.products ) {
                        var product_base_details = res.data.products;
                        var html_data = product_designer.structure_preview_popup( product_base_details );
                        $( '.pdr-popup-product-details' ).html( html_data );
                    }
                } else {
                    product_designer.pdr_alert( res.data.error );
                }
                product_designer.pdr_unblock( $( 'body' ) );
            } );
        },
        fetch_cliparts : function( cat = 0 ) {
            product_designer.pdr_block( $( 'body' ) );
            var data = ( {
                action : 'pdr_get_category_cliparts',
                tax_id : cat,
                pdr_security : pdr_frontend.fetch_cliparts_nonce
            } );
            $.post( pdr_frontend.ajaxurl, data, function( res ) {
                if ( true === res.success ) {
                    if ( res.data.cliparts ) {
                        var cliparts_data = res.data.cliparts;
                        $( '.cliparts-container' ).html( cliparts_data );
                    }
                } else {
                    product_designer.pdr_alert( res.data.error );
                }
                product_designer.pdr_unblock( $( 'body' ) );
            } );
        },
        fetch_shapes : function( cat = 0 ) {
            product_designer.pdr_block( $( 'body' ) );
            var data = ( {
                action : 'pdr_get_category_shapes',
                tax_id : cat,
                pdr_security : pdr_frontend.fetch_shapes_nonce
            } );
            $.post( pdr_frontend.ajaxurl, data, function( res ) {
                if ( true === res.success ) {
                    if ( res.data.shapes ) {
                        var shapes_data = res.data.shapes;
                        $( '.pdr-shapes-container' ).html( shapes_data );
                    }
                } else {
                    product_designer.pdr_alert( res.data.error );
                }
                product_designer.pdr_unblock( $( 'body' ) );
            } );
        },
        fetch_templates : function( cat = 0 ) {
            product_designer.pdr_block( $( 'body' ) );
            var data = ( {
                action : 'pdr_get_category_templates',
                tax_id : cat,
                pdr_security : pdr_frontend.fetch_templates_nonce
            } );
            $.post( pdr_frontend.ajaxurl, data, function( res ) {
                if ( true === res.success ) {
                    if ( res.data.templates ) {
                        var templates_data = res.data.templates;
                        $( '.pdr-templates-container' ).html( templates_data );
                    }
                } else {
                    product_designer.pdr_alert( res.data.error );
                }
                product_designer.pdr_unblock( $( 'body' ) );
            } );
        },
        fetch_my_designs : function( ) {
            if ( is_edited === true ) {
                window.onbeforeunload = function() {
                    return "Are you sure want to leave current design? data you have entered may not be saved !";
                };
            }
            product_designer.pdr_block( $( 'body' ) );
            var data = ( {
                action : 'pdr_get_my_designs',
                user_id : pdr_frontend.user_id,
                pdr_security : pdr_frontend.my_designs_nonce
            } );
            $.post( pdr_frontend.ajaxurl, data, function( res ) {
                if ( true === res.success ) {
                    if ( res.data.designs ) {
                        var product_base_details = res.data.designs;
                        var html_data = product_designer.structure_preview_popup( product_base_details, true );
                        if ( html_data != '' ) {
                            $( '.pdr-popup-my-designs' ).html( html_data );
                        } else {
                            html_data = "<div class='ui error message pdr-popup-error-message'><div class='header'>" + pdr_frontend.no_design_msg + "</div></div>";
                            $( '.pdr-popup-my-designs' ).before( html_data );
                        }
                    }
                } else {
                    product_designer.pdr_alert( res.data.error );
                }
                product_designer.pdr_unblock( $( 'body' ) );
            } );
        },
        structure_preview_popup : function( data, is_design = false, go_back = false, base_id = '' ) {
            var html_data = '';
            for ( var key in data ) {
                var val = data[key];
                if ( is_design == true ) {
                    html_data += "\
<a id='" + key + "' href='" + val.url + "' class='card'>\n\
<div class='image'>\n\
<img src='" + val.preview + "'>\n\
</div>\n\
<div class='extra content'><div class='ui pdr-my-design-delete red button right floated' data-delete-id='" + key + "'>Delete</div></div>\n\
</a>";
                } else {
                    html_data += "\
<a data-baseid='" + val.pdr_id + "' href='" + val.url + "' class='pdr_popup_card card'>\n\
<div class='image'>\n\
<img src='" + val.preview + "'>\n\
</div>\n\
<div class='extra'>" + val.title + "</div>\n\
</a>";
                }
            }
            return html_data;
        },
        set_design_data : function( event ) {
            var mydesign = $( this );
            product_designer.pdr_block( $( 'body' ) );
            var data = ( {
                action : 'pdr_load_design',
                design_id : mydesign.attr( 'data-id' ),
                user_id : pdr_frontend.user_id,
                pdr_security : pdr_frontend.save_design_nonce
            } );
            $.post( pdr_frontend.ajaxurl, data, function( res ) {
                if ( true === res.success ) {
                    master_data = res.data.design.post_content;
                    master_data = JSON.parse( master_data );
                    product_designer.loadCurrentView();
                    $( '#pdr-switch-view-select' ).trigger( 'change' );
                } else {
                    product_designer.pdr_alert( res.data.error );
                }
                product_designer.pdr_unblock( $( 'body' ) );
            } );
        },
        prepare_object_details : function() {
            var object_details = {
                shape : 0,
                clipart : 0,
                text : 0,
                templates : 0
            };

            // Get the price from objects.
            var objects = canvas.getObjects();
            for ( var i in objects ) {
                if ( 'undefined' !== typeof objects[i].pdr_property ) {
                    switch ( objects[i].pdr_property.type ) {
                        case 'shape':
                            object_details.shape = object_details.shape + parseFloat( objects[i].pdr_property.price );
                            break;
                        case 'clipart':
                            object_details.clipart = object_details.clipart + parseFloat( objects[i].pdr_property.price );
                            break;
                        case 'text':
                            object_details.text = object_details.text + parseFloat( objects[i].pdr_property.price );
                            break;
                        case 'image_upload':
                            object_details.image_upload = object_details.image_upload + parseFloat( objects[i].pdr_property.price );
                            break;
                        case 'templates':
                            object_details.templates = parseFloat( objects[i].pdr_property.price );
                            break;
                    }

                }
            }
            return object_details;
        },
        pdr_block : function( id ) {
            if ( ! this.pdr_is_blocked( id ) ) {
                $( id ).addClass( 'processing' ).block( {
                    message : null,
                    overlayCSS : {
                        background : '#fff',
                        opacity : 0.7
                    }
                } );
            }
        },
        pdr_unblock : function( id ) {
            $( id ).removeClass( 'processing' ).unblock( );
        },
        pdr_is_blocked : function( id ) {
            return $( id ).is( '.processing' ) || $( id ).parents( '.processing' ).length;
        },
        pdr_alert : function( msg ) {
            $.alertable.alert( msg );
        },
        _callback_for_overlay : function( canvas_d, src ) {
            product_designer.updateCanvasState( canvas );
            product_designer.updateCanvasStatewithView( canvas );
            canvas_d.renderAll( );
        },
        set_overlay : function( src ) {
            const img = new Image( );
            var width, height;
            img.onload = function( ) {
                width = this.width * scalefactor;
                height = this.height * scalefactor;
                canvas.setDimensions( {
                    width : width,
                    height : height
                } );

                if ( pdr_frontend.image_segmentation == 'background' ) {
                    canvas.setBackgroundImage( this.src, product_designer._callback_for_overlay.bind( this, canvas, src ), {
                        scaleX : scalefactor,
                        scaleY : scalefactor
                    } );
                } else {
                    canvas.setOverlayImage( this.src, product_designer._callback_for_overlay.bind( this, canvas, src ), {
                        scaleX : scalefactor,
                        scaleY : scalefactor
                    } );
                }

                canvas.requestRenderAll( );
            };
            img.src = src;
        },
        set_area : function( x, y, w, h ) {
            if ( pdr_frontend.image_segmentation == 'background' ) {
                return;
            }
            area = new fabric.Rect( {
                left : x,
                top : y,
                width : w,
                height : h,
                selectable : false,
                fill : "transparent",
                hoverCursor : "pointer",
                opacity : 1,
                absolutePositioned : true,
                controlsAboveOverlay : true,
                hasBorders : true,
                borderDashArray : [ 3, 3 ],
                borderColor : 'red',
                strokeLineJoin : 'mitter',
                strokeMiterLimit : 3000,
                objectCaching : false,
                affectStroke : true,
                excludeFromExport : true
            } );

            area.toObject = ( function( toObject ) {
                return function( propertiesToInclude ) {
                    return fabric.util.object.extend( toObject.call( this, propertiesToInclude ), {
                        pdr_property : {
                            name : 'cliparea'
                        }
                    } );
                };
            } )( area.toObject );
            area.evented = false;
            canvas.clipPath = area;
            canvas.add( area );
        },
        ray_cast : function( p, vs ) { //ray casting algorithm used to find object 
            var inside = false;
            for ( var i = 0, j = vs.length - 1; i < vs.length; j = i ++ ) {
                var xi = vs[i].x, yi = vs[i].y;
                var xj = vs[j].x, yj = vs[j].y;
                var intersect = yi > p.y !== yj > p.y && p.x < ( ( xj - xi ) * ( p.y - yi ) ) / ( yj - yi ) + xi;
                if ( intersect )
                    inside = ! inside;
            }
            return inside;
        },
        ray_cast_obj : function( e ) {
            var cCoords = getCoords( area );
            var inBounds = inside( {
                x : e.target.left,
                y : e.target.top
            }, cCoords );
            if ( inBounds ) {
                e.target.setCoords( );
                e.target.saveState( );
            } else {
                e.target.left = e.target._stateProperties.left;
                e.target.top = e.target._stateProperties.top;
            }
        },
        alternate_clippath : function( e ) {
            var obj = e.target;
            obj.set( {
                top : this.data_test( obj.top, area.height, area.height + ( area.height - obj.getScaledHeight( ) ) ),
                left : this.data_test( obj.left, area.width, area.width + ( area.width - obj.getScaledWidth( ) ) )
            } );
            obj.setCoords( );
        },
        data_test : function( num, min, max ) {
            return Math.min( Math.max( num, min ), max );
        },
        canvas_event : function( ) {
            canvas.on( {
                'object:added' : this.object_added,
                'object:modified' : this.object_modified,
                'object:removed' : this.object_removed,
                'selection:created' : this.object_selection,
                'selection:updated' : this.object_selection,
                'selection:cleared' : this.object_selection_clear,
                'mouse:over' : this.add_border_on_hover,
                'mouse:out' : this.remove_border_on_hover,
                'mouse:wheel' : this.zoom
            } );

            if ( pdr_frontend.is_mobile ) {
                //var canvas = new fabric.CanvasWithViewport('canvas');
                //console.log(canvas.getZoom());
                canvas.on( {
                    'touch:gesture' : function( e ) {
                        return false;

                        if ( e.e.touches && e.e.touches.length == 2 ) {
                            //console.log("Yes touch Gesture supported");
                            pausePanning = true;
                            var point = new fabric.Point( e.self.x, e.self.y );
                            if ( e.self.state == "start" ) {
                                zoomStartScale = canvas.getZoom();
                            }
                            var delta = zoomStartScale * e.self.scale;
                            console.log( delta );
                            console.log( 'scale ratio:' + e.self.scale );
                            canvas.zoomToPoint( point, delta );
                            pausePanning = false;
                        }
                    },
                    'object:selected' : function() {
                        pausePanning = true;
                    },
                    'touch:drag' : function( e ) {
                        return false;
                        console.log( "Yes touch drag supported" );
                        if ( pausePanning == false && undefined != e.self.x && undefined != e.self.x ) {
                            currentX = e.self.x;
                            currentY = e.self.y;
                            xChange = currentX - lastX;
                            yChange = currentY - lastY;
                            console.log( Math.abs( currentX - lastX ) );
                            console.log( Math.abs( currentY - lastY ) );

                            if ( ( Math.abs( currentX - lastX ) <= 50 ) && ( Math.abs( currentY - lastY ) <= 50 ) ) {
                                var delta = new fabric.Point( xChange, yChange );
                                canvas.relativePan( delta );
                            }
                            lastX = e.self.x;
                            lastY = e.self.y;
                        }
                    }
                } );
            }
        },
        zoom : function( opt ) {
            var delta = opt.e.deltaY;
            var zoom = canvas.getZoom();
            var min_zoom = $( '#pdr-zoom-information' ).attr( 'data-min-zoom' );
            var max_zoom = $( '#pdr-zoom-information' ).attr( 'data-max-zoom' );
            zoom *= 0.999 ** delta;
            // console.log( zoom ) ;
            if ( zoom > max_zoom )
                zoom = max_zoom;
            if ( zoom < min_zoom )
                zoom = min_zoom;
            var current_view = $( '#pdr-switch-view-select' ).val( );
            canvas.zoomToPoint( new fabric.Point( canvas.width / 2, canvas.height / 2 ), zoom );
            opt.e.preventDefault();
            opt.e.stopPropagation();
        },
        onresize : function( e ) {
            var width = e.target.outerWidth;
            var height = e.target.outerHeight;
            if ( width <= 500 ) {
                var current_view = $( '#pdr-switch-view-select' ).val( );
                product_designer.reinitialize_viewport( width, height, current_view );
            }
        },
        add_border_on_hover : function( e ) {
            if ( pdr_frontend.image_segmentation == 'background' ) {
                return;
            }
            area.strokeDashArray = [ 5, 5 ];
            area.stroke = "black";
            area.strokeWidth = 5;
            canvas.add( area );
            canvas.renderAll( );
        },
        remove_border_on_hover : function( e ) {
            if ( pdr_frontend.image_segmentation == 'background' ) {
                return;
            }
            area.strokeDashArray = [ 5, 5 ];
            area.stroke = "transparent";
            area.strokeWidth = 5;
            canvas.add( area );
            canvas.renderAll( );
        },
        viewPort : function() {
            var viewPortWidth;
            var viewPortHeight;

            // the more standards compliant browsers (mozilla/netscape/opera/IE7) use window.innerWidth and window.innerHeight
            if ( typeof window.innerWidth != 'undefined' ) {
                viewPortWidth = window.innerWidth,
                        viewPortHeight = window.innerHeight
            }

            // IE6 in standards compliant mode (i.e. with a valid doctype as the first line in the document)
            else if ( typeof document.documentElement != 'undefined'
                    && typeof document.documentElement.clientWidth !=
                    'undefined' && document.documentElement.clientWidth != 0 ) {
                viewPortWidth = document.documentElement.clientWidth,
                        viewPortHeight = document.documentElement.clientHeight
            }

            // older versions of IE
            else {
                viewPortWidth = document.getElementsByTagName( 'body' )[0].clientWidth,
                        viewPortHeight = document.getElementsByTagName( 'body' )[0].clientHeight
            }
            return { "width" : viewPortWidth, "height" : viewPortHeight };
        },

        object_selection : function( e ) {
            var selectedObject = this.getActiveObject();
            $( '.pdr-object-tools' ).show( );
            $( '.pdr-switch-section' ).hide( );
            if ( selectedObject && selectedObject.type === 'i-text' || selectedObject && selectedObject.type === 'textbox' ) {
                $( '.pdr-text-tools' ).show( );
            } else if ( selectedObject && selectedObject.type !== 'i-text' ) {
                $( '.pdr-text-tools' ).hide( );
            }
            if ( selectedObject && selectedObject.pdr_property.type === 'shape' ) {
                $( '.pdr-shape-tools' ).show();
            }

            if ( selectedObject && 'image' === selectedObject.type ) {
                $( '#pdr-object-filters' ).show();

                if ( 'object' === typeof selectedObject.filters ) {
                    var active_filter = 'object' === typeof selectedObject.filters[0] ? selectedObject.filters[0].type : '',
                            active_brightness_range_value = 0,
                            active_contrast_range_value = 0,
                            active_saturation_range_value = 0;

                    $( '.pdr-object-filters' ).find( 'img' ).each( function() {
                        $( this ).removeClass( 'pdr-object-filter-selected' );
                    } );

                    for ( var i = 0; i <= 3; i ++ ) {
                        if ( 'object' !== typeof selectedObject.filters[i] ) {
                            continue;
                        }

                        if ( 'object' === typeof selectedObject.filters[i].brightness ) {
                            active_brightness_range_value = parseFloat( selectedObject.filters[i].brightness );
                        }

                        if ( 'object' === typeof selectedObject.filters[i].contrast ) {
                            active_contrast_range_value = parseFloat( selectedObject.filters[i].contrast );
                        }

                        if ( 'object' === typeof selectedObject.filters[i].saturation ) {
                            active_saturation_range_value = parseFloat( selectedObject.filters[i].saturation );
                        }
                    }

                    if ( 'Convolute' === active_filter ) {
                        if ( '0,-1,0,-1,5,-1,0,-1,0' == selectedObject.filters[0].matrix ) {
                            active_filter = 'Sharpen';
                        } else {
                            active_filter = 'Emboss';
                        }
                    }

                    if ( $( '.pdr-object-filter-' + active_filter ).length ) {
                        $( '.pdr-object-filter-' + active_filter ).addClass( 'pdr-object-filter-selected' ).click();
                    } else {
                        $( '.pdr-object-filter-Original' ).addClass( 'pdr-object-filter-selected' ).click();
                    }

                    $( '.pdr-object-brightness-range' ).val( active_brightness_range_value ).change();
                    $( '.pdr-object-contrast-range' ).val( active_contrast_range_value ).change();
                    $( '.pdr-object-saturation-range' ).val( active_saturation_range_value ).change();
                }
            } else {
                $( '#pdr-object-filters' ).hide();
            }

            $( document ).trigger( 'pdr_calculate_price' );
        },
        object_selection_clear : function( e ) {
            $( '.pdr-text-tools' ).hide();
            $( '.pdr-object-tools' ).hide();
            $( '.pdr-shape-tools' ).hide();
            $( '#pdr-object-filters' ).hide();
            $( '.pdr-switch-section' ).show( );
            pausePanning = false;
            $( document ).trigger( 'pdr_calculate_price' );
        },
        object_added : function( e ) {
            var active = canvas.getActiveObject();
            if ( e.target.hasOwnProperty( 'pdr_property' ) ) {
                if ( e.target.pdr_property.name !== 'cliparea' ) {
                    history_data.push( JSON.stringify( canvas.toJSON( ) ) );
                    var current_view = $( '#pdr-switch-view-select' ).val();
                    image_data[current_view] = product_designer.add_bg_to_canvas( '', true );
                }
            }
            canvas.setActiveObject( active );
            $( document ).trigger( 'pdr_calculate_price' );

            if ( e.target.hasOwnProperty( 'pdr_property' ) ) {
                if ( e.target.pdr_property.name !== 'cliparea' ) {
                    product_designer.updateCanvasState();
                    product_designer.updateCanvasStatewithView();
                }
            }

            if ( active && 'image' === active.type ) {
                $( '#pdr-object-filters' ).show();
            } else {
                $( '#pdr-object-filters' ).hide();
            }
        },
        object_modified : function( e ) {
            var active = canvas.getActiveObject();
            if ( e.target.hasOwnProperty( 'pdr_property' ) ) {
                if ( e.target.pdr_property.name !== 'cliparea' ) {
                    history_data.push( JSON.stringify( canvas ) );
                    var current_view = $( '#pdr-switch-view-select' ).val();
                    image_data[current_view] = product_designer.add_bg_to_canvas( '', true );
                }
            }
            canvas.setActiveObject( active );
            $( document ).trigger( 'pdr_calculate_price' );

            if ( e.target.hasOwnProperty( 'pdr_property' ) ) {
                if ( e.target.pdr_property.name !== 'cliparea' ) {
                    product_designer.updateCanvasState();
                    product_designer.updateCanvasStatewithView();
                }
            }

            if ( active && 'image' === active.type ) {
                $( '#pdr-object-filters' ).show();
            } else {
                $( '#pdr-object-filters' ).hide();
            }
        },
        object_removed : function( e ) {
            var active = canvas.getActiveObject();
            if ( e.target.hasOwnProperty( 'pdr_property' ) ) {
                if ( e.target.pdr_property.name !== 'cliparea' ) {
                    history_data.push( JSON.stringify( canvas.toJSON( ) ) );
                    var current_view = $( '#pdr-switch-view-select' ).val();
                    image_data[current_view] = product_designer.add_bg_to_canvas( '', true );
                }
            }
            canvas.setActiveObject( active );

            if ( active && 'image' === active.type ) {
                $( '#pdr-object-filters' ).show();
            } else {
                $( '#pdr-object-filters' ).hide();
            }

            $( document ).trigger( 'pdr_calculate_price' );
        },
        zoom_slider : function( ) {
            const $valueSpan = $( '.pdr-zoom-value' );
            const $value = $( '#pdr-zoom-range' );
            $valueSpan.html( $value.val( ) );
            $value.on( 'input change', ( ) => {
                $valueSpan.html( $value.val( ) );
            } );
        },
        zoom_in : function( ) {
            canvas.zoomToPoint( new fabric.Point( canvas.width / 2, canvas.height / 2 ), canvas.getZoom( ) * 1.01 );
            canvasScale = canvasScale * SCALE_FACTOR;
            var objects = canvas.getObjects( );
            for ( var i in objects ) {
                var scaleX = objects[i].scaleX;
                var scaleY = objects[i].scaleY;
                var left = objects[i].left;
                var top = objects[i].top;
                var tempScaleX = scaleX * SCALE_FACTOR;
                var tempScaleY = scaleY * SCALE_FACTOR;
                var tempLeft = left * SCALE_FACTOR;
                var tempTop = top * SCALE_FACTOR;
                objects[i].scaleX = tempScaleX;
                objects[i].scaleY = tempScaleY;
                objects[i].left = tempLeft;
                objects[i].top = tempTop;
                objects[i].setCoords( );
            }
            canvas.renderAll( );
        },
        zoom_out : function( ) {
            canvas.zoomToPoint( new fabric.Point( canvas.width / 2, canvas.height / 2 ), canvas.getZoom( ) / 1.01 );
            canvasScale = canvasScale / SCALE_FACTOR;
            var objects = canvas.getObjects( );
            for ( var i in objects ) {
                var scaleX = objects[i].scaleX;
                var scaleY = objects[i].scaleY;
                var left = objects[i].left;
                var top = objects[i].top;
                var tempScaleX = scaleX * ( 1 / SCALE_FACTOR );
                var tempScaleY = scaleY * ( 1 / SCALE_FACTOR );
                var tempLeft = left * ( 1 / SCALE_FACTOR );
                var tempTop = top * ( 1 / SCALE_FACTOR );
                objects[i].scaleX = tempScaleX;
                objects[i].scaleY = tempScaleY;
                objects[i].left = tempLeft;
                objects[i].top = tempTop;
                objects[i].setCoords( );
            }
            canvas.renderAll( );
        },
        export_file : function( e ) {
            $( '#pdr-switch-view-select' ).trigger( 'change' );
            download_txt_file( JSON.stringify( master_data ), product_designer.pdr_file_name() + '.txt', 'text/plain' );
        },
        exportTemplate : function( e ) {
            var dataUrl,
                    dataToExport = { },
                    bgImage = canvas.backgroundImage,
                    olImage = canvas.overlayImage;

            // Apply
            if ( 'background' === pdr_frontend.image_segmentation ) {
                canvas.backgroundImage = null;
            } else {
                canvas.overlayImage = null;
            }

            canvas.renderAll();
            dataUrl = canvas.toDataURL();
            dataUrl = product_designer.convert_dpi( dataUrl );

            dataToExport.canvasObject = master_data;
            dataToExport.dataUrl = dataUrl;

            // Restore to original state
            if ( 'background' === pdr_frontend.image_segmentation ) {
                canvas.backgroundImage = bgImage;
            } else {
                canvas.overlayImage = olImage;
            }

            canvas.renderAll();
            download_txt_file( JSON.stringify( dataToExport ), 'canvas-object.txt', 'text/plain' );
        },
        canvas2png : function( e ) {
            canvas.isDrawingMode = false;
            var dataimage = fillCanvasBackgroundWithColor( canvas, 'green' );
            if ( ! window.localStorage ) {
                product_designer.pdr_alert( "This function is not supported by your browser." );
                return;
            }
            // to PNG
            window.open( dataimage );
        },
        original_area : function( view ) {
            var x = rules[view].x;
            var y = rules[view].y;
            var w = rules[view].w;
            var h = rules[view].h;
            var factor = rules[view].factor;
            var original_factor = this.original_area_factor( x, y, w, h, factor );
            return original_factor;
        },
        pagination_view : function( ) {
            var pagination_dropdown = $( '#pdr-switch-view-select' );
            for ( var key in rules ) {
                pagination_dropdown.append( $( '<option></option>' ).val( key ).html( rules[key].title ) );
            }

            //load default general
            this.set_overlay( rules['general'].img_url ); //set overlay
            var getviewport = product_designer.viewPort();

            var ww = pdr_frontend.is_mobile ? getviewport.width : 530;
            var wh = pdr_frontend.is_mobile ? getviewport.height : 630;
            product_designer.reinitialize_viewport( ww, wh, 'general' );
            var original_factor = this.original_area( 'general' );
            this.set_area( original_factor.x, original_factor.y, original_factor.w, original_factor.h );
        },
        original_area_factor : function( x, y, w, h, factor ) {
            return {
                x : x * ( 1 / factor ),
                y : y * ( 1 / factor ),
                w : w * ( 1 / factor ),
                h : h * ( 1 / factor )
            };
        },
        loadCurrentView : function() {
            var key = $( '#pdr-switch-view-select' ).val( ),
                    json_data = master_data[key];

            product_designer.load_google_font(); //set saved google fonts
            product_designer.canvas_load_from_json( json_data );

            //get bg
            if ( 'undefined' !== typeof master_data.pdr_extra ) {
                $( '.canvas-container' ).css( 'background', master_data['pdr_extra'].bg );
                $( '#pdr-color-preview-selection' ).val( master_data['pdr_extra'].bg );
                $( '#pdr-color-preview-selection' ).attr( 'data-price', master_data['pdr_extra'].price );
                $( '.pdr-clear-bg' ).show();

                image_data[key] = product_designer.add_bg_to_canvas( '', true );
            }
        },
        canvas_load_from_json : function( json_data, key = '' ) {
            canvas.loadFromJSON( json_data, function( ) {
                canvas.renderAll( );

                if ( '' !== key ) {
                    image_data[key] = product_designer.add_bg_to_canvas( '', true );
                }

                $( document ).trigger( 'pdr_calculate_price' );
            }, function( o, object ) {
                if ( typeof object.pdr_property !== 'undefined' ) {
                    object.toObject = ( function( toObject ) {
                        return function( propertiesToInclude ) {
                            return fabric.util.object.extend( toObject.call( this, propertiesToInclude ), {
                                pdr_property : object.pdr_property
                            } );
                        };
                    } )( object.toObject );
                }
            } );
        },
        reinitialize_viewport : function( w, h, key, vsrc = '' ) {
            var ww = w;
            var wh = h;

            var newimage = new Image();
            canvas.setZoom( 1 );

            newimage.onload = function() {
                if ( newimage.width > ww ) {
                    var zoom = Math.min( ww / newimage.width, wh / newimage.height );
                    var newcanvaswidth = newimage.width * zoom;
                    var newcanvasheight = newimage.height * zoom;
                    canvas.setWidth( newcanvaswidth );
                    canvas.setHeight( newcanvasheight );

                    canvas.setZoom( zoom );
                    $( '.canvas-container' ).width( newcanvaswidth );
                    $( '.canvas-container' ).height( newcanvasheight );
                    $( '#pdr-zoom-information' ).attr( 'data-min-zoom', zoom );
                    $( '#pdr-zoom-information' ).attr( 'data-max-zoom', zoom * 50 );
                } else {
                    $( '#pdr-zoom-information' ).attr( 'data-min-zoom', 1 );
                    $( '#pdr-zoom-information' ).attr( 'data-max-zoom', 1 * 50 );
                    canvas.setWidth( newimage.width );
                    canvas.setHeight( newimage.height );
                    $( '.canvas-container' ).width( newimage.width );
                    $( '.canvas-container' ).height( newimage.height );
                    canvas.renderAll();
                }
                canvas.calcOffset();
            };
            newimage.src = vsrc !== '' ? vsrc : rules[key].img_url;
        },
        switch_view : function( e ) {
            product_designer.pdr_block( $( 'body' ) );
            var key = $( this ).val( );
            var previous_view_key = $( '#pdr-present-view' ).val( );
            image_data[previous_view_key] = product_designer.add_bg_to_canvas( '', true, true );
            product_designer.calculateObjectPrice();

            canvas.setViewportTransform( [ 1, 0, 0, 1, 0, 0 ] );
            master_data[previous_view_key] = JSON.stringify( canvas.toJSON( ) ); //default view data goes here when switch view(data will be general)
            canvas.removeArea( 'area' );

            var x = rules[key].x;
            var y = rules[key].y;
            var w = rules[key].w;
            var h = rules[key].h;
            var factor = rules[key].factor;
            var original_factor = product_designer.original_area_factor( x, y, w, h, factor );

            if ( ! master_data[key] ) {
                canvas.clear( );
                product_designer.set_overlay( rules[key].img_url ); //set overlay
            } else {
                var json_data = master_data[key];
                product_designer.canvas_load_from_json( json_data );
            }

            $( '#pdr-present-view' ).val( key );
            product_designer.set_area( original_factor.x, original_factor.y, original_factor.w, original_factor.h );
            canvas.renderAll( );
            product_designer.pdr_unblock( $( 'body' ) );
            product_designer.reinitialize_viewport( ww, wh, key );
            return false;
        },
        sync_canvas : function( ) {
            var previous_view_key = $( '#pdr-present-view' ).val( );
            master_data[previous_view_key] = JSON.stringify( canvas );
        },
        set_default_font_family : function( event ) {
            var fontfamily = $( this ).attr( 'data-family' );
            canvas.getActiveObject( ).set( "fontFamily", fontfamily );
            canvas.requestRenderAll( );
        },
        set_font_size : function( event ) {
            var fontsize = $( '#pdr-number-field' ).val( );
            var get_count = canvas.getActiveObject( ).getSelectionStyles().length;
            if ( canvas.getActiveObject( ) ) {
                if ( get_count > 0 ) {
                    canvas.getActiveObject( ).setSelectionStyles( { fontSize : fontsize } );
                } else {
                    canvas.getActiveObject( ).set( "fontSize", fontsize );
                }

            }
            canvas.requestRenderAll( );
            product_designer.updateCanvasStatewithView();
        },
        set_font_weight : function( event ) {
            var fontweight = $( this ).attr( "data-fweight" );
            canvas.getActiveObject( ).set( "fontWeight", fontweight );
            canvas.requestRenderAll( );
            product_designer.updateCanvasStatewithView();
        },
        set_font_color : function( event ) {
            var fontcolor = $( this ).val( );
            var get_count = canvas.getActiveObject( ).getSelectionStyles().length;

            if ( get_count > 0 ) {
                canvas.getActiveObject( ).setSelectionStyles( { fill : fontcolor } );
            } else {
                canvas.getActiveObject( ).set( "fill", fontcolor );
            }
            canvas.requestRenderAll( );
            product_designer.updateCanvasStatewithView();
        },
        set_bg_color : function( event ) {
            var bgcolor = $( this ).val();
            var get_count = canvas.getActiveObject( ).getSelectionStyles().length;
            if ( get_count > 0 ) {
                canvas.getActiveObject().setSelectionStyles( { textBackgroundColor : bgcolor } );
            } else {
                canvas.getActiveObject().set( "textBackgroundColor", bgcolor );
            }
            canvas.requestRenderAll();
            product_designer.updateCanvasStatewithView();
        },
        copy : function( event ) {
            product_designer.copy_object();
        },
        paste : function( event ) {
            product_designer.paste_object();
            product_designer.updateCanvasStatewithView();
        },
        copy_object : function() {
            canvas.getActiveObject().clone( function( cloned ) {
                _clipboard = cloned;
                $( '.pdr-paste-object-tools' ).show();
                product_designer.pdr_alert( "Selected Object copied successfully" );
            } );
        },
        paste_object : function() {
            _clipboard.clone( function( clonedObj ) {
                canvas.discardActiveObject();
                clonedObj.set( {
                    evented : true,
                } );

                product_designer.fit_obj( clonedObj );
                if ( clonedObj.type === 'activeSelection' ) {
                    // active selection needs a reference to the canvas.
                    clonedObj.canvas = canvas;
                    clonedObj.forEachObject( function( obj ) {
                        canvas.add( obj );
                    } );
                    // this should solve the unselectability
                    clonedObj.setCoords();
                } else {
                    canvas.add( clonedObj );
                }
                _clipboard.top += 10;
                _clipboard.left += 10;
                canvas.setActiveObject( clonedObj );
                canvas.requestRenderAll();
                product_designer.updateCanvasStatewithView();
            } );
        },
        set_shape_fill_color : function( event ) {
            var fontcolor = $( this ).val( );
            canvas.getActiveObject( ).set( "fill", fontcolor );
            canvas.requestRenderAll( );
            product_designer.updateCanvasStatewithView();
        },
        set_shape_stroke_color : function( event ) {
            var fontcolor = $( this ).val( );
            canvas.getActiveObject( ).set( "stroke", fontcolor );
            canvas.requestRenderAll( );
            product_designer.updateCanvasStatewithView();
        },
        set_default_fonts : function( fontfamily ) {
            if ( canvas.getActiveObject( ) ) {
                var getcount = canvas.getActiveObject().getSelectionStyles().length;

                if ( getcount > 0 ) {
                    canvas.getActiveObject( ).setSelectionStyles( { fontFamily : fontfamily } );
                } else {
                    canvas.getActiveObject( ).set( "fontFamily", fontfamily );
                }
                canvas.requestRenderAll( );
                product_designer.updateCanvasStatewithView();
            }
        },
        set_google_fonts : function( fontfamily ) {
            WebFont.load( {
                google : {
                    families : [ fontfamily ]
                },
                timeout : 5000,
                fontactive : function( familyName, fvd ) { //This is called once font has been rendered in browser
                    google_font_data.push( fontfamily );
                    product_designer.is_font_loaded( familyName );
                    master_data['pdr_google_fonts'] = JSON.stringify( google_font_data );
                },
            } );
        },
        set_custom_fonts: function(fontfamily) {
             WebFont.load( {
                custom : {
                    families : [ fontfamily ]
                },
                timeout : 5000,
                fontactive : function( familyName, fvd ) { //This is called once font has been rendered in browser
                    google_font_data.push( fontfamily );
                    product_designer.is_font_loaded( familyName );
                    master_data['pdr_google_fonts'] = JSON.stringify( google_font_data );
                },
            } );
        },
        get_text_objects : function() {
            canvas.getObjects().forEach( object => {
                if ( object.isType( 'i-text' ) ) { // object is a text
                    var fontfamily = object.fontFamily;
                    WebFont.load( {
                        google : {
                            families : [ fontfamily ]
                        },
                        timeout : 6000,
                        fontactive : function( font_family ) {
                            FontFaceOnload( font_family, {
                                success : function( ) {
                                    object.set( "fontFamily", object.fontFamily );
                                }, error : function() {
                                    alert( "Unable to load font" );
                                }
                            } );
                        },
                        fontinactive : function() {
                            product_designer.get_text_objects();
                        },
                    } );
                    canvas.requestRenderAll();
                    product_designer.updateCanvasStatewithView();
                }
            } );
        },

        mobile_menu_active : function( sectionId = 'canvas' ) {

            if ( pdr_frontend.is_mobile ) {
                $( '.active-link' ).removeClass( 'active-link' );
                document.querySelector( '.nav__menu a' ).classList.remove( 'active-link' );
                document.querySelector( '.nav__menu a[href*=' + sectionId + ']' ).classList.add( 'active-link' );

                $( 'section.pdr-container' ).addClass( 'hidden' );
                $( 'section#' + sectionId ).removeClass( 'hidden' );
        }
        },

        load_google_font : function() {
            if ( typeof master_data.pdr_google_fonts !== 'undefined' ) {
                var data = master_data['pdr_google_fonts'];
                var parse_data = JSON.parse( data );
                var length = parse_data.length;
                for ( let i = 0; i < length; i ++ ) {
                    var x = parse_data[i];
                    google_font_data.push( x );
                }
            }
        },
        is_font_loaded : function( font ) {
            product_designer.pdr_block( $( 'body' ) );
            FontFaceOnload( font, {
                success : function( ) {
                    var getcount = canvas.getActiveObject().getSelectionStyles().length;
                    if ( getcount > 0 ) {
                        canvas.getActiveObject( ).setSelectionStyles( { fontFamily : font } );
                    } else {
                        canvas.getActiveObject( ).set( "fontFamily", font );
                    }
                    canvas.requestRenderAll( );
                    product_designer.pdr_unblock( $( 'body' ) );
                },
                error : function( ) {
                    product_designer.pdr_unblock( $( 'body' ) );
                },
                timeout : 10000,
            } );
        },
        download_image : function( e ) {
            $( 'body' ).dimmer( 'show' );
            var pagewidth = $( '#pdr-print-data-width' ).val( );
            var pageheight = $( '#pdr-print-data-height' ).val( );
            var get_measure = $( '.pdr-print-paper-measure' ).dropdown( 'get value' );
            var data_url = product_designer._print( );
            product_designer.hermite_resize( 'download', data_url, pagewidth, pageheight, get_measure, false );
        },
        download_as_pdf : function( e ) {
            $( 'body' ).dimmer( 'show' );
            var pagewidth = $( '#pdr-print-data-width' ).val( );
            var pageheight = $( '#pdr-print-data-height' ).val( );
            var get_measure = $( '.pdr-print-paper-measure' ).dropdown( 'get value' );
            var data_url = product_designer._print( );
            product_designer.hermite_resize( 'data', data_url, pagewidth, pageheight, get_measure, false, true );
        },
        _pdf_pdf : function( dataurl, width, height, measure, w, h ) {
            var unit = ( measure === 'inch' ) ? 'in' : 'mm';
            //pixel to inch or mm calculation
            var pdf = new jspdf.jsPDF( 'p', unit, [ height, width ] );
            // width to the user select prefer unit
            if ( unit == 'in' ) {
                //if it is inch convert it to pixel first
                w = w * 0.0104166667;
                h = h * 0.0104166667;
            } else if ( unit == 'mm' ) {
                // if it is mm convert it to pixel first
                w = w * 0.2645833333;
                h = h * 0.2645833333;
            }

            height = h / w * width;
            pdf.addImage( dataurl, 'PNG', 0, 0, width, height, '', 'NONE' );
            pdf.save( product_designer.pdr_file_name() + '.pdf' );
        },
        export_image : function( e ) {
            var get_current_color = $( '#pdr-color-preview-selection' ).val( );
            var dataUrl = product_designer.add_bg_to_canvas( get_current_color, false, true );
            var pngHeader = 'data:image/png;base64,';
            var data = dataUrl.replace( pngHeader, '' );
            var binary_string = window.atob( data );
            var len = binary_string.length;
            var bytes = new Uint8Array( len );
            for ( var i = 0; i < len; i ++ ) {
                bytes[i] = binary_string.charCodeAt( i );
            }
            // dots per inch
            var dpi = 600;
            // pixels per metre
            var ppm = Math.round( dpi / 2.54 * 100 );
            bytes = rewrite_pHYs_chunk( bytes, ppm, ppm );
            // re-encode PNG (btoa method)
            var b64encoded = btoa( handleCodePoints( bytes ) );
            print_data( pngHeader + b64encoded, 'canvas' );
        },
        convert_dpi : function( data_url ) {
            var pngHeader = 'data:image/png;base64,';
            var data = data_url.replace( pngHeader, '' );
            var binary_string = window.atob( data );
            var len = binary_string.length;
            var bytes = new Uint8Array( len );
            for ( var i = 0; i < len; i ++ ) {
                bytes[i] = binary_string.charCodeAt( i );
            }

            // pixels per metre
            var ppm = Math.round( dpi / 2.54 * 100 );
            bytes = rewrite_pHYs_chunk( bytes, ppm, ppm );
            // re-encode PNG (btoa method)
            var b64encoded = btoa( handleCodePoints( bytes ) );
            data_url = pngHeader + b64encoded;
            return data_url;
        },
        datauritoblob : function( dataURI ) {
            var bytestring = atob( dataURI.split( ',' )[1] );
            var mimestring = dataURI.split( ',' )[0].split( ':' )[1].split( ';' )[0];
            var arraybuffer = new ArrayBuffer( bytestring.length );
            var initarray = new Uint8Array( arraybuffer );

            for ( var i = 0; i < bytestring.length; i ++ ) {
                initarray[i] = bytestring.charCodeAt( i );
            }
            var blob = new Blob( [ arraybuffer ], { type : mimestring } );
            return blob;
        },
        watermark : function( dataurl, watermark_text ) {
            var canvas_temp = document.createElement( 'canvas' );
            var ctx_temp = canvas_temp.getContext( '2d' );
            var tempimg = new Image();
            tempimg.src = dataurl;
            tempimg.onload = function() {
                var canvas_temp_width = tempimg.width;
                var canvas_temp_height = tempimg.height;
                canvas_temp.width = canvas_temp_width;
                canvas_temp.height = canvas_temp_height;
                ctx_temp.drawImage( tempimg, 0, 0 );
                ctx_temp.font = "24px verdana";
                var textWidth = ctx_temp.measureText( watermark_text ).width;
                ctx_temp.globalAlpha = .50;
                ctx_temp.fillStyle = 'white'
                ctx_temp.fillText( watermark_text, canvas_temp_width - textWidth - 10, canvas_temp_height - 20 );
                ctx_temp.fillStyle = 'black'
                ctx_temp.fillText( watermark_text, canvas_temp_width - textWidth - 10 + 2, canvas_temp_height - 20 + 2 );
                console.log( canvas_temp.toDataURL() );
            };
        },
        add_watermark : function( dataURI, watermark_text, print, w, pdf, mw, mh, measure ) {
            var text = watermark.text;
            var watermark_url;
            var fontsize = pdr_frontend.watermark_fontsize + "px verdana";
            var fontcolor = pdr_frontend.watermark_fontcolor;
            watermark( [ dataURI ] )
                    .dataUrl( text.center( watermark_text, fontsize, fontcolor, 0.5 ) )
                    .then( function( url ) {
                        product_designer._pdr_printing( print, url, w, pdf, mw, mh, measure );
                    } );
        },
        apply_qr : function( e ) {
            var qrtext = $( '#pdr-qr-code-text' ).val();
            if ( '' === qrtext ) {
                product_designer.pdr_alert( pdr_frontend.qr_input_text_error_msg );
                return false;
            }
            product_designer.add_qr( qrtext );
            canvas.requestRenderAll();
        },
        add_qr : function( text ) {
            var qrcode = new QRCode( document.createElement( "div" ), {
                text : text,
                callback : ( value ) => {
                    var qrImg = new Image();
                    qrImg.onload = function( img ) {
                        var qrcanvas = new fabric.Image( qrImg );
                        product_designer.fit_obj( qrcanvas );
                        qrcanvas['pdr_property'] = {
                            type : 'qr',
                            price : 0
                        };
                        canvas.add( qrcanvas ).setActiveObject( qrcanvas ).renderAll( );
                    };
                    qrImg.src = value._elImage.src;
                }
            } );
        },
        _pdr_printing : function( print, dataURI, w, pdf, mw, mh, measure, ratio = '', h = '' ) {
            if ( print ) {
                printJS( { printable : dataURI, type : 'image', base64 : true, maxWidth : w, maxHeight : ratio != '' ? ratio * w : '', showModal : true, modalMessage : "Retrieving Print Data" } );
            } else {
                //download call
                if ( ! pdf ) {
                    print_data( dataURI, product_designer.pdr_file_name() + '.png' );
                } else {
                    //if pdf is true then perform pdf creation
                    product_designer._pdf_pdf( dataURI, mw, mh, measure, w, h );
                }
        }
        },
        hermite_resize : function( format = 'download', canvas, w, h, measure, print = true, pdf = false ) {
            var i = new Image();

            i.onload = function() {
                var mw = w;
                var mh = h;
                if ( measure === 'inch' ) {
                    w = mw * 25.4; // inch to mm
                    h = mh * 25.4; //inch to mm
                }
                w = ( dpi * w ) / 25.4; //return value will be in px
                h = ( dpi * h ) / 25.4; //return value will be in px
                console.log( "Before Width and Height " + w + " " + h );
                // aspect ratio
                h = i.height / i.width * w;

                console.log( 'After Width and Height ' + w + " " + h );
                console.log( 'Image Width and Height ' + i.width + " " + i.height );

                w = w.toFixed();
                h = h.toFixed();

                console.log( 'After Width and Height Fixed' + w + " " + h );

                var b5 = Blitz.create();
                b5( {
                    source : canvas,
                    width : w,
                    height : h,
                    outputFormat : 'png',
                    quality : 1,
                    logPerformance : true,
                    output : format
                } ).then( download => {
                    console.log( 'Resize using file successful' )
                    if ( 'download' === format ) {
                        download();
                    } else {
                        var dataURI = download;
                        var is_watermark = pdr_frontend.is_watermark_enabled;
                        var is_order = pdr_frontend.is_order;
                        var watermark_content = pdr_frontend.watermark_content;
                        if ( '1' == is_watermark && ! is_order && '' != watermark_content ) {
                            product_designer.add_watermark( dataURI, watermark_content, print, w, pdf, mw, mh, measure );
                        } else {
                            product_designer._pdr_printing( print, dataURI, w, pdf, mw, mh, measure );
                        }
                    }

                    $( 'body' ).dimmer( 'hide' );
                } ).catch( err => {
                    console.log( err );
                    $( 'body' ).dimmer( 'hide' );
                } );
            };
            i.src = canvas;
        },
        resize_data_url : function( dataurl, w, h, measure, print = true, pdf = false ) {
            var mw = w;
            var mh = h;
            if ( measure === 'inch' ) {
                w = w * 25.4; // inch to mm
                h = h * 25.4; //inch to mm
            }
            w = ( dpi * w ) / 25.4; //return value will be in px
            h = ( dpi * h ) / 25.4; //return value will be in px

            var img = new Image();

            img.onload = function() {
                var canvas_resize = document.createElement( 'canvas' );
                var ctx = canvas_resize.getContext( '2d' );

                canvas_resize.width = w;
                canvas_resize.height = h;

                var x = 0, y = 0;

                if ( $( '.pdr-print-data-include-base' ).is( ':checked' ) ) {
                    var compositeOperation = ctx.globalCompositeOperation;
                    ctx.globalCompositeOperation = "source-over";
                    ctx.fillStyle = 'rgba(255, 255, 255, 1)';
                    ctx.fillRect( 0, 0, w, h );
                }
                var ratio = img.width / img.height;
                h = w / ratio;
                ctx.drawImage( img, x, y, w, h );
                var dataURI = canvas_resize.toDataURL( "image/png", 1 );

                console.log( dataURI );
                var is_watermark = pdr_frontend.is_watermark_enabled;
                var is_order = pdr_frontend.is_order;
                var watermark_content = pdr_frontend.watermark_content;
                if ( '1' == is_watermark && ! is_order && '' != watermark_content ) {
                    product_designer.add_watermark( dataURI, watermark_content, print, w, pdf, mw, mh, measure );
                } else {
                    dataURI = product_designer.convert_dpi( dataURI );
                    product_designer._pdr_printing( print, dataURI, w, pdf, mw, mh, measure );
                }

                $( 'body' ).dimmer( 'hide' );
            };
            img.src = dataurl;
        },
        pdr_file_name : function() {
            var timestamp = new Date().getTime();
            var product_name = $( '#pdr-file-product-name' ).val();
            return ( product_name + timestamp );
        },
        _print : function( ) {
            var get_current_color = $( '#pdr-color-preview-selection' ).val( );
            var canvasdataurl;
            if ( $( '.pdr-print-data-include-base' ).is( ':checked' ) ) {
                canvasdataurl = product_designer.add_bg_to_canvas( get_current_color, false, true );
            } else {
                //not include base
                canvasdataurl = product_designer.export_without_obj( );
            }
            return canvasdataurl;
        },
        print : function( e ) {
            $( 'body' ).dimmer( 'show' );
            var pagewidth = $( '#pdr-print-data-width' ).val( );
            var pageheight = $( '#pdr-print-data-height' ).val( );
            var get_measure = $( '.pdr-print-paper-measure' ).dropdown( 'get value' );
            var data_url = product_designer._print( );
            product_designer.hermite_resize( 'data', data_url, pagewidth, pageheight, get_measure, true, true );
            return false;
        },
        add_bg_to_canvas : function( background, crop = true, scale = false ) {
            var canvasclippath = canvas.clipPath,
                    scaleBy = ( true === scale ? 6 : 1 ), //Do you want to scale six times bigger than the actual size?
                    dataUrl;

            if ( false === crop ) {
                canvas.clipPath = null;
            }

            canvas.setBackgroundColor( background );
            dataUrl = canvas.toDataURL( { multiplier : scaleBy } );
            canvas.clipPath = canvasclippath; // Restore the last known state
            return dataUrl;
        },
        remove_bg_canvas : function( ) {
            var only_obj;
            canvas.on( 'before:render', product_designer.hide_overlay( ) );
            canvas.on( 'after:render', product_designer.show_overlay( ) );
            canvas.discardActiveObject( );
            only_obj = canvas.toDataURL( 'png' );
            canvas.off( 'before:render', product_designer.hide_overlay( ) );
            canvas.off( 'after:render', product_designer.show_overlay( ) );
            return only_obj;
        },
        show_overlay : function( ) {
            canvas.overlayImage = overlayImage;
        },
        hide_overlay : function( ) {
            overlayImage = canvas.overlayImage;
            canvas.overlayImage = null;
        },
        export_without_obj : function( ) {
            canvas.on( 'before:render', hide_overlay );
            canvas.on( 'after:render', show_overlay );
            canvas.discardActiveObject( );
            var dataurl = product_designer.add_bg_to_canvas( 'rgba(255, 255, 255, 0)', true, true );
            canvas.off( 'before:render', hide_overlay );
            canvas.off( 'after:render', show_overlay );
            return dataurl;
        },
        reiterate_all_views : function( key ) {
            $( '#pdr-switch-view-select' ).val( key ).trigger( 'change' );
        },
        //for undo/redo
        updateCanvasState : function( canvas_json = '' ) {
            return;
            var current_view = $( '#pdr-switch-view-select' ).val();
            if ( ( _config.undoStatus == false && _config.redoStatus == false ) ) {
                if ( canvas_json == '' ) {
                    canvas_json = canvas.toJSON();
                }

                var jsonData = canvas_json;
                var canvasAsJson = JSON.stringify( jsonData );

                console.log( _config.canvasState.length );

                if ( _config.currentStateIndex < _config.canvasState.length - 1 ) {
                    var indexToBeInserted = _config.currentStateIndex + 1;
                    _config.canvasState[indexToBeInserted] = canvasAsJson;
                    var numberOfElementsToRetain = indexToBeInserted + 1;
                    _config.canvasState = _config.canvasState.splice( 0, numberOfElementsToRetain );
                } else {
                    _config.canvasdata[current_view] = canvasAsJson;
                    _config.canvasState.push( _config.canvasdata );
                    console.log( _config.canvasState );

                }
                _config.currentStateIndex = _config.canvasState.length - 1;
        }
        },
        updateCanvasStatewithView : function( canvas_json = '' ) {
            var current_view = $( '#pdr-switch-view-select' ).val();

            if ( ( _config.undoViewStatus[current_view] == false && _config.redoViewStatus[current_view] == false ) ) {
                if ( canvas_json == '' ) {
                    canvas_json = canvas.toJSON();
                }
                var jsonData = canvas_json;
                var canvasAsJson = JSON.stringify( jsonData );

                if ( _config.currentStateViewIndex[current_view] < _config.canvasdata[current_view].length - 1 ) {
                    var indexToBeInserted = _config.currentStateViewIndex[current_view] + 1;
                    _config.currentStateViewIndex[current_view] + 1;

                    _config.canvasdata[current_view].push( canvasAsJson );
                    var numberOfElementsToRetain = indexToBeInserted + 1;
                    _config.canvasdata[current_view] = _config.canvasdata[current_view].splice( 0, numberOfElementsToRetain );
                } else {
                    _config.canvasdata[current_view].push( canvasAsJson );
                }

                _config.currentStateViewIndex[current_view] = _config.canvasdata[current_view].length - 1;

                if ( _config.canvasdata[current_view].length - 1 == 1 ) {
                    $( '.pdr-button-undo' ).removeAttr( 'disabled' );
                }
        }
        },
        newundo : function() {
            var current_view = $( '#pdr-switch-view-select' ).val();
            if ( _config.undoViewFinishedStatus[current_view] ) {

                if ( _config.currentStateViewIndex[current_view] == 0 ) {
                    _config.undoViewStatus[current_view] = false;
                } else {
                    if ( _config.canvasdata[current_view].length > 1 ) {
                        _config.undoViewFinishedStatus[current_view] = 0;
                        if ( _config.currentStateViewIndex[current_view] != 0 ) {
                            _config.undoViewStatus[current_view] = true;

                            var fetchcanvasdata = _config.canvasdata[current_view][_config.currentStateViewIndex[current_view] - 1];
                            fetchcanvasdata = JSON.parse( fetchcanvasdata );

                            canvas.loadFromJSON( _config.canvasdata[current_view][_config.currentStateViewIndex[current_view] - 1], function() {
                                var jsonData = JSON.parse( _config.canvasdata[current_view][_config.currentStateViewIndex[current_view] - 1] );
                                canvas.renderAll();
                                _config.undoViewStatus[current_view] = false;
                                _config.currentStateViewIndex[current_view] -= 1;
                                _config.undoViewFinishedStatus[current_view] = 1;
                            } );

                            if ( _config.currentStateViewIndex[current_view] == 1 ) {
                                $( '.pdr-button-undo' ).attr( 'disabled', 'disabled' );
                                $( '.pdr-button-redo' ).removeAttr( 'disabled' );
                            } else if ( _config.currentStateViewIndex[current_view] >= 1 ) {
                                $( '.pdr-button-redo' ).removeAttr( 'disabled' );
                            }
                        } else if ( _config.currentStateViewIndex[current_view] == 0 ) {
                            _config.undoViewFinishedStatus[current_view] = 1;
                            _config.currentStateViewIndex[current_view] -= 1;
                        }
                        console.log( 'undo' );
                        console.log( _config.currentStateViewIndex[current_view] );
                        console.log( _config.undoViewFinishedStatus[current_view] );
                        console.log( _config.canvasdata[current_view].length );
                    }
                }
            }
        },
        newredo : function() {
            var current_view = $( '#pdr-switch-view-select' ).val();
            if ( _config.redoViewFinishedStatus[current_view] ) {
                if ( ( _config.currentStateViewIndex[current_view] == _config.canvasdata[current_view].length - 1 ) && _config.currentStateViewIndex[current_view] != - 1 ) {
                    //  $( '.pdr-button-redo' ).attr( 'disabled' , 'disabled' ) ;
                } else {
                    if ( _config.canvasdata[current_view].length > _config.currentStateViewIndex[current_view] && _config.canvasdata[current_view].length != 0 ) {
                        _config.redoViewFinishedStatus[current_view] = 0;
                        _config.redoViewStatus[current_view] = true;
                        var fetchredocanvas = _config.canvasdata[current_view][_config.currentStateViewIndex[current_view] + 1];

                        canvas.loadFromJSON( _config.canvasdata[current_view][_config.currentStateViewIndex[current_view] + 1], function() {
                            var jsonData = JSON.parse( _config.canvasdata[current_view][_config.currentStateViewIndex[current_view] + 1] );
                            canvas.renderAll();
                            _config.redoViewStatus[current_view] = false;
                            _config.currentStateViewIndex[current_view] += 1;
                            if ( _config.currentStateViewIndex[current_view] != - 1 ) {
                                // $( '.pdr-button-undo' ).removeAttr( 'disabled' ) ;
                            }
                            _config.redoViewFinishedStatus[current_view] = 1;
                            console.log( _config.currentStateViewIndex[current_view] );
                            console.log( _config.canvasdata[current_view].length );

                            if ( ( _config.currentStateViewIndex[current_view] == _config.canvasdata[current_view].length - 1 ) ) {
                                $( '.pdr-button-redo' ).attr( 'disabled', 'disabled' );
                            } else if ( _config.currentStateViewIndex[current_view] >= 1 ) {
                                $( '.pdr-button-undo' ).removeAttr( 'disabled' );
                            }
                        } );
                    }
                }
            }
        },
        undo : function() {
            if ( _config.undoFinishedStatus ) {

                if ( _config.currentStateIndex == 0 ) {
                    _config.undoStatus = false;
                } else {
                    if ( _config.canvasState.length > 1 ) {
                        _config.undoFinishedStatus = 0;
                        if ( _config.currentStateIndex != 0 ) {
                            _config.undoStatus = true;

                            var fetchcanvasdata = _config.canvasState[_config.currentStateIndex - 1];
                            fetchcanvasdata = JSON.parse( fetchcanvasdata );
                            canvas.loadFromJSON( _config.canvasState[_config.currentStateIndex - 1], function() {
                                var jsonData = JSON.parse( _config.canvasState[_config.currentStateIndex - 1] );
                                canvas.renderAll();
                                _config.undoStatus = false;
                                _config.currentStateIndex -= 1;
                                _config.undoFinishedStatus = 1;
                            } );
                        } else if ( _config.currentStateIndex == 0 ) {
                            _config.undoFinishedStatus = 1;
                            _config.currentStateIndex -= 1;
                        }
                    }
                }
            }
        },
        redo : function() {
            if ( _config.redoFinishedStatus ) {
                if ( ( _config.currentStateIndex == _config.canvasState.length - 1 ) && _config.currentStateIndex != - 1 ) {
                    //_config.redoButton.disabled = "disabled" ;
                } else {
                    if ( _config.canvasState.length > _config.currentStateIndex && _config.canvasState.length != 0 ) {
                        _config.redoFinishedStatus = 0;
                        _config.redoStatus = true;
                        var fetchredocanvas = _config.canvasState[_config.currentStateIndex + 1];
                        canvas.loadFromJSON( _config.canvasState[_config.currentStateIndex + 1], function() {
                            var jsonData = JSON.parse( _config.canvasState[_config.currentStateIndex + 1] );
                            canvas.renderAll();
                            _config.redoStatus = false;
                            _config.currentStateIndex += 1;

                            _config.redoFinishedStatus = 1;
                        } );
                    }
                }
            }
        },
    };
    fabric.Canvas.prototype.removeArea = function( ) {
        var objects = canvas.getObjects( );
        for ( var i in objects ) {
            if ( typeof objects[i].pdr_property !== 'undefined' ) {

                if ( objects[i].pdr_property.name === 'cliparea' ) {
                    canvas.remove( objects[i] );
                }
            }
        }
        canvas.renderAll( );
    };
    const pageSizes = {
        a0 : {
            portrait : {
                inch : {
                    width : 33.1,
                    height : 46.8,
                },
                mm : {
                    width : 841,
                    height : 1189,
                }
            },
        },
        a1 : {
            portrait : {
                inch : {
                    width : 23.4,
                    height : 33.1,
                },
                mm : {
                    width : 594,
                    height : 841,
                }
            },
        },
        a2 : {
            portrait : {
                inch : {
                    width : 16.5,
                    height : 23.4,
                },
                mm : {
                    width : 420,
                    height : 594,
                }
            },
        },
        a3 : {
            portrait : {
                inch : {
                    width : 11.7,
                    height : 16.5,
                },
                mm : {
                    width : 297,
                    height : 420,
                }
            },
        },
        a4 : {
            portrait : {
                inch : {
                    width : 8.27,
                    height : 11.69,
                },
                mm : {
                    width : 210,
                    height : 297,
                }
            },
            landscape : {
                inch : {
                    height : 8.27,
                    width : 11.69,
                },
                mm : {
                    width : 297,
                    height : 210,
                }
            },
        },
        a5 : {
            portrait : {
                inch : {
                    width : 5.8,
                    height : 8.3,
                },
                mm : {
                    width : 148,
                    height : 210,
                }
            },
        },
        a6 : {
            portrait : {
                inch : {
                    width : 4.1,
                    height : 5.8,
                },
                mm : {
                    width : 105,
                    height : 148,
                }
            },
        },
        a7 : {
            portrait : {
                inch : {
                    width : 2.9,
                    height : 4.1,
                },
                mm : {
                    width : 74,
                    height : 105,
                }
            },
        },
        a8 : {
            portrait : {
                inch : {
                    width : 2.0,
                    height : 2.9,
                },
                mm : {
                    width : 52,
                    height : 74,
                }
            },
        },
        a9 : {
            portrait : {
                inch : {
                    width : 1.5,
                    height : 2.0,
                },
                mm : {
                    width : 37,
                    height : 52,
                }
            },
        },
        a10 : {
            portrait : {
                inch : {
                    width : 1.0,
                    height : 1.5,
                },
                mm : {
                    width : 26,
                    height : 37,
                }
            },
        }
    };

    var scalefactor = 1;
    //for zoom
    var canvasScale = 1.0;
    var SCALE_FACTOR = 1.01;
    var master_data = { };
    var price_data = { };
    var image_data = { };
    var overlayimgdata = { };
    var object_data = 0;
    var history_data = [ ];
    var google_font_data = [ ];
    var redo_data = [ ];

    let pause_saving = false;
    let undo_stack = [ ]
    let redo_stack = [ ]
    var overlayImage;
    var rules = pdr_frontend.pdr_rules;
    var _config = {
        canvasState : [ ],
        canvasdata : { },
        currentStateViewIndex : { },
        currentStateIndex : - 1,
        undoViewStatus : { },
        redoViewStatus : { },
        undoViewFinishedStatus : { },
        redoViewFinishedStatus : { },
        undoStatus : false,
        redoStatus : false,
        undoFinishedStatus : 1,
        redoFinishedStatus : 1,
    };

    for ( var key in rules ) {
        _config.canvasdata[key] = [ ],
                _config.currentStateViewIndex[key] = - 1;
        _config.undoViewStatus[key] = false,
                _config.redoViewStatus[key] = false,
                _config.undoViewFinishedStatus[key] = 1;
        _config.redoViewFinishedStatus[key] = 1;
    }

    let canvas = product_designer.initialize_canvas( ); //initialize canvas

    if ( '1' == pdr_frontend.force_guest && ! pdr_frontend.is_user_logged_in ) {
        //show alert message to user to ask them to login
        $.alertable.alert( pdr_frontend.force_guest_alert_message, {
            okButton : ' ',
            html : true,
        } );
        return false;
    }

    canvas.on( 'text:changed', function( e ) {
        var price = product_designer.calculate_text_price( pdr_frontend.text_char_fee, e.target.text, pdr_frontend.text_char_count );
        var textobj = e.target;
        textobj['pdr_property'] = {
            type : 'texts',
            price : price
        };
        $( document ).trigger( 'pdr_calculate_price' );
    } );

    $( '.pdr-pick-base.modal' ).modal( {
        blurring : true,
        autofocus : false,
        onVisible : function( ) {
            if ( is_edited === true ) {
                window.onbeforeunload = function() {
                    return "Are you sure want to leave current design? data you have entered may not be saved !";
                };
            }
            $( '.pdr-pick-base .header' ).html( pdr_frontend.choose_product_base_caption );
            product_designer.fetch_product_bases( );
            //upon card click in popup
            $( document ).on( 'click', 'a.pdr_popup_card', function() {
                var base_id = $( this ).attr( 'data-baseid' );
                //now pass base id to fetch products 
                product_designer.pdr_block( $( 'body' ) );
                var data = ( {
                    action : 'pdr_get_base_products',
                    baseid : base_id,
                    pdr_security : pdr_frontend.popup_product_base
                } );
                $.post( pdr_frontend.ajaxurl, data, function( res ) {
                    if ( true === res.success ) {
                        if ( res.data.products ) {
                            var product_base_details = res.data.products;
                            var html_data = product_designer.structure_preview_popup( product_base_details, false, true, base_id );
                            $( '.pdr-popup-product-details' ).html( html_data );
                            $( '.pdr-pick-base .header' ).html( pdr_frontend.choose_product_caption );
                        }
                    } else {
                        product_designer.pdr_alert( res.data.error );
                    }
                    product_designer.pdr_unblock( $( 'body' ) );
                } );
            } );
        },
    } ).modal( 'attach events', '.pdr-popup-product-base-btn', 'show' );

    //my designs for only members
    if ( pdr_frontend.is_user_logged_in ) {
        $( '.pdr-my-designs.modal' ).modal( {
            blurring : true,
            onVisible : function() {
                $( '.pdr-popup-my-designs' ).html( '' );
                $( '.pdr-popup-error-message' ).remove();
                product_designer.fetch_my_designs();
            },
        } ).modal( 'attach events', '.pdr-my-design', 'show' );
    }

    var area, textbox, clipart, objs, _clipboard, pattern, patternSourceCanvas;
    var dpi = 300;
    var pausePanning, zoomStartScale, currentX, currentY, xChange, yChange, lastX, lastY;
    var getviewport = product_designer.viewPort();

    var ww = pdr_frontend.is_mobile ? getviewport.width : 530;
    var wh = pdr_frontend.is_mobile ? getviewport.height : 630;

    if ( true === pdr_frontend.is_contain_pdr ) {
        product_designer.pagination_view( );
        product_designer.init( );
    } else {
        $( '.pdr-popup-product-base-btn' ).trigger( 'click' );
    }

    $( '.ui.pdr-popup-product-bases' ).dropdown( {
        onChange : function( value, text, choice ) {
            $( '.ui .search' ).blur();
            product_designer.fetch_product_bases( value );
            $( '.pdr-pick-base .header' ).html( pdr_frontend.choose_product_base_caption );
        }
    } );

    var saved_master_data = pdr_frontend.master_data, get_keys, count = 0;

    if ( PDR_ClientDB.isSupported && '' !== pdr_frontend.cart_item_key ) {
        let clientdb = new PDR_ClientDB( 'pdr', 1, { name : 'cart', keyOptions : { keyPath : 'itemKey', autoIncrement : false } } );

        $( document ).on( 'pdr_clientdb_ready_handler', function() {
            clientdb
                    .getValue( 'cart', pdr_frontend.cart_item_key )
                    .onsuccess = function( e ) {
                        if ( 'object' === typeof e.target.result ) {
                            saved_master_data = JSON.parse( e.target.result.canvas );
                            pdr_load_saved_canvas();
                        }
                    };
        } );
    }

    pdr_load_saved_canvas();

    function pdr_load_saved_canvas() {
        if ( saved_master_data ) {
            count = Object.keys( rules ).length;
            get_keys = Object.keys( rules );

            $( '.pdr-canvas-area' ).dimmer( 'add content', $( '#pdr_pre_loader' ) ).dimmer( 'show' );

            master_data = saved_master_data;
            product_designer.loadCurrentView();
            pdr_fetch_all_views( count );
        }
    }

    function pdr_fetch_all_views( i ) {
        setTimeout( function( e ) {
            if ( -- i ) {
                product_designer.reiterate_all_views( get_keys[i] );
                pdr_fetch_all_views( i );
            }

            if ( i === 0 ) {
                product_designer.reiterate_all_views( get_keys[i] );
                $( '.pdr-canvas-area' ).dimmer( 'hide' );
                product_designer.mobile_menu_active();
            }
        }, 1000 );
    }

    product_designer.set_print_input_data( );
    product_designer.set_form_data( );
    $( document ).trigger( 'pdr_calculate_price' );
    //avoid page reload
    var is_edited = false;

    window.addEventListener( "resize", product_designer.onresize );

    var bgImage;
    function hide_overlay( ) {
        bgImage = canvas.overlayImage;
        canvas.overlayImage = null;
    }

    function show_overlay( ) {
        canvas.overlayImage = bgImage;
    }

    function print_a4( html ) {
        var tab = window.open( 'about:blank', '_blank' );
        tab.document.write( "<html><body style='width:100%'></body></html>" ); // where 'html' is a variable containing your HTML
        tab.document.body.appendChild( html );
        tab.document.close( ); // to finish loading the page
        return false;
    }

    function handleCodePoints( array ) {
        var CHUNK_SIZE = 0x8000; // arbitrary number here, not too small, not too big
        var index = 0;
        var length = array.length;
        var result = '';
        var slice;
        while ( index < length ) {
            slice = array.slice( index, Math.min( index + CHUNK_SIZE, length ) );
            result += String.fromCharCode.apply( null, slice );
            index += CHUNK_SIZE;
        }
        return result;
    }

    function print_data( dataurl, filename ) {
        var a = document.createElement( "a" );
        if ( '1' == pdr_frontend.dataurl_to_blob ) {
            var file = product_designer.datauritoblob( dataurl );
            dataurl = URL.createObjectURL( file );
        }
        a.href = dataurl;
        a.setAttribute( "download", filename );
        a.click( );
    }

    function download_txt_file( text, name, type ) {
        var a = document.createElement( "a" );
        var file = new Blob( [ text ], { type : type } );
        a.href = URL.createObjectURL( file );
        a.download = name;
        a.click( );
    }

//history
// Check pressed button is Z - Ctrl+Z.
    var j = 0;
    document.addEventListener( 'keyup', ( { keyCode, ctrlKey } = event ) => {
        // Check Ctrl key is pressed.
        if ( ! ctrlKey ) {
            return;
        }

        if ( keyCode === 90 ) {
            product_designer.newundo();
        } else if ( keyCode === 89 ) {
            product_designer.newredo();
    }
    } );

    $( document ).on( 'click', '.pdr-button-undo', function() {
        product_designer.newundo();
    } );

    $( document ).on( 'click', '.pdr-button-redo', function() {
        product_designer.newredo();
    } );
} );

( function() {
    var defaultOnTouchStartHandler = fabric.Canvas.prototype._onTouchStart;
    fabric.util.object.extend( fabric.Canvas.prototype, {
        _onTouchStart : function( e ) {
            var target = this.findTarget( e );
            // if allowTouchScrolling is enabled, no object was at the
            // the touch position and we're not in drawing mode, then 
            // let the event skip the fabricjs canvas and do default
            // behavior
            if ( this.allowTouchScrolling && ! target && ! this.isDrawingMode ) {
                // returning here should allow the event to propagate and be handled
                // normally by the browser
                return;
            }

            // otherwise call the default behavior
            defaultOnTouchStartHandler.call( this, e );
        }
    } );
} )();
