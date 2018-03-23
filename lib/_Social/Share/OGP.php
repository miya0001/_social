<?php

namespace _Social\Share;
use _Social\Share as Share;

class OGP extends Share
{
	const default_type = 'website';

	public static function get_instance()
	{
		static $instance;
		if ( ! $instance ) {
			$instance = new OGP();
		}
		return $instance;
	}

	protected function get_meta_key() {
		return 'property';
	}

	protected function get_data() {
		$data = array(
			'og:type' => $this->get_the_og_type(),
			'og:title' => $this->get_the_title(),
			'og:description' => $this->get_the_description(),
			'og:url' => $this->get_the_url(),
			'fb:app_id' => \_Social::get_instance()->get_option( 'fb-app_id' ),
			'fb:admins' => \_Social::get_instance()->get_option( 'fb-admins' ),
		);

		$src = $this->get_the_image();
		if ( ! empty( $src[0] ) ) {
			$data['og:image'] = $src[0];
			if ( ! empty( $src[1] ) && ! empty( $src[2] ) ) {
				$data['og:image:width'] = $src[1];
				$data['og:image:height'] = $src[2];
			}
		}

		if ( is_singular() ) {
			$post = get_post( get_the_ID() );
			$data['og:site_name'] = get_bloginfo( 'name' );
			$data['article:published_time'] = date( 'c', strtotime( $post->post_date_gmt ) );
			$data['article:modified_time'] = date( 'c', strtotime( $post->post_modified_gmt ) );
		}

		return $data;
	}

	private function get_the_og_type()
	{
		if ( is_home() && is_front_page() ) {
			return \_Social::get_instance()->get_option( 'fb-type', self::default_type );
		} elseif ( is_singular() ) {
			return 'article';
		}
	}
}
