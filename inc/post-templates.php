<?php
/**
 * Plugin Name: Single Post Template
 * Plugin URI: http://www.nathanrice.net/plugins
 *
 * Description: This plugin allows theme authors to include single post templates, much like a theme author can use page template files.
 *
 * Author: Nathan Rice
 * Author URI: http://www.nathanrice.net/
 *
 * Version: 1.4.4
 *
 * License: GNU General Public License v2.0
 * License URI: http://www.opensource.org/licenses/gpl-license.php
 *
 * Last Edited: 02.10.16
 * Edited by: Robert DeVore
 * Editor URI: http://www.robertdevore.com/
 *
 * @package SinglePostTemplate
 */

/**
 * Single Post Template Plugin
 */
class Single_Post_Template_Plugin {

	/**
	 * Function __construct()
	 */
	function __construct() {

		add_action( 'admin_menu', array( $this, 'add_metabox' ) );
		add_action( 'save_post', array( $this, 'metabox_save' ), 1, 2 );

		add_filter( 'single_template', array( $this, 'get_post_template' ) );

	}

	/**
	 * Function get_post_template()
	 */
	function get_post_template( $template ) {

		global $post;

		$custom_field = get_post_meta( $post->ID, '_wp_post_template', true );

		if ( ! $custom_field ) {
			return $template;
		}

		/** Prevent directory traversal */
		$custom_field = str_replace( '..', '', $custom_field );

		if ( file_exists( get_stylesheet_directory() . "/{$custom_field}" ) ) {
			$template = get_stylesheet_directory() . "/{$custom_field}";
		} elseif ( file_exists( get_template_directory() . "/{$custom_field}" ) ) {
			$template = get_template_directory() . "/{$custom_field}";
		}

		return $template;

	}

	function get_post_templates() {

		$templates = wp_get_theme()->get_files( 'php', 1 );
		$post_templates = array();

		$base = array( trailingslashit( get_template_directory() ), trailingslashit( get_stylesheet_directory() ) );

		foreach ( $templates as $file => $full_path ) {

			if ( ! preg_match( '|Single Post Template:(.*)$|mi', file_get_contents( $full_path ), $header ) ) {
				continue;
			}

			$post_templates[ $file ] = _cleanup_header_comment( $header[1] );

		}

		return $post_templates;

	}

	function post_templates_dropdown() {

		global $post;

		$post_templates = $this->get_post_templates();

		/** Loop through templates, make them options */
		foreach ( (array) $post_templates as $template_file => $template_name ) {
			$opt = '<option value="' . esc_attr( $template_file ) . '"' . $selected . '>' . esc_html( $template_name ) . '</option>';
			echo $opt;
		}

	}

	function add_metabox() {

		if ( $this->get_post_templates() ) {
			add_meta_box( 'pt_post_templates', __( 'Single Post Template', 'cleanblog' ), array( $this, 'metabox' ), 'post', 'normal', 'high' );
		}

	}

	function metabox( $post ) {

		?>
		<input type="hidden" name="pt_noncename" id="pt_noncename" value="<?php echo wp_create_nonce( plugin_basename( __FILE__ ) ); ?>" />

		<label class="hidden" for="post_template"><?php  esc_html_e( 'Post Template', 'cleanblog' ); ?></label><br />
		<select name="_wp_post_template" id="post_template" class="dropdown">
			<option value=""><?php esc_html_e( 'Default', 'cleanblog' ); ?></option>
			<?php $this->post_templates_dropdown(); ?>
		</select>
		<?php

	}

	function metabox_save( $post_id, $post ) {
		/*
		 * Verify this came from the our screen and with proper authorization,
		 * because save_post can be triggered at other times
		 */
		if ( ! wp_verify_nonce( $_POST['pt_noncename'], plugin_basename( __FILE__ ) ) ) {
			return $post->ID;
		}

		/** Is the user allowed to edit the post or page? */
		if ( 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post->ID ) ) {
				return $post->ID;
			}
		} else {
			if ( ! current_user_can( 'edit_post', $post->ID ) ) {
				return $post->ID;
			}
		}

		/** OK, we're authenticated: we need to find and save the data */

		/** Put the data into an array to make it easier to loop though and save */
		$mydata['_wp_post_template'] = $_POST['_wp_post_template'];

		/** Add values of $mydata as custom fields */
		foreach ( $mydata as $key => $value ) {
			/** Don't store custom data twice */
			if ( 'revision' == $post->post_type ) {
				return;
			}

			/** If $value is an array, make it a CSV (unlikely) */
			$value = implode( ',', (array) $value );

			/** Update the data if it exists, or add it if it doesn't */
			if ( get_post_meta( $post->ID, $key, false ) ) {
				update_post_meta( $post->ID, $key, $value );
			} else {
				add_post_meta( $post->ID, $key, $value );
			}

			/** Delete if blank */
			if ( ! $value ) {
				delete_post_meta( $post->ID, $key );
			}
		}
	}
}

add_action( 'after_setup_theme', 'post_templates_plugin_init' );
/**
 * Instantiate the class after theme has been set up.
 */
function post_templates_plugin_init() {
	new Single_Post_Template_Plugin;
}
