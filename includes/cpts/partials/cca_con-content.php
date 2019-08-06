

<?php

$custom_fields = CCA_Con_Fields_Settings::get_fields();
global $post;
$post_meta = get_post_meta( $post->ID );
foreach ( $custom_fields as $custom_field ) {
    if ( isset( $post_meta[ $custom_field->id]) ) {
        $custom_field->render( $post->ID, $post_meta[$custom_field->id][0]);
    }
}
$competitions = $this->get_competitions( $post->ID );
if ( count($competitions ) > 0 ) {
	echo '<p><div class="cca_custom_field_label cca_cca_con_fields_competitions_label">Competitions</div></p><ul>';

	foreach ( $competitions as $competition ) {
		$link = get_permalink( $competition->ID );
		echo "<li><a href='{$link}'>{$competition->post_title}</a></li>";
	}
	echo '</ul>';
}
$album_id = isset($post_meta['con_album']) ? intval($post_meta['con_album'][0]) : 0;
if ( $album_id != 0 ) {
	echo do_shortcode( foogallery_build_album_shortcode( $album_id ) );
}

