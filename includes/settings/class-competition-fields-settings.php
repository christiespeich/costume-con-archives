<?php


class CCA_Competition_Fields_Settings extends CCA_Custom_Fields_Settings {

	public static function get_fields( $setting_page = 'cca_competition_fields_settings', $field = 'cca_competition_custom_fields') {
		return parent::get_fields( $setting_page, $field );
	}

		public static function delete_fields($setting_page = 'cca_competition_fields_settings', $field = 'cca_competition_custom_fields', $post_type = COSTUME_CON_ARCHIVES_COMPETITION_CPT ) {
		parent::delete_fields( 'cca_competition_fields_settings', 'cca_competition_custom_fields', COSTUME_CON_ARCHIVES_COMPETITION_CPT );

	}

		public static function delete_data($setting_page = 'cca_competition_fields_settings', $field = 'cca_competition_custom_fields', $post_type = COSTUME_CON_ARCHIVES_COMPETITION_CPT ) {
		parent::delete_data( 'cca_competition_fields_settings', 'cca_competition_custom_fields', COSTUME_CON_ARCHIVES_COMPETITION_CPT );

	}

	public static function get_field_id( $name, $setting_page = 'cca_competition_fields_settings', $field = 'cca_competition_custom_fields' ) {
		return parent::get_field_id($name, $setting_page, $field);
	}

}
