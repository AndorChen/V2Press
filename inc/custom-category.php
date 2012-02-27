<?php
/**
 * This file includes all functionalities related to category.
 *
 * @since 0.0.1
 */

/* =============================================================================
 * Add extra meta box to create/edit category page.
 *
 * @since 0.0.1
 ============================================================================ */

/**
 * Add custom meta box to add new category page.
 *
 * @since 0.0.1
 */
function vp_category_add_meta_field() {
?>
<div class="form-field">
	<label for="category_meta[vp_category_logo_url]"><?php _e( 'Logo URL', 'v2press' ); ?></label>
	<input type="text" name="category_meta[vp_category_logo_url]" id="category_meta[vp_category_logo_url]" value="">
	<p><?php _e( 'The category logo will display in the category show page. You should upload logo image into V2Press\'s images/logo directory, and enter the image name only. The image size is 72x72px.', 'v2press' ); ?></p>
</div>
<div class="form-field">
  <label for="category_meta[vp_category_description_sidebar]"><?php _e( 'Description in sidebar', 'v2press' ); ?></label>
  <textarea name="category_meta[vp_category_description_sidebar]" id="category_meta[vp_category_description_sidebar]" rows="10" cols="40"></textarea>
  <p><?php _e( 'The description text in sidebar. This should content some more detailed infomation about this category and should use some HTML for format.', 'v2press' ); ?></p>
</div>
<div class="form-field">
  <label for="category_meta[vp_category_section]"><?php _e( 'Section', 'v2press' ); ?></label>
  <?php vp_section_dropdown_list(); ?>
  <p><?php _e( 'Please select what section this category belongs to.', 'v2press' ); ?></p>
</div>
<?php
}
add_action( 'category_add_form_fields', 'vp_category_add_meta_field' );

/**
 * Add custom meta box to edit category page.
 *
 * @since 0.0.1
 */
function vp_taxonomy_edit_meta_field( $term ) {

	$t_id = $term->term_id;

	// Retrieve the existing value(s) for this meta field
	// This returns an array
	$term_meta = get_option( "v2press_category_$t_id" );
?>
<tr class="form-field">
	<th scope="row" valign="top">
	  <label for="category_meta[vp_category_logo_url]"><?php _e( 'Logo URL', 'v2press' ); ?></label>
	</th>
	<td>
		<input type="text" name="category_meta[vp_category_logo_url]" id="category_meta[vp_category_logo_url]" value="<?php echo esc_attr( $term_meta['vp_category_logo_url'] ) ? esc_attr( $term_meta['vp_category_logo_url'] ) : ''; ?>">
		<p class="description"><?php _e( 'The category logo will display in the category show page. You should upload logo image into V2Press\'s images/logo directory, and enter the image name only. The image size is 72x72px.', 'v2press' ); ?></p>
	</td>
</tr>
<tr class="form-field">
	<th scope="row" valign="top">
	  <label for="category_meta[vp_category_description_sidebar]"><?php _e( 'Description in sidebar', 'v2press' ); ?></label>
	</th>
	<td>
    <textarea name="category_meta[vp_category_description_sidebar]" id="category_meta[vp_category_description_sidebar]" rows="10" cols="40"><?php echo esc_attr($term_meta['vp_category_description_sidebar']) ? esc_attr($term_meta['vp_category_description_sidebar']) : ''; ?></textarea>
		<p class="description"><?php _e( 'The description text in sidebar. This should content some more detailed infomation about this category and should use some HTML for format.', 'v2press' ); ?></p>
	</td>
</tr>
<tr class="form-field">
  <th scope="row" valign="top">
    <label for="category_meta[vp_category_section]"><?php _e( 'Section', 'v2press' ); ?></label>
  </th>
  <td>
    <?php vp_section_dropdown_list(); ?>
    <p class="description"><?php _e( 'Please select what section this category belongs to.', 'v2press' ); ?></p>
  </td>
</tr>
<?php
}
add_action( 'category_edit_form_fields', 'vp_taxonomy_edit_meta_field' );

/**
 * Manipulation the add and edit category form action.
 *
 * @since 0.0.1
 */
function vp_category_save_custom_field( $term_id ) {
	if ( isset( $_POST['category_meta'] ) ) {
		$t_id = $term_id;
		$term_meta = get_option( "v2press_category_$t_id" );

		$cat_keys = array_keys( $_POST['category_meta'] );
		foreach ( $cat_keys as $key ) {
			if ( isset ( $_POST['category_meta'][$key] ) && '-1' != $_POST['category_meta'][$key] ) {
				$term_meta[$key] = stripslashes( $_POST['category_meta'][$key] );
			}
		}

    vp_update_section_option( $_POST['category_meta']['vp_category_section'], $t_id );
		update_option( "v2press_category_$t_id", $term_meta );
	}
}
add_action( 'create_category', 'vp_category_save_custom_field', 10, 2 );
add_action( 'edited_category', 'vp_category_save_custom_field', 10, 2 );


/* =============================================================================
 * Category related template tags.
 *
 * @since 0.0.1
 ============================================================================ */

/**
 * Retrieve the custom category meta field in frontend category display page.
 *
 * @since 0.0.1
 *
 * @param string $meta The meta field you want to retrieve.
 * @return false|string Return false when the parameter is not right, or the retrieved meta field value.
 */
function vp_get_category_meta( $meta='' ) {
  if ( empty( $meta ) )
    return false;

  $term = get_queried_object();
  $t_id = $term->term_id;
  $option = get_option("v2press_category_$t_id");

  switch( $meta ) {
    case 'logo':
    defalt:
      $image =  $option['vp_category_logo_url'];
      if ( empty( $image ) )
        $image = 'default-node-logo.png';

      $url = get_stylesheet_directory_uri() . '/images/logo/' . $image;
      return $url;
      break;
    
    case 'description_sidebar':
      return $option['vp_category_description_sidebar'];
      break;
  }
}

/* =============================================================================
 * Parse sections config and generate node navigation output.
 *
 * @since 0.0.1
 ============================================================================ */

/**
 * Load the sections from theme options.
 *
 * @since 0.0.1
 * @for vp_node_navi()
 */
function vp_load_sections() {
  $sections = vp_get_theme_option( 'sections' );
  if ( !$sections ) {
    $sections = array();
  } else {
    $sections = split( ',', $sections );
    $sections = array_map( 'trim', $sections ); // trim spaces
    $sections = array_filter( $sections ); // remove empty element
  }

  return $sections;
}

/**
 * Generate a dropdown select list of available sections.
 *
 * @since 0.0.1
 * @use vp_load_sections()
 */
function vp_section_dropdown_list() {
  $sections = vp_load_sections();

  $output = '<select name="category_meta[vp_category_section]">';
  $output .= '<option value="-1">' . __( 'None' ) . '</option>';

  if ( !empty( $sections ) ) {
    if ( isset( $_GET['tag_ID'] ) ) {
      $term_id = $_GET['tag_ID'];
      $options = get_option( "v2press_category_$term_id" );

      if ( is_array( $options ) && array_key_exists( 'vp_category_section', $options ) )
        $belong_to = $options['vp_category_section'];
    } else {
      $belong_to = '';
    }

    foreach ( $sections as $sec ) {
      $output .= '<option value="' . $sec . '"';
      if ( !empty( $belong_to ) && $belong_to == $sec ) {
        $output .= ' selected="selected"';
      }
      $output .= '>' . $sec . '</option>';
    }
  }

  $output .= '</select>';
  echo $output;
}

/**
 * Update the section contents which nodes option.
 *
 * @since 0.0.1
 * @param string $sec The section name.
 * @param int $cat_id The category ID.
 */
function vp_update_section_option( $sec, $cat_id ) {
  // delete from old section
  $old_options = get_option( "v2press_category_{$cat_id}" );
  if ( is_array( $$old_options ) && array_key_exists( 'vp_category_section', $old_options ) )
    $old_sec = $old_options['vp_category_section'];
  else
    $old_sec = '';

  if ( !empty( $old_sec ) && $sec != $old_sec ) {
    $old_sec_list = get_option( "v2press_section_{$old_sec}" );
    if ( !is_array( $old_sec_list ) ) $old_sec_list = array();
    $old_sec_list = array_diff( $old_sec_list, array( $cat_id ) );
    update_option( "v2press_section_{$old_sec}", $old_sec_list );
  }
  
  // update new section option
  $option_name = "v2press_section_$sec";
  $cats = get_option( $option_name );
  if ( !is_array( $cats ) || empty( $cats ) )
    $cats = array();

  if ( in_array( $cat_id, $cats ) )
    return;

  $cats[] = $cat_id;
  sort( $cats );

  update_option( $option_name, $cats );
}

/**
 * Generate the node navigation in home.php
 *
 * @since 0.0.1
 *
 * @use vp_load_sections()
 */
function vp_node_navi() {
  $sections = vp_load_sections();
  if ( empty( $sections ) ) {
    echo '<p class="inner xlarge fade center">' . __( 'No sections yet.' ,'v2press' ) . '</p>';
    return;
  }

  $output = '<ul class="node-navi-list">';
  foreach ( $sections as $sec ) {
    $output .= '<li class="node-navi-section"><h3 class="node-navi-section-title">' . $sec . '</h3>';

    $cats = get_option( "v2press_section_$sec" );
		if ( empty( $cats ) ) {
			$output .= '<p class="fade center">' . __( 'No nodes yet.', 'v2press' ) . '</p>';
			continue;
		}

    $output .= '<ul class="node-navi-nodes">';
    foreach ( $cats as $cat ) {
      $node = get_term( $cat, 'category' );
      if ( !$node )
        continue;

      $node_link = get_category_link( $node );
      $output .= '<li class="node-navi-cat-item"><a href="' . $node_link . '">' . $node->name . '</a></li>';
    }

    $output .= '</ul>';
  }
  $output .= '</ul>';

  echo $output;
}
