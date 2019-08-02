<?php


class CCA_Photo_Fields_Settings extends CCA_Custom_Fields_Settings {

	public static function get_fields( $setting_page = 'cca_photo_fields_settings', $field = 'cca_photo_custom_fields') {
		return parent::get_fields( $setting_page, $field );
	}

	public static function delete_fields($setting_page = 'cca_photo_fields_settings', $field = 'cca_photo_custom_fields', $post_type = 'attachment') {
		parent::delete_fields( 'cca_photo_fields_settings', 'cca_photo_custom_fields', 'attachment' );
	}

	public static function delete_data($setting_page = 'cca_photo_fields_settings', $field = 'cca_photo_custom_fields', $post_type = COSTUME_CON_ARCHIVES_COMPETITION_CPT ) {
		parent::delete_data( 'cca_photo_fields_settings', 'cca_photo_custom_fields', 'attachment' );

	}

		public static function get_field_id( $name, $setting_page = 'cca_photo_fields_settings', $field = 'cca_photo_custom_fields' ) {
		return parent::get_field_id($name, $setting_page, $field);
	}

}
