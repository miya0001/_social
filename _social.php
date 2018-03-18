<?php
/**
 * Plugin Name:     _social
 * Plugin URI:      https://github.com/miya0001/_social
 * Description:     A WordPress plugin to share the contents
 * Author:          Takayuki Miyauchi
 * Author URI:      https://miya.io/
 * Text Domain:     _social
 * Domain Path:     /languages
 * Version:         nightly
 *
 * @package         _social
 */


require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );

add_action( 'init', '_social_activate_updater' );

function _social_activate_updater() {
	$plugin_slug = plugin_basename( __FILE__ );
	$gh_user = 'miya0001';
	$gh_repo = '_social';

	new Miya\WP\GH_Auto_Updater( $plugin_slug, $gh_user, $gh_repo );
}

_Social::get_instance()->register();
