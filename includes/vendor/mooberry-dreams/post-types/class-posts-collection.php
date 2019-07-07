<?php
abstract class MOOBD_Posts_Collection implements Iterator {

	protected $posts;
	protected $post_type;

	public function __construct( $post_type = 'post', $args = array() ) {

		$this->post_type = $post_type;

		$args = array_merge( array(
									'post_type' => $post_type,
									'post_status'   =>  'publish',
									'posts_per_page'    =>  -1,
		), $args );

		$posts = get_posts( $args );
			$this->posts = array();
			foreach ( $posts as $post ) {
				$this->posts[ $post->ID ] = $this->new_object( $post );
			}
	}

	// post can be WP_Post obj OR a Post ID
	protected abstract function new_object(  $post );

	public function next() {
		return next( $this->posts );
	}

	public function rewind() {
		reset( $this->posts );
	}

	public function current() {
		return current( $this->posts );
	}

	public function key() {
		return key( $this->posts );
	}

	public function valid() {
		$key = $this->key();
		return  ( $key !== null && $key !== false );
	}

	public function get( $index ) {
		if ( array_key_exists( $index, $this->posts ) ) {
			return $this->posts[ $index ];
		}
		return '';
	}

}
