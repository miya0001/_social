<?php

namespace _Social;

class OGP
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
			$instance = new OGP();
		}
		return $instance;
	}

	public function display()
	{
		echo "<!-- _Social Open Graph Tags -->\n";

		if ( is_home() || is_front_page() ) {
			echo $this->og_tag( 'og:type', 'website' );
			echo $this->og_tag( 'og:title', esc_attr( get_bloginfo( 'name' ) ) );
			echo $this->og_tag( 'og:description', esc_attr( get_bloginfo( 'description' ) ) );
			echo $this->og_tag( 'og:url', esc_url( home_url( '/' ) ) );
		} elseif ( is_singular() ) {
			$post = $this->post;
			echo $this->og_tag( 'og:type', 'article' );
			echo $this->og_tag( 'og:site_name', esc_attr( get_bloginfo( 'name' ) ) );
			echo $this->og_tag( 'og:title', esc_attr( get_the_title() ) );
			echo $this->og_tag( 'og:url', esc_url( get_permalink() ) );
			echo $this->og_tag( 'og:description', $this->get_the_og_description() );
			echo $this->og_tag( 'article:published_time',
					date( 'c', strtotime( $post->post_date_gmt ) ) );
			echo $this->og_tag( 'article:modified_time',
					date( 'c', strtotime( $post->post_modified_gmt ) ) );
			echo $this->og_tag( 'og:image', $this->get_the_og_image() );
		}

		echo "<!-- End _Social Open Graph Tags -->\n";
	}

	/**
	 * @param string $property
	 * @param string $content
	 *
	 * @return string The Open Graph Tag.
	 */
	private function og_tag( $property, $content )
	{
		if ( ! empty( $content ) ) {
			$meta = '<meta property="%s" content="%s" />' . "\n";
			return sprintf( $meta, $property, $content );
		}

		return "";
	}

	/**
	 * @return string The description of the post.
	 */
	private function get_the_og_description()
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
	private function get_the_og_image()
	{
		if ( has_post_thumbnail() ) {
			return get_the_post_thumbnail_url();
		}

		return false;
	}
}
