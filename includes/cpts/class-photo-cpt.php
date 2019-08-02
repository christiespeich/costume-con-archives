<?php


class CCA_Photo_CPT extends CCA_CPT_With_Custom_fields {

	public function __construct( $post_type, $field_settings ) {
		parent::__construct( $post_type, $field_settings );
		remove_action( 'save_post', array($this, 'save_taxonomies'), 99 );
		add_action( 'edit_attachment', array($this, 'save_taxonomies'), 99 );
	}
}
