<?php
/**
 * V2Press version constants.
 * Mimic how Rails does.
 *
 * @since 0.0.2
 */

define( 'VP_VERSION_MAJOY', '0' );
define( 'VP_VERSION_MINOR', '0' );
define( 'VP_VERSION_TINY', '2' );
define( 'VP_VERSION_PRE', 'alpha' );

$version = join( '.', array( VP_VERSION_MAJOY, VP_VERSION_MINOR, VP_VERSION_TINY, VP_VERSION_PRE ) );
define( 'VP_VERSION', $version );