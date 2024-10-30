<?php
/**
 * Fired when the plugin is uninstalled.
 *
* @package   MasterIDs
 * @author    Luis Rock <luisrock@luisrock.com>
 * @license   GPL-2.0+
 * @link      http://luisrock.com
 * @copyright 2013 Luis Rock
 */

// If uninstall, not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// TODO: Define uninstall functionality here