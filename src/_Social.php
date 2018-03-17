<?php

class _Social
{
	private $prefix = '_social';

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
		_Social\Share\OGP::get_instance()->display();
		_Social\Share\Twitter::get_instance()->display();
	}

	public function get_prefix()
	{
		return $this->prefix;
	}

	public function get_option( $key, $default = null )
	{
		$option = get_option( $this->get_prefix(), array() );
		if ( ! empty( $option[ $key ] ) && trim( $option[ $key ] ) ) {
			return trim( $option[ $key ] );
		} else {
			return $default;
		}
	}
}
