<?php


class CCA_Email_Field extends CCA_Custom_Field {

	protected function output_value( $object_id, $value ) {
		return "<a href='mailto:{$value}'>{$value}</a>";
	}
}
