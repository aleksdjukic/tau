
jQuery( function ( $ ) {
	'use strict' ;

	try {

		$( document.body ).on( 'pdr-jcrop-init' , function () {
			var crop_image = $( '.pdr-crop-image' ) ;

			crop_image.each( function () {

				// To avoid jcrop for without source of image.
				if ( '' == $( this ).attr( 'src' ) ) {
					return true ;
				}

				var event = this ;

				var container = $( event ).closest( '.pdr-upload-img-container' ) ,
						factor = container.find( '.pdr-upload-img-factor' ) ,
						top = container.find( '.pdr-upload-img-top' ) ,
						left = container.find( '.pdr-upload-img-left' ) ,
						width = container.find( '.pdr-upload-img-width' ) ,
						height = container.find( '.pdr-upload-img-height' ) ,
						imgx = container.find( '.pdr-upload-original-img-top' ) ,
						imgy = container.find( '.pdr-upload-original-img-left' ) ,
						imgw = container.find( '.pdr-upload-original-img-width' ) ,
						imgh = container.find( '.pdr-upload-original-img-height' ) ;

				// To avoid multiple jcrop for same element.
				if ( container.find( 'div.jcrop-stage' ).length ) {
					return true ;
				}




				// Attached the element in Jcrop.         
				var stage = Jcrop.attach( event ) ;
				//                console.log( event ) ;
				//                console.log( stage ) ;
				//return;
				// Create a Rectangle widget with specific coordinates.
				var rect = Jcrop.Rect.create( top.val() , left.val() , width.val() , height.val() ) ;
				// Add a widget in Jcrop.
				stage.newWidget( rect.scale( stage.scalex , stage.scaley ) , { } ) ;
				//console.log(imgw.val(),imgh.val());

				var ratio = calculateAspectRatioFit( imgw.val() , imgh.val() , event.width , event.height ) ;
				var original_image = getoriginalimage( stage.active.pos.x , stage.active.pos.y , stage.active.pos.w , stage.active.pos.h , ratio.aspectratio ) ;
				factor.val( ratio.aspectratio ) ;
				imgx.val( original_image.x ) ;
				imgy.val( original_image.y ) ;
				imgw.val( imgw.val() ) ;
				imgh.val( imgh.val() ) ;

				stage.listen( 'crop.change' , ( widget , e ) => {
					factor.val( ratio.aspectratio ) ;
					top.val( widget.pos.x ) ;
					left.val( widget.pos.y ) ;
					width.val( widget.pos.w ) ;
					height.val( widget.pos.h ) ;

					var original_image = getoriginalimage( widget.pos.x , widget.pos.y , widget.pos.w , widget.pos.h , ratio.aspectratio ) ;

					imgx.val( original_image.x ) ;
					imgy.val( original_image.y ) ;
					imgw.val( imgw.val() ) ;
					imgh.val( imgh.val() ) ;
				} ) ;



			} ) ;

			function calculateAspectRatioFit( srcWidth , srcHeight , maxWidth , maxHeight ) {
				var ratio = Math.min( maxWidth / srcWidth ) ;

				return {
					width : srcWidth * ratio ,
					height : srcHeight * ratio ,
					aspectratio : ratio
				} ;
			}

			function getoriginalimage( cimgw , cimgh , x , y , ratio ) {
				return {
					w : cimgw * ( 1 / ratio ) ,
					h : cimgh * ( 1 / ratio ) ,
					x : x * ( 1 / ratio ) ,
					y : y * ( 1 / ratio )
				}
			}

		} ) ;

		$( document.body ).trigger( 'pdr-jcrop-init' ) ;
	} catch ( err ) {
		window.console.log( err ) ;
	}

} ) ;
