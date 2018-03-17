<?php
/**
 * Plugin Name:     _social
 * Plugin URI:      https://github.com/miya0001/_social
 * Description:     A WordPress plugin to share the contents
 * Author:          Takayuki Miyauchi
 * Author URI:      https://miya.io/
 * Text Domain:     _social
 * Domain Path:     /languages
 * Version:         nightly
 *
 * @package         _social
 */

// Autoload
require_once( dirname( __FILE__ ) . '/vendor/autoload.php' );

_Social::get_instance()->register();
