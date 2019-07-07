<?php


class MOOBD_Taxonomy_Settings_Page extends MOOBD_Settings_Page {




	public function __construct( ) { //MOOBD_Settings_Page $email_tab ) {
		$option_key = 'moobdir_taxonomies';
		parent::__construct( 'moobdir_taxonomies_settings_options_pages', $option_key ); //, 'Taxonomies', $tab_page );
			$this->set_title( __( 'Taxonomies', 'mooberry-directory' ) );
		//	$email_tab->set_title( 'Directory Settings' );
		$this->set_parent_slug( 'cca_main_settings' );
		$this->create_metabox();

		$this->add_fields(); // $email_tab );

		add_action( 'update_option_' . $option_key, array( $this, 'options_updated' ), 10, 2 );

	}


	private function add_fields() {//MOOBD_Settings_Page $email_tab ) {

		$this->add_field( array(
				'id'      => 'taxonomies',
				'type'    => 'group',
				'desc'    => __( 'Taxonomies are ways of grouping listings.  You can create a taxonomy that will become a drop down, checkbox list, or radio button list on a listing form.', 'mooberry-directory' ),
				'options' => array(
					'group_title'   => __( 'Taxonomy ', 'mooberry-directory' ) . ' {#}',
					// since version 1.1.4, {#} gets replaced by row number
					'add_button'    => __( 'Add New Taxonomy', 'mooberry-directory' ),
					'remove_button' => __( 'Remove Taxonomy', 'mooberry-directory' ),
					'sortable'      => false,
					// beta
				),
			)
		);

		$this->add_group_field( 'taxonomies', array(
				'name'       => __( 'Singular Name', 'mooberry-directory' ),
				'id'         => 'singular_name',
				'type'       => 'text_medium',
				'attributes' => array(
					'required' => 'required',
				),
			)
		);

		$this->add_group_field( 'taxonomies', array(
				'name'       => __( 'Plural Name', 'mooberry-directory' ),
				'id'         => 'plural_name',
				'type'       => 'text_medium',
				'attributes' => array(
					'required' => 'required',
				),
			)
		);

	/*	$this->add_group_field( 'taxonomies', array(
				'name'       => __( 'Plural Name', 'mooberry-directory' ),
				'id'         => 'plural_name',
				'type'       => 'text_medium',
				'attributes' => array(
					'required' => 'required',
				),
			)
		);*/

			// break up the description into multiple sections to keep the HTML
		// out of the translatable text
		$description1 = __('These will be used to build website URL to display the listings assigned to this taxonomy.  Text entered in these fields will be converted to "friendly URLs" by making them lower-case, removing the spaces, etc.', 'mooberry-book-manager');
		$description2 = '<b>' . __('NOTE:', 'mooberry-book-manager') . '</b> ' . __('Wordpress reserved terms are not allowed here.', 'mooberry-book-manager');
		$description4 = __('Reserved Terms', 'mooberry-book-manager');
		$description5 = __('See a list of reserved terms.', 'mooberry-book-manager');

		$description = $description1 .
						'<br><br>' .
						$description2 .
						' <a href="" onClick="window.open(\'partials/reserved_terms.php\', \'' . $description4 .
						'\',  \'width=460, height=300, left=550, top=250, scrollbars=yes\'); return false;">' .
						$description5 .
						'</a>';

			$this->add_group_field( 'taxonomies', array(
				'name'       => __( 'Taxonomy URL', 'mooberry-directory' ),
				'id'         => 'slug',
				'type'       => 'text_medium',
				'desc'       => $description,
				'sanitization_cb'	=> array( $this, 'sanitize_slug'),
				'attributes' => array(
					'required' => 'required',
				),
			)
		);

				$this->add_group_field( 'taxonomies', array(
				'name'       => __( 'Hierarchical?', 'mooberry-directory' ),
				'id'         => 'hierarchical',
				'type'       => 'radio',
				'desc'       => 'Hierarchical taxonomies allow you to create parent-child relationship within a taxonomy. <br/><br/> For example, with a Region taxonomy you might have a country (Canada) and then a region (Alberta) of the country. If you create this as a hierarchical taxonomy and set Canada to be a parent of Alberta, a listing will appear in both the Canada page and the Alberta page.<br/><br/>It is recommended to name the children with the parent name first, ie "CANADA" for the parent and then "CANADA -- Alberta" for the child so that the drop down/checkbox/radio button list keeps parent and children sorted together.',
				'default'   =>  'no',
				'options'   =>  array( 'no' =>  'No',
										'yes'   =>  'Yes' ),
				'attributes' => array(
					'required' => 'required',
				),
			)
		);
	}

	/**
	 *
	 *  If any of the tax slugs were changed, or any taxonomies added or removed,  the rewrite rules
	 *  need to be flushed.
	 *  This function runs if ANY of the fields were updated.
	 *
	 *
	 *  @since 3.0
	 *  @param [string] $old_value
	 *  @param [string] $new_value
	 *
	 *  @access public
	 */
	public function options_updated( $old_value, $new_value ) {
		flush_rewrite_rules();
	}



	/**
	 *
	 *  Verifies the Grid URL slug is not a WP reserved term
	 *
	 *
	 * @since  3.0
	 *
	 * @param  [string] $meta_value value the user entered
	 * @param  [array] $args        contains field id
	 * @param  [obj] $object        contains original value before user input
	 *
	 * @return string sanitized value. Either the inputted value if it checks out
	 *                            or the original value if not
	 *
	 * @access public
	 */
	public function sanitize_slug( $meta_value, $args, $object ) {

		// make sure none of the fields are blank
		if ( ! isset( $meta_value ) || trim( $meta_value ) == '' ) {
			// default to the field id as a last resort
			$meta_value = $args['id'];

			// pull the singular name from the field id
			$field_id = $args['id'];
			$results  = preg_match( '/mbdb_book_grid_(mbdb_.*)_slug/', $field_id, $matches );
			if ( $results ) {
				$taxonomy = get_taxonomy( $matches[1] );
				if ( $taxonomy ) {
					$meta_value = sanitize_title( $taxonomy->labels->singular_name );
				}
			}

		}
		$reserved_terms = $this->wp_reserved_terms();
		if ( in_array( $meta_value, $reserved_terms ) ) {
			//show a message
			$msg = '"' . $meta_value . '" ' . __( 'is a reserved term and not allowed. This field was not saved.', 'mooberry-directory' );
			add_settings_error( $this->option_key . '-error', '', $msg, 'error' );
			settings_errors( $this->option_key . '-error' );

			// return the original value
			return sanitize_title( $object->value );
		}

		// entered value is OK. Sanitize it and return it
		return sanitize_title( $meta_value );
	}

	private function wp_reserved_terms() {
		return array(
			'attachment',
			'attachment_id',
			'author',
			'author_name',
			'calendar',
			'cat',
			'category',
			'category__and',
			'category__in',
			'category__not_in',
			'category_name',
			'comments_per_page',
			'comments_popup',
			'customize_messenger_channel',
			'customized',
			'cpage',
			'day',
			'debug',
			'error',
			'exact',
			'feed',
			'hour',
			'link_category',
			'm',
			'minute',
			'monthnum',
			'more',
			'name',
			'nav_menu',
			'nonce',
			'nopaging',
			'offset',
			'order',
			'orderby',
			'p',
			'page',
			'page_id',
			'paged',
			'pagename',
			'pb',
			'perm',
			'post',
			'post__in',
			'post__not_in',
			'post_format',
			'post_mime_type',
			'post_status',
			'post_tag',
			'post_type',
			'posts',
			'posts_per_archive_page',
			'posts_per_page',
			'preview',
			'robots',
			's',
			'search',
			'second',
			'sentence',
			'showposts',
			'static',
			'subpost',
			'subpost_id',
			'tag',
			'tag__and',
			'tag__in',
			'tag__not_in',
			'tag_id',
			'tag_slug__and',
			'tag_slug__in',
			'taxonomy',
			'tb',
			'term',
			'terms',
			'theme',
			'title',
			'type',
			'w',
			'withcomments',
			'withoutcomments',
			'year',
		);
	}
}
