<?php


class Costume_Con_Archives_Migrate_Photos_Background extends WP_Background_Process  {

	/**
	 * @var string
	 */
	protected $action = 'cca_migrate_photos';
	protected $g2_migration;
	protected $db;


	public function __construct( $g2_migration) {
		parent::__construct();
		$this->g2_migration = $g2_migration;
		$this->db = new CCA_G2_Original_Data_Table();
	}


	/**
	 * Task
	 *
	 * Override this method to perform any actions required on each
	 * queue item. Return the modified item for further processing
	 * in the next pass through. Or, return false to remove the
	 * item from the queue.
	 *
	 * @param mixed $item Queue item to iterate over
	 *
	 * @return mixed
	 */
	protected function task( $item ) {
		// Actions to perform
		//$row = $item['row'];
		//$column_names = $item['columns'];
		$this->db = new CCA_G2_Original_Data_Table();
		$this->db->get( $item );
		$this->import_photo( $item ); //, $column_names );

		return false;
	}


	public function update( $key, $data ) {
		parent::update( $key, $data );

		Costume_Con_Archives_Admin_Notice_Manager::add_new( 'Batch ' . $key . ' Complete!', 'notice-info is-dismissible', 'cca_photo_migrate_background');
	}

	/**
	 * Complete
	 *
	 * Override if applicable, but ensure that the below actions are
	 * performed, or, call parent::complete().
	 */
	protected function complete() {
		parent::complete();

		// Show notice to user or perform some other arbitrary task...
		Costume_Con_Archives_Admin_Notice_Manager::add_new( 'Photo Migration Complete!', 'notice-info is-dismissible', 'cca_photo_migrate_background');
	}




	private function import_photo( $row ) { //}, $column_names ) {
		// get the id mapping
		$this->id_mapping = get_option( 'cca_migration_id_mapping', array() );
		$column_names = $this->g2_migration->get_photo_column_names();

		// upload the file into WP
		$attachment_id = $this->upload_photo( $row->ImagePath, $row, $column_names );
		if ( $attachment_id != 0 ) {



			// add it to gallery
			$competition_id = $this->g2_migration->get_parent(  $row->g_parentSequence );
			$gallery_id     = get_post_meta( $competition_id, 'competition_album', true );
			$gallery_photos = get_post_meta( $gallery_id, FOOGALLERY_META_ATTACHMENTS, true );
			if (  $gallery_photos == '' ) {
				$gallery_photos = array(  );
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
			$attachment = array_merge( $attachment, $this->g2_migration->get_meta_data( $row, $column_names, CCA_Photo_Fields_Settings::class ) );


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


}
