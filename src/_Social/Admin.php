<?php

namespace _Social;
use _Social\Share\Twitter;

/**
 * Customize the list table on the admin screen.
 *
 * @package LogBook
 */
final class Admin
{
	const default_color = '#0084FF';
	private $prefix;

	public function __construct()
	{
		$this->prefix = \_Social::get_instance()->get_prefix();
	}

	public static function get_instance()
	{
		static $instance;
		if ( ! $instance ) {
			$instance = new Admin();
		}
		return $instance;
	}

	public function register()
	{
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}

	public function admin_menu() {
		add_options_page(
			'Social Share',
			'Social Share',
			'manage_options',
			$this->prefix,
			array( $this, "display" )
		);
	}

	public function admin_init()
	{
		register_setting( '_social-settings', $this->prefix );

		add_settings_section( 'ogp-settings', 'Facebook Open Graph', null, $this->prefix );
		add_settings_section( 'twitter-settings', 'Twitter Cards', function() {
			echo '<p>The setting section for the <a href="https://dev.twitter.com/web/sign-inhttps://dev.twitter.com/cards/overview.html">Twitter Cards</a>.</p>';
		}, $this->prefix );

		add_settings_field(
			'app_id',
			'fb:app_id',
			function() {
				$value = esc_attr( \_Social::get_instance()->get_option( 'fb-app_id' ) );
				$prefix = esc_attr( $this->prefix );
				echo "<input type='text' name='{$prefix}[fb-app_id]' value='$value' />";
			},
			$this->prefix,
			'ogp-settings'
		);

		add_settings_field(
			'admins',
			'fb:admins',
			function() {
				$value = esc_attr( \_Social::get_instance()->get_option( 'fb-admins' ) );
				$prefix = esc_attr( $this->prefix );
				echo "<input type='text' name='{$prefix}[fb-admins]' value='$value' />";
			},
			$this->prefix,
			'ogp-settings'
		);

		add_settings_field(
			'twitter-card',
			'twitter:card',
			function() {
				$value = esc_attr( \_Social::get_instance()->get_option( 'twitter-card' ) );
				$prefix = esc_attr( $this->prefix );
				echo "<input type='text' name='{$prefix}[twitter-card]' value='" . esc_attr( $value ) . "' placeholder='" . esc_attr( Twitter::default_card ) . "' />";
				echo "<p class=\"description\" style=\"width: auto;\">The type of card for your content, <code>summary</code> or <code>summary_large_image</code>. The default value is <code>" . esc_html( Twitter::default_card ) . "</code>.</p>";
			},
			$this->prefix,
			'twitter-settings'
		);

		add_settings_field(
			'twitter-site',
			'twitter:site',
			function() {
				$value = esc_attr( \_Social::get_instance()->get_option( 'twitter-site' ) );
				$prefix = esc_attr( $this->prefix );
				echo "<input type='text' name='{$prefix}[twitter-site]' value='$value' placeholder='@username' />";
				echo "<p class=\"description\" style=\"width: auto;\">The Twitter username of the owner of this site's domain.</p>";
			},
			$this->prefix,
			'twitter-settings'
		);
	}

	public function display()
	{
		$action = untrailingslashit( admin_url() ) . '/options.php';
		?>
		<div class="wrap _social-settings">
			<h1 class="wp-heading-inline">Customer Chat Settings</h1>
			<form action="<?php echo esc_url( $action ); ?>" method="post">
				<?php
				settings_fields($this->prefix . '-settings');
				do_settings_sections( $this->prefix );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}
}
