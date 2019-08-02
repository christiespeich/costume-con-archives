<?php


class CCA_Main_Settings_Page  extends MOOBD_Settings_Page {


	public function __construct() {
		parent::__construct( 'cca_main_settings_page_metabox', 'cca_main_settings');


		$this->set_menu_title( __( 'CCA Settings', 'costume-con-archives' ) );
		$this->set_title( __( 'Main Settings', 'costume-con-archives' ) );
		$this->create_metabox();

		$this->add_fields();


	}

	protected function add_fields() {

		$pages       = get_pages();
		$pages_array = array('0' => '');
		foreach ( $pages as $page ) {
			$pages_array[ $page->ID ] = $page->post_title;
		}

		$this->add_field( array(
			'name'    => esc_html__( 'Album Page', 'costume-con-archives' ),
			'id'      => 'album_page',
			'type'    => 'select',
			'options' => $pages_array,
		) );

		$this->add_field( array(
			'name'  =>  esc_html__('Show Data Migration Menu', 'costume-con-archives'),
			'id'    =>  'show_migration_menu',
			'type'  =>  'checkbox'
		));
	}


}

