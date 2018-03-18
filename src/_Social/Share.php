<?php

namespace _Social;

abstract class Share
{
	/**
	 * @var $post \WP_Post
	 */
	private $post;

	/**
	 * @return string The meta key of the meta tag. e.g, `name`, `property`.
	 */
	abstract protected function get_meta_key();

	/**
	 * @return array An array of the key and value for meta tags.
	 */
	abstract protected function get_data();

	abstract public static function get_instance();

	public function __construct()
	{
	}

	public function display()
	{
		$data = $this->get_data();

		$meta = '<meta %s="%s" content="%s" />' . "\n";
		echo '<!-- _share -->' . "\n";
		foreach ( $data as $key => $value ) {
			if ( trim( $key ) && trim( $value ) ) {
				printf(
					$meta,
					esc_attr( $this->get_meta_key() ),
					esc_attr( $key ),
					esc_attr( $value )
				);
			}
		}
		echo '<!-- End _share -->' . "\n";
	}

	/**
	 * @return string The URL.
	 */
	protected function get_the_url()
	{
		if ( is_home() && is_front_page() ) {
			return esc_url( home_url( '/' ) );
		} elseif ( is_singular() ) {
			return esc_url( get_permalink() );
		}
	}

	/**
	 * @return string The title.
	 */
	protected function get_the_title()
	{
		if ( is_home() && is_front_page() ) {
			return get_bloginfo( 'name' );
		} elseif ( is_singular() ) {
			return get_the_title();
		}
	}

	/**
	 * @return string The description.
	 */
	protected function get_the_description()
	{
		if ( is_home() && is_front_page() ) {
			return get_bloginfo( 'description' );
		} elseif ( is_singular() ) {
			if ( ! post_password_required() ) {
				$post = get_post( get_the_ID() );
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
		}

		return '';
	}

	/**
	 * @return array An array of the width and height of the post thumbnail.
	 */
	protected function get_the_image()
	{
		if ( is_singular() ) {
			if ( has_post_thumbnail() ) {
				$id = get_post_thumbnail_id();
				$size = $card_type = apply_filters( '_social_image_size', 'large' );
				return wp_get_attachment_image_src( $id, $size );
			}
		}

		return array(
			\_Social::get_instance()->get_option( 'default-image', null ),
			false,
			false,
			false
		);
	}
}
