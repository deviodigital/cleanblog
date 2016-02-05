<?php

/**
 * Install GitHub Updater
 * Derived from WP Install Dependencies
 * <https://github.com/afragen/wp-install-dependencies>
 *
 * @author    Matt Gibbs
 * @license   GPL-2.0+
 * @link      https://github.com/mgibbs189/install-github-updater
 */

defined( 'ABSPATH' ) or exit;

if ( ! class_exists( 'Install_GitHub_Updater' ) ) {

    class Install_GitHub_Updater
    {

        public $message = array();
        public $slug = 'github-updater/github-updater.php';
        public $zip = 'https://github.com/afragen/github-updater/archive/master.zip';


        function __construct() {
            add_action( 'admin_init', array( $this, 'admin_init' ) );
            add_action( 'admin_footer', array( $this, 'admin_footer' ) );
            add_action( 'admin_notices', array( $this, 'admin_notices' ) );
            add_action( 'network_admin_notices', array( $this, 'admin_notices' ) );
            add_action( 'wp_ajax_github_updater', array( $this, 'ajax_router' ) );
        }


        /**
         * Determine if GHU is active or installed
         */
        function admin_init() {
            if ( get_transient( 'github_updater_dismiss_notice' ) ) {
                return;
            }

            if ( $this->is_installed() ) {
                if ( ! is_plugin_active( $this->slug ) ) {
                    $this->message = array( 'action' => 'activate', 'text' => __( 'Please activate the GitHub Updater plugin.' ) );
                }
            }
            else {
                $this->message = array( 'action' => 'install', 'text' => __( 'The GitHub Updater plugin is required.' ) );
            }
        }


        /**
         * Register jQuery AJAX
         */
        function admin_footer() {
        ?>
        <script>
        (function($) {
            $(function() {
                $(document).on('click', '.ghu-button', function() {
                    var $this = $(this);
                    $('.github-updater p').html('Running...');
                    $.post(ajaxurl, {
                        action: 'github_updater',
                        method: $this.attr('data-action')
                    }, function(response) {
                        $('.github-updater p').html(response);
                    });
                });

                $(document).on('click', '.github-updater .notice-dismiss', function() {
                    $.post(ajaxurl, {
                        action: 'github_updater',
                        method: 'dismiss'
                    });
                });
            });
        })(jQuery);
        </script>
        <?php
        }


        /**
         * AJAX router
         */
        function ajax_router() {
            $method = isset( $_POST['method'] ) ? $_POST['method'] : '';
            $whitelist = array( 'install', 'activate', 'dismiss' );

            if ( in_array( $method, $whitelist ) ) {
                $response = $this->$method();
                echo $response['message'];
            }

            wp_die();
        }


        /**
         * Is GHU installed?
         */
        function is_installed() {
            $plugins = get_plugins();
            return isset( $plugins[ $this->slug ] );
        }


        /**
         * Install GHU
         */
        function install() {
            add_filter( 'upgrader_source_selection', array( $this, 'upgrader_source_selection' ), 10, 2 );

            $skin = new IGU_Plugin_Installer_Skin( array(
                'type'      => 'plugin',
                'nonce'     => wp_nonce_url( $this->zip ),
            ) );

            $upgrader = new Plugin_Upgrader( $skin );
            $result = $upgrader->install( $this->zip );

            if ( is_wp_error( $result ) ) {
                return array( 'status' => 'error', 'message' => $result->get_error_message() );
            }

            wp_cache_flush();

            $result = $this->activate();

            if ( 'error' == $result['status'] ) {
                return $result;
            }

            return array( 'status' => 'ok', 'message' => __( 'GitHub Updater has been installed and activated.' ) );
        }


        /**
         * Rename the plugin folder to "github-updater"
         */
        function upgrader_source_selection( $source, $remote_source ) {
            global $wp_filesystem;
            $new_source = trailingslashit( $remote_source ) . dirname( $this->slug );
            $wp_filesystem->move( $source, $new_source );
            return trailingslashit( $new_source );
        }


        /**
         * Activate GHU
         */
        function activate() {
            $result = activate_plugin( $this->slug );

            if ( is_wp_error( $result ) ) {
                return array( 'status' => 'error', 'message' => $result->get_error_message() );
            }

            return array( 'status' => 'ok', 'message' => __( 'GitHub Updater has been activated.' ) );
        }


        /**
         * Dismiss admin notice for a week
         */
        function dismiss() {
            set_transient( 'github_updater_dismiss_notice', 'yes', ( 60 * 60 * 24 * 7 ) );
            return array( 'status' => 'ok', 'message' => '' );
        }


        /**
         * Display admin notices / action links
         */
        function admin_notices() {
            if ( ! empty( $this->message ) ) {
                $action = $this->message['action'];
                $notice = $this->message['text'];
                $notice .= ' <a href="javascript:;" class="ghu-button" data-action="' . $action . '">' . ucfirst( $action ) . ' Now &raquo;</a>';
        ?>
            <div class="updated notice is-dismissible github-updater">
                <p><?php echo $notice; ?></p>
            </div>
        <?php
            }
        }
    }


    require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';


    /**
     * Override Plugin_Installer_Skin to disable automatic output
     */
    class IGU_Plugin_Installer_Skin extends Plugin_Installer_Skin
    {
        public function header() {}
        public function footer() {}
        public function error( $errors ) {}
        public function feedback( $string ) {}
    }


    new Install_GitHub_Updater();
}
