<?php


class CCA_Competition_CPT extends CCA_CPT_With_Custom_fields {

	public function add_metaboxes() {
		// add con link to competitions
		$cons = get_posts( array( 'post_type' => array(COSTUME_CON_ARCHIVES_CON_CPT, COSTUME_CON_ARCHIVES_COMPETITION_CPT), 'posts_per_page'=>-1));
		$options = array( '' => '');
		foreach ( $cons as $con ) {
			$options[$con->ID] = $con->post_title;
		}
		$cmb = new_cmb2_box( array(
				'id'               => 'competition_con_metabox',
				'title'            => __( 'Competition Information', 'cmb2' ),
				'object_types'     => array( COSTUME_CON_ARCHIVES_COMPETITION_CPT ), // Post type
				'context'          => 'normal',
				'priority'         => 'high',
				'show_names'       => true, // Show field names on the left
			) );
		$cmb->add_field( array(
			'id'    =>  'competition_parent',
			'name'  =>  'Parent Con or Competition',
			'type'  =>  'select',
			'options'   => $options,
		));

		$albums = get_posts( array( 'post_type' => 'foogallery', 'posts_per_page'=>-1));
		$album_list = array( ''=>'');
		foreach ( $albums as $album ) {
			$album_list[ $album->ID] = $album->post_title;
		}
		$cmb->add_field( array(
			'id'    =>  'competition_album',
			'name'  =>  'Photo Album',
			'type'  =>  'select',
			'options'   => $album_list,
		));

		parent::add_metaboxes();
	}

	public function get_children( $id ) {
		return get_posts( array( 'post_status'    => 'publish',
		                         'post_type' => array(COSTUME_CON_ARCHIVES_CON_CPT, COSTUME_CON_ARCHIVES_COMPETITION_CPT),
		                         'posts_per_page' => - 1,
		                         'meta_query'     => array( array( 'key' => 'competition_parent', 'value' => $id ) )
		) );
	}

	public function get_parent( $id ) {
		$parent_id = get_post_meta( $id, 'competition_parent', true );
		if ($parent_id != '' ) {
			return get_post( $parent_id );
		}
		return null;
	}
}
