<?php

/**
 * Add V2Press theme options menu to Appearance.
 *
 * @since 0.0.1
 */
function vp_theme_options_menu() {
  add_theme_page( 'V2Press Options', 'V2Press Options', 'administrator', 'v2press-options', 'vp_theme_options_page' );
}
add_action( 'admin_menu', 'vp_theme_options_menu' );

/**
 * The options page markup.
 *
 * @since 0.0.1
 */
function vp_theme_options_page() {
?>
<div class="wrap">
  <?php screen_icon(); ?>
  <h2><?php esc_html_e( 'V2Press Options', 'v2press' ); ?></h2>
  <?php if ( isset( $_GET['settings-updated'] ) && 'true' == $_GET['settings-updated'] ) { ?>
  <div id="setting-error-settings_updated" class="updated settings-error"> 
    <p><strong><?php _e( 'Settings saved.' ); ?></strong></p>
  </div>
  <?php } ?>
  <form action="options.php" method="post">
  <?php 
    settings_fields( 'theme_v2press_options' );
    do_settings_sections( 'v2press' );
  ?>
  <input type="submit" name="submit" class="button-primary" value="<?php esc_attr_e( 'Save Changes' ); ?>" />
  </form>
</div>
<?php
}

/**
 * Register what options V2Press need ed.
 *
 * @since 0.0.1
 */
function vp_register_options() {
  register_setting( 'theme_v2press_options', 'theme_v2press_options', 'vp_theme_options_validate' );
  add_settings_section( 'vp-node-sections', __( 'Node Sections', 'v2press' ), 'vp_node_sections_helper_text', 'v2press' ); 
  add_settings_field( 'vp-node-sections-list', __( 'Section Names', 'v2press' ), 'vp_node_sections_field' , 'v2press', 'vp-node-sections' );
}
add_action( 'admin_init', 'vp_register_options' );

/**
 * The helper text display just below the 'Node Sections'.
 *
 * @since 0.0.1
 */
function vp_node_sections_helper_text() {
  echo '<p>' . __( 'Set what section a regular category belongs to.', 'v2press' ) . '</p>';
}

/**
 * The 'Section Name' field.
 *
 * @since 0.0.1
 */
function vp_node_sections_field() {
  $options = get_option( 'theme_v2press_options' );
  
  $output = '<textarea name="theme_v2press_options[sections-list]" rows="5" cols="50">' . stripslashes( $options['sections-list'] ) . '</textarea>';
  $output .= '<p class="description">' . __( 'Please enter section names, delimited with commas(,).', 'v2press' ) . '</p>';
  
  echo $output;
}

/**
 * Validate option values.
 *
 * @since 0.0.1
 */
function vp_theme_options_validate($input) {
  $input['sections-list'] = wp_filter_nohtml_kses( $input['sections-list'] );
  return $input;
}