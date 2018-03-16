<?php
/**
 * Class SampleTest
 *
 * @package _social
 */

/**
 * Sample test case.
 */
class SampleTest extends WP_UnitTestCase
{
	public function test_og_description()
	{
		$post = $this->factory()->post->create_and_get( array(
			'post_title' => 'Hello World',
			'post_excerpt' => 'Hi! Are you happy?',
		) );

		$this->go_to( get_permalink( $post->ID ) );

		$ogp = _Social\OGP::get_instance();
		$method = new ReflectionMethod( get_class( $ogp ), 'get_the_og_description' );
		$method->setAccessible( true );
		$res = $method->invoke( $ogp );
		$this->assertSame( "Hi! Are you happy?", $res );
	}

	public function test_og_description_with_no_excerpt()
	{
		$post = $this->factory()->post->create_and_get( array(
			'post_title' => 'Hello World',
			'post_content' => 'Hi! Are you happy?',
			'post_excerpt' => '',
		) );

		$this->go_to( get_permalink( $post->ID ) );

		$ogp = _Social\OGP::get_instance();
		$method = new ReflectionMethod( get_class( $ogp ), 'get_the_og_description' );
		$method->setAccessible( true );
		$res = $method->invoke( $ogp );
		$this->assertSame( "Hi! Are you happy?", $res );
	}
}
