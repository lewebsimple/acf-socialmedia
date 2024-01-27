<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class lws_acf_field_socialmedia extends \acf_field {
	/**
	 * Controls field type visibilty in REST requests.
	 *
	 * @var bool
	 */
	public $show_in_rest = true;

	/**
	 * Environment values relating to the theme or plugin.
	 *
	 * @var array $env Plugin or theme context such as 'url' and 'version'.
	 */
	private $env;

	/**
	 * Constructor.
	 */
	public function __construct() {
		/**
		 * Field type reference used in PHP and JS code.
		 * No spaces. Underscores allowed.
		 */
		$this->name = 'socialmedia';

		/**
		 * Field type label.
		 */
		$this->label = __( 'Social Media', 'acf-socialmedia' );

		/**
		 * The category the field appears within in the field type picker.
		 */
		$this->category = 'basic'; // basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME

		/**
		 * Field type Description.
		 */
		$this->description = __( 'Social Media field for ACF', 'acf-socialmedia' );

		/**
		 * Field type Doc URL.
		 *
		 * For linking to a documentation page. Displayed in the field picker modal.
		 */
		$this->doc_url = '';

		/**
		 * Field type Tutorial URL.
		 *
		 * For linking to a tutorial resource. Displayed in the field picker modal.
		 */
		$this->tutorial_url = '';

		/**
		 * Defaults for your custom user-facing settings for this field type.
		 */
		$this->defaults = array(
			'return_format' => 'url',
		);

		/**
		 * Strings used in JavaScript code.
		 *
		 * Allows JS strings to be translated in PHP and loaded in JS via:
		 *
		 * ```js
		 * const errorMessage = acf._e("socialmedia", "error");
		 * ```
		 */
		$this->l10n = array();

		$this->env = array(
			'url'     => site_url( str_replace( ABSPATH, '', __DIR__ ) ),
			'version' => '2.0.0',
		);

		parent::__construct();
	}

	/**
	 * Settings to display when users configure a field of this type.
	 *
	 * These settings appear on the ACF “Edit Field Group” admin page when
	 * setting up the field.
	 *
	 * @param array $field
	 * @return void
	 */
	public function render_field_settings( $field ) {
		// Return format
		acf_render_field_setting(
			$field,
			array(
				'label'        => __( 'Return format', 'acf-socialmedia' ),
				'instructions' => __( 'Specify the return format used in the templates', 'acf-socialmedia' ),
				'type'         => 'select',
				'name'         => 'return_format',
				'choices'      => array(
					'icon'  => __( "Icon", 'acf-socialmedia' ),
					'url'   => __( "URL", 'acf-socialmedia' ),
					'array' => __( "Values (array)", 'acf-socialmedia' ),
				),
			)
		);
	}

	/**
	 * HTML content to show when a publisher edits the field on the edit screen.
	 *
	 * @param array $field The field settings and values.
	 * @return void
	 */
	public function render_field( $field ) {
		?>
		<div class="acf-input-wrap acf-socialmedia">
				<i class="acf-icon -globe -small"></i>
				<input type="url" name="<?= $field['name'] ?>" value="<?= $field['value'] ?>"/>
		</div>
		<?php
	}

	/**
	 * Enqueues CSS and JavaScript needed by HTML in the render_field() method.
	 *
	 * Callback for admin_enqueue_script.
	 *
	 * @return void
	 */
	public function input_admin_enqueue_scripts() {
		$url     = trailingslashit( $this->env['url'] );
		$version = $this->env['version'];

		wp_register_style( 'acf-socialmedia', "{$url}assets/css/acf-socialmedia.css", array( 'acf-input' ), $version );
		wp_enqueue_style( 'acf-socialmedia' );
	}

	/**
	 * Validate socialmedia value
	 *
	 * @param $valid (boolean) validation status based on the value and the field's required setting
	 * @param value (mixed) the                                                                     $_POST value
	 * @param $field (array) the field array holding all the field options
	 * @param input (string) the corresponding input name for                                       $_POST value
	 *
	 * @return mixed
	 */
	function validate_value( $valid, $value, $field, $input ) {
		if ( empty( $value ) ) {
			return $valid;
		}
		if ( !acf_socialmedia_plugin::get_social_media( $value ) ) {
			$valid = __( 'Invalid social media URL', 'acf-socialmedia' );
		}
		return $valid;
	}

	/**
	 * Format full name value according to field settings
	 *
	 * @param  $value (mixed) the value which was loaded from the database
	 * @param  post_id (mixed) the                                         $post_id from which the value was loaded
	 * @param  $field (array) the field array holding all the field options
	 *
	 * @return $value (mixed) the formatted value
	 */
	function format_value( $value, $post_id, $field ) {
		return acf_socialmedia_plugin::format_value( $value, $field['return_format'] ?? 'national' );
	}
}
