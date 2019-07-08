<?php


class CCA_Custom_Field {

	protected $name;
	protected $id;
	protected $type;
	protected $is_tax_field;


	public function __construct( $field_settings ) {
		$this->id = '';
		$this->name         = '';
		$this->type         = 'text';
		$this->is_tax_field = false;


		if ( array_key_exists( 'name', $field_settings ) ) {
			$this->name = $field_settings['name'];
		}

		if ( array_key_exists( 'type', $field_settings ) ) {

			$this->type = $field_settings['type'];
		}

		if ( array_key_exists( 'unique_id', $field_settings ) ) {
			$this->id = $field_settings['unique_id']; //sanitize_key( $this->name );
		}
	}

	public function has_options() {
		return $this->type == 'select' || $this->type == 'multicheck';
	}

	public function render() {

	}

	public function __get( $name ) {
		if ( method_exists($this, 'get_' . $name ) ) {
			return call_user_func( array( $this, 'get_' . $name ) );
		}
		if ( property_exists($this, $name)) {
			return $this->{$name};
		}
		return '';
	}
}
