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

		public function display_competition_metadata( $post_id ) {
		$parent = $this->get_parent( $post_id );
		if ( $parent != null ) {
			$post_type = get_post_type_object( $parent->post_type );
			$type      = $post_type->labels->singular_name;
			$link      = get_permalink( $parent->ID );
			echo "<div class='cca_custom_field_label cca_cca_con_fields_parent_label'>Parent {$type}</div>";
			echo "<a href='{$link}'>{$parent->post_title}</a>";
		}


		$custom_fields = CCA_Competition_Fields_Settings::get_fields();
		$this->display_custom_fields( $custom_fields, $post_id );


		$children = $this->get_children( $post_id );
		if ( count( $children ) > 0 ) {
			echo '<p><div class="cca_custom_field_label cca_cca_con_fields_children_label">Child Competitions</div></p><ul>';

			foreach ( $children as $competition ) {
				$link = get_permalink( $competition->ID );
				echo "<li><a href='{$link}'>{$competition->post_title}</a></li>";
			}
			echo '</ul>';
		}
	}

}
