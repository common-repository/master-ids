<?php
/**
 *
* @package   MasterIDs
 * @author    Luis Rock <luisrock@luisrock.com>
 * @license   GPL-2.0+
 * @link      http://luisrock.com
 * @copyright 2013 Luis Rock
 *
 * @wordpress-plugin
 * Plugin Name: Master IDs
 * Plugin URI:  http://www.luisrock.com
 * Description: Easily find the ID for a page, post or custom post type.
 * Version:     1.0.0
 * Author:      Luis Rock
 * Author URI:  http://www.luisrock.com
 * Text Domain: master-ids
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// TODO: replace `class-plugin-name.php` with the name of the actual plugin's class file
require_once( plugin_dir_path( __FILE__ ) . 'class-master-ids.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
// TODO: replace PluginName with the name of the plugin defined in `class-plaugin-name.php`
register_activation_hook( __FILE__, array( 'MasterIDs', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'MasterIDs', 'deactivate' ) );

// TODO: replace PluginName with the name of the plugin defined in `class-plugin-name.php`
MasterIDs::get_instance();