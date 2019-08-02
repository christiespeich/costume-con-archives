<?php


class CCA_Photo_Fields_Settings_Page extends CCA_Custom_Fields_Settings_Page {

	public function __construct() {
		parent::__construct( 'cca_photo_fields_settings_options_page', 'cca_photo_fields_settings' );

		$this->set_parent_slug( 'cca_main_settings' );
		$this->set_menu_title( __( 'Photo Custom Fields', 'costume-con-archives' ) );
		$this->set_title( __( 'Photo Custom Fields Settings', 'costume-con-archives' ) );
		$this->create_metabox();

		$this->add_fields( 'cca_photo_custom_fields');
	}

}
