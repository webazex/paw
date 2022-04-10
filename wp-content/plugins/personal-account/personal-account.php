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
use PersonalAccount\Core\Db as DB;
use PersonalAccount\Core\User as User;

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PERSONAL_ACCOUNT_VERSION', '1' );


//\R::freeze(true);
function activatePersonalAccount() {
	App::start();
}
function deactivatePersonalAccount() {

}
$user = [
	'login' => 'webazex@gmail.com',
	'psw' => "1234",
	'address' => "Kh",
	'addressold' => "Od Sl Len",
	'addressnew' => "Kh Kh Pot",
	'city' => "Kherson",
	'state' => "Kherson state",
	'zip' => "73039",
];
echo'<pre>';
var_dump(User::addUser($user));
//App::addUser([
//	'login' => "webazex@gmail.com",
//	'psw' => "webazex@gmail.comss",
//]);
echo'</pre>';
//App::addUser([
//	'login' => "webazex2@gmail.com",
//	'psw' => "webazex2@gmail.comss",
//]);
register_activation_hook( __FILE__, 'activatePersonalAccount' );
register_deactivation_hook( __FILE__, 'deactivatePersonalAccount' );