<?php
/* 
Plugin Name: JSL3 Facebook Wall Feed
Plugin URI: http://www.takanudo.com/jsl3-facebook-wall-feed
Description: Displays your facebook wall. Makes use of Fedil Grogan's <a href="http://fedil.ukneeq.com/2011/06/23/facebook-wall-feed-for-wordpress-updated/">Facebook Wall Feed for WordPress</a> code and changes suggested by <a href="http://danielwestergren.se">Daniel Westergren</a>.
Version: 1.1
Author: Takanudo
Author URI: http://www.takanudo.com
License: GPL2

Copyright 2011  Takanudo  (email : fwf@takanudo.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/**
 * Perform clean-up when uninstalling the widget plugin
 *
 * Removes all WordPress database options created by the widget plugin.
 *
 * PHP version 5
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as 
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   WordPress_Plugin
 * @package    JSL3_FWF
 * @author     Takanudo <fwf@takanudo.com>
 * @copyright  2011-2012
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License 3
 * @version    1.1
 * @link       http://takando.com/jsl3-facebook-wall-feed
 * @since      File available since Release 1.0
 */

/**
 * Include the constants used by the plugin
 */
include_once 'constants.php';

/**
 * Include the JSL3_Facebook_Wall_Feed class
 */
include_once 'php/class-jsl3-facebook-wall-feed.php';

/**
 * Include the JSL3_FWF_Widget class
 */
include_once 'php/class-jsl3-fwf-widget.php';

/**
 * Include the UKI_Facebook_Wall_Feed class
 */
include_once 'php/class-' . UKI_FWF_NAME . '.php';

// Instantiate a JSL3_Facebook_Wall_Feed object
if ( class_exists( 'JSL3_Facebook_Wall_Feed' ) )
    $jsl3_fwf = new JSL3_Facebook_Wall_Feed();

// {{{ jsl3_facebook_wall_feed_ap()

/**
 * Initializes the admin panel
 *
 * Adds the plugin to the admin settings menu.  Then creates the admin
 * page for this plugin.
 *
 * @access public
 * @since Method available since Release 1.0
 */
if ( ! function_exists( 'jsl3_facebook_wall_feed_ap' ) ) {
    function jsl3_facebook_wall_feed_ap() {
        global $jsl3_fwf;
        if ( ! isset( $jsl3_fwf ) )
            return;

        if ( function_exists( 'add_options_page' ) ) {
            add_options_page(
                JSL3_FWF_TITLE,
                JSL3_FWF_TITLE,
                'manage_options',
                JSL3_FWF_SLUG,
                array( &$jsl3_fwf, 'print_admin_page' ) );
        }
    }   
}

// }}}
// {{{ jsl3_fwf_plugin_action_links()

/**
 * Add a 'Settings' link to the admin plugin page
 *
 * Add a 'Settings' link next to the Deactivate link to the admin plugin
 * page.
 *
 * @param array $links an array of plugin links.
 * @param string the basename of the plugin file.
 *
 * @access public
 * @since Method available since Release 1.0
 */
// Add a 'Settings' link to the admin plugin page
if ( ! function_exists( 'jsl3_fwf_plugin_action_links' ) ) {
    function jsl3_fwf_plugin_action_links( $links, $file ) {
        static $this_plugin;

        if ( ! $this_plugin )
            $this_plugin = plugin_basename( __FILE__ );

        if ( $file == $this_plugin ) {
            // The "page" query string value must be equal to the slug
            // of the Settings admin page we defined earlier, which in
            // this case equals "myplugin-settings".
            $settings_link =
                '<a href="' . get_bloginfo('wpurl') .
                '/wp-admin/admin.php?page=' . JSL3_FWF_SLUG .'">Settings</a>';
            array_unshift( $links, $settings_link );
        }

        return $links;
    }
}

// }}}

//Actions and Filters
if ( isset( $jsl3_fwf ) ) {
    //Actions
    add_action( 'admin_menu', 'jsl3_facebook_wall_feed_ap' );
    add_action( 'activate_' . JSL3_FWF_PLUGIN_NAME . '/' .
        JSL3_FWF_PLUGIN_NAME . '.php', array( &$jsl3_fwf, 'init' ) );
    add_action( 'widgets_init',
        create_function( '',
            "return register_widget( '" . JSL3_FWF_WIDGET . "' );" ) );
    add_action( 'wp_print_styles', array(&$jsl3_fwf, 'enqueue_style' ) );

    //Filters
    //add_filter( 'plugin_action_links', 'jsl3_fwf_plugin_action_links', 10, 2 );
    add_filter( 'plugin_action_links', 'jsl3_fwf_plugin_action_links', 10, 2 );
    
    //Shortcode
    add_shortcode( 'jsl3_fwf', array( &$jsl3_fwf, 'shortcode_handler' ) );
}

?>
