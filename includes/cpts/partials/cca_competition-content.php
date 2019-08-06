

<?php
$parent = $this->get_parent( $post->ID );
if ( $parent != null ) {
	$post_type = get_post_type_object( $parent->post_type );
	$type = $post_type->labels->singular_name;
	$link = get_permalink( $parent->ID );
	echo "<div class='cca_custom_field_label cca_cca_con_fields_parent_label'>Parent {$type}</div>";
	echo "<a href='{$link}'>{$parent->post_title}</a>";
}



$custom_fields = CCA_Competition_Fields_Settings::get_fields();
global $post;
$post_meta = get_post_meta( $post->ID );
foreach ( $custom_fields as $custom_field ) {
    if ( isset( $post_meta[ $custom_field->id]) ) {
        $custom_field->render( $post->ID, $post_meta[$custom_field->id][0]);
    }
}


$children = $this->get_children( $post->ID );
if ( count($children ) > 0 ) {
	echo '<p><div class="cca_custom_field_label cca_cca_con_fields_children_label">Child Competitions</div></p><ul>';

	foreach ( $children as $competition ) {
		$link = get_permalink( $competition->ID );
		echo "<li><a href='{$link}'>{$competition->post_title}</a></li>";
	}
	echo '</ul>';
}

$album_id = isset($post_meta['competition_album']) ? intval($post_meta['competition_album'][0]) : 0;
if ( $album_id != 0 ) {
	echo do_shortcode( foogallery_build_gallery_shortcode( $album_id ) );
}

