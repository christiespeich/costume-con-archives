<h1>Migrate Data from Old Site</h1>
<div style="border:1px solid grey; padding:10px;margin:10px;">
    <h2 >Custom Fields</h2>
<p><b>Type of Custom Data</b></p>
<ul style="margin-left:10px;">
<li><input type="checkbox" id="cca_custom_fields_type_con" name="cca_custom_fields_type[]" value="con"/><label for="cca_custom_fields_type_con">Con</label> </li>
<li><input type="checkbox" id="cca_custom_fields_type_competition" name="cca_custom_fields_type[]" value="competition"/><label for="cca_custom_fields_type_competition">Competition</label> </li>
<li><input type="checkbox" id="cca_custom_fields_type_photo" name="cca_custom_fields_type[]" value="photo"/><label for="cca_custom_fields_type_photo">Photo</label> </li>
    <?php
    /*$custom_taxonomies = CCA_Taxonomies_Settings::get_taxonomies();
    foreach ( $custom_taxonomies as $custom_taxonomy ) {
        echo '<li><input type="checkbox" id="cca_custom_fields_type_' . $custom_taxonomy->get_name() . '" name="cca_custom_fields_type[]" value="' . $custom_taxonomy->get_name() . '"/><label   for="cca_custom_fields_type_' . $custom_taxonomy->get_name() . '">' . $custom_taxonomy->get_singular() . '</label> </li>';
    }*/
    ?>

</ul>
<p><input type="checkbox" id="cca_delete_custom_fields" name="cca_delete_custom_fields"/><label for="cca_delete_custom_fields">Delete current custom fields?</label> </p>
<button id="cca_create_custom_fields">Create Custom Fields</button>
<img id="cca_create_custom_fields_progress" style="display:none;" src="<?php echo COSTUME_CON_ARCHIVES_PLUGIN_URL; ?>/includes/images/process.gif" />
<div id="cca_create_custom_fields_results" ></div>
</div>

<div style="border:1px solid grey; padding:10px;margin:10px;">
    <h2 >Import Con and Competition Data (no photos)</h2>

    <p><input type="checkbox" id="cca_delete_custom_data" name="cca_delete_custom_data"/><label for="cca_delete_custom_data">Delete current custom fields data?</label> </p>
<button id="cca_import_custom_data">Import Custom Fields Data</button>
<img id="cca_import_data_progress" style="display:none;" src="<?php echo COSTUME_CON_ARCHIVES_PLUGIN_URL; ?>/includes/images/process.gif" />
<div id="cca_import_data_results" ></div>

</div>


<div style="border:1px solid grey; padding:10px;margin:10px;">
    <h2 >Import Photos</h2>
    <p><label for="cca_photo_increments">How many photos to import at a time?</label><input type="number" id="cca_photo_increments" name="cca_photo_increments"/></p>
    <p><input type="checkbox" id="cca_delete_custom_photo_data" name="cca_delete_custom_photo_data"/><label for="cca_delete_custom_photo_data">Delete current custom fields data?</label> </p>
<button id="cca_import_photos">Import Photos</button>
<img id="cca_import_photos_progress" style="display:none;" src="<?php echo COSTUME_CON_ARCHIVES_PLUGIN_URL; ?>/includes/images/process.gif" />
<div id="cca_import_photos_results" ></div>

</div>
