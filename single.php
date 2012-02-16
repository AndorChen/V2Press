<?php
if ( isset( $_GET['edit'] ) && 'true' == $_GET['edit'] ) {
  get_template_part( 'content', 'edit' );
} else {
  get_template_part( 'content', 'single' );
}
?>