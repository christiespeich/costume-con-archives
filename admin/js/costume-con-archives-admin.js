jQuery(document).ready(function () {

	jQuery('#cca_create_custom_fields').on( 'click', cca_migration_create_custom_fields );

	jQuery('#cca_import_custom_data').on('click', cca_migration_import_data );

	jQuery('#cca_import_photos').on('click', cca_migration_import_photos );
});

function cca_migration_import_photos() {

	var photos_at_a_time = parseInt(jQuery('#cca_photo_increments').val());

	x = 0;
	while (x < 9000) {
		var data = {
			'action': 'cca_import_photos',
			'delete': jQuery('#cca_delete_custom_photo_data')
			  .prop('checked'),
			'start': x,
			'count': photos_at_a_time,
		}


	//	cca_run_admin_ajax(data, '#cca_import_photos_results', '#cca_import_photos_progress');


		jQuery('#cca_import_photos_results').append('<div id="cca_import_photos_results_' + x + '">Kicking off ' + x + ' - ' + (x+photos_at_a_time) + '<img src="/wp-content/plugins/costume-con-archives/includes/images/process.gif" id="cca_import_photos_process_' + x + '"/></div>' );


		x = x + photos_at_a_time;

		cca_run_admin_ajax(data, '', '#cca_import_photos_progress')
		.done( function(results) {
			results = parseInt(results);
			console.log( results + 'done!');
			jQuery('#cca_import_photos_process_' + results).hide();
			jQuery('#cca_import_photos_results_' + results).append("Done!");
		})
		  .fail( function(results) {
		  	console.log(results );
		  	//jQuery('#cca_import_photos_process_' + results).hide();
			//jQuery('#cca_import_photos_results_' + results).append(" Failed!");
		  })
	}

}



function cca_migration_import_data() {


	var data = {
		'action': 'cca_import_data',
		'delete': jQuery('#cca_delete_custom_data').prop('checked'),
	};

	cca_run_admin_ajax( data, '#cca_import_data_results', '#cca_import_data_progress' );

}

function cca_migration_create_custom_fields() {

	var checked = [];
	jQuery("input[name='cca_custom_fields_type[]']:checked").each(function () {
	  checked.push(jQuery(this).val());
	});
console.log(checked);

	var data = {
		'action': 'cca_migrate_create_custom_fields',
		'delete': jQuery('#cca_delete_custom_fields').prop('checked'),
		'types': checked
	};

	cca_run_admin_ajax( data, '#cca_create_custom_fields_results', '#cca_create_custom_fields_progress' )

}



function cca_run_admin_ajax( data,  results_field, progress_image ) {

	jQuery(progress_image).show();
	jQuery(results_field).text(' ');


	data.security = cca_ajax_admin.ajax_nonce;
	var ajax_results = jQuery.post( cca_ajax_admin.ajax_url, data );

	ajax_results.done( function (results) {
		jQuery(results_field).text(results);
	} );
	ajax_results.always ( function (results ) {
		jQuery(progress_image)
		  .hide();
	});

	ajax_results.fail( function(results) {
		jQuery(results_field).text('ERROR!');
	});

	return ajax_results;

}
