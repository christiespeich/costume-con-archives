<?php


class CCA_Tax_Fields_Settings_Page extends CCA_Custom_Fields_Settings_Page {

	protected $taxonomy;

	public function __construct( $taxonomy ) {
		$this->taxonomy = $taxonomy;

		parent::__construct( 'cca_' . $taxonomy->get_name() . '_fields_settings_options_page', 'cca_' . $taxonomy->get_name() . '_fields_settings' );

		$this->set_parent_slug( 'cca_main_settings' );
		$this->set_menu_title( __( ucwords($taxonomy->get_singular()) . ' Custom Fields', 'costume-con-archives' ) );
		$this->set_title( __( ucwords($taxonomy->get_singular()) . ' Custom Fields Settings', 'costume-con-archives' ) );
		$this->create_metabox();

		$this->add_fields( 'cca_' . $this->taxonomy->get_name() . '_custom_fields' );
	}


}
