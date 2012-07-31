<?php
if ( isset( $_GET['edit'] ) && 'true' == $_GET['edit'] ) {
    vp_get_template_part( 'content', 'edit' );
} else {
    vp_get_template_part( 'content', 'single' );
}
?>
