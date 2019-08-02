<?php

$custom_fields = CCA_Tax_Fields_Settings::get_fields_for_taxonomy( $taxonomy );
$slug = get_query_var( 'term' );
$term = get_term_by('slug', $slug, $taxonomy);


$post_meta = get_term_meta( $term->term_id );
	foreach ( $custom_fields as $custom_field ) {
		if ( isset( $post_meta[ $custom_field->id ] ) ) {
			$custom_field->render( $term->term_id, $post_meta[ $custom_field->id ][0] );
		}
	}




if ( $term ) {
	// get attachments with this term
	$posts      = get_objects_in_term( $term->term_id, $taxonomy );
	$galleries = foogallery_get_all_galleries();
	$found_galleries = array();
	foreach ( $galleries as $gallery ) {
		$attachments = array_values($gallery->attachment_ids );
		if ( count(array_intersect( $attachments, $posts))>0)  {
			$found_galleries[] = $gallery;
		}
	}

	if ( count($found_galleries) > 0 ) {
		echo '<div class="cca_custom_field cca_cca_person_gallery_field" id="cca_person_gallery_div"><div class="cca_custom_field_label cca_cca_person_gallery_field_label">Appears in these galleries</div><ul id="cca_person_gallery_list">';
		foreach ( $found_galleries as $found_gallery ) {
			//foogallery_render_gallery( $found_gallery->ID);
			$album_page_id = CCA_Main_Settings::get_album_page();
			$link = get_permalink( $album_page_id );
			echo '<li><div class="cca_custom_field_value cca_cca_person_gallery_field_value"><a href="' . $link . '?gallery=' . $found_gallery->ID . '">' . $found_gallery->name . '</a></div></li>';
		}
		echo '</ul></div>';
	}


}
