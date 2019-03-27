<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'acf_field_socialmedia' ) ) :

	class acf_field_socialmedia extends acf_field {

		public $settings;

		function __construct( $settings ) {
			$this->name     = 'socialmedia';
			$this->label    = __( 'Social Media', 'acf-socialmedia' );
			$this->category = 'basic';
			$this->defaults = array(
				'return_format' => 'url',
			);
			$this->settings = $settings;
			parent::__construct();
		}

		function render_field_settings( $field ) {
			// Return Format
			acf_render_field_setting( $field, array(
				'label'        => __( 'Return Format', 'acf-socialmedia' ),
				'instructions' => __( 'Specify the value returned in the template.', 'acf-socialmedia' ),
				'type'         => 'select',
				'choices'      => array(
					'icon'  => __( "Icon", 'acf-socialmedia' ),
					'url'   => __( "URL", 'acf-socialmedia' ),
					'array' => __( "Values (array)", 'acf-socialmedia' ),
				),
				'name'         => 'return_format',
			) );
		}

		function render_field( $field ) {
			?>
            <div class="acf-input-wrap acf-socialmedia">
                <i class="acf-icon -globe -small"></i>
                <input type="url" name="<?= $field['name'] ?>" value="<?= $field['value'] ?>"/>
            </div>
			<?php
		}

		function input_admin_enqueue_scripts() {
			$url     = $this->settings['url'];
			$version = $this->settings['version'];

			wp_register_style( 'acf-socialmedia', "{$url}assets/css/acf-socialmedia.css", array( 'acf-input' ), $version );
			wp_enqueue_style( 'acf-socialmedia' );
		}

		function format_value( $value, $post_id, $field ) {
			if ( empty( $value ) ) {
				return $value;
			}

			$social_media = acf_plugin_socialmedia::get_social_media( $value );

			switch ( $field['return_format'] ) {
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

			if ( acf_plugin_socialmedia::get_social_media( $value ) ) {
				return $valid;
			}

			return __( "Unrecognized social media URL.", 'acf-socialmedia' );
		}

	}

	new acf_field_socialmedia( $this->settings );

endif;
