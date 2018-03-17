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

		$ogp = _Social\Share\OGP::get_instance();
		$method = new ReflectionMethod( get_class( $ogp ), 'get_the_description' );
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

		$ogp = _Social\Share\OGP::get_instance();
		$method = new ReflectionMethod( get_class( $ogp ), 'get_the_description' );
		$method->setAccessible( true );
		$res = $method->invoke( $ogp );
		$this->assertSame( "Hi! Are you happy?", $res );
	}

	public function test_get_data()
	{
		$post = $this->factory()->post->create_and_get( array(
			'post_title' => 'Hello World',
			'post_content' => 'Hi! Are you happy?',
			'post_excerpt' => '',
		) );

		$obj = _Social\Share\OGP::get_instance();
		$method = new ReflectionMethod( get_class( $obj ), 'get_data' );
		$method->setAccessible( true );

		$this->go_to( get_permalink( $post->ID ) );
		$res = $method->invoke( $obj );
		$this->assertSame( 10, count( array_keys( $res ) ) );

		$this->go_to( '/' );
		$res = $method->invoke( $obj );
		$this->assertSame( 7, count( array_keys( $res ) ) );
	}
}
