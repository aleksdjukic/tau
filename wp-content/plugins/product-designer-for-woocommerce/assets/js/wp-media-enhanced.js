/* global pdr_wp_media_params, ajaxurl */

jQuery( function ( $ ) {
	'use strict' ;

	// Set all varibales to be used in scope.
	var wp_frame ;
	$( 'body' ).on( 'click' , '.pdr-upload-img' , function ( e ) {

		e.preventDefault( ) ;
		var img_container = $( this ).closest( '.pdr-upload-img-container' ) ;

		//        // If the media frame already exists, reopen it.
		//        if ( wp_frame ) {
		//            wp_frame.open( ) ;
		//            return ;
		//        }

		// Create a new media frame.
		wp_frame = wp.media( {
			frame : 'select' ,
			title : pdr_admin_params.media_title ,
			multiple : false ,
			library : {
				type : 'image'
			} ,
			button : {
				text : pdr_admin_params.media_button_text
			}
		} ) ;

		// When an image is selected, run a callback.
		wp_frame.on( 'select' , function ( ) {
			// Get the attachement details from the media state.
			var attachment = wp_frame.state( ).get( 'selection' ).first( ).toJSON( ) ;

			// Send the attachment id to input hidden field.
			img_container.find( '.pdr-upload-img-id' ).val( attachment.id ) ;
			img_container.find( '.pdr-upload-img-url' ).val( attachment.url ) ;

			// Send the attachement URL to custom preview.
			var img = $( '<img />' ) ;
			img.attr( 'src' , attachment.url ) ;
			img.attr( 'class' , 'pdr-crop-image pdr-uploaded-img-preview' ) ;
			img_container.find( '.pdr-uploaded-img' ).empty( ).append( img ) ;

			img_container.find( '.pdr-delete-uploaded-img' ).show() ;
			img_container.find( '.pdr-upload-original-img-width' ).val( attachment.width ) ;
			img_container.find( '.pdr-upload-original-img-height' ).val( attachment.height ) ;


			$( document.body ).trigger( 'pdr-jcrop-init' ) ;
		} ) ;

		// Finally, open the modal.
		wp_frame.open( ) ;
	} ) ;

	$( 'body' ).on( 'click' , '.pdr-delete-uploaded-img' , function ( e ) {
		e.preventDefault( ) ;
		var img_container = $( this ).closest( '.pdr-upload-img-container' ) ;

		// Remove the attachment id and image.
		img_container.find( '.pdr-upload-img-id' ).val( '' ) ;
		img_container.find( '.pdr-uploaded-img' ).empty( ) ;
		img_container.find( '.pdr-upload-img-top' ).val( 0 ) ;
		img_container.find( '.pdr-upload-img-left' ).val( 0 ) ;
		img_container.find( '.pdr-upload-img-width' ).val( 0 ) ;
		img_container.find( '.pdr-upload-img-height' ).val( 0 ) ;

		$( this ).hide() ;
	} ) ;


	// Set all varibales to be used in scope.
	var setting_wp_frame ;
	$( 'body' ).on( 'click' , '.pdr-setting-upload-img' , function ( e ) {

		e.preventDefault( ) ;
		var img_container = $( this ).closest( '.pdr-setting-upload-img-container' ) ;

		//        // If the media frame already exists, reopen it.
		//        if ( wp_frame ) {
		//            wp_frame.open( ) ;
		//            return ;
		//        }

		// Create a new media frame.
		setting_wp_frame = wp.media( {
			frame : 'select' ,
			title : pdr_admin_params.media_title ,
			multiple : false ,
			library : {
				type : 'image'
			} ,
			button : {
				text : pdr_admin_params.media_button_text
			}
		} ) ;

		// When an image is selected, run a callback.
		setting_wp_frame.on( 'select' , function ( ) {
			// Get the attachement details from the media state.
			var attachment = setting_wp_frame.state( ).get( 'selection' ).first( ).toJSON( ) ;

			// Send the attachment url to input hidden field.
			img_container.find( '.pdr-setting-upload-img-url' ).val( attachment.url ) ;

			// Send the attachement URL to custom preview.
			var img = $( '<img />' ) ;
			img.attr( 'src' , attachment.url ) ;
			img_container.find( '.pdr-setting-uploaded-img' ).empty( ).append( img ) ;

			img_container.find( '.pdr-setting-delete-uploaded-img' ).show() ;

		} ) ;

		// Finally, open the modal.
		setting_wp_frame.open( ) ;
	} ) ;

	$( 'body' ).on( 'click' , '.pdr-setting-delete-uploaded-img' , function ( e ) {
		e.preventDefault( ) ;
		var img_container = $( this ).closest( '.pdr-setting-upload-img-container' ) ;

		// Remove the attachment url.
		img_container.find( '.pdr-setting-upload-img-url' ).val( '' ) ;
		img_container.find( '.pdr-setting-uploaded-img' ).empty( ) ;

		$( this ).hide() ;
	} ) ;

} ) ;
