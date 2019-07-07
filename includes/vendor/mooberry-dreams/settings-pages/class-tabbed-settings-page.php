<?php



	class MOOBD_Tabbed_Settings_Page extends MOOBD_Settings_Page {

	protected $tab_group;
	protected $tab_title;
	protected $display_cb;

	public function __construct( $id, $options_key, $tab_group, $tab_title ) {
		parent::__construct( $id, $options_key );
		$this->tab_group = $tab_group;
		$this->tab_title = $tab_title;
		$this->display_cb = array( $this, 'tab_display' );
	}

	public function create_metabox( $args = array() ) {
		$args = array(
			'tab_group'  => $this->tab_group,
			'tab_title' =>  $this->tab_title,
			'display_cb' => $this->display_cb,
		);
		parent::create_metabox( $args );
	}

	public function tab_display( $cmb_options ) {
		$tabs             = $this->get_tabs( $cmb_options );
		$option_key       = $cmb_options->option_key;
		$admin_title      = get_admin_page_title();
		$cmb_id           = $cmb_options->cmb->cmb_id;
		$save_button_text = $cmb_options->cmb->prop( 'save_button' );
		include 'partials/setting-page-tabs.php';

	}


	/**
	 * Gets navigation tabs array for CMB2 options pages which share the given
	 * display_cb param.
	 *
	 * @param CMB2_Options_Hookup $cmb_options The CMB2_Options_Hookup object.
	 *
	 * @return array Array of tab information.
	 */
	function get_tabs( $cmb_options ) {
		$tab_group = $cmb_options->cmb->prop( 'tab_group' );
		$tabs      = array();
		foreach ( CMB2_Boxes::get_all() as $cmb_id => $cmb ) {
			if ( $tab_group === $cmb->prop( 'tab_group' ) ) {
				$tabs[ $cmb->options_page_keys()[0] ] = $cmb->prop( 'tab_title' )
					? $cmb->prop( 'tab_title' )
					: $cmb->prop( 'title' );
			}
		}

		return $tabs;

	}

	function __get( $name ) {
		if ( property_exists($this, $name)) {
			return $this->{$name};
		} else {
			return null;
		}
	}

}
