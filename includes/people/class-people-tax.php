<?php


class CCA_People_Tax extends MOOBD_Custom_Taxonomy {

	public function __construct( ) {
		parent::set_up( COSTUME_CON_ARCHIVES_PERSON_TAX, array( COSTUME_CON_ARCHIVES_CON_CPT), 'Person', 'People');
		parent::register();
	}
}
