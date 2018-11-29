<?php
/**
 * Plugin Name:     ACF Sovial Media
 * Plugin URI:      https://github.com/lewebsimple/acf-socialmedia
 * Description:     Social Media field for Advanced Custom Field v5.
 * Author:          Pascal Martineau <pascal@lewebsimple.ca>
 * Author URI:      https://lewebsimple.ca
 * License:         GPLv2 or later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     acf-socialmedia
 * Domain Path:     /languages
 * Version:         1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'acf_plugin_socialmedia' ) ) :

	class acf_plugin_socialmedia {

		function __construct() {
			$this->settings = array(
				'version' => '1.0.0',
				'url'     => plugin_dir_url( __FILE__ ),
				'path'    => plugin_dir_path( __FILE__ )
			);
			add_action( 'acf/include_field_types', array( $this, 'include_field_types' ) );
		}

		function include_field_types( $version = false ) {
			load_plugin_textdomain( 'acf-socialmedia', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
			include_once( 'fields/acf-socialmedia-v5.php' );
		}

	}

	new acf_plugin_socialmedia();

endif;
