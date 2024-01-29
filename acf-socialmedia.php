<?php
/**
 * Plugin Name:     ACF Social Media
 * Plugin URI:      https://github.com/lewebsimple/acf-socialmedia
 * Description:     Social Media field for Advanced Custom Fields.
 * Author:          Pascal Martineau <pascal@lewebsimple.ca>
 * Author URI:      https://websimple.com
 * License:         GPLv2 or later
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     acf-socialmedia
 * Domain Path:     /languages
 * Version:         2.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'lws_include_acf_field_socialmedia' );
/**
 * Registers the ACF field type.
 */
function lws_include_acf_field_socialmedia() {
	if ( ! function_exists( 'acf_register_field_type' ) ) {
		return;
	}

	load_plugin_textdomain( 'acf-socialmedia', false, plugin_basename( __DIR__ ) . '/languages' );
	require_once __DIR__ . '/class-lws-acf-field-socialmedia.php';

	acf_register_field_type( 'lws_acf_field_socialmedia' );
}

/**
 * Legacy support for ACF Social Media 1.x
 */
class acf_socialmedia_plugin {

	static private $social_medias = array(
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
		'tiktok'      => 'tiktok.com',
		'tumblr'      => 'tumblr.com',
		'twitter'     => 'twitter.com',
		'vimeo'       => 'vimeo.com',
		'vine'        => 'vine.co',
		'youtube'     => 'youtube.com',
	);

	/**
	 * Helper for displaying acf-socialmedia field value in different formats
	 *
	 * @param array  $value the raw socialmedia value
	 * @param string $format the desired format
	 *
	 * @return mixed the formatted value
	 */
	static function format_value( $value, $format ) {
		if ( empty( $value ) ) {
			return $value;
		}
		$social_media = acf_socialmedia_plugin::get_social_media( $value );
		switch ( $format ) {
			case 'icon':
				ob_start();
				?>
				<a href="<?= $value ?>" target="_blank" class="acf-socialmedia-icon">
					<i class="fab fa-<?= $social_media ?>"></i>
				</a>
				<?php
				return ob_get_clean();

			case 'array':
				return array(
					'url'          => $value,
					'social_media' => $social_media,
				);

			case 'url':
			default:
				return $value;
		}
	}

	/**
	 * Helper for getting social media from URL
	 * 
	 * @param string $url the URL to check
	 * 
	 * @return string|false
	 */
	static function get_social_media( $url ) {
		foreach ( self::$social_medias as $key => $social_media ) {
			if ( strpos( $url, $social_media ) !== false ) {
				return $key;
			}
		}
		return false;
	}
}
