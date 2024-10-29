<?php
/**
 * Plugin Name: Acobot Contact Chatbot
 * Plugin URI: https://acobot.ai/p/wordpress
 * Description: Acobot Contact Chatbot
 * Version: 1.0.1
 * Author: Acobot LLC
 * Author URI: https://acobot.ai/
 * License: GPL2
 * Text-Domain: acobot
 * Domain Path: /languages
 */

/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Abort loading if WordPress is upgrading
 */
if ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) {
	return;
}

add_action( 'activated_plugin', 'acobot_activate' );
function acobot_activate( $plugin ) {
	if ( $plugin == plugin_basename( __FILE__ ) ) {
		wp_safe_redirect( admin_url( 'options-general.php?page=acobot' ) );
		exit();
	}
}

add_action( 'plugins_loaded', 'acobot_run', 0 );
function acobot_run() {
	define( 'ACOBOT_SLUG', '_acobot_' );

	add_action( 'admin_init', 'acobot_register_settings' );
	add_action( 'admin_menu', 'acobot_menu' );
	add_action( 'wp_footer', 'acobot_add_script' );
}

function acobot_add_script() {
	$email = get_option(ACOBOT_SLUG . 'email');
    if(empty($email)){
        $email = get_option('admin_email');
    }        
    
	if ( !empty( $email ) ) {
        $src = "https://acobot.ai/js/cbw/$email";
        $in_footer = true;
        wp_enqueue_script( 'acobot-contact-bot', $src, array(), false, $in_footer);
	}

}

function acobot_menu() {
	add_submenu_page( 'options-general.php', __( 'Acobot Settings', 'acobot' ), __( 'Acobot', 'acobot' ), 'manage_options', 'acobot', 'acobot_settings' );
}

function acobot_register_settings() {
	register_setting( 'acobot', ACOBOT_SLUG . 'email' );
	add_settings_section( 'acobot', __( 'Acobot', 'acobot' ), 'acobot_settings_section', 'acobot' );
	//add_settings_field( ACOBOT_SLUG . 'api', __( 'API Key', 'acobot' ), 'acbobot_settings_api', 'acobot', 'acobot' );
	add_settings_field( ACOBOT_SLUG . 'email', __( 'Email', 'acobot' ), 'acobot_settings_email', 'acobot', 'acobot');
}

function acobot_settings_section() {
	echo '<p>' . __( 'Replace your contact form with this chatbot and impress your customers with a unique conversational experience. ', 'acobot' ) . '</p>';
    echo '<p>' . __( 'A contact button will show up on every public page of your website after you enable and set it up. ', 'acobot' ) . '</p>';
    echo '<p>' . __( 'The contact information will be delivered to admin email or any email address you specify. ', 'acobot' ) . '</p>';
}

function acobot_settings_email() {
	echo '<input type="text" class="regular-text" name="' . ACOBOT_SLUG . 'email" placeholder="' . __( 'Email', 'acobot' ) . '" value="' . get_option( ACOBOT_SLUG . 'email' ) . '">';
	get_option( ACOBOT_SLUG . 'email' );
	echo '<p class="description">'._('Email address to receive the contact information. Leave it blank to use admin email. ').'</p>';
}

function acobot_settings() {
?>
	<div class='wrap'>
		  <h2><?php _e( 'Settings', 'acobot' ); ?></h2>
		  <form method='post' action='options.php'>
			<?php
		    	settings_fields( 'acobot' );
			    do_settings_sections( 'acobot' );
			    submit_button();
			?>
		  </form>
	 </div>
<?php
}
