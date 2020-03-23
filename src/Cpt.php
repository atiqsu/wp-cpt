<?php

namespace XS\CPT;

defined('ABSPATH') || exit;

/**
 * Wordpress custom post type
 *
 * @author Md. Atiqur Rahman <atiqur.su@gmail.com>
 *
 * @package XS\CPT
 */
abstract class Cpt implements Cpt_Contract {

	private $post_type;
	private $arguments = [];

	protected $text_domain;

	protected $singular;
	protected $plural;
	protected $slug = '';


	/**
	 * Cpt constructor.
	 *
	 * @param $post_type
	 * @param $singular
	 * @param $plural
	 * @param $txt_domain
	 */
	public function __construct($post_type, $singular, $plural, $txt_domain) {

		$this->post_type = $post_type;
		$this->singular = $singular;
		$this->plural = $plural;
		$this->text_domain = $txt_domain;
	}


	/**
	 *
	 * @author Md. Atiqur Rahman <atiqur.su@gmail.com>
	 *
	 * @since 1.0.0
	 *
	 * @param array $conf
	 */
	public function register(array $conf) {

		$def = $this->get_default_args();
		$actions = isset($conf['__actions__']) ? $conf['__actions__'] : array();

		if(!empty($conf['labels'])) {

			foreach($conf['labels'] as $key => $val) {
				$def['labels'][$key] = $val;
			}
		}

		unset($conf['labels'], $conf['__actions__']);

		foreach($conf as $key => $value) {
			$def[$key] = $value; #overring with user provided settings
		}

		$this->arguments = $def;

		$this->init($actions);
	}


	private function init($actions = []) {

		#later we will add other actions here related to cpt
		#todo - add dependency injection for meta box class + custom column rendering class etc.

		add_action('init', [$this, 'register_cpt']);
	}


	/**
	 *
	 * @author Md. Atiqur Rahman <atiqur.su@gmail.com>
	 *
	 * @since 1.0.0
	 *
	 */
	private function register_cpt() {

		register_post_type($this->post_type, $this->arguments);

		flush_rewrite_rules();
	}


	/**
	 *
	 * @author Md. Atiqur Rahman <atiqur.su@gmail.com>
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	private function get_default_args() {

		$plural     = empty($this->plural) ? 'CPs' : $this->plural;
		$singular   = empty($this->singular) ? 'CPT' : $this->singular;

		$labels = array(
			'name'                     => __($plural, $this->text_domain),
			'singular_name'            => __($singular, $this->text_domain),
			'add_new'                  => __('Add New ' . $singular, $this->text_domain),
			'add_new_item'             => __('Add New ' . $singular, $this->text_domain),
			'edit_item'                => __('Edit ' . $singular, $this->text_domain),
			'new_item'                 => __('New ' . $singular, $this->text_domain),
			'view_item'                => __('View ' . $singular, $this->text_domain),
			'view_items'               => __('View ' . $plural, $this->text_domain),
			'search_items'             => __('Search ' . $plural, $this->text_domain),
			'not_found'                => __('No ' . strtolower( $plural ) . ' found.', $this->text_domain),
			'not_found_in_trash'       => __('No ' . strtolower( $plural ) . ' found in Trash.', $this->text_domain),
			'parent_item_colon'        => __('Parent ' . $singular . ':', $this->text_domain),
			'all_items'                => __('All ' . $plural, $this->text_domain),
			'archives'                 => __($singular . ' Archives', $this->text_domain),
			'attributes'               => __($singular . ' Attributes', $this->text_domain),
			'insert_into_item'         => __('Insert into ' . strtolower( $singular ), $this->text_domain),
			'uploaded_to_this_item'    => __('Uploaded to this ' . strtolower( $singular ), $this->text_domain),
			'featured_image'           => __($singular . ' Featured Image', $this->text_domain),
			'set_featured_image'       => __('Set ' . strtolower( $singular ) . ' featured image', $this->text_domain),
			'remove_featured_image'    => __('Remove ' . strtolower( $singular ) . ' featured image', $this->text_domain),
			'use_featured_image'       => __('Use as ' . strtolower( $singular ) . ' featured image', $this->text_domain),
			'filter_items_list'        => __('Filter ' . strtolower( $plural ) . ' list', $this->text_domain),
			'items_list_navigation'    => __($plural . ' list navigation', $this->text_domain),
			'items_list'               => __($plural . ' list', $this->text_domain),
			'item_published'           => __($singular . ' published.', $this->text_domain),
			'item_published_privately' => __($singular . ' published privately.', $this->text_domain),
			'item_reverted_to_draft'   => __($singular . ' reverted to draft.', $this->text_domain),
			'item_scheduled'           => __($singular . ' scheduled.', $this->text_domain),
			'item_updated'             => __($singular . ' updated.', $this->text_domain),
			'menu_name'                => __($plural, $this->text_domain),
			'name_admin_bar'           => __($singular, $this->text_domain),
		);


		$rewrite = array(
			'slug'       => $this->post_type,
			'with_front' => true,
			'pages'      => false,
			'feeds'      => false,
		);

		$args = array(
			'label'               => __($plural, $this->text_domain),
			'description'         => __('A database of '. $singular, $this->text_domain),
			'labels'              => $labels,
			'supports'            => array('title', 'permalink'),
			'hierarchical'        => true,
			'public'              => true,
			'show_ui'             => true,
			'menu_position'       => 15,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'rewrite'             => $rewrite,
			'query_var'           => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
			'show_in_rest'        => false,
		);


		return $args;
	}


	/**
	 * @return mixed
	 */
	public function getPostType() {
		return $this->post_type;
	}


	/**
	 * @return array
	 */
	public function getArguments() {
		return $this->arguments;
	}
}

 