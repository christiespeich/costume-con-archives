<?php


class CCA_Con_Fields_Settings_Page extends MOOBD_Settings_Page {

	public function __construct() {
		parent::__construct( 'cca_con_fields_settings_options_page', 'cca_con_fields_settings' );

		$this->set_parent_slug( 'cca_main_settings' );
		$this->set_menu_title( __( 'Con Custom Fields', 'costume-con-archives' ) );
		$this->set_title( __( 'Con Custom Fields Settings', 'costume-con-archives' ) );
		$this->create_metabox();

		$this->add_fields();
	}


	private function add_fields() {

		$group_field_id = $this->add_field( array(
			'id'          => 'cca_con_custom_fields',
			'type'        => 'group',
			'description' => __( 'Custom Fields for Cons', 'costume-con-archives' ),
			// 'repeatable'  => false, // use false if you want non-repeatable group
			'options'     => array(
				'group_title'    => __( 'Field {#}', 'costume-con-archives' ),
				// since version 1.1.4, {#} gets replaced by row number
				'add_button'     => __( 'Add Another Field', 'costume-con-archives' ),
				'remove_button'  => __( 'Remove Field', 'costume-con-archives' ),
				'sortable'       => true,
				// 'closed'         => true, // true to have the groups closed by default
				'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'costume-con-archives' ),
				// Performs confirmation before removing group.
			),
		) );

// Id's for group's fields only need to be unique for the group. Prefix is not needed.
		$this->add_group_field( $group_field_id, array(
			'name' => 'Entry Title',
			'id'   => 'title',
			'type' => 'text',
		) );


	}


}
