<?php


class CCA_Taxonomy {

	protected $singular_label;
	protected $plural_label;
	protected $slug;
	protected $name;
	protected $hierarchical;

	public function __construct( $args = array() ) {
		$defaults = array( 'singular_name'  =>  '',
							'plural_name'   =>  '',
							'slug'          =>  '',
							'hierarchical'  =>  'no',
		);
		$args = array_merge( $defaults, $args );

		$this->singular_label = $args[ 'singular_name' ];
		$this->plural_label = $args[ 'plural_name' ];
		$this->slug = $args[ 'slug' ];
		$this->name = substr($this->slug, 0, 4 ) == 'cca_' ? $this->slug : 'cca_' . $this->slug;
		$this->hierarchical = $args[ 'hierarchical' ];
	}

	public function get_singular() {
		return $this->singular_label;
	}

	public function get_plural() {
		return $this->plural_label;
	}

	public function get_slug() {
		return $this->slug;
	}

	public function get_name() {
		return $this->name;
	}

	public function is_hierarchical() {
		return $this->hierarchical == 'yes';
	}

	public function to_array() {
		return array( 'singular_name'  =>  $this->singular_label,
							'plural_name'   =>  $this->plural_label,
							'slug'          =>  $this->slug,
							'hierarchical'  =>  $this->hierarchical,
		);
	}

}
