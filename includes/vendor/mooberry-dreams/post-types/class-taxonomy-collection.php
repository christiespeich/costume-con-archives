<?php
abstract class MOOBD_Taxonomy_Collection implements Iterator {

	protected $terms;
	protected $taxonomy;

	public function __construct( $taxonomy, $args = array() ) {

		$this->taxonomy = $taxonomy;

		$args = array_merge( array(
									'taxonomy' => $taxonomy,
			'hide_empty' => false,

		), $args );

		$terms = get_terms( $args );
			$this->terms = array();
			foreach ( $terms as $term ) {
				$this->terms[ $term->term_id ] = $this->new_object( $term );
			}
	}

	protected abstract function new_object( WP_Term $term );

	public function next() {
		return next( $this->terms );
	}

	public function rewind() {
		reset( $this->terms );
	}

	public function current() {
		return current( $this->terms );
	}

	public function key() {
		return key( $this->terms );
	}

	public function valid() {
		$key = $this->key();
		return  ( $key !== null && $key !== false );
	}

	public function get( $index ) {
		if ( array_key_exists( $index, $this->terms ) ) {
			return $this->terms[ $index ];
		}
		return '';
	}

}
