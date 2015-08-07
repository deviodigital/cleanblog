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
	wp.customize( 'cleanblog_social_twitter', function( value ) {
		value.bind( function( to ) {
			$( '#social-twitter' ).text( to );
		});
	});
	wp.customize( 'cleanblog_social_facebook', function( value ) {
		value.bind( function( to ) {
			$( '#social-facebook' ).text( to );
		});
	});
	wp.customize( 'cleanblog_social_google', function( value ) {
		value.bind( function( to ) {
			$( '#social-google' ).text( to );
		});
	});
	wp.customize( 'cleanblog_social_pinterest', function( value ) {
		value.bind( function( to ) {
			$( '#social-pinterest' ).text( to );
		});
	});
	wp.customize( 'cleanblog_social_linkedin', function( value ) {
		value.bind( function( to ) {
			$( '#social-linkedin' ).text( to );
		});
	});
	wp.customize( 'cleanblog_social_github', function( value ) {
		value.bind( function( to ) {
			$( '#social-github' ).text( to );
		});
	});
	wp.customize( 'cleanblog_social_instagram', function( value ) {
		value.bind( function( to ) {
			$( '#social-instagram' ).text( to );
		});
	});
	wp.customize( 'cleanblog_social_medium', function( value ) {
		value.bind( function( to ) {
			$( '#social-medium' ).text( to );
		});
	});
	wp.customize( 'cleanblog_social_vine', function( value ) {
		value.bind( function( to ) {
			$( '#social-vine' ).text( to );
		});
	});
	wp.customize( 'cleanblog_social_tumblr', function( value ) {
		value.bind( function( to ) {
			$( '#social-tumblr' ).text( to );
		});
	});
	wp.customize( 'cleanblog_social_youtube', function( value ) {
		value.bind( function( to ) {
			$( '#social-youtube' ).text( to );
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