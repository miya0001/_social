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

// Autoload
require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );

class _Social
{
	public function __construct()
	{
	}

	public static function get_instance()
	{
		static $instance;
		if ( ! $instance ) {
			$instance = new _Social();
		}
		return $instance;
	}

	public function register()
	{
		if ( is_admin() ) {
			_Social\Admin::get_instance()->register();
		}

		add_action( 'init', array( $this, 'init' ) );
		add_action( 'wp_head', array( $this, 'wp_head' ) );
	}

	public function init()
	{
		$plugin_slug = plugin_basename( __FILE__ );
		$gh_user = 'miya0001';
		$gh_repo = '_social';

		new Miya\WP\GH_Auto_Updater( $plugin_slug, $gh_user, $gh_repo );
	}

	public function wp_head()
	{
		_Social\OGP::get_instance()->display();
		_Social\Twitter::get_instance()->display();
	}
}

_Social::get_instance()->register();
