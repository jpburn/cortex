<?php

class Cortex_Settings {

	//--------------------------------------------------------------------------
	// Properties
	//--------------------------------------------------------------------------

	/**
	 * The main plugin.
	 * @property plugin
	 * @since 0.1.0
	 */
	private $plugin;

	/**
	 * The name of this plugin.
	 * @property plugin_name
	 * @since 0.1.0
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 * @property plugin_version
	 * @since 0.1.0
	 */
	private $plugin_version;

	//--------------------------------------------------------------------------
	// Methods
	//--------------------------------------------------------------------------

	/**
	 * Initialize the class and set its properties.
	 * @constructor
	 * @since 0.1.0
	 */
	public function __construct($plugin, $plugin_name, $plugin_version) {
		$this->plugin = $plugin;
		$this->plugin_name = $plugin_name;
		$this->plugin_version = $plugin_version;
	}

	/**
	 * Register a setting.
	 * @method register
	 * @since 0.1.0
	 */
	public function register($name) {
		register_setting('cortex', $name);
	}

	/**
	 * Registers a setting group.
	 * @method register
	 * @since 0.1.0
	 */
	public function register_group($name, $title) {
		add_settings_section($name, $title, array($this, 'group_' . $name), 'cortex');
	}

	/**
	 * Registers a setting field.
	 * @method register_field
	 * @since 0.1.0
	 */
	public function register_field($name, $title, $group) {
		add_settings_field($name, $title, array($this, 'field_' . $name), 'cortex', $group);
	}

	/**
	 * Register the plugin settings.
	 * @method setup
	 * @since 0.1.0
	 */
	public function setup() {
		$this->register('cortex_post_types');
		$this->register('cortex_block_status');
		$this->register('cortex_style_include_path');
		//$this->register_group('licence_key', __('Licence Key', 'cortex'));
		//$this->register_field('licence_key', __('Licence Key', 'cortex'), 'licence_key');
		$this->register_group('post_types', __('Post Types', 'cortex'));
		$this->register_field('post_types', __('Post Types', 'cortex'), 'post_types');
		$this->register_group('block_status', __('Blocks', 'cortex'));
		$this->register_field('block_status', __('Blocks', 'cortex'), 'block_status');
		$this->register_group('style_include_path', __('Styles Include Path', 'cortex'));
		$this->register_field('style_include_path', __('Styles Include Path', 'cortex'), 'style_include_path');
	}

	/**
	 * @method group_licence_key
	 * @since 0.1.0
	 * @hidden
	 */
	public function group_licence_key($args) {
		Cortex::render('settings/cortex-licence-key-group.twig');
	}

	/**
	 * @method field_licence_key
	 * @since 0.1.0
	 * @hidden
	 */
	public function field_licence_key($args) {
		Cortex::render('settings/cortex-licence-key-field.twig');
	}

	/**
	 * @method group_post_types
	 * @since 0.1.0
	 * @hidden
	 */
	public function group_post_types($args) {
		Cortex::render('settings/cortex-post-types-group.twig');
	}

	/**
	 * @method field_post_types
	 * @since 0.1.0
	 * @hidden
	 */
	public function field_post_types($args) {

		$option = get_option('cortex_post_types');

		if ($option == false) {
			$option = array('page' => 1);
		}

		$values = array();

		foreach (get_post_types(array(), 'objects') as $post_type) {

			if ($post_type->public == false) {
				continue;
			}

			$name = $post_type->label;
			$slug = $post_type->name;

			$checked = $option == false || (isset($option[$slug]) && $option[$slug] == 1);

			$values[] = array(
				'name' => $name,
				'slug' => $slug,
				'checked' => $checked
			);
		}

		Cortex::render('settings/cortex-post-types-field.twig', array('values' => $values));
	}

	/**
	 * @method group_block_status
	 * @since 0.1.0
	 * @hidden
	 */
	public function group_block_status($args) {
		Cortex::render('settings/cortex-block-status-group.twig');
	}

	/**
	 * @method field_post_types
	 * @since 0.1.0
	 * @hidden
	 */
	public function field_block_status($args) {

		$option = get_option('cortex_block_status');

		$values = array();

		foreach (Cortex::get_block_templates() as $block_template) {

			$name = $block_template->get_name();
			$slug = $block_template->get_guid();

			$enabled = $option === false || !(isset($option[$slug])) || $option[$slug] == 'enabled';

			$values[] = array(
				'name' => $name,
				'slug' => $slug,
				'enabled' => $enabled
			);
		}

		Cortex::render('settings/cortex-block-status-field.twig', array('values' => $values));
	}

	/**
	 * @method group_style_include_path
	 * @since 0.1.0
	 * @hidden
	 */
	public function group_style_include_path($args) {
		Cortex::render('settings/cortex-style-include-path-group.twig');
	}

	/**
	 * @method field_style_include_path
	 * @since 0.1.0
	 * @hidden
	 */
	public function field_style_include_path($args) {
		Cortex::render('settings/cortex-style-include-path-field.twig', array('value' => get_option('cortex_style_include_path')));
	}
}