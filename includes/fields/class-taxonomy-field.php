<?php


class CCA_Taxonomy_Field extends CCA_Custom_Field {

	protected $taxonomy;
	protected $options;
	protected $type;

	public function __construct( $field_settings ) {
		parent::__construct( $field_settings );


		$type_split = explode( '-', $this->type );
		$type       = str_replace( 'taxonomy_', '', $type_split[0] );

		$taxonomy     = 'cca_' . $type_split[1];
		$terms        = get_terms( $taxonomy, array( 'hide_empty' => false ) );
		$term_options = array();
		if ( $type == 'select' ) {
			$term_options[''] = '';
		}
		if ( ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$term_options[ $term->term_id ] = $term->name;
			}
		}
		$this->options    = $term_options;
		$this->taxonomy = $taxonomy;
		$this->type     = $type;
		$this->is_tax_field = true;


	}

	public function output_value ( $object_id, $value ) {

		$terms = array();
		if ( !is_array($value) ) {
			$value = array($value);
		}
		foreach ( $value as $term_id ) {
			$term_id = intval($term_id);
			$term = get_term( $term_id, $this->taxonomy );
			if ( !is_wp_error( $term ) ) {
				$terms[] = '<a href="' . get_term_link( $term_id, $this->taxonomy ) . '">' . $term->name . '</a>';
			}
		}
		return join( ', ' , $terms );
	}

}
