

<?php

$custom_fields = CCA_Con_Fields_Settings::get_fields();
global $post;
$this->display_custom_fields( $custom_fields, $post->ID );

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

