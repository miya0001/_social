<?php

namespace _Social\Share;
use _Social\Share as Share;

class Twitter extends Share
{
	const default_card = "summary_large_image";

	public static function get_instance()
	{
		static $instance;
		if ( ! $instance ) {
			$instance = new Twitter();
		}
		return $instance;
	}

	protected function get_meta_key() {
		return 'name';
	}

	protected function get_data() {
		$data = array(
			'twitter:title' => $this->get_the_title(),
			'twitter:description' => $this->get_the_description(),
			'twitter:card' => $this->get_the_card(),
			'twitter:site' => $this->get_the_site(),
		);

		$src = $this->get_the_image();
		if ( ! empty( $src[0] ) ) {
			$data['twitter:image'] = $src[0];
		}

		return $data;
	}

	private function get_the_site()
	{
		return \_Social::get_instance()->get_option( 'twitter-site' );
	}

	private function get_the_card()
	{
		return \_Social::get_instance()->get_option( 'twitter-card', self::default_card );
	}
}
