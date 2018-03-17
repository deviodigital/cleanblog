/**
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

(function( $ ) {
	"use strict";

	var sFont;

	/* Link hover color */
	wp.customize( 'cleanblog_link_color', function( value ) {
		value.bind( function( to ) {
			$( 'a:hover' ).css( 'color', to );
		} );
	});

	/* Social icons */
	var socialNetworks = [
		'titter',
		'facebook',
		'google',
		'pinterest',
		'linkedin',
		'github',
		'instagram',
		'flickr',
		'medium',
		'vine',
		'tumblr',
		'youtube',
	];
	socialNetworks.forEach(function (network) {
		wp.customize('cleanblog_social_' + network, function (value) {
			value.bind(function (to) {
				$('#social-' + network).text(to);
			});
		});
	});

	/* Post excerpt */
	wp.customize( 'cleanblog_post_excerpt', function( value ) {
		value.bind( function( to ) {

			if ( 'hide' === to ) {

				$( 'p.excerpt' ).hide();

			} else {

				$( 'p.excerpt' ).show();

			} // end if/else

		});
	});

	/* Footer copyright */
	wp.customize( 'cleanblog_footer_copyright_text', function( value ) {
		value.bind( function( to ) {
			$( 'p.copyright' ).text( to );
		});
	});

	/* Home intro */

	wp.customize( 'cleanblog_homeintro_image', function( value ) {
		value.bind( function( to ) {

			0 === $.trim( to ).length ?
				$( '.intro-header' ).css( 'background-image', '' ) :
				$( '.intro-header' ).css( 'background-image', 'url( ' + to + ')' );

		});
	});

	wp.customize( 'cleanblog_homeintro_title', function( value ) {
		value.bind( function( to ) {
			$( 'h1.homeintro' ).text( to );
		});
	});

	wp.customize( 'cleanblog_homeintro_subtitle', function( value ) {
		value.bind( function( to ) {
			$( 'span.subheading' ).text( to );
		});
	});

})( jQuery );
