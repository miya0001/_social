<?php

namespace _Social\Share;
use _Social\Share as Share;

class OGP extends Share
{
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
			'og:image' => $this->get_the_image_url(),
			'fb-app_id' => \_Social::get_instance()->get_option( 'fb-app_id' ),
			'fb-admins' => \_Social::get_instance()->get_option( 'fb-admins' ),
		);

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
			return 'website';
		} elseif ( is_singular() ) {
			return 'article';
		}
	}
}
