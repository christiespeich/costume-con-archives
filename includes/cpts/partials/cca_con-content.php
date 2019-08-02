

<?php

$custom_fields = CCA_Con_Fields_Settings::get_fields();
global $post;
$post_meta = get_post_meta( $post->ID );
foreach ( $custom_fields as $custom_field ) {
    if ( isset( $post_meta[ $custom_field->id]) ) {
        $custom_field->render( $post->ID, $post_meta[$custom_field->id][0]);
    }
}
