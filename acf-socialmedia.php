<?php
/*
Plugin Name: Advanced Custom Fields: Social Media
Plugin URI: https://gitlab.ledevsimple.ca/wordpress/acf-socialmedia
Description: Social Media field for ACF.
Version: 0.2.0
Author: Pascal Martineau <pascal@lewebsimple.ca>
Author URI: https://lewebsimple.ca
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'acf_plugin_socialmedia' ) ) :

	class acf_plugin_socialmedia {

		function __construct() {
			$this->settings = array(
				'version' => '0.2.0',
				'url'     => plugin_dir_url( __FILE__ ),
				'path'    => plugin_dir_path( __FILE__ )
			);

			load_plugin_textdomain( 'acf-socialmedia', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

			add_action( 'acf/include_field_types', array( $this, 'include_field_types' ) );
		}

		function include_field_types( $version = false ) {
			if ( ! $version ) {
				$version = 5;
			}
			include_once( 'fields/acf-socialmedia-v' . $version . '.php' );
		}

	}

	new acf_plugin_socialmedia();

endif;
