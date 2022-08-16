<?php
/**
 * Plugin Name:     ACF Social Media
 * Plugin URI:      https://gitlab.ledevsimple.ca/wordpress/plugins/acf-socialmedia
 * Description:     Social Media field for Advanced Custom Field v5.
 * Author:          Pascal Martineau <pascal@lewebsimple.ca>
 * Author URI:      https://lewebsimple.ca
 * License:         GPLv2 or later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     acf-socialmedia
 * Domain Path:     /languages
 * Version:         1.1.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'acf_plugin_socialmedia' ) ) :

	class acf_plugin_socialmedia {

		static $social_medias = array(
			'bandcamp'    => 'bandcamp.com',
			'facebook'    => 'facebook.com',
			'flickr'      => 'flickr.com',
			'github'      => 'github.com',
			'google-plus' => 'plus.google.com',
			'instagram'   => 'instagram.com',
			'linkedin'    => 'linkedin.com',
			'medium'      => 'medium.com',
			'pinterest'   => 'pinterest.com',
			'reddit'      => 'reddit.com',
			'soundcloud'  => 'soundcloud.com',
			'tiktok'       => 'tiktok.com',
			'tumblr'      => 'tumblr.com',
			'twitter'     => 'twitter.com',
			'vimeo'       => 'vimeo.com',
			'vine'        => 'vine.co',
			'youtube'     => 'youtube.com',
		);

		function __construct() {
			$this->settings = array(
				'version' => '1.1.1',
				'url'     => plugin_dir_url( __FILE__ ),
				'path'    => plugin_dir_path( __FILE__ )
			);
			add_action( 'acf/include_field_types', array( $this, 'include_field_types' ) );
		}

		function include_field_types( $version = false ) {
			load_plugin_textdomain( 'acf-socialmedia', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
			include_once( 'fields/acf-socialmedia-v5.php' );
		}

		// Helper: Determine social media from URL
		static function get_social_media( $url ) {
			foreach ( self::$social_medias as $key => $social_media ) {
				if ( strpos( $url, $social_media ) !== false ) {
					return $key;
				}
			}

			return false;
		}

	}

	new acf_plugin_socialmedia();

endif;
