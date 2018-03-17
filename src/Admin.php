<?php

namespace _Social;

/**
 * Customize the list table on the admin screen.
 *
 * @package LogBook
 */
final class Admin
{
	const default_color = '#0084FF';

	public function __construct()
	{
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
		if ( ! empty( $_GET['page'] ) && '_social' === $_GET['page'] ) {
			add_action( 'admin_enqueue_scripts', function () {
				wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_style(
					'_social',
					plugins_url( 'css/style.css', dirname( __FILE__ ) )
				);
				wp_enqueue_script(
					'_social',
					plugins_url( 'js/script.js', dirname( __FILE__ ) ),
					array( 'wp-color-picker' ),
					false,
					true
				);
			} );
		}
	}

	public function admin_menu() {
		add_options_page(
			'Customer Chat',
			'Customer Chat',
			'manage_options',
			'_social',
			array( $this, "display" )
		);
	}

	public function admin_init()
	{
		register_setting( '_social-settings', '_social' );

		add_settings_section( 'app-settings', 'Facebook App', null, '_social' );
		add_settings_section( 'page-settings', 'Customer Chat Plugin', function() {
			$url = 'https://developers.facebook.com/docs/messenger-platform/reference/web-plugins/#customer_chat';
			echo 'Options for the customer chat plugin for facebook. ';
			echo 'For usage details, <a href="' . $url . '">see documentation</a>.';
		}, '_social' );

		add_settings_field(
			'app_id',
			'App ID',
			function() {
				$app_id = esc_attr( get_app_id() );
				if ( defined( 'FB_CUSTOMER_CHAT_APP_ID' ) && FB_CUSTOMER_CHAT_APP_ID ) {
					echo "<input type='text' name='_social[app_id]' value='$app_id' required disabled />";
					echo ' <a href="https://developers.facebook.com/apps/">Create a new facebook app.</a>';
				} else {
					echo "<input type='text' name='_social[app_id]' value='$app_id' required />";
					echo ' <a href="https://developers.facebook.com/apps/">Create a new facebook app.</a>';
				}
			},
			'_social',
			'app-settings'
		);

		add_settings_field(
			'lang',
			'Language',
			function() {
				$lang = esc_attr( get_lang() );
				echo "<input type='text' name='_social[lang]' value='$lang' required />";
			},
			'_social',
			'app-settings'
		);

		add_settings_field(
			'page_id',
			'page_id',
			function() {
				$page_id = esc_attr( get_page_id() );
				if ( defined( 'FB_CUSTOMER_CHAT_PAGE_ID' ) && FB_CUSTOMER_CHAT_PAGE_ID ) {
					echo "<input type='text' name='_social[page_id]' value='$page_id' required disabled />";
				} else {
					echo "<input type='text' name='_social[page_id]' value='$page_id' required />";
				}
				echo "<p class='description'>Your <a href='https://findmyfbid.com/'>page ID</a>.</p>";
			},
			'_social',
			'page-settings'
		);

		add_settings_field(
			'ref',
			'ref',
			function() {
				$ref = esc_attr( get_ref() );
				echo "<input type='text' name='_social[ref]' value='$ref' />";
				echo "<p class='description'><strong>Optional.</strong>
						Custom string passed to your webhook in <code>messaging_postbacks</code> 
								and <code>messaging_referrals</code> events.</p>";
			},
			'_social',
			'page-settings'
		);

		add_settings_field(
			'minimized',
			'minimized',
			function() {
				$minimized = esc_attr( is_minimized() );

				if ( 'true' === $minimized ) {
					echo "<label><input type='radio' name='_social[minimized]' value='auto' /> Auto</label>";
					echo "<label style='margin-left: 20px;'><input type='radio' name='_social[minimized]' value='true' checked /> true</label>";
					echo "<label style='margin-left: 20px;'><input type='radio' name='_social[minimized]' value='false' /> false</label>";
				} elseif( 'false' === $minimized ) {
					echo "<label><input type='radio' name='_social[minimized]' value='auto' /> Auto</label>";
					echo "<label style='margin-left: 20px;'><input type='radio' name='_social[minimized]' value='true' /> true</label>";
					echo "<label style='margin-left: 20px;'><input type='radio' name='_social[minimized]' value='false' checked /> false</label>";
				} else {
					echo "<label><input type='radio' name='_social[minimized]' value='auto' checked /> Auto</label>";
					echo "<label style='margin-left: 20px;'><input type='radio' name='_social[minimized]' value='true' /> true</label>";
					echo "<label style='margin-left: 20px;'><input type='radio' name='_social[minimized]' value='false' /> false</label>";
				}
				echo "<p class='description'><strong>Optional.</strong> Specifies whether the plugin should be minimized or shown.
						Defaults to <code>false</code> on desktop and <code>true</code> on mobile browsers.</p>";
			},
			'_social',
			'page-settings'
		);

		add_settings_field(
			'theme-color',
			'theme_color',
			function() {
				$theme_color = esc_attr( get_theme_color() );
				echo "<input type='text' class='theme-color' name='_social[theme_color]' data-default-color='#0084FF' value='$theme_color' />";
				echo "<p class='description'><strong>Optional.</strong>
						Specifies a hexidecimal color code to use as a theme for the plugin,
							including the customer chat icon and the background color of messages sent by users.
								All colors except white are supported.
								The color code has to start with a leading number sign, e.g.
									<code>#0084FF.</code></p>";
			},
			'_social',
			'page-settings'
		);

		add_settings_field(
			'logged-in-greeting',
			'logged_in_greeting',
			function() {
				$logged_in_greeting = esc_attr( get_logged_in_greeting() );
				echo "<input style='width: 100%;' type='text' name='_social[logged_in_greeting]' value='$logged_in_greeting' />";
				echo "<p class='description'><strong>Optional.</strong>
						The greeting text that will be displayed if the user is currently logged in to Facebook.
							Maximum 80 characters.</p>";
			},
			'_social',
			'page-settings'
		);

		add_settings_field(
			'logged-out-greeting',
			'logged_out_greeting',
			function() {
				$logged_out_greeting = esc_attr( get_logged_out_greeting() );
				echo "<input style='width: 100%;' type='text' name='_social[logged_out_greeting]' value='$logged_out_greeting' />";
				echo "<p class='description'><strong>Optional.</strong>
						The greeting text that will be displayed if the user is not currently logged in to Facebook.
							Maximum 80 characters.</p>";
			},
			'_social',
			'page-settings'
		);
	}

	public function display()
	{
		$checked = "";
		if ( get_activated() ) {
			$checked = 'checked';
		}

		$action = untrailingslashit( admin_url() ) . '/options.php';
		?>
		<div class="wrap _social-settings">
			<h1 class="wp-heading-inline">Customer Chat Settings</h1>
			<form action="<?php echo esc_url( $action ); ?>" method="post">
				<div class="toggle-customer-chat" style="margin: 2em 0;"><label class="switch">
						<input type="checkbox" name="_social[activated]" value="1" <?php echo esc_attr( $checked ); ?> />
						<span class="slider round"></span>
					</label></div>
				<?php
				settings_fields('_social-settings');
				do_settings_sections('_social');
				submit_button();
				?>
			</form>
		</div>
		<?php
	}
}
