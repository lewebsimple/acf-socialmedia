<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'acf_field_socialmedia' ) ) :

	class acf_field_socialmedia extends acf_field {

		public $settings;

		public $social_medias;

		/**
		 * acf_field_socialmedia constructor.
		 *
		 * This function will setup the field type data
		 *
		 * @param $settings (array) The plugin settings
		 */
		function __construct( $settings ) {
			$this->name          = 'socialmedia';
			$this->label         = __( 'Social Media', 'acf-socialmedia' );
			$this->category      = 'basic';
			$this->defaults      = array(
				'return_format' => 'icon',
			);
			$this->settings      = $settings;
			$this->social_medias = array(
				'bandcamp'    => 'bandcamp.com',
				'facebook'    => 'https://www.facebook.com',
				'flickr'      => 'https://www.flickr.com',
				'github'      => 'https://github.com',
				'google-plus' => 'https://plus.google.com',
				'instagram'   => 'https://www.instagram.com',
				'linkedin'    => 'https://www.linkedin.com',
				'medium'      => 'https://medium.com',
				'pinterest'   => 'https://www.pinterest.com',
				'reddit'      => 'https://www.reddit.com',
				'soundcloud'  => 'https://soundcloud.com',
				'tumblr'      => 'tumblr.com',
				'twitter'     => 'https://twitter.com',
				'vine'        => 'https://vine.co',
				'youtube'     => 'https://www.youtube.com',
			);
			parent::__construct();
		}

		/**
		 * Create extra settings for your field. These are visible when editing a field
		 *
		 * @param $field (array) the $field being edited
		 */
		function render_field_settings( $field ) {
			// Return Format
			acf_render_field_setting( $field, array(
				'label'        => __( 'Return Format', 'acf-socialmedia' ),
				'instructions' => __( 'Specify the value returned in the template.', 'acf-socialmedia' ),
				'type'         => 'select',
				'choices'      => array(
					'icon'  => __( "Icon", 'acf-socialmedia' ),
					'array' => __( "Values (array)", 'acf-socialmedia' ),
				),
				'name'         => 'return_format',
			) );
		}

		/**
		 * Create the HTML interface for your field
		 *
		 * @param $field (array) the $field being rendered
		 */
		function render_field( $field ) {
			?>
            <div class="acf-input-wrap acf-socialmedia">
                <i class="acf-icon -globe -small"></i>
                <input type="text" name="<?= $field['name'] ?>" value="<?= $field['value'] ?>"/>
            </div>
			<?php
		}

		/**
		 *  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
		 *  Use this action to add CSS + JavaScript to assist your render_field() action.
		 */
		function input_admin_enqueue_scripts() {
			$url     = $this->settings['url'];
			$version = $this->settings['version'];
			wp_register_script( 'acf-input-socialmedia', "{$url}assets/js/input.js", array( 'acf-input' ), $version );
			wp_enqueue_script( 'acf-input-socialmedia' );

			wp_register_style( 'acf-socialmedia', "{$url}assets/css/acf-socialmedia.css", array( 'acf-input' ), $version );
			wp_enqueue_style( 'acf-socialmedia' );
		}

		/**
		 * This filter is appied to the $value after it is loaded from the db and before it is returned to the template
		 *
		 * @param  $value (mixed) the value which was loaded from the database
		 * @param  $post_id (mixed) the $post_id from which the value was loaded
		 * @param  $field (array) the field array holding all the field options
		 *
		 * @return $value (mixed) the formatted value
		 */
		function format_value( $value, $post_id, $field ) {
			if ( empty( $value ) ) {
				return $value;
			}

			$social_media = $this->get_social_media( $value );
			if ( $social_media === false ) {
				return '';
			}

			switch ( $field['return_format'] ) {
				case 'icon':
					$output = '<a href="' . $value . '" target="_blank">';
					$output .= '<i class="fa fa-' . $social_media . '" ></i>';
					$output .= '</a>';
					break;

				case 'array':
				default:
					$output = array(
						$social_media => $value,
					);
					break;
			}

			return $output;
		}

		/**
		 *  This filter is used to perform validation on the value prior to saving.
		 *  All values are validated regardless of the field's required setting. This allows you to validate and return
		 *  messages to the user if the value is not correct
		 *
		 * @param  $valid (boolean) validation status based on the value and the field's required setting
		 * @param  $value (mixed) the $_POST value
		 * @param  $field (array) the field array holding all the field options
		 * @param  $input (string) the corresponding input name for $_POST value
		 *
		 * @return $valid
		 */
		function validate_value( $valid, $value, $field, $input ) {
			if ( empty( $value ) ) {
				return $valid;
			}

			if ( strpos( $value, 'https://' ) === false ) {
				return __( "Invalid social media URL.", 'acf-socialmedia' );
			}

			if ( $this->get_social_media( $value ) ) {
				return $valid;
			}

			return __( "Unrecognized social media URL.", 'acf-socialmedia' );
		}

		/**
		 * Determine the social media from URL value.
		 *
		 * @param string $url Social media URL.
		 *
		 * @return bool|string
		 * Returns the social media key (i.e. 'facebook') if the url is valid.
		 * Returns false otherwise.
		 */
		function get_social_media( $url ) {
			foreach ( $this->social_medias as $key => $social_media ) {
				if ( strpos( $url, $social_media ) !== false ) {
					return $key;
				}
			}

			return false;
		}

	}

	new acf_field_socialmedia( $this->settings );

endif;
