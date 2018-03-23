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
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				wp_enqueue_style(
					'_social',
					plugins_url( 'src/style.css', dirname( __FILE__ ) ),
					array( 'dashicons' ),
					filemtime( dirname( dirname( __FILE__ ) ) . '/src/style.css' )
				);
				wp_enqueue_script(
					'_social',
					plugins_url( 'src/script.js', dirname( __FILE__ ) ),
					array(),
					filemtime( dirname( dirname( __FILE__ ) ) . '/src/script.js' ),
					true
				);
			} else {
				wp_enqueue_style(
					'_social',
					plugins_url( 'css/style.min.css', dirname( __FILE__ ) ),
					array( 'dashicons' ),
					filemtime( dirname( dirname( __FILE__ ) ) . '/css/style.min.css' )
				);
				wp_enqueue_script(
					'_social',
					plugins_url( 'js/script.min.js', dirname( __FILE__ ) ),
					array(),
					filemtime( dirname( dirname( __FILE__ ) ) . '/js/script.min.js' ),
					true
				);
			}
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
		$button .= '<a class="twitter" href="https://twitter.com/share?text=' . $title . '" rel="nofollow"><span class="dashicons dashicons-twitter"></span></a>';
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
