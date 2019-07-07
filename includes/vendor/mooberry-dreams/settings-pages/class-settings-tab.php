<?php


class MOOBD_Settings_Tab {

	protected $settings_page;
	protected $tab_group;
	protected $tab_title;
	protected $display_cb;
	protected $parent_slug;
	protected $title;

	public function __construct( $metabox_id, $option_key, $tab_title, MOOBD_Tabbed_Settings_Page $tab_page ) {
		$this->settings_page = new MOOBD_Settings_Page( $metabox_id, $option_key);
		$this->tab_group = $tab_page->tab_group;
		$this->tab_title = $tab_title;
		$this->display_cb = $tab_page->display_cb;
		$this->parent_slug = $tab_page->option_key;
		$this->title = $tab_page->title;

	}

	public function create_metabox( $args = array() ) {
		$args = array_merge ( $args, array(
			'parent_slug' => $this->parent_slug,
			'tab_group' => $this->tab_group,
			'tab_title' => $this->tab_title,
			'display_cb' => $this->display_cb,
			'title' => $this->title,
		));

		return $this->settings_page->create_metabox($args);
	}

	public function add_field ($args ) {
		return $this->settings_page->add_field($args);
	}

	public function add_group_field( $field_id, $args ) {
		return $this->settings_page->add_group_field( $field_id, $args );
	}

}
