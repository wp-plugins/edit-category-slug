<?php
/*
Plugin Name: Edit Category Slug
Version: 0.5-trunk
Plugin URI: http://uplift.ru/projects/
Description: Allows to specify or edit a category slug in WordPress MU.
Author: Sergey Biryukov
Author URI: http://sergeybiryukov.ru/
*/

function ecs_display_slug_row($category) {
	$label = function_exists('_x') && function_exists('_ex') ? _x('Slug', 'Taxonomy Slug') : __('Category Slug');
	$category_slug = apply_filters('editable_slug', $category->slug);
	$slug = function_exists('esc_attr') ? esc_attr($category_slug) : attribute_escape($category_slug);
?>
<script type="text/javascript">
//<![CDATA[
var form_addcat = document.getElementById('addtag') != null ? document.getElementById('addtag') : document.getElementById('addcat');
var form_editcat = document.getElementById('edittag') != null ? document.getElementById('edittag') : document.getElementById('editcat');

var slugRow = '<tr class="form-field">' +
	'<th scope="row" valign="top"><label for="category_nicename"><?php echo $label; ?></label></th>' +
	'<td><input name="category_nicename" id="category_nicename" type="text" value="<?php echo $slug; ?>" size="40" /><br />' +
	'<?php _e("The &#8220;slug&#8221; is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens."); ?></td>' +
	'</tr>';
var slugDiv = '<div class="form-field">' +
	'<label for="category_nicename"><?php echo $label; ?></label>' +
	'<input name="category_nicename" id="category_nicename" type="text" value="" size="40" />' +
	'<p><?php _e("The &#8220;slug&#8221; is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens."); ?></p>' +
	'</div>';

if ( form_addcat != null ) {
	form_addcat.innerHTML = form_addcat.innerHTML.replace(/<\/div>/i, '</div>' + slugDiv);
	form_addcat.innerHTML = form_addcat.innerHTML.replace(/<\/tr>/i, '</tr>' + slugRow);
}
if ( form_editcat != null )
	form_editcat.innerHTML = form_editcat.innerHTML.replace(/<\/tr>/i, '</tr>' + slugRow);
//]]>
</script>
<?php
}
add_action('edit_category_form', 'ecs_display_slug_row');

function ecs_edit_slug($cat_id) {
	global $wpdb;
	if ( isset($_POST['category_nicename']) ) {
		if ( !empty($_POST['category_nicename']) ) 
			$category_nicename = function_exists('esc_html') ? esc_html($_POST['category_nicename']) : wp_specialchars($_POST['category_nicename']);
		else
			$category_nicename = sanitize_title($_POST['name']);

		$wpdb->update( $wpdb->terms, array( 'slug' => $category_nicename ), array( 'term_id' => $cat_id ) );
	}
}
add_action('edit_category', 'ecs_edit_slug');

function ecs_remove_sync_category_tag_slugs() {
	remove_filter('get_term', 'sync_category_tag_slugs');
}
add_action('admin_init', 'ecs_remove_sync_category_tag_slugs');
?>