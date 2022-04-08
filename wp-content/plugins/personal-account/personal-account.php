<?php
/**
 * Plugin Name: Personal Account
 * Description: Персональні акаунти для WordPress
 * Plugin URI:  ""
 * Author URI:  ""
 * Author:      Anton
 * Version:     1
 *
 * Text Domain: pa
 * Domain Path: ""
 * Requires at least: 5.8
 * Requires PHP: 7.3
 *
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * Network:     Укажите "true" для возможности активировать плагин для сети Multisite.
 * Update URI: https://site.ru/link_to_update
 */
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
require 'authoload.php';
use PersonalAccount\Core\App as App;
/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PERSONAL_ACCOUNT_VERSION', '1' );
//App::start();
App::test();
function activatePersonalAccount() {
	App::start();
}
function deactivatePersonalAccount() {

}
register_activation_hook( __FILE__, 'activatePersonalAccount' );
register_deactivation_hook( __FILE__, 'deactivatePersonalAccount' );