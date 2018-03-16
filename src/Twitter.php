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
			echo $this->twitter_tag( 'twitter:title', esc_attr( get_the_title() ) );
			echo $this->twitter_tag( 'twitter:description', $this->get_the_description() );
			echo $this->twitter_tag( 'twitter:image', $this->get_the_image() );
			$card_type = apply_filters( '_social_twitter_card', 'summary_large_image' );
			echo $this->twitter_tag( 'twitter:card', $card_type );
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
	 * @return string The description of the post.
	 */
	private function get_the_description()
	{
		if ( ! post_password_required() ) {
			$post = $this->post;
			if ( ! empty( $post->post_excerpt ) ) {
				$content = preg_replace( '@https?://[\S]+@', '',
					strip_shortcodes( wp_kses( $post->post_excerpt, array() ) ) );
			} else {
				$exploded_content_on_more_tag = explode( '<!--more-->', $post->post_content );
				$content = wp_trim_words( preg_replace( '@https?://[\S]+@', '',
					strip_shortcodes( wp_kses( $exploded_content_on_more_tag[0], array() ) ) ) );
			}
			return $content;
		}

		return '';
	}

	/**
	 * @return false|string The URL of the post thumbnail.
	 */
	private function get_the_image()
	{
		if ( has_post_thumbnail() ) {
			$size = $card_type = apply_filters( '_social_image_size', 'large' );
			return get_the_post_thumbnail_url( $this->post, $size );
		}

		return false;
	}
}
