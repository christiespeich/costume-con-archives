<?php


class CCA_Custom_Field {

	protected $name;
	protected $id;
	protected $type;
	protected $is_tax_field;
	protected $label_classes;
	protected $value_classes;
	protected $field_classes;


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

		$this->label_classes = array( 'cca_custom_field_label',
									'cca_' . $this->id  . '_label',
			);

		$this->value_classes = array( 'cca_custom_field_value',
									'cca_' . $this->id  . '_value',
			);
		$this->field_classes = array( 'cca_custom_field',
									'cca_' . $this->id,
			);

	}

	public function has_options() {
		return $this->type == 'select' || $this->type == 'multicheck';
	}

	public function render( $object_id, $value ) {

		$label_classes = $this->label_classes + array(
				'cca_custom_field_label_' . $object_id,
				' cca_' . $this->id . '_label_' . $object_id
			);
		$value_classes = $this->value_classes + array(
				'cca_custom_field_value_' . $object_id,
				' cca_' . $this->id . '_value_' . $object_id
			);
		$field_classes = $this->field_classes + array(
				'cca_custom_field_' . $object_id,
				' cca_' . $this->id . '_' . $object_id
			);

		$value = maybe_unserialize( $value );
		$output = '<div class="' . join( ' ', $field_classes ) . '">';
		$output .= '<div class="' . join( ' ', $label_classes ) . '">' . $this->name . '</div>';
		$output .= '<div class="' . join( ' ', $value_classes ) . '">' . $this->output_value($object_id, $value) . '</div>';
		$output .= '</div>';

		echo $output;
	}

	protected function output_value( $object_id, $value ) {
		if ( is_array( $value ) ) {
			return join(', ', $value );
		}
		return $value;

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
