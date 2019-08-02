<?php


class CCA_Website_Field extends CCA_Custom_Field {

	protected function output_value( $object_id, $value ) {
		return "<a href='{$value}'>{$value}</a>";
	}
}
