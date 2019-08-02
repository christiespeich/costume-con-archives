
<?php


class CCA_Custom_Fields_Settings extends CCA_Settings {

	protected static $fields;

	public static function get_fields( $setting_page, $setting_field ) {
		if ( ! isset( self::$fields[ $setting_field ] ) || ! is_array( self::$fields[ $setting_field ] ) ) {
			$fields                         = self::get( $setting_page, $setting_field, array() );
			self::$fields[ $setting_field ] = array();
			foreach ( $fields as $field ) {
				switch ( $field['type'] ) {
					case 'state':
						self::$fields[ $setting_field ][] = new CCA_State_Field( $field );
						break;
					case 'text_url':
						self::$fields[ $setting_field ][] = new CCA_Website_Field( $field );
						break;
					case 'text_email':
						self::$fields[ $setting_field ][] = new CCA_Email_Field( $field );
						break;
					default:
						if ( substr( $field['type'], 0, 9 ) == 'taxonomy_' ) {
							self::$fields[ $setting_field ][] = new CCA_Taxonomy_Field( $field );

						} else {
							self::$fields[ $setting_field ][] = new CCA_Custom_Field( $field );
						}
				}
			}
		}

		return self::$fields[ $setting_field ];

	}


	public static function delete_data( $settings_page, $field, $post_type ) {
		// delete all the post meta before deleting the fields
		$fields = self::get_fields( $settings_page, $field );
		$objects = get_posts( array('post_type'=>$post_type, 'posts_per_page'=>-1));
		foreach ( $fields as $field_obj ) {
			delete_metadata( 'post', null, $field_obj->id, '', true );
			// if it's a tax field also delete all associated terms
			if ( $field_obj->is_tax_field ) {
				$taxonomy = $field_obj->taxonomy;
				foreach ( $objects as $object ) {
					wp_delete_object_term_relationships( $object->ID, $taxonomy );
				}
			}
		}
	}

	public static function delete_fields( $settings_page, $field, $post_type ) {

		self::delete_data( $settings_page, $field, $post_type );

		parent::delete( $settings_page, $field );
	}

	public static function get_field_id( $name, $setting_page, $field ) {
		$fields = self::get_fields($setting_page, $field );
		foreach ( $fields as $field ) {
			if ( str_replace('-', '_',  sanitize_title($field->name ) ) == $name ) {
				return $field->id;
			}
		}
		return '';
	}

}
