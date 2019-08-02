
<?php


class CCA_Main_Settings extends CCA_Settings {


	public static function get_album_page() {
		return self::get( 'cca_main_settings', 'album_page');
	}

	public static function get_show_migration_menu() {
		$setting = self::get( 'cca_main_settings', 'show_migration_menu');
		if ( $setting == "on" ) {
			return true;
		} else {
			return false;
		}
	}
}
