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

		add_action( 'wp_head', array( $this, 'wp_head' ) );
		add_filter( 'the_content', array( $this, 'the_content' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
	}

	public function wp_enqueue_scripts()
	{
		if ( is_singular() ) {
			wp_enqueue_style( 'dashicons' );
			wp_enqueue_script(
				'_social',
				plugins_url( 'js/script.min.js', dirname( __FILE__ ) ),
				array(),
				filemtime( dirname( dirname( __FILE__ ) ) . '/js/script.min.js' ),
				true
			);
		}
	}

	public function wp_head()
	{
		_Social\Share\OGP::get_instance()->display();
		_Social\Share\Twitter::get_instance()->display();
	}

	public function the_content( $content )
	{
		if ( ! is_singular() ) {
			return $content;
		}

		$url = urlencode( get_permalink() );
		$title = urlencode( get_the_title() );

		$button = '<div class="underscore-social"><p>';
		$twitter_url = sprintf(
			'https://twitter.com/share?text=%s&url=%s',
			$title,
			$url
		);
		$button .= '<a class="twitter" href="' . esc_url( $twitter_url )
		           . '" rel="nofollow"><span class="dashicons dashicons-twitter"></span></a>';
		$button .= '<a class="facebook" href="http://www.facebook.com/share.php?u=' . $url . '" rel="nofollow"><span class="dashicons dashicons-facebook-alt"></span></a>';
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
