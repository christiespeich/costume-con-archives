<?php


class CCA_Custom_Fields_Settings_Page extends MOOBD_Settings_Page {

	public function __construct( $metabox_id, $option_key) {//}, $menu_title, $page_title, $field_id ) {
		parent::__construct( $metabox_id, $option_key );


	}


	protected function add_fields( $field_id) {

		$group_field_id = $this->add_field( array(
			'id'          => $field_id,
			'type'        => 'group',
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


		$this->add_group_field( $group_field_id, array(
			'name' => 'Name',
			'id'   => 'name',
			'type' => 'text',
		) );


		$options = array(
			'text'            => 'Text',
			'textarea'        => 'Large Text Box',
			'text_number'     => 'Number',
			'text_date'       => 'Date',
			'text_time'       => 'Time',
			'select_timezone' => 'Timezone (Dropdown)',
			'text_url'        => 'Website',
			'text_email'      => 'Email',
			'text_money'      => 'Money',
			'state'           => 'State (Dropdown)',
			'wysiwyg'          => 'WYSIWYG',
		);

		$taxonomies = CCA_Taxonomies_Settings::get_taxonomies();

		foreach ( $taxonomies as $taxonomy ) {
			$options[ 'taxonomy_select-' . $taxonomy->get_slug() ] = $taxonomy->get_singular() . ' (Dropdown) ';
			$options[ 'taxonomy_multicheck-' . $taxonomy->get_slug() ] = $taxonomy->get_singular() . ' (Checkboxes) ';
		}


		$this->add_group_field( $group_field_id, array(
				'name'    => 'Type',
				'id'      => 'type',
				'type'    => 'select',
				'options' => $options
			)
		);

		$this->add_group_field( $group_field_id, array(
			'name'  =>  '',
			'id'    =>  'unique_id',
			'type'  =>  'unique_id'
		));

	}


}
