<?php


class CCA_CPT_With_Custom_fields {

	protected $post_type;
	protected $field_settings;

	public function __construct( $post_type, $field_settings ) {
		$this->post_type = $post_type;
		$this->field_settings = $field_settings;

		add_filter( 'the_content', array( $this, 'content'));
		add_action( 'cmb2_admin_init', array( $this, 'add_metaboxes' ) );
		add_action( 'save_post', array($this, 'save_taxonomies'), 99 );


		/*parent::set_up( COSTUME_CON_ARCHIVES_CON_CPT, 'Con', 'Cons' );
		parent::add_existing_taxonomy('category' );
		$this->set_arg( 'supports', array('title') );
		parent::register();*/


	}

	public function add_metaboxes() {

		$cmb = new_cmb2_box( array(
		'id'            => 'custom_fields_' . $this->post_type,
		'title'         => __( 'Custom Fields', 'cmb2' ),
		'object_types'  => array( $this->post_type ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left


	) );


		$fields = $this->field_settings::get_fields();
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

		global $post;
		if ( get_post_type( $post) != $this->post_type ) {
			return;
		}
		// if it's a tax field, save all selected items as terms AND save as post meta

		// get all taxonomy selections
		$fields = $this->field_settings::get_fields();
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
		if ( get_post_type( $post ) != $this->post_type ) {
			return $content;
		}
		if ( !is_main_query() ) {
			return $content;
		}

		$filename = $this->post_type . '-content.php';
		ob_start();
		include 'partials/' . $filename;
		$content .= ob_get_clean();

		return $content;
	}

	public function display_custom_fields( $custom_fields, $post_id ) {
		$post_meta = get_post_meta( $post_id );
		foreach ( $custom_fields as $custom_field ) {
			if ( isset( $post_meta[ $custom_field->id ] ) ) {
				$custom_field->render( $post_id, $post_meta[ $custom_field->id ][0] );
			}
		}
	}


}
