<?php

namespace _Social;

class Twitter
{
	/**
	 * @var $post \WP_Post
	 */
	private $post;

	public function __construct()
	{
		if ( is_singular() ) {
			$this->post = get_post( get_the_ID() );
		}
	}

	public static function get_instance()
	{
		static $instance;
		if ( ! $instance ) {
			$instance = new Twitter();
		}
		return $instance;
	}

	public function display()
	{
		echo "<!-- _Social Twitter Tags -->\n";

		if ( is_singular() ) {
			$post = get_post( get_the_ID() );
			echo $this->twitter_tag( 'twitter:text:title', esc_attr( get_the_title() ) );
			echo $this->twitter_tag( 'twitter:image', $this->get_the_image() );
			echo $this->twitter_tag( 'twitter:card', 'summary_large_image' );
		}

		echo "<!-- End _Social Twitter Tags -->\n";
	}

	/**
	 * @param string $property
	 * @param string $content
	 * @return string The Open Graph Tag.
	 */
	private function twitter_tag( $property, $content )
	{
		if ( ! empty( $content ) ) {
			$meta = '<meta name="%s" content="%s" />' . "\n";
			return sprintf( $meta, $property, $content );
		}

		return "";
	}

	/**
	 * @return false|string The URL of the post thumbnail.
	 */
	private function get_the_image()
	{
		if ( has_post_thumbnail() ) {
			return get_the_post_thumbnail_url( $this->post, 'large' );
		}

		return false;
	}
}
