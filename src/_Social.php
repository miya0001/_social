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
		add_filter( 'the_content', array( $this, 'the_content' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
	}

	public function init()
	{
		$plugin_slug = plugin_basename( __FILE__ );
		$gh_user = 'miya0001';
		$gh_repo = '_social';

		new Miya\WP\GH_Auto_Updater( $plugin_slug, $gh_user, $gh_repo );
	}

	public function wp_enqueue_scripts()
	{
		wp_enqueue_style( 'dashicons' );
	}

	public function wp_head()
	{
		_Social\Share\OGP::get_instance()->display();
		_Social\Share\Twitter::get_instance()->display();
		?>
		<style>
			._share
			{
				margin-top: 2em;
			}

			._share a,
			._share a:link,
			._share a:visited,
			._share a:hover,
			._share a:active
			{
				text-decoration: none;
				box-shadow: none;
				border: none;
			}

			._share .dashicons,
			._share .dashicons-before::before
			{
				font-size: 30px;
				line-height: 30px;
				height: 30px;
				margin-right: 16px;
				color: #555555;
			}
		</style>
		<?php
	}

	public function the_content( $content )
	{
		$url = urlencode( get_permalink() );
		$title = urlencode( get_the_title() );

		$button = '<div class="_share"><p>';
		$button .= '<a href="https://twitter.com/share?text=' . $title . '" rel="nofollow" onClick="window.open(encodeURI(decodeURI(this.href)),\'twwindow\',\'width=550, height=450, personalbar=0, toolbar=0, scrollbars=1\'); return false;"><span class="dashicons dashicons-twitter"></span></a>';
		$button .= '<a href="http://www.facebook.com/share.php?u=' . $url . '" onclick="window.open(this.href,\'FBwindow\',\'width=650,height=450,menubar=no,toolbar=no,scrollbars=yes\');return false;" rel="nofollow"><span class="dashicons dashicons-facebook"></span></a>';
		$button .= '</p></div>';

		return $content . $button;
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
