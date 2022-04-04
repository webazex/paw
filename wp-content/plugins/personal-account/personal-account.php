<?php
/**
 * Personal Account
 *
 * Plugin Name: Personal Account
 * Plugin URI:  https:latul.website/
 * Description: Personal Account in WordPress
 * Version:     1
 * Author:      https:latul.website/
 * Author URI:  https:latul.website/
 * License:     GNU GENERAL PUBLIC LICENSE v3 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-3.0.html
 * Text Domain: personal-account
 * Domain Path: /languages
 * Requires at least: 5.8
 * Tested up to: 7.1
 * Requires PHP: 7.3
 */

if ( ! defined( 'ABSPATH' ) ) {
    die( 'Invalid request.' );
}
define( 'PERSONAL_ACCOUNT_VERSION', '1.0.0' );
require plugin_dir_path( __FILE__ ).'authoload.php';
use PersonalAccount\Core\App as App;
use PersonalAccount\Core\Db as DB;
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_personal_account() {
    App::start();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_personal_account() {
    App::stop();
}

register_activation_hook( __FILE__, 'activate_personal_account' );
register_deactivation_hook( __FILE__, 'deactivate_personal_account' );
