<?php


class CCA_Tax_Fields_Settings extends CCA_Custom_Fields_Settings {

	public static function get_fields_for_taxonomy( $taxonomy ) {
		return parent::get_fields( 'cca_' . $taxonomy . '_fields_settings', 'cca_' . $taxonomy . '_custom_fields');
	}

}
