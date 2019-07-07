<?php


class CCA_Cons_CPT extends MOOBD_Custom_Post_Type {

	public function __construct( ) {
		parent::set_up( COSTUME_CON_ARCHIVES_CON_CPT, 'Con', 'Cons' );
		parent::add_existing_taxonomy(COSTUME_CON_ARCHIVES_PERSON_TAX );
		parent::register();
	}

}
