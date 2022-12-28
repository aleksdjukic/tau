(function( $ ) {
	'use strict';
	jQuery( '.multiselect2' ).select2();

	function allowSpeicalCharacter( str ) {
		return str.replace( '&#8211;', '–' ).replace( '&gt;', '>' ).replace( '&lt;', '<' ).replace( '&#197;', 'Å' );
	}

	function productFilter() {
		jQuery( '.product_fees_conditions_values_product' ).each( function() {
			$( '.product_fees_conditions_values_product' ).select2( {
				ajax: {
					url: coditional_vars.ajaxurl,
					dataType: 'json',
					delay: 250,
					data: function( params ) {
						return {
							value: params.term,
							action: 'wcpfc_pro_product_fees_conditions_values_product_ajax'
						};
					},
					processResults: function( data ) {
						var options = [];
						if ( data ) {
							$.each( data, function( index, text ) {
								options.push( { id: text[ 0 ], text: allowSpeicalCharacter( text[ 1 ] ) } );
							} );

						}
						return {
							results: options
						};
					},
					cache: true
				},
				minimumInputLength: 3
			} );
		} );
	}

	function varproductFilter() {
		$( '.product_fees_conditions_values_var_product' ).each( function() {
			$( '.product_fees_conditions_values_var_product' ).select2( {
				ajax: {
					url: coditional_vars.ajaxurl,
					dataType: 'json',
					delay: 250,
					data: function( params ) {
						return {
							value: params.term,
							action: 'wcpfc_pro_product_fees_conditions_varible_values_product_ajax'
						};
					},
					processResults: function( data ) {
						var options = [];
						if ( data ) {
							$.each( data, function( index, text ) {
								options.push( { id: text[ 0 ], text: allowSpeicalCharacter( text[ 1 ] ) } );
							} );

						}
						return {
							results: options
						};
					},
					cache: true
				},
				minimumInputLength: 3
			} );
		} );
	}

	function getProductListBasedOnThreeCharAfterUpdate() {
		$( '.fees_pricing_rules .ap_product, ' +
			'.fees_pricing_rules .ap_product_weight, ' +
			'.fees_pricing_rules .ap_product_subtotal' ).each( function() {
			$( '.fees_pricing_rules .ap_product, ' +
				'.fees_pricing_rules .ap_product_weight, ' +
				'.fees_pricing_rules .ap_product_subtotal' ).select2( {
				ajax: {
					url: coditional_vars.ajaxurl,
					dataType: 'json',
					delay: 250,
					data: function( params ) {
						return {
							value: params.term,
							action: 'wcpfc_pro_simple_and_variation_product_list_ajax'
						};
					},
					processResults: function( data ) {
						var options = [];
						if ( data ) {
							$.each( data, function( index, text ) {
								options.push( { id: text[ 0 ], text: allowSpeicalCharacter( text[ 1 ] ) } );
							} );

						}
						return {
							results: options
						};
					},
					cache: true
				},
				minimumInputLength: 3
			} );
		} );
	}

	function numberValidateForAdvanceRules() {
		$( '.number-field' ).keypress( function( e ) {
			var regex = new RegExp( '^[0-9-%.]+$' );
			var str = String.fromCharCode( ! e.charCode ? e.which : e.charCode );
			if ( regex.test( str ) ) {
				return true;
			}
			e.preventDefault();
			return false;
		} );
		$( '.qty-class' ).keypress( function( e ) {
			var regex = new RegExp( '^[0-9]+$' );
			var str = String.fromCharCode( ! e.charCode ? e.which : e.charCode );
			if ( regex.test( str ) ) {
				return true;
			}
			e.preventDefault();
			return false;
		} );
		$( '.weight-class, .price-class' ).keypress( function( e ) {
			var regex = new RegExp( '^[0-9.]+$' );
			var str = String.fromCharCode( ! e.charCode ? e.which : e.charCode );
			if ( regex.test( str ) ) {
				return true;
			}
			e.preventDefault();
			return false;
		} );
	}

	$( window ).load( function() {
		jQuery( '.multiselect2' ).select2();

		$( 'a[href="admin.php?page=wcpfc-pro-list"]' ).parent().addClass( 'current' );
		$( 'a[href="admin.php?page=wcpfc-pro-list"]' ).addClass( 'current' );

		$( '#fee_settings_start_date' ).datepicker( {
			dateFormat: 'dd-mm-yy',
			minDate: '0',
			onSelect: function() {
				var dt = $( this ).datepicker( 'getDate' );
				dt.setDate( dt.getDate() + 1 );
				$( '#fee_settings_end_date' ).datepicker( 'option', 'minDate', dt );
			}
		} );
		$( '#fee_settings_end_date' ).datepicker( {
			dateFormat: 'dd-mm-yy',
			minDate: '0',
			onSelect: function() {
				var dt = $( this ).datepicker( 'getDate' );
				dt.setDate( dt.getDate() - 1 );
				$( '#fee_settings_start_date' ).datepicker( 'option', 'maxDate', dt );
			}
		} );
		var ele = $( '#total_row' ).val();
		var count;
		if ( ele > 2 ) {
			count = ele;
		} else {
			count = 2;
		}
		$( 'body' ).on( 'click', '#fee-add-field', function() {
			var fee_add_field = $( '#tbl-product-fee tbody' ).get( 0 );

			var tr = document.createElement( 'tr' );
			tr = setAllAttributes( tr, { 'id': 'row_' + count } );
			fee_add_field.appendChild( tr );

			// generate td of condition
			var td = document.createElement( 'td' );
			td = setAllAttributes( td, { 'class': 'titledesc th_product_fees_conditions_condition' } );
			tr.appendChild( td );
			var conditions = document.createElement( 'select' );
			conditions = setAllAttributes( conditions, {
				'rel-id': count,
				'id': 'product_fees_conditions_condition_' + count,
				'name': 'fees[product_fees_conditions_condition][]',
				'class': 'product_fees_conditions_condition'
			} );
			conditions = insertOptions( conditions, get_all_condition() );
			td.appendChild( conditions );
			// td ends

			// generate td for equal or no equal to
			td = document.createElement( 'td' );
			td = setAllAttributes( td, { 'class': 'select_condition_for_in_notin' } );
			tr.appendChild( td );
			var conditions_is = document.createElement( 'select' );
			conditions_is = setAllAttributes( conditions_is, {
				'name': 'fees[product_fees_conditions_is][]',
				'class': 'product_fees_conditions_is product_fees_conditions_is_' + count
			} );
			conditions_is = insertOptions( conditions_is, condition_types( false ) );
			td.appendChild( conditions_is );
			// td ends

			// td for condition values
			td = document.createElement( 'td' );
			td = setAllAttributes( td, { 'id': 'column_' + count, 'class': 'condition-value' } );
			tr.appendChild( td );
			condition_values( jQuery( '#product_fees_conditions_condition_' + count ) );

			var condition_key = document.createElement( 'input' );
			condition_key = setAllAttributes( condition_key, {
				'type': 'hidden',
				'name': 'condition_key[value_' + count + '][]',
				'value': '',
			} );
			td.appendChild( condition_key );
			// var conditions_values_index = jQuery( '.product_fees_conditions_values_' + count ).get( 0 );
			jQuery( '.product_fees_conditions_values_' + count ).trigger( 'change' );
			jQuery( '.multiselect2' ).select2();
			// td ends

			// td for delete button
			td = document.createElement( 'td' );
			tr.appendChild( td );
			var delete_button = document.createElement( 'a' );
			delete_button = setAllAttributes( delete_button, {
				'id': 'fee-delete-field',
				'rel-id': count,
				'title': coditional_vars.delete,
				'class': 'delete-row',
				'href': 'javascript:;'
			} );
			var deleteicon = document.createElement( 'i' );
			deleteicon = setAllAttributes( deleteicon, {
				'class': 'fa fa-trash'
			} );
			delete_button.appendChild( deleteicon );
			td.appendChild( delete_button );
			// td ends

			count ++;
		} );

		function insertOptions( parentElement, options ) {
			var option;
			for ( var i = 0; i < options.length; i ++ ) {
				if ( options[ i ].type === 'optgroup' ) {
					var optgroup = document.createElement( 'optgroup' );
					optgroup = setAllAttributes( optgroup, options[ i ].attributes );
					for ( var j = 0; j < options[ i ].options.length; j ++ ) {
						option = document.createElement( 'option' );
						option = setAllAttributes( option, options[ i ].options[ j ].attributes );
						option.textContent = options[ i ].options[ j ].name;
						optgroup.appendChild( option );
					}
					parentElement.appendChild( optgroup );
				} else {
					option = document.createElement( 'option' );
					option = setAllAttributes( option, options[ i ].attributes );
					option.textContent = allowSpeicalCharacter( options[ i ].name );
					parentElement.appendChild( option );
				}

			}
			return parentElement;

		}

		function allowSpeicalCharacter( str ) {
			return str.replace( '&#8211;', '–' ).replace( '&gt;', '>' ).replace( '&lt;', '<' ).replace( '&#197;', 'Å' );
		}

		function get_all_condition() {
			return [
				{
					'type': 'optgroup',
					'attributes': { 'label': coditional_vars.location_specific },
					'options': [
						{ 'name': coditional_vars.country, 'attributes': { 'value': 'country' } },
						{ 'name': coditional_vars.city, 'attributes': { 'value': 'city' } },
						
					]
				},
				{
					'type': 'optgroup',
					'attributes': { 'label': coditional_vars.product_specific },
					'options': [
						{ 'name': coditional_vars.cart_contains_product, 'attributes': { 'value': 'product' } },
						{ 'name': coditional_vars.cart_contains_variable_product, 'attributes': { 'value': 'variableproduct' } },
						{ 'name': coditional_vars.cart_contains_category_product, 'attributes': { 'value': 'category' } },
						{ 'name': coditional_vars.cart_contains_tag_product, 'attributes': { 'value': 'tag' } },
						{ 'name': coditional_vars.cart_contains_product_qty, 'attributes': { 'value': 'product_qty' } },
					]
				},
                
				{
					'type': 'optgroup',
					'attributes': { 'label': coditional_vars.user_specific },
					'options': [
						{ 'name': coditional_vars.user, 'attributes': { 'value': 'user' } },
						
					]
				},
				{
					'type': 'optgroup',
					'attributes': { 'label': coditional_vars.cart_specific },
					'options': [
						{ 'name': coditional_vars.cart_subtotal_before_discount, 'attributes': { 'value': 'cart_total' } },
						
						{ 'name': coditional_vars.quantity, 'attributes': { 'value': 'quantity' } },
						
					]
				},
				

			];
		}

		$( '#fee_settings_select_fee_type' ).change( function() {
			if ( jQuery( this ).val() === 'fixed' ) {
				jQuery( '#fee_settings_product_cost' ).attr('type', 'text');
				jQuery( '#fee_settings_product_cost' ).attr( 'placeholder', coditional_vars.currency_symbol );
				jQuery( '.fees_on_cart_total_wrap' ).hide();
			} else if ( jQuery( this ).val() === 'percentage' ) {
				jQuery( '#fee_settings_product_cost' ).attr('type', 'number');
				jQuery( '#fee_settings_product_cost' ).attr( 'placeholder', '%' );
				jQuery( '#fee_settings_product_cost' ).attr( 'step', '0.01' );
				jQuery( '.fees_on_cart_total_wrap' ).show();
			}
			jQuery( '#fee_settings_product_cost' ).val('');
		} );
		if( $( '#fee_settings_select_fee_type' ).val() === 'fixed' ){
			jQuery( '.fees_on_cart_total_wrap' ).hide();
		} else if( $( '#fee_settings_select_fee_type' ).val() === 'percentage' ) {
			jQuery( '.fees_on_cart_total_wrap' ).show();
		}

		$( 'body' ).on( 'change', '.product_fees_conditions_condition', function() {
			condition_values( this );
		} );

		function condition_values( element ) {
			var posts_per_page = 3; // Post per page
			var page = 0; // What page we are on.
			var condition = $( element ).val();
			var count = $( element ).attr( 'rel-id' );
			var column = jQuery( '#column_' + count ).get( 0 );
			jQuery( column ).empty();
			var loader = document.createElement( 'img' );
			loader = setAllAttributes( loader, { 'src': coditional_vars.plugin_url + 'images/ajax-loader.gif' } );
			column.appendChild( loader );
            
			$.ajax( {
				type: 'GET',
				url: coditional_vars.ajaxurl,
				data: {
					'action': 'wcpfc_pro_product_fees_conditions_values_ajax',
					'wcpfc_pro_product_fees_conditions_values_ajax': $( '#wcpfc_pro_product_fees_conditions_values_ajax' ).val(),
					'condition': condition,
					'count': count,
					'posts_per_page': posts_per_page,
					'offset': (page * posts_per_page),
				},
				contentType: 'application/json',
				success: function( response ) {
					page ++;
					var condition_values;
					jQuery( '.product_fees_conditions_is_' + count ).empty();
					var column = jQuery( '#column_' + count ).get( 0 );
					var condition_is = jQuery( '.product_fees_conditions_is_' + count ).get( 0 );
					if ( condition === 'cart_total'
						|| condition === 'quantity'
						|| condition === 'product_qty'
						
					) {
						condition_is = insertOptions( condition_is, condition_types( true ) );
					} else {
						condition_is = insertOptions( condition_is, condition_types( false ) );
					}
					jQuery( '.product_fees_conditions_is_' + count ).trigger( 'change' );
					jQuery( column ).empty();

					var condition_values_id = '';
					var extra_class = '';
					if ( condition === 'product' ) {
						condition_values_id = 'product-filter-' + count;
						extra_class = 'product_fees_conditions_values_product';
					}
					if ( condition === 'variableproduct' ) {
						condition_values_id = 'var-product-filter-' + count;
						extra_class = 'product_fees_conditions_values_var_product';
					}
					if ( condition === 'product_qty' ) {
						condition_values_id = 'product-qry-filter-' + count;
						extra_class = 'product_fees_conditions_values_product_qty';
					}

					if ( isJson( response ) ) {
						condition_values = document.createElement( 'select' );
						condition_values = setAllAttributes( condition_values, {
							'name': 'fees[product_fees_conditions_values][value_' + count + '][]',
							'class': 'wcpfc_select product_fees_conditions_values product_fees_conditions_values_' + count + ' multiselect2 ' + extra_class,
							'multiple': 'multiple',
							'id': condition_values_id
						} );
						column.appendChild( condition_values );
						var data = JSON.parse( response );
						condition_values = insertOptions( condition_values, data );
					} else {
						var input_extra_class;
						if ( condition === 'quantity' ) {
							input_extra_class = ' qty-class';
						}
						if ( condition === 'weight' ) {
							input_extra_class = ' weight-class';
						}
						if ( condition === 'cart_total' || condition === 'cart_totalafter' || condition === 'product_qty' || condition === 'cart_specificproduct' ) {
							input_extra_class = ' price-class';
						}

						condition_values = document.createElement( jQuery.trim( response ) );
						condition_values = setAllAttributes( condition_values, {
							'name': 'fees[product_fees_conditions_values][value_' + count + ']',
							'class': 'product_fees_conditions_values' + input_extra_class,
							'type': 'text',

						} );
						column.appendChild( condition_values );
					}
					column = $( '#column_' + count ).get( 0 );
					var input_node = document.createElement( 'input' );
					input_node = setAllAttributes( input_node, {
						'type': 'hidden',
						'name': 'condition_key[value_' + count + '][]',
						'value': ''
					} );
					column.appendChild( input_node );
					var p_node, b_node, b_text_node, text_node;
					
					if( condition === 'city' ){
						p_node = document.createElement( 'p' );
						b_node = document.createElement( 'b' );
						b_node = setAllAttributes( b_node, {
							'style': 'color: red;',
						} );
						b_text_node = document.createTextNode( coditional_vars.note );
						b_node.appendChild( b_text_node );

						if ( condition === 'city' ) {
							text_node = document.createTextNode( coditional_vars.city_msg );
						}
						p_node.appendChild( b_node );
						p_node.appendChild( text_node );
						column.appendChild( p_node );
					}

					jQuery( '.multiselect2' ).select2();

					productFilter();

					
					varproductFilter();
					getProductListBasedOnThreeCharAfterUpdate();
					
					numberValidateForAdvanceRules();
				}
			} );
		}

		function condition_types( text ) {
			if ( text === true ) {
				return [
					{ 'name': coditional_vars.equal_to, 'attributes': { 'value': 'is_equal_to' } },
					{ 'name': coditional_vars.less_or_equal_to, 'attributes': { 'value': 'less_equal_to' } },
					{ 'name': coditional_vars.less_than, 'attributes': { 'value': 'less_then' } },
					{ 'name': coditional_vars.greater_or_equal_to, 'attributes': { 'value': 'greater_equal_to' } },
					{ 'name': coditional_vars.greater_than, 'attributes': { 'value': 'greater_then' } },
					{ 'name': coditional_vars.not_equal_to, 'attributes': { 'value': 'not_in' } },
				];
			} else {
				return [
					{ 'name': coditional_vars.equal_to, 'attributes': { 'value': 'is_equal_to' } },
					{ 'name': coditional_vars.not_equal_to, 'attributes': { 'value': 'not_in' } },
				];

			}

		}

		function isJson( str ) {
			try {
				JSON.parse( str );
			} catch ( err ) {
				return false;
			}
			return true;
		}

		productFilter();

		

		$( 'body' ).on( 'click', '.condition-check-all', function() {
			$( 'input.multiple_delete_fee:checkbox' ).not( this ).prop( 'checked', this.checked );
		} );

		$( 'body' ).on( 'click', '#detete-conditional-fee', function() {
			if ( $( '.multiple_delete_fee:checkbox:checked' ).length === 0 ) {
				alert( coditional_vars.select_atleast_one_checkbox );
				return false;
			}
			if ( confirm( coditional_vars.delete_confirmation_msg ) ) {
				var allVals = [];
				$( '.multiple_delete_fee:checked' ).each( function() {
					allVals.push( $( this ).val() );
				} );
				$.ajax( {
					type: 'GET',
					url: coditional_vars.ajaxurl,
					data: {
						'action': 'wcpfc_pro_wc_multiple_delete_conditional_fee',
						'nonce': coditional_vars.dsm_ajax_nonce,
						'allVals': allVals
					},
					success: function( response ) {
						alert( response );
						$( '.multiple_delete_fee' ).prop( 'checked', false );
						location.reload();
					}
				} );
			}
		} );

		$( '.disable-enable-conditional-fee' ).click( function() {
			if ( $( '.multiple_delete_fee:checkbox:checked' ).length === 0 ) {
				alert( coditional_vars.select_chk );
				return false;
			}
			if ( confirm( coditional_vars.change_status ) ) {
				var allVals = [];
				$( '.multiple_delete_fee:checked' ).each( function() {
					allVals.push( $( this ).val() );
				} );

				$.ajax( {
					type: 'GET',
					url: coditional_vars.ajaxurl,
					data: {
						'action': 'wcpfc_pro_wc_disable_conditional_fee',
						'nonce': coditional_vars.disable_fees_ajax_nonce,
						'do_action': $( this ).attr( 'id' ),
						'allVals': allVals
					},
					success: function( response ) {
						alert( response );
						$( '.multiple_delete_fee' ).prop( 'checked', false );
						location.reload();
					}
				} );
			}
		} );
		/* description toggle */
		$( 'span.woocommerce_conditional_product_fees_checkout_tab_description' ).click( function( event ) {
			event.preventDefault();
			$( this ).next( 'p.description' ).toggle();
		} );

        var fixHelperModified = function( e, tr ) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each( function( index ) {
                $( this ).width( $originals.eq( index ).width() );
            } );
            return $helper;
        };
        //Make diagnosis table sortable
        $( '.wcpfc-main-table table.wp-list-table tbody' ).sortable( {
            helper: fixHelperModified,
            stop: function() {
                $('.wcpfc-main-table').block({
                    message: null,
                    overlayCSS: {
                        background: 'rgb(255, 255, 255)',
                        opacity: 0.6,
                    },
                });
                var listing = [];
                var paged = $('.current_paged').val();
                jQuery( '.ui-sortable-handle' ).each(function() {
                    listing.push(jQuery( this ).find( 'input' ).val());
                });
                $.ajax( {
                    type: 'POST',
                    url: coditional_vars.ajaxurl,
                    // contentType: 'application/json',
                    data: {
                        'action': 'wcpfc_pro_product_fees_conditions_sorting',
                        'sorting_conditional_fee': jQuery( '#sorting_conditional_fee' ).val(),
                        'listing': listing,
                        'paged': paged
                    },
                    success: function( response ) {
                        jQuery('.wcpfc-main-table').unblock();
                        var div_wrap = $('<div></div>').addClass('notice notice-success');
                        var p_text = $('<p></p>').text(response.data.message);
                        div_wrap.append(p_text);
                        // $(div_wrap).insertAfter($('.search-box'));
                        $('.wcpfc-main-table').prepend(div_wrap);
                        setTimeout( function(){
                            div_wrap.remove();
                        }, 2000 );
                    }
                } );

            }
        } );
        $( '.wcpfc-main-table table tbody' ).disableSelection();
		
		if( $( '#ds_time_from' ).length || $( '#ds_time_to' ).length ){
			var ds_time_from = $( '#ds_time_from' ).val();
			var ds_time_to = $( '#ds_time_to' ).val();
			$( '#ds_time_from' ).timepicker({
				timeFormat: 'h:mm p',
				interval: 30,
				minTime: '00:00 AM',
				maxTime: '11:59 PM',
				startTime: ds_time_from,
				dynamic: true,
				dropdown: true,
				scrollbar: true,
				change: function () {
					var newTime = $(this).val();					
					$( '#ds_time_to' ).timepicker( 'option', 'minTime', newTime );
				}
			});
			
			$('#ds_time_to').timepicker({
				timeFormat: 'h:mm p',
				interval: 30,
				minTime: '00:00AM',
				maxTime: '11:59PM',
				startTime: ds_time_to,
				dynamic: true,
				dropdown: true,
				scrollbar: true
			});
		}
		jQuery( '.ds_reset_time' ).click(function(){
			$( '#ds_time_from' ).val('');
			$( '#ds_time_to' ).val('');
		});
		

		$( '[id^=fee_settings_product_cost]' ).keypress( validateNumber );

		function validateNumber( event ) {
			
			var key = window.event ? event.keyCode : event.which;
			if ( event.keyCode === 8 || event.keyCode === 46 ) {
				return true;
			} else if ( key < 48 || key > 57 ) {
				return false;
			} else if ( key === 45 ) {
				return true;
			} else if ( key === 37 ) {
				return true;
			} else {
				return true;
			}
		}
		numberValidateForAdvanceRules();


		/*Start: Change shipping status form list section*/
		$( document ).on( 'click', '#fees_status_id', function() {
			var current_fees_id = $( this ).attr( 'data-smid' );
			var current_value = $( this ).prop( 'checked' );
            $('.wcpfc-main-table').block({
                message: null,
                overlayCSS: {
                    background: 'rgb(255, 255, 255)',
                    opacity: 0.6,
                },
            });
			$.ajax( {
				type: 'GET',
				url: coditional_vars.ajaxurl,
				data: {
					'action': 'wcpfc_pro_change_status_from_list_section',
					'current_fees_id': current_fees_id,
					'current_value': current_value
				}, beforeSend: function() {
					var div = document.createElement( 'div' );
					div = setAllAttributes( div, {
						'class': 'loader-overlay',
					} );

					var img = document.createElement( 'img' );
					img = setAllAttributes( img, {
						'id': 'before_ajax_id',
						'src': coditional_vars.ajax_icon
					} );

					div.appendChild( img );
					jQuery( '#conditional-fee-listing' ).after( div );
				}, complete: function() {
					jQuery( '.wcpfc-main-table .loader-overlay' ).remove();
                    jQuery('.wcpfc-main-table').unblock();
				}, success: function( response ) {
                    var div_wrap = $('<div></div>').addClass('notice notice-success');
                    var p_text = $('<p></p>').text(jQuery.trim( response ));
                    div_wrap.append(p_text);
                    // $(div_wrap).insertAfter($('.search-box'));
                    $('.wcpfc-main-table').prepend(div_wrap);
                    setTimeout( function(){
                        div_wrap.remove();
                    }, 2000 );
                    jQuery('.wcpfc-main-table').unblock();
				}
			} );
		} );
		/*End: Change shipping status form list section*/

		/*Start: Get last url parameters*/
		function getUrlVars() {
			var vars = [], hash;
			// var get_current_url = coditional_vars.current_url;
			var get_current_url = location.href;
			var hashes = get_current_url.slice( get_current_url.indexOf( '?' ) + 1 ).split( '&' );
			for ( var i = 0; i < hashes.length; i ++ ) {
				hash = hashes[ i ].split( '=' );
				vars.push( hash[ 0 ] );
				vars[ hash[ 0 ] ] = hash[ 1 ];
			}
			return vars;
		}

		/*End: Get last url parameters*/

		function setAllAttributes( element, attributes ) {
			Object.keys( attributes ).forEach( function( key ) {
				element.setAttribute( key, attributes[ key ] );
				// use val
			} );
			return element;
		}

		//remove tr on delete icon click
		$( 'body' ).on( 'click', '.delete-row', function() {
			$( this ).parent().parent().remove();
		} );

		//Save Master Settings
		$( document ).on( 'click', '#save_master_settings', function() {
			var chk_enable_logging;
			var chk_enable_coupon_fee;
			var chk_enable_custom_fun;
			var chk_enable_all_fee_tax;
			var chk_enable_all_fee_tooltip;
			var chk_enable_all_fee_tooltip_text = $('#chk_enable_all_fee_tooltip_text').val();
			var chk_fees_per_page = $('#chk_fees_per_page').val();

			if ( $( '#chk_enable_logging' ).prop( 'checked' ) === true ) {
				chk_enable_logging = 'on';
			} else {
				chk_enable_logging = 'off';
			}
			if ( $( '#chk_enable_coupon_fee' ).prop( 'checked' ) === true ) {
				chk_enable_coupon_fee = 'on';
			} else {
				chk_enable_coupon_fee = 'off';
			}
			if ( $( '#chk_enable_custom_fun' ).prop( 'checked' ) === true ) {
				chk_enable_custom_fun = 'on';
			} else {
				chk_enable_custom_fun = 'off';
			}
			if( chk_enable_all_fee_tooltip_text.length > 25 && 'on' === chk_enable_custom_fun ){
				alert('Please add max 25 character!');
				return;
			}
			if ( $( '#chk_enable_all_fee_tax' ).prop( 'checked' ) === true ) {
				chk_enable_all_fee_tax = 'on';
			} else {
				chk_enable_all_fee_tax = 'off';
			}
			if ( $( '#chk_enable_all_fee_tooltip' ).prop( 'checked' ) === true ) {
				chk_enable_all_fee_tooltip = 'on';
			} else {
				chk_enable_all_fee_tooltip = 'off';
			}

			$.ajax( {
				type: 'GET',
				url: coditional_vars.ajaxurl,
				data: {
					'action': 'wcpfc_pro_save_master_settings',
					'chk_enable_logging': chk_enable_logging,
					'chk_enable_coupon_fee': chk_enable_coupon_fee,
					'chk_enable_custom_fun': chk_enable_custom_fun,
					'chk_enable_all_fee_tax': chk_enable_all_fee_tax,
					'chk_enable_all_fee_tooltip': chk_enable_all_fee_tooltip,
					'chk_enable_all_fee_tooltip_text': chk_enable_all_fee_tooltip_text,
					'chk_fees_per_page': chk_fees_per_page,
				},
				success: function() {
					// var div = document.createElement( 'div' );
					// div = setAllAttributes( div, {
					// 	'class': 'ms-msg'
					// } );
					// div.textContent = coditional_vars.success_msg2;
					// $( div ).insertBefore( '.wcpfc-section-left .wcpfc-main-table' );

                    var div_wrap = $('<div></div>').addClass('notice notice-success');
                    var p_text = $('<p></p>').text(coditional_vars.success_msg2);
                    div_wrap.append(p_text);
                    $('.wcpfc-main-table').prepend(div_wrap);
                    $( 'html, body' ).animate( { scrollTop: 0 }, 'slow' );

					setTimeout( function() {
						div_wrap.remove();
                        window.location.replace(coditional_vars.ajax_redirect_after);
					}, 3000 );
				}
			} );
		} );

		// $( document ).on( 'click', '.conditional-fee-order', function() {
		// 	saveAllIdOrderWise( 'on_click' );
		// } );

		// saveAllIdOrderWise( 'on_load' );

		// /*Start code for save all method as per sequence in list*/
		// function saveAllIdOrderWise( position ) {
		// 	var smOrderArray = [];

		// 	$( 'table#conditional-fee-listing tbody tr' ).each( function() {
		// 		smOrderArray.push( this.id );
		// 	} );
		// 	$.ajax( {
		// 		type: 'GET',
		// 		url: coditional_vars.ajaxurl,
		// 		data: {
		// 			'action': 'wcpfc_pro_sm_sort_order',
		// 			'smOrderArray': smOrderArray
		// 		},
		// 		success: function() {
		// 			if ( 'on_click' === jQuery.trim( position ) ) {
		// 				alert( coditional_vars.success_msg1 );
		// 			}
		// 		}
		// 	} );
		// }

		/*Start: Change shipping status form list section*/
		$( document ).on( 'click', '#shipping_status_id', function() {
			var current_shipping_id = $( this ).attr( 'data-smid' );
			var current_value = $( this ).prop( 'checked' );
			$.ajax( {
				type: 'GET',
				url: coditional_vars.ajaxurl,
				data: {
					'action': 'afrsm_pro_change_status_from_list_section',
					'current_shipping_id': current_shipping_id,
					'current_value': current_value
				}, beforeSend: function() {
					var div = document.createElement( 'div' );
					div = setAllAttributes( div, {
						'class': 'loader-overlay',
					} );

					var img = document.createElement( 'img' );
					img = setAllAttributes( img, {
						'id': 'before_ajax_id',
						'src': coditional_vars.ajax_icon
					} );

					div.appendChild( img );
					jQuery( '#shipping-methods-listing' ).after( div );
				}, complete: function() {
					jQuery( '.afrsm-main-table .loader-overlay' ).remove();
				}, success: function( response ) {
					alert( jQuery.trim( response ) );
				}
			} );
		} );
		/*End: Change shipping status form list section*/

	} );
	jQuery( window ).on( 'load', function() {
		jQuery( '.multiselect2' ).select2();

		jQuery('#ds_select_day_of_week').select2({
			placeholder: 'Select days of the week'
		});

		function allowSpeicalCharacter( str ) {
			return str.replace( '&#8211;', '–' ).replace( '&gt;', '>' ).replace( '&lt;', '<' ).replace( '&#197;', 'Å' );
		}

		jQuery( '.product_fees_conditions_values_product' ).each( function() {
			jQuery( '.product_fees_conditions_values_product' ).select2( {
				ajax: {
					url: coditional_vars.ajaxurl,
					dataType: 'json',
					delay: 250,
					data: function( params ) {
						return {
							value: params.term,
							action: 'wcpfc_pro_product_fees_conditions_values_product_ajax'
						};
					},
					processResults: function( data ) {
						var options = [];
						if ( data ) {
							jQuery.each( data, function( index, text ) {
								options.push( { id: text[ 0 ], text: allowSpeicalCharacter( text[ 1 ] ) } );
							} );

						}
						return {
							results: options
						};
					},
					cache: true
				},
				minimumInputLength: 3
			} );
		} );
		
		jQuery( '.product_fees_conditions_values_var_product' ).each( function() {
			jQuery( '.product_fees_conditions_values_var_product' ).select2( {
				ajax: {
					url: coditional_vars.ajaxurl,
					dataType: 'json',
					delay: 250,
					data: function( params ) {
						return {
							value: params.term,
							action: 'wcpfc_pro_product_fees_conditions_varible_values_product_ajax'
						};
					},
					processResults: function( data ) {
						var options = [];
						if ( data ) {
							jQuery.each( data, function( index, text ) {
								options.push( { id: text[ 0 ], text: allowSpeicalCharacter( text[ 1 ] ) } );
							} );

						}
						return {
							results: options
						};
					},
					cache: true
				},
				minimumInputLength: 3
			} );
		} );
        
	} );
})( jQuery );

jQuery( document ).ready( function() {
	// if ( jQuery( window ).width() <= 980 ) {
		jQuery( '.fees-pricing-rules .fees_pricing_rules .tab-content' ).click( function() {
			var acc_id = jQuery( this ).attr( 'id' );
			jQuery( '.fees-pricing-rules .fees_pricing_rules .tab-content' ).removeClass( 'current' );
			jQuery( '#' + acc_id ).addClass( 'current' );
		} );
	// }
	
    //move notices to top
    // if( jQuery('.notice').length > 0 ) {
    //     setTimeout(function(){
    //         jQuery('.notice').each(function(){
    //             var clone = jQuery('.notice').clone();
    //             jQuery('.notice').remove();
    //             jQuery('.all-pad').prepend(clone);
    //         });
    //     }, 100);
    // }
    // if( jQuery('#message').length > 0 ){
    //     setTimeout(function(){
    //         jQuery('#message').each(function(){
    //             var clone = jQuery('#message').clone();
    //             jQuery('#message').remove();
    //             jQuery('.all-pad').prepend(clone);
    //         });
    //     }, 100);
    // }
    

	
	
	// script for plugin rating
	jQuery(document).on('click', '.dotstore-sidebar-section .content_box .et-star-rating label', function(e){
		e.stopImmediatePropagation();
		var rurl = jQuery('#et-review-url').val();
		window.open( rurl, '_blank' );
	});

    /** tiptip js implementation */
    jQuery( '.woocommerce-help-tip' ).tipTip( {
        'attribute': 'data-tip',
        'fadeIn': 50,
        'fadeOut': 50,
        'delay': 200,
        'keepAlive': true
    } );

    //Toggle sidebar start
    var span_full = jQuery('.toggleSidebar .dashicons');
    var show_sidebar = localStorage.getItem('wcpfc-sidebar-display');
    if( ( null !== show_sidebar || undefined !== show_sidebar ) && ( 'hide' === show_sidebar ) ) {
        jQuery('.all-pad').addClass('hide-sidebar');
        span_full.removeClass('dashicons-arrow-right-alt2').addClass('dashicons-arrow-left-alt2');
    } else {
        jQuery('.all-pad').removeClass('hide-sidebar');
        span_full.removeClass('dashicons-arrow-left-alt2').addClass('dashicons-arrow-right-alt2');
    }

    jQuery(document).on( 'click', '.toggleSidebar', function(){
        jQuery('.all-pad').toggleClass('hide-sidebar');
        if( jQuery('.all-pad').hasClass('hide-sidebar') ){
            localStorage.setItem('wcpfc-sidebar-display', 'hide');
            span_full.removeClass('dashicons-arrow-right-alt2').addClass('dashicons-arrow-left-alt2');
            jQuery('.all-pad .dots-settings-right-side').css({'-webkit-transition': '.3s ease-in width', '-o-transition': '.3s ease-in width',  'transition': '.3s ease-in width'});
            jQuery('.all-pad .dots-settings-left-side').css({'-webkit-transition': '.3s ease-in width', '-o-transition': '.3s ease-in width',  'transition': '.3s ease-in width'});
            setTimeout(function() {
                jQuery('#dotsstoremain .dotstore_plugin_sidebar').css('display', 'none');
            }, 300);
        } else {
            localStorage.setItem('wcpfc-sidebar-display', 'show');
            span_full.removeClass('dashicons-arrow-left-alt2').addClass('dashicons-arrow-right-alt2');
            jQuery('.all-pad .dots-settings-right-side').css({'-webkit-transition': '.3s ease-out width', '-o-transition': '.3s ease-out width',  'transition': '.3s ease-out width'});
            jQuery('.all-pad .dots-settings-left-side').css({'-webkit-transition': '.3s ease-out width', '-o-transition': '.3s ease-out width',  'transition': '.3s ease-out width'});
            // setTimeout(function() {
                jQuery('#dotsstoremain .dotstore_plugin_sidebar').css('display', 'block');
            // }, 300);
        }
    });
    //Toggle sidebar end

    jQuery(document).on('click', '.notice-dismiss', function(){ 
        jQuery(this).parent().remove(); 
    });
} );



jQuery( window ).resize( function() {
	if ( jQuery( window ).width() <= 980 ) {
		jQuery( '.fees-pricing-rules .fees_pricing_rules .tab-content' ).click( function() {
			var acc_id = jQuery( this ).attr( 'id' );
			jQuery( '.fees-pricing-rules .fees_pricing_rules .tab-content' ).removeClass( 'current' );
			jQuery( '#' + acc_id ).addClass( 'current' );
		} );
	}
} );