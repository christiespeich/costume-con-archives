<?php


class CCA_G2_Migration {

	protected $original_data_table;
	protected $id_mapping;
	protected $albums;
	protected $galleries;
	protected $people_fields;
	protected $wysiwyg_fields;
	protected $website_fields;
	protected $column_names;
	protected $db_object;
	protected $photo_migrate_process;


	public function __construct() {

		//add_filter( 'foogallery_defaults', array( $this, 'set_foogallery_defaults' ) );

		$this->original_data_table   = new CCA_G2_Original_Data_Table();
		$this->db_object             = new CCA_G2_Original_Data_Table();
		$this->id_mapping            = array();
		$this->albums                = array();
		$this->galleries             = array();
		$this->photo_migrate_process = new Costume_Con_Archives_Migrate_Photos_Background( $this );

		$types = array( 'con', 'competition', 'photo' );
		// get all column names
		$this->column_names = array();
		foreach ( $types as $type ) {
			$fields = call_user_func( array( $this, 'get_' . $type . '_fields' ) );
			foreach ( $fields['fields'] as $field ) {
				$this->column_names[ $type ][] = str_replace( '-', '_', sanitize_title( $field ) );
			}
		}


		$this->people_fields  = array(
			'Chair',
			'Founder\'s Award Recipient',
			'Lifetime Achievement Award Recipient',
			'Director',
			'Master of Ceremonies',
			'Presentation Judges',
			'Workmanship Judges',
			'Constructed By',
			'Designed By',
			'Worn By',
		);
		$this->website_fields = array(
			'convention_website'
		);
		$this->wysiwyg_fields = array(
			'Rules',
			'Miscellaneous Notes',
			'Program and Participants',
			'Publications Contents',
			'Special Activities',
			'Voting Info',
		);
	}

	public function set_foogallery_defaults( $defaults ) {
		$defaults['link'] = 'page';

		return $defaults;
	}

	public function create_custom_fields( $delete, $types ) {

		// first delete existing custom fields if selected
		if ( $delete ) {
			$this->delete_custom_fields( $types );
		}

		// now create the fields that we need
		// Custom fields need a name, type, and unique ID
		// con fields
		$custom_fields = array();
		if ( in_array( 'con', $types ) ) {
			$custom_fields[] = $this->get_con_fields();
		}

		// competition fields
		if ( in_array( 'competition', $types ) ) {
			$custom_fields[] = $this->get_competition_fields();
		}

		// photo fields
		if ( in_array( 'photo', $types ) ) {
			$custom_fields[] = $this->get_photo_fields();
		}


		foreach ( $custom_fields as $custom_field_type ) {
			$group_field_id = $custom_field_type['group_field_id'];
			$option_key     = $custom_field_type['option_key'];


			$custom_field_settings = array();
			foreach ( $custom_field_type['fields'] as $custom_field ) {

				$type = 'text';
				if ( in_array( $custom_field, $this->people_fields ) ) {
					$type = 'taxonomy_multicheck-person';
				}
				if ( $custom_field == 'Convention Website' ) {
					$type = 'text_url';
				}
				if ( in_array( $custom_field, $this->wysiwyg_fields ) ) {
					$type = 'wysiwyg';
				}


				$custom_field_settings[ $group_field_id ][] = array(
					'unique_id' => uniqid( $option_key . '_', false ),
					'name'      => $custom_field,
					'type'      => $type,
				);
			}

			update_option( $option_key, $custom_field_settings );
		}

	}

	protected function delete_custom_fields( $types ) {
		foreach ( $types as $type ) {
			switch ( $type ) {
				case 'con':
					$this->delete_albums();
					CCA_Con_Fields_Settings::delete_fields();
					break;
				case 'competition':
					$this->delete_galleries();
					CCA_Competition_Fields_Settings::delete_fields();
					break;
				case 'photo':
					CCA_Photo_Fields_Settings::delete_fields();
					break;
				default:
					CCA_Tax_Fields_Settings::delete_tax_fields( $type );

			}

		}
	}


	protected function delete_albums() {
		$posts = get_posts( array(
			'post_type'      => array( COSTUME_CON_ARCHIVES_CON_CPT ),
			'posts_per_page' => - 1,
			'fields'         => 'ids'
		) );
		foreach ( $posts as $post ) {

			$album_id = get_post_meta( $post, 'con_album', true );
			wp_delete_post( $album_id, true );
		}
	}

	protected function delete_galleries() {
		$posts = get_posts( array(
			'post_type'      => array( COSTUME_CON_ARCHIVES_COMPETITION_CPT ),
			'posts_per_page' => - 1,
			'fields'         => 'ids'
		) );
		foreach ( $posts as $post ) {
			$album_id = get_post_meta( $post, 'competition_album', true );
			wp_delete_post( $album_id, true );
		}
	}

	protected function get_con_fields() {
		return array(
			'option_key'     => 'cca_con_fields_settings',
			'group_field_id' => 'cca_con_custom_fields',
			'fields'         => array(
				'Attending Membership',
				'Chair',
				'City',
				'Conference Dates',
				'Convention Committee',
				'Convention Website',
				'Dealers',
				'Event Name',
				'Final Membership',
				'Founder\'s Award Recipient',
				'Friday Social',
				'Lifetime Achievement Award Recipient',
				'Membership Fees',
				'Miscellaneous Notes',
				'Other Bids',
				'Pioneered',
				'Program and Participants',
				'Publications Contents',
				'Reviews/Related Websites',
				'Special Activities',
				'Sponsored by',
				'State',
				'Supporting fees',
				'Theme',
				'Venue',
				'Voting Info',
			)
		);
	}

	protected function get_competition_fields() {
		return array(
			'option_key'     => 'cca_competition_fields_settings',
			'group_field_id' => 'cca_competition_custom_fields',
			'fields'         => array(
				'Director',
				'Master of Ceremonies',
				'Miscellaneous Notes',
				'Presentation Judges',
				'Rules',
				'Workmanship Judges',
			)
		);
	}

	protected function get_photo_fields() {
		return array(
			'option_key'     => 'cca_photo_fields_settings',
			'group_field_id' => 'cca_photo_custom_fields',
			'fields'         => array(
				'Category',
				'Constructed By',
				'Designed By',
				'Division',
				'Documentation Award',
				'Entry Number',
				'Entry Title',
				'Miscellaneous Notes',
				'Other Awards',
				'Photo Ordering ID',
				'Presentation Award',
				'Workmanship Award',
				'Worn By',

			)
		);

	}

	public function get_photo_column_names() {
		return $this->column_names['photo'];
	}

	public function import_photos( $delete, $start_at, $how_many ) {
		if ( $delete ) {
			CCA_Photo_Fields_Settings::delete_data();
		}
		// finally do photos
		//for ( $x = 0; $x < 8464; $x = $x + 500 ) {
		//	$rows = $db_object->get_photos( null, null, false, $x, 500 );
		$rows = $this->db_object->get_photos( null, null, false, $start_at, $how_many );


		//$x =0;
		//	$rows = $this->db_object->get_photos();
		foreach ( $rows as $row ) {
			$pathComponent = isset( $row->g_pathComponent ) ? trim( $row->g_pathComponent ) : '';
			//$parentSequence = isset( $row->g_parentSequence ) ? trim( $row->g_parentSequence ) : '';
			if ( $pathComponent == '' || $pathComponent == null ) {
				continue;
			}
			//$this->photo_migrate_process->push_to_queue( $row->g_itemId ); // array('row'=> $row, 'columns' => $this->column_names['photo'] ) );
			$this->import_photo( $row, $this->column_names['photo'] );
			//		$x++;
			// batch 100 at a time
			//		if ( $x > 100 ) {
			//		$this->photo_migrate_process->save()->dispatch();
			//		$x = 0;
			//			break;
			//		}
		}
		//$this->photo_migrate_process->save()->dispatch();
		//	}

	}


	private function import_photo( $row, $column_names ) {
		// get the id mapping
		$this->id_mapping = get_option( 'cca_migration_id_mapping', array() );
		//$column_names = $this->g2_migration->get_photo_column_names();

		// upload the file into WP
		$attachment_id = $this->upload_photo( $row->ImagePath, $row, $column_names );
		if ( $attachment_id != 0 ) {


			// add it to gallery
			$competition_id = $this->get_parent( $row->g_parentSequence );
			$gallery_id     = get_post_meta( $competition_id, 'competition_album', true );
			$gallery_photos = get_post_meta( $gallery_id, FOOGALLERY_META_ATTACHMENTS, true );
			if ( $gallery_photos == '' ) {
				$gallery_photos = array();
			}
			$gallery_photos[] = $attachment_id;
			update_post_meta( $gallery_id, FOOGALLERY_META_ATTACHMENTS, $gallery_photos );

		}
	}

	private function upload_photo( $image_url, $row, $column_names ) {

		$upload_dir = wp_upload_dir();

		/*		if ( !file_exists( 'http://local.cca.test/CC_gallery/' . $image_url )) {
				return 0;
				}*/
//		$image_data = file_get_contents( 'http://local.cca.test/CC_gallery/' . $image_url );

//		$filename = str_replace( 'albums/', '', $image_url );
//		$pathname = str_replace( strrchr( $image_url, '/' ), '', $filename );
//		wp_mkdir_p( $upload_dir['basedir'] . '/' . $pathname );
		$filename = $upload_dir['basedir'] . '/' . $image_url;

//		file_put_contents( $file, $image_data );
		$attach_id = 0;
		//$filename = get_home_path() . 'CC_gallery/' . $image_url;
		if ( file_exists( $filename ) ) {
			$wp_filetype = wp_check_filetype( $filename, null );

			$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_content'   => '',
				'post_status'    => 'inherit'
			);

			// Update the attachment to add the meta data
			$attachment = array_merge( $attachment, $this->get_meta_data( $row, $column_names, CCA_Photo_Fields_Settings::class ) );


			$attach_id = wp_insert_attachment( $attachment, $filename, 0, true );
			if ( is_wp_error( $attach_id ) ) {
				echo 'Error! ' . $attach_id->get_error_message();
				wp_die();
			}

			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
			wp_update_attachment_metadata( $attach_id, $attach_data );
		}

		return $attach_id;
	}


	public function import_data( $delete ) {


		// delete existing data if selected
		if ( $delete ) {
			$this->delete_albums();
			CCA_Con_Fields_Settings::delete_data();
			$this->delete_galleries();
			CCA_Competition_Fields_Settings::delete_data();


			//delete all cons
			//delete all competitions
			$posts = get_posts( array(
				'post_type'      => array(
					COSTUME_CON_ARCHIVES_CON_CPT,
					COSTUME_CON_ARCHIVES_COMPETITION_CPT
				),
				'posts_per_page' => - 1,
				'fields'         => 'ids'
			) );
			foreach ( $posts as $post ) {
				wp_delete_post( $post, true );
			}

		}


		// loop through each row of the database
		// decide if it's a con, competition, or photo
		// and add it to the appropriate array

		$rows = $this->db_object->get_cons();
		//for ( $x = 0; $x < 8464; $x = $x + 500 ) {
		//	$rows = $db_object->get_rows( $x, 500 );
		foreach ( $rows as $row ) {
			$pathComponent = isset( $row->g_pathComponent ) ? trim( $row->g_pathComponent ) : '';
			//$parentSequence = isset( $row->g_parentSequence ) ? trim( $row->g_parentSequence ) : '';
			if ( $pathComponent == '' || $pathComponent == null ) {
				continue;
			}
			// do the cons first

			$new_post_id = $this->import_post_type( $row, COSTUME_CON_ARCHIVES_CON_CPT, $this->column_names['con'], CCA_Con_Fields_Settings::class );

			// create album
			$this->create_album( $row, $new_post_id );
		}
		//}


		// now do competitions
		//for ( $x = 0; $x < 8464; $x = $x + 500 ) {
		//	$rows = $db_object->get_rows( $x, 500 );
		$rows = $this->db_object->get_competitions();

		// if it's not a photo and not a con, it's a competition
		foreach ( $rows as $row ) {
			$pathComponent = isset( $row->g_pathComponent ) ? trim( $row->g_pathComponent ) : '';
			//$parentSequence = isset( $row->g_parentSequence ) ? trim( $row->g_parentSequence ) : '';
			if ( $pathComponent == '' || $pathComponent == null ) {
				continue;
			}

			$new_post_id = $this->import_post_type( $row, COSTUME_CON_ARCHIVES_COMPETITION_CPT, $this->column_names['competition'], CCA_Competition_Fields_Settings::class );
		}

		// the first loop ensures all of the id_mapping values are in place
		// now we can set up parents, etc.
		foreach ( $rows as $row ) {
			$pathComponent = isset( $row->g_pathComponent ) ? trim( $row->g_pathComponent ) : '';
			//$parentSequence = isset( $row->g_parentSequence ) ? trim( $row->g_parentSequence ) : '';
			if ( $pathComponent == '' || $pathComponent == null ) {
				continue;
			}

			$new_post_id = $this->id_mapping[ $row->g_itemId ];
			// set parent
			$parent = $this->get_parent( $row->g_parentSequence );
			update_post_meta( $new_post_id, 'competition_parent', $parent );

			// create gallery
			$parent_con = $this->get_con( $row->g_parentSequence );
			$this->create_gallery( $row, $new_post_id, $parent_con );
		}


		// save the id_mapping to the database so it's available for photo imports
		update_option( 'cca_migration_id_mapping', $this->id_mapping );


		echo 'Done!';

	}


	private function import_post_type( $row, $post_type, $column_names, $custom_fields_class ) {

		$args = $this->get_meta_data( $row, $column_names, $custom_fields_class );

		$args = array_merge( $args, array(
			'post_type'   => $post_type,
			'post_status' => 'publish',
		) );

		$new_post_id = wp_insert_post( $args, true );

		if ( is_wp_error( $new_post_id ) ) {
			echo 'Error! ' . $new_post_id->get_error_message();
			wp_die();
		}

		$this->id_mapping[ $row->g_itemId ] = $new_post_id;

		return $new_post_id;
	}

	private function translate_markup_to_html( $text ) {
		$output = str_replace( '[i]', '<i>', $text );
		$output = str_replace( '[/i]', '</i>', $output );
		$output = str_replace( '[b]', '<b>', $output );
		$output = str_replace( '[/b]', '</b>', $output );
		$output = str_replace( '[list]', '<ul>', $output );
		$output = str_replace( '[/list]', '</ul>', $output );
		$output = str_replace( '[*]', '<li>', $output );

		if ( is_array( $output ) ) {
			foreach ( $output as $key => $value ) {
				$output[ $key ] = $this->replace_html( $value );
			}
		} else {
			$output = $this->replace_html( $output );
		}


		return $output;
	}

	private function replace_html( $text ) {
		$text = preg_replace( '/\[url\](.*)\[\/url\]/mU', '<a href="$1">$1</a>', $text );
		$text = preg_replace( '/\[url=(.*)\](.*)\[\/url\]/mU', '<a href="$1">$2</a>', $text );
		$text = preg_replace( '/\[color=(.*)\]/', '<span style="color:$1">', $text );
		$text = preg_replace( '/\[\/color\]/', '</span>', $text );

		return $text;

	}

	public function get_meta_data( $row, $column_names, $custom_fields_class ) {
		$title     = isset( $row->g_title ) ? trim( $row->g_title ) : '';
		$summary   = isset( $row->g_summary ) ? trim( $row->g_summary ) : '';
		$tags_text = isset( $row->g_keywords ) ? trim( $row->g_keywords ) : '';
		$tags      = array();
		if ( $tags_text != '' ) {
			$tags = explode( ',', $tags_text );
		}

		$meta_data     = array();
		$tax_input     = array();
		$people_fields = array_map( 'sanitize_title', $this->people_fields );
		foreach ( $column_names as $column_name ) {
			$meta_key   = $custom_fields_class::get_field_id( $column_name );
			$meta_value = isset( $row->$column_name ) ? trim( $row->$column_name ) : '';
			if ( $meta_value != '' ) {
				if ( in_array( $column_name, $this->website_fields ) ) {
					$meta_value = str_replace( '[url]', '', $meta_value );
					$meta_value = str_replace( '[/url]', '', $meta_value );

				}

				$meta_data[ $meta_key ] = $meta_value;

				if ( in_array( str_replace( '_', '-', $column_name ), $people_fields ) ) {

					$meta_data[ $meta_key ] = array();

					$meta_value = wp_specialchars_decode( $meta_value, ENT_QUOTES );
					$meta_value = $this->translate_markup_to_html( $meta_value );

					$meta_value = explode( '&', $meta_value );
					$values     = array();
					foreach ( $meta_value as $value ) {
						$values = array_merge( $values, explode( ',', $value ) );
					}
					$meta_value = array_map( 'trim', $values );

					// add to terms
					foreach ( $meta_value as $term ) {
						$term_data = term_exists( $term, 'cca_person' );
						// insert if it doesn't already exists
						if ( $term_data === null ) {
							$term_data = wp_insert_term( $term, 'cca_person' );
						}
						$tax_input['cca_person'][] = $term;

						// also add to post meta
						if ( array_key_exists( 'term_id', $term_data ) ) {
							$meta_data[ $meta_key ][] = $term_data['term_id'];
						}
					}
				}


			}
		}
		$summary   = $this->translate_markup_to_html( $summary );
		$meta_data = $this->translate_markup_to_html( $meta_data );

		return array(
			'post_title'   => $title,
			'post_content' => $summary,
			'post_excerpt' => $summary,
			'meta_input'   => $meta_data,
			'tags_input'   => $tags,
			'tax_input'    => $tax_input
		);

	}

	private function get_con( $parentSequence ) {
		$parents   = explode( '/', trim( $parentSequence ) );
		$parent_id = intval( $parents[1] );

		return isset( $this->id_mapping[ $parent_id ] ) ? $this->id_mapping[ $parent_id ] : 0;
	}

	public function get_parent( $parentSequence ) {

		// set its parent con
		// reverse the array to more easily get the last element
		$parents = array_reverse( explode( '/', trim( $parentSequence ) ) );
		// the first element will most likely be empty because the data ends in a /
		if ( $parents[0] == '' ) {
			$parent_id = intval( isset( $parents[1] ) ? $parents[1] : 0 );
		} else {
			$parent_id = intval( $parents[0] );
		}

		$parent_con = isset( $this->id_mapping[ $parent_id ] ) ? $this->id_mapping[ $parent_id ] : 0;

		return $parent_con;

	}

	private function create_album( $row, $new_post_id ) {
		// create album
		//create an empty foogallery album
		$foogallery_album_args = array(
			'post_title'  => $row->g_title,
			'post_type'   => FOOGALLERY_CPT_ALBUM,
			'post_status' => 'publish',
		);
		$album_id              = wp_insert_post( $foogallery_album_args );

		//set a default gallery template
		add_post_meta( $album_id, FOOGALLERY_ALBUM_META_TEMPLATE, foogallery_default_album_template(), true );
		$this->albums[ $new_post_id ] = $album_id;

		// set the album to the con
		update_post_meta( $new_post_id, 'con_album', $album_id );
	}


	private function create_gallery( $row, $new_post_id, $parent_con ) {
		// create gallery
		$new_gallery_id = foogallery_create_gallery( null, '' );
		wp_update_post( array( 'ID' => $new_gallery_id, 'post_title' => $row->g_title ) );
		$this->galleries[ $new_post_id ] = $new_gallery_id;
		$settings                        = array( 'default_thumbnail_link' => 'page' );
		update_post_meta( $new_gallery_id, FOOGALLERY_META_SETTINGS, $settings );

		// set the gallery to the competition
		update_post_meta( $new_post_id, 'competition_album', $new_gallery_id );

		// add gallery to con's album
		$album_id  = $this->albums[ $parent_con ];
		$galleries = get_post_meta( $album_id, FOOGALLERY_ALBUM_META_GALLERIES, true );
		if ( ! is_array( $galleries ) ) {
			$galleries = array( $galleries );
		}
		$galleries[] = $new_gallery_id;
		update_post_meta( $album_id, FOOGALLERY_ALBUM_META_GALLERIES, $galleries );

	}
}
