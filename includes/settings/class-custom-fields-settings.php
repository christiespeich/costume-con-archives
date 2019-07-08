<?php


class CCA_Custom_Fields_Settings extends CCA_Settings {

	protected static $fields;

	public static function get_fields($setting_page, $setting_field ) {
		if ( !isset( self::$fields[$setting_field] ) || !is_array( self::$fields[$setting_field] ) ) {
			$fields = self::get( $setting_page, $setting_field, array() );
			self::$fields[$setting_field] = array();
			foreach ( $fields as $field ) {
				if ( substr( $field['type'], 0, 9 ) == 'taxonomy_' ) {
					self::$fields[$setting_field][] = new CCA_Taxonomy_Field( $field );
				} else {
					if ( $field['type'] == 'state' ) {
						self::$fields[$setting_field][] = new CCA_State_Field( $field );
					} else {
						self::$fields[$setting_field][] = new CCA_Custom_Field( $field );
					}
				}
			}
		}

		return self::$fields[$setting_field];

	}

}
