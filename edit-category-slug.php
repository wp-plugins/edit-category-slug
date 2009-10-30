<?php
/*
Plugin Name: Edit Category Slug
Version: 0.3
Plugin URI: http://uplift.ru/projects/
Description: Allows to specify or edit a category slug in WordPress MU.
Author: Sergey Biryukov
Author URI: http://sergeybiryukov.ru/
*/

function ecs_display_slug_row($category) {
?>
<script type="text/javascript">
//<![CDATA[
var form_addcat = document.getElementById('addcat');
var form_editcat = document.getElementById('editcat');
var slugRow = '<tr class="form-field">' +
	'<th scope="row" valign="top"><label for="category_nicename"><?php _e("Category Slug") ?></label></th>' +
	'<td><input name="category_nicename" id="category_nicename" type="text" value="<?php echo attribute_escape(apply_filters("editable_slug", $category->slug)); ?>" size="40" /><br />' +
	'<?php _e("The &#8220;slug&#8221; is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens."); ?></td>' +
	'</tr>';
var slugDiv = '<div class="form-field">' +
	'<label for="category_nicename"><?php _e("Category Slug") ?></label>' +
	'<input name="category_nicename" id="category_nicename" type="text" value="<?php echo attribute_escape(apply_filters("editable_slug", $category->slug)); ?>" size="40" />' +
	'<p><?php _e("The &#8220;slug&#8221; is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens."); ?></p>' +
	'</div>';
if ( form_addcat != null )
	form_addcat.innerHTML = form_addcat.innerHTML.replace(/<\/div>/i, '</div>' + slugDiv);
if ( form_editcat != null )
	form_editcat.innerHTML = form_editcat.innerHTML.replace(/<\/tr>/i, '</tr>' + slugRow);
//]]>
</script>
<?php
}
add_action('edit_category_form', 'ecs_display_slug_row');

function ecs_edit_slug($cat_ID) {
	global $wpdb;
	if ( !empty($_POST['category_nicename']) )
		$wpdb->update( $wpdb->terms, array( 'slug' => $_POST['category_nicename'] ), array( 'term_id' => $cat_ID ) );
}
add_action('edit_category', 'ecs_edit_slug');

function ecs_remove_sync_category_tag_slugs() {
	remove_filter('get_term', 'sync_category_tag_slugs');
}
add_action('admin_init', 'ecs_remove_sync_category_tag_slugs');
?>