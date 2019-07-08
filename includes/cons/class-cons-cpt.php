<?php


class CCA_Cons_CPT {

	public function __construct( ) {
		/*parent::set_up( COSTUME_CON_ARCHIVES_CON_CPT, 'Con', 'Cons' );
		parent::add_existing_taxonomy('category' );
		$this->set_arg( 'supports', array('title') );
		parent::register();*/


	}

	public function add_metaboxes() {

		$cmb = new_cmb2_box( array(
		'id'            => 'custom_fields',
		'title'         => __( 'Custom Fields', 'cmb2' ),
		'object_types'  => array( COSTUME_CON_ARCHIVES_CON_CPT ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left


	) );


		$fields = CCA_Con_Fields_Settings::get_fields();
		foreach ( $fields as $field ) {

			$args = array(
				'name'  =>  $field->name,
				'id'    =>  $field->id,
			);

			$args[ 'type' ]  =  $field->type;

			if ( $field->has_options() ) {
				$args['options'] = $field->options;
			}

			$cmb->add_field( $args );
		}
	}

	public function save_taxonomies( $id ) {

		// if it's a tax field, save all selected items as terms AND save as post meta

		// get all taxonomy selections
		$fields = CCA_Con_Fields_Settings::get_fields();
		$tax_values = array();
		foreach ( $fields as $field ) {

			if ( $field->is_tax_field ) {

				$taxonomy = $field->taxonomy;
				$values     = get_post_meta( $id, $field->id, true );
				if ( ! is_array( $values ) ) {
					$values = array( $values );
				}
				// make sure they are ints or WP will add them as new terms
				$values = array_map( 'intval', $values );

				if ( array_key_exists( $taxonomy, $tax_values ) ) {
					$tax_values[ $taxonomy ] = array_merge( $tax_values[ $taxonomy ], $values );
				} else {
					$tax_values[ $taxonomy ] = $values;
				}

			}
		}
		foreach ( array_keys($tax_values) as $tax ) {
			$values = array_unique( $tax_values[ $tax ] );
			wp_set_object_terms( $id, $values, $tax );
		}

	}


	public function content( $content ) {
		global $post;
		if ( get_post_type( $post ) != COSTUME_CON_ARCHIVES_CON_CPT ) {
			return $content;
		}
		if ( !is_main_query() ) {
			return $content;
		}

		ob_start();
		include 'partials/con-cpt-content.php';
		$content = ob_get_clean();

		return $content;
	}


}
