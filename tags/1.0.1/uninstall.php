<?php

// if uninstall not called from WordPress exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}


defined( 'ACOBOT_SLUG' ) || define( 'ACOBOT_SLUG', '_acobot_' );

$opts   = wp_load_alloptions();
foreach ( $opts as $key => $value ) {
	if ( strpos( $key, ACOBOT_SLUG ) === 0 ) {
		delete_option( $key );
	}
}
