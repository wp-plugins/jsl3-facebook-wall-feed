<?php
/* 
Plugin Name: JSL3 Facebook Wall Feed
Plugin URI: http://www.takanudo.com/jsl3-facebook-wall-feed
Description: Displays your facebook wall. Makes use of Fedil Grogan's <a href="http://fedil.ukneeq.com/2011/06/23/facebook-wall-feed-for-wordpress-updated/">Facebook Wall Feed for WordPress</a> code and changes suggested by <a href="http://danielwestergren.se">Daniel Westergren</a> and <a href="http://www.neilpie.co.uk">Neil Pie</a>. German translation provided by Remo Fleckinger. Facebook Graph API v2.0 bug fix provided by Andrew Bloom.
Version: 1.7.4
Author: Takanudo
Author URI: http://www.takanudo.com
License: GPL2

Copyright 2015  Takanudo  (email : fwf@takanudo.com)

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
 * @copyright  2011-2015
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License 3
 * @version    1.7.4
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
        global $jsl3_fwf_plugin_hook;
        global $jsl3_fwf;

        if ( ! isset( $jsl3_fwf ) )
            return;

        if ( function_exists( 'add_options_page' ) ) {
            $jsl3_fwf_plugin_hook = add_options_page(
                __( 'JSL3 Facebook Wall Feed', JSL3_FWF_TEXT_DOMAIN ),
                __( 'JSL3 Facebook Wall Feed', JSL3_FWF_TEXT_DOMAIN ),
                'manage_options',
                JSL3_FWF_SLUG,
                array( &$jsl3_fwf, 'print_admin_page' ) );
    
            if ( get_bloginfo( 'version' ) >= 3.3 )
                add_action( "load-$jsl3_fwf_plugin_hook",
                    'jsl3_fwf_help_tabs' );
            else
                add_filter( 'contextual_help', 'jsl3_fwf_help', 10, 3 );
    
        }
    }   
}

// }}}
// {{{ jsl3_fwf_print_menu()

/**
 * Print help menu
 *
 * Prints the contextual help menu.
 *
 * @access public
 * @since Method available since Release 1.1
 */
if ( ! function_exists( 'jsl3_fwf_print_menu' ) ) {
    function jsl3_fwf_print_menu() {

        return '<h2 id="jsl3_fwf_top">Menu</h2>' .
               '<ul>' .
               '  <li><a href="#jsl3_fwf_config">' .
               __( 'Configuration', JSL3_FWF_TEXT_DOMAIN ) . '</a></li>' .
               '  <li><a href="#jsl3_fwf_widget">' .
               __( 'Widget Usage', JSL3_FWF_TEXT_DOMAIN ) . '</a></li>' .
               '  <li><a href="#jsl3_fwf_short">' .
               __( 'Shortcode Usage', JSL3_FWF_TEXT_DOMAIN ) . '</a></li>' .
               '</ul>';
    
    }
}

// }}}
// {{{ jsl3_fwf_print_config()

/**
 * Print the configuration help page
 *
 * Prints the configuration contextual help page.
 *
 * @access public
 * @since Method available since Release 1.1
 */
if ( ! function_exists( 'jsl3_fwf_print_config' ) ) {
    function jsl3_fwf_print_config() {

        return '<ol>' .
               '  <li><a href="https://developers.facebook.com/apps">' .
               __( 'Create your Facebook App', JSL3_FWF_TEXT_DOMAIN ) .
               '</a>. ' .
               __( 'NOTE: You cannot use a Facebook Page to create a Facebook App.  You must use your personal Facebook profile.  However, once you create your Facebook App, you can use its App ID and App Secret along with the Facebook ID of the Facebook Page you want to get the feed from on the settings page for the plugin.', JSL3_FWF_TEXT_DOMAIN ) .
               '</li>' .
               '  <li>' .
               __( 'If this is your first time creating a Facebook App, you will need to register.  Otherwise, skip to step <strong>6</strong>', JSL3_FWF_TEXT_DOMAIN ) . '<br />' .
               '    <img src="' . JSL3_FWF_PLUGIN_URL . '/screenshot-2.png" alt="' . __( 'If this is your first time creating a Facebook App, you will need to register.', JSL3_FWF_TEXT_DOMAIN ) . '"  />' .
               '  </li>' .
               '  <li>' .
               __( 'Toggle the button to <strong>Yes</strong> to agree to the Facebook Policies and then click <strong>Next</strong>', JSL3_FWF_TEXT_DOMAIN ) . '<br />' .
               '    <img src="' . JSL3_FWF_PLUGIN_URL . '/screenshot-3.png" alt="' . __( 'Toggle the button to &#039;Yes&#039; to agree to the Facebook Policies and then click &#039;Next.&#039;', JSL3_FWF_TEXT_DOMAIN ) . '" />' .
               '  </li>' .
               '  <li>' .
               __( 'After you enter the confirmation code that Facebook sent to your phone, click <strong>Register</strong>.', JSL3_FWF_TEXT_DOMAIN ) . '<br />' .
               '    <img src="' . JSL3_FWF_PLUGIN_URL . '/screenshot-4.png" alt="' . __( 'After you enter the confirmation code that Facebook sent to your phone, click &#039;Register.&#039;', JSL3_FWF_TEXT_DOMAIN ) . '" />' .
               '  </li>' .
               '  <li>' .
               __( 'Click <strong>Done</strong> to complete the registration.', JSL3_FWF_TEXT_DOMAIN ) . '<br />' .
               '    <img src="' . JSL3_FWF_PLUGIN_URL . '/screenshot-5.png" alt="' . __( 'Click &#039;Done&#039; to complete the registration.', JSL3_FWF_TEXT_DOMAIN ) . '" />' .
               '  </li>' .
               '  <li>' .
               __( 'When you <strong>Add a New App</strong>, select the <strong>Website</strong> option.', JSL3_FWF_TEXT_DOMAIN ) . '<br />' .
               '    <img src="' . JSL3_FWF_PLUGIN_URL . '/screenshot-6.png" alt="' . __( 'When you &#039;Add a New App&#039;, select the &#039;Website&#039; option.', JSL3_FWF_TEXT_DOMAIN ) . '" />' .
               '  </li>' .
               '  <li>' .
               __( 'Click <strong>Create New Facebook App ID</strong>.', JSL3_FWF_TEXT_DOMAIN ) . '<br />' .
               '    <img src="' . JSL3_FWF_PLUGIN_URL . '/screenshot-7.png" alt="' . __( 'Click &#039;Create New Facebook App ID.&#039;', JSL3_FWF_TEXT_DOMAIN ) . '" />' .
               '  </li>' .
               '  <li>' .
               __( 'Select a category that best fits your WordPress site and then click <strong>Create App ID</strong>.', JSL3_FWF_TEXT_DOMAIN ) . '<br />' .
               '    <img src="' . JSL3_FWF_PLUGIN_URL . '/screenshot-8.png" alt="' . __( 'Select a category that best fits your WordPress site and then click &#039;Create App ID.&#039;', JSL3_FWF_TEXT_DOMAIN ) . '" />' .
               '  </li>' .
               '  <li>' .
               __( 'Enter your <strong>Site URL</strong> and <strong>Mobile Site URL</strong> in the <strong>Tell us about your website</strong> section at the bottom of the page.  Do not enter <strong>www.</strong> Then click <strong>Next</strong>.', JSL3_FWF_TEXT_DOMAIN ) . '<br />' .
               '    <img src="' . JSL3_FWF_PLUGIN_URL . '/screenshot-9.png" alt="' . __( 'Enter your &#039;Site URL&#039; and &#039;Mobile Site URL&#039; in the &#039;Tell us about your website&#039; section at the bottom of the page.', JSL3_FWF_TEXT_DOMAIN ) . '" />' .
               '  </li>' .
               '  <li>' .
               __( 'Click the <strong>Skip to Developer Dashboard</strong> link in the <strong>Next Steps</strong> section at the bottom of the page.', JSL3_FWF_TEXT_DOMAIN ) . '<br />' .
               '    <img src="' . JSL3_FWF_PLUGIN_URL . '/screenshot-10.png" alt="' . __( 'Click the &#039;Skip to Developer Dashboard&#039; link in the &#039;Next Steps&#039; section at the bottom of the page.', JSL3_FWF_TEXT_DOMAIN ) . '" />' .
               '  </li>' .
               '  <li>' .
               __( 'Select the <strong>Settings</strong> menu option. Enter your <strong>App Domain</strong> and <strong>Contact Email</strong>. Then click <strong>Save Changes</strong>.', JSL3_FWF_TEXT_DOMAIN ) . '<br />' .
               '    <img src="' . JSL3_FWF_PLUGIN_URL . '/screenshot-11.png" alt="' . __( 'Select the &#039;Settings&#039; menu option. Enter your &#039;App Domain&#039; and &#039;Contact Email.&#039;', JSL3_FWF_TEXT_DOMAIN ) . '" />' .
               '  </li>' .
               '  <li>' .
               __( 'Click the <strong>Show</strong> button to reveal your <strong>App Secret</strong>.', JSL3_FWF_TEXT_DOMAIN ) . '<br />' .
               '    <img src="' . JSL3_FWF_PLUGIN_URL . '/screenshot-12.png" alt="' . __( 'Click the &#039;Show&#039; button to reveal your &#039;App Secret.&#039;', JSL3_FWF_TEXT_DOMAIN ) . '" />' .
               '  </li>' .
               '  <li>' .
               __( 'Make note of your <strong>App ID</strong> and <strong>App Secret</strong>.  You will need them in step <strong>16</strong>.', JSL3_FWF_TEXT_DOMAIN ) . '<br />' .
               '    <img src="' . JSL3_FWF_PLUGIN_URL . '/screenshot-13.png" alt="' . __( 'Make note of your &#039;App ID&#039; and &#039;App Secret.&#039;', JSL3_FWF_TEXT_DOMAIN ) . '" />' .
               '  </li>' .
               '  <li>' .
               __( 'Select the <strong>Status &amp; Review</strong> menu option. Change the toggle button to <strong>Yes</strong> to make you Facebook App available to the public.', JSL3_FWF_TEXT_DOMAIN ) . '<br />' .
               '    <img src="' . JSL3_FWF_PLUGIN_URL . '/screenshot-14.png" alt="' . __( 'Select the &#039;Status &amp; Review&#039; menu option. Change the toggle button to &#039;Yes&#039; to make you Facebook App available to the public.', JSL3_FWF_TEXT_DOMAIN ) . '" />' .
               '  </li>' .
               '  <li>' .
               __( 'Click <strong>Confirm</strong> to complete the creation of your Facebook App.', JSL3_FWF_TEXT_DOMAIN ) . '<br />' .
               '    <img src="' . JSL3_FWF_PLUGIN_URL . '/screenshot-15.png" alt="' . __( 'Click &#039;Confirm&#039; to complete the creation of your Facebook App.', JSL3_FWF_TEXT_DOMAIN ) . '" />' .
               '  </li>' .
               '  <li>' .
               __( 'Go to <strong>JSL3 Facebook Wall Feed</strong> under <strong>Settings</strong> on your WordPress Administration menu. Enter the <strong>App ID</strong> and <strong>App Secret</strong> you recorded earlier.  Also, enter your <strong>Facebook ID</strong>. If you do not know your Facebook ID, you can find it at <a href="http://findmyfacebookid.com/">http://findmyfacebookid.com</a>.', JSL3_FWF_TEXT_DOMAIN ) . '<br />' .
               '    <img src="' . JSL3_FWF_PLUGIN_URL . '/screenshot-16.png" alt="' . __( 'Go to &#039;JSL3 Facebook Wall Feed&#039; under &#039;Settings&#039; on your WordPress Administration menu. Enter the &#039;App ID&#039; and &#039;App Secret&#039; you recorded earlier.  Also, enter your &#039;Facebook ID.&#039;', JSL3_FWF_TEXT_DOMAIN ) . '" />' .
               '  </li>' .
               '  <li>' .
               __( 'Click the <strong>Save Changes</strong> button at the bottom of the page.  You will be redirected to Facebook. You may be prompted to login a couple of times.', JSL3_FWF_TEXT_DOMAIN ) . '<br />' .
               '    <img src="' . JSL3_FWF_PLUGIN_URL . '/screenshot-17.png" alt="' . __( 'Click the &#039;Save Changes&#039; button at the bottom of the page.  You will be redirected to Facebook.', JSL3_FWF_TEXT_DOMAIN ) . '" />' .
               '  </li>' .
               '  <li>' .
               __( 'Click <strong>Okay</strong> to give your Facebook App permission to access your public profile, News Feed, status updates and groups.', JSL3_FWF_TEXT_DOMAIN ) . '<br />' .
               '    <img src="' . JSL3_FWF_PLUGIN_URL . '/screenshot-18.png" alt="' . __( 'Click &#039;Okay&#039; to give your Facebook App permission to access your public profile, News Feed, status updates and groups.', JSL3_FWF_TEXT_DOMAIN ) . '" />' .
               '  </li>' .
               '  <li>' .
               __( 'Click <strong>Okay</strong> to give your Facebook App permission to manage your Pages.', JSL3_FWF_TEXT_DOMAIN ) . '<br />' .
               '    <img src="' . JSL3_FWF_PLUGIN_URL . '/screenshot-19.png" alt="' . __( 'Click &#039;Okay&#039; to give your Facebook App permission to manage your Pages.', JSL3_FWF_TEXT_DOMAIN ) . '" />' .
               '  </li>' .
               '  <li>' .
               __( 'You will be returned to the JSL3 Facebook Wall Feed settings page with your <strong>Access Token</strong> and its expiration date.', JSL3_FWF_TEXT_DOMAIN ) . '<br />' .
               '    <img src="' . JSL3_FWF_PLUGIN_URL . '/screenshot-20.png" alt="' . __( 'You will be returned to the JSL3 Facebook Wall Feed settings page with your &lt;strong&gt;Access Token&lt;/strong&gt; and its expiration date.', JSL3_FWF_TEXT_DOMAIN ) . '" />' .
               '  </li>' .
               '  <li>' .
               __( 'NOTE: Facebook has changed how the Facebook ID is used with new Facebook Apps.  If, after adding your widget to your WordPress site, you see that the feed is blank, go back to the  settings page for the plugin and check the box below your Facebook ID and then click the <strong>Save Changes</strong> button.  This will tell the plugin to request the App Scoped User ID instead of your Facebook ID.', JSL3_FWF_TEXT_DOMAIN ) . '<br />' .
               '    <img src="' . JSL3_FWF_PLUGIN_URL . '/screenshot-21.png" alt="' . __( 'If, after adding your widget to your WordPress site, you see that the feed is blank, go back to the  settings page for the plugin and check the box below your Facebook ID and then click the &#039;Save Changes&#039; button.', JSL3_FWF_TEXT_DOMAIN ) . '" />' .
               '  </li>' .
               '</ol>';

    }
}

// }}}
// {{{ jsl3_fwf_print_widget()

/**
 * Print the widget usage help page
 *
 * Prints the widget usage contextual help page.
 *
 * @access public
 * @since Method available since Release 1.1
 */
if ( ! function_exists( 'jsl3_fwf_print_widget' ) ) {
    function jsl3_fwf_print_widget() {

        return '<ol>' .
               '  <li>' .
               __( 'Go to <strong>Widgets</strong> under <strong>Appearance</strong> on your WordPress Administration menu.  Drag the <strong>JSL3 Facebook Wall Feed</strong> widget to the sidebar of your choice.', JSL3_FWF_TEXT_DOMAIN ) . '<br />' .
               '    <img src="' . JSL3_FWF_PLUGIN_URL . '/screenshot-22.png" alt="' . __( 'Drag the &#039;SL3 Facebook Wall Feed&#039; widget to the sidebar of your choice.', JSL3_FWF_TEXT_DOMAIN ) . '" />' .
               '  </li>' .
               '  <li>' .
               __( 'Give the widget a title (or leave it blank) and enter how many posts you want to get from your wall. You may also enter the Facebook ID of the Facebook page you want to display in the widget.  If you leave the Facebook ID blank, the widget will use the Facebook ID entered on the settings page for the plugin. Click <strong>Save</strong>.', JSL3_FWF_TEXT_DOMAIN ) . '<br />' .
               '    <img src="' . JSL3_FWF_PLUGIN_URL . '/screenshot-23.png" alt="' . __( 'Give the widget a title (or leave it blank) and enter how many posts you want to get from your wall. You may also enter the Facebook ID of the Facebook page you want to display in the widget.', JSL3_FWF_TEXT_DOMAIN ) . '" />' .
               '  </li>' .
               '  <li>' .
               __( 'Go check out your Facebook Wall Feed on your WordPress site.', JSL3_FWF_TEXT_DOMAIN ) . '<br />' .
               '    <img src="' . JSL3_FWF_PLUGIN_URL . '/screenshot-24.png" alt="' . __( 'Go check out your Facebook Wall Feed on your WordPress site.', JSL3_FWF_TEXT_DOMAIN ) . '" />' .
               '  </li>' .
               '</ol>';

    }
}

// }}}
// {{{ jsl3_fwf_print_short()

/**
 * Print the shortcode usage help page
 *
 * Prints the shortcode usage contextual help page.
 *
 * @access public
 * @since Method available since Release 1.1
 */
if ( ! function_exists( 'jsl3_fwf_print_short' ) ) {
    function jsl3_fwf_print_short() {

        return '<ol>' .
               '  <li>' .
               __( 'Add the shortcode <strong>&#091;jsl3_fwf&#093;</strong> or <strong>&#091;jsl3_fwf limit=&quot;1&quot;&#093;</strong> or even <strong>&#091;jsl3_fwf limit=&quot;1&quot; fb_id=&quot;1405307559&quot;&#093;</strong> to the <strong>Text</strong> view of a post or page. If you do not enter a Facebook ID, the plugin will use the Facebook ID entered on the settings page for the plugin.  If your feed is blank, try setting the <strong>fb_id</strong> property to your <strong>App Scoped User ID</strong>.  If you do not know your App Scoped User ID, set the <strong>app_scoped_user_id</strong> property to <strong>true</strong> like this: <strong>&#091;jsl3_fwf limit=&quot;1&quot; fb_id=&quot;1405307559&quot; app_scoped_user_id=&quot;true&quot;&#093;</strong>', JSL3_FWF_TEXT_DOMAIN ) . '<br />' .
               '    <img src="' . JSL3_FWF_PLUGIN_URL . '/screenshot-25.png" alt="' . __( 'Add the shortcode &#091;jsl3_fwf&#093; or &#091;jsl3_fwf limit=&quot;1&quot;&#093; or even &#091;jsl3_fwf limit=&quot;1&quot; fb_id=&quot;1405307559&quot;&#093; to the &#039;Text&#039; view of a post or page.', JSL3_FWF_TEXT_DOMAIN ) . '" />' .
               '  </li>' .
               '  <li>' .
               __( 'View your Facebook Wall Feed on your WordPress post or page.', JSL3_FWF_TEXT_DOMAIN ) . '<br />' .
               '    <img src="' . JSL3_FWF_PLUGIN_URL . '/screenshot-26.png" alt="' . __( 'View your Facebook Wall Feed on your WordPress post or page.', JSL3_FWF_TEXT_DOMAIN ) . '" />' .
               '  </li>' .
               '</ol>';

    }
}

// }}}
// {{{ jsl3_fwf_help()

/**
 * Adds contextual help
 *
 * Adds contextual help to the admin page for the plugin on WordPress 3.2.1.
 *
 * @param string $contextual_help the existing contextual help.
 * @param string $screen_id the screen ID.
 * @param string $screen the screen name.
 *
 * @return string the new contextual help
 *
 * @access public
 * @since Method available since Release 1.1
 */
if ( ! function_exists( 'jsl3_fwf_help' ) ) {
    function jsl3_fwf_help( $contextual_help, $screen_id, $screen ) {
        global $jsl3_fwf_plugin_hook;

        if ( $screen_id == $jsl3_fwf_plugin_hook ) {
            $contextual_help =
                jsl3_fwf_print_menu() .
                '<h2 id="jsl3_fwf_config">' .
                __( 'Configuration' , JSL3_FWF_TEXT_DOMAIN ) . '</h2>' .
                jsl3_fwf_print_config() .
                '<a href="#jsl3_fwf_top">' .
                __( 'Back to top', JSL3_FWF_TEXT_DOMAIN ) .
                '</a><br /><br />' .
                '<h2 id="jsl3_fwf_widget">' .
                __( 'Widget Usage', JSL3_FWF_TEXT_DOMAIN ) . '</h2>' .
                jsl3_fwf_print_widget() .
                '<a href="#jsl3_fwf_top">' .
                __( 'Back to top', JSL3_FWF_TEXT_DOMAIN ) .
                '</a><br /><br />' .
                '<h2 id="jsl3_fwf_short">' .
                __( 'Shortcode Usage', JSL3_FWF_TEXT_DOMAIN ) . '</h2>' .
                jsl3_fwf_print_short() .
                '<a href="#jsl3_fwf_top">' .
                __( 'Back to top', JSL3_FWF_TEXT_DOMAIN ) .
                '</a><br /><br />' .
                $contextual_help;
        }

        return $contextual_help;
    }   
}

// }}}
// {{{ jsl3_fwf_help_tabs()

/**
 * Adds contextual help
 *
 * Adds contextual help to the admin page for the plugin on WordPress 3.3.
 *
 * @access public
 * @since Method available since Release 1.1
 */
if ( ! function_exists( 'jsl3_fwf_help_tabs' ) ) {
    function jsl3_fwf_help_tabs() {
        global $jsl3_fwf_plugin_hook;

        $screen = get_current_screen();

        if ( $screen->id == $jsl3_fwf_plugin_hook ) {

            $screen->add_help_tab( array(
                'id' => 'jsl3-fwf-config',
                'title' => __( 'Configuration', JSL3_FWF_TEXT_DOMAIN ),
                'content' => jsl3_fwf_print_config() ) );

            $screen->add_help_tab( array(
                'id' => 'jsl3-fwf-widget',
                'title' => __( 'Widget Usage', JSL3_FWF_TEXT_DOMAIN ),
                'content' => jsl3_fwf_print_widget() ) );

            $screen->add_help_tab( array(
                'id' => 'jsl3-fwf-short',
                'title' => __( 'Shortcode Usage', JSL3_FWF_TEXT_DOMAIN ),
                'content' => jsl3_fwf_print_short() ) );
        
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
 * @param string $file the basename of the plugin file.
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
                '/wp-admin/admin.php?page=' . JSL3_FWF_SLUG .'">' .
                __( 'Settings', JSL3_FWF_TEXT_DOMAIN ) . '</a>';
            array_unshift( $links, $settings_link );
        }

        return $links;
    }
}

// }}}
// {{{ jsl3_fwf_more_schedules()

/**
 * Add more cron schedules.
 *
 * Adds a "Bi-monthly" schedule to Wordress Cron.
 *
 * @access public
 * @since Method available since Release 1.4
 */
if ( ! function_exists( 'jsl3_fwf_more_schedules' ) ) {
    function jsl3_fwf_more_schedules() {
        return array(
            'jsl3_fwf_bimonthly' => array(
                'interval' => 60 * 60 * 24 * 60,
                'display' => 'Once Bimonthly' ) );
    }
}

// }}}

//Actions and Filters
if ( isset( $jsl3_fwf ) ) {
    //Text Domain
    load_plugin_textdomain( JSL3_FWF_TEXT_DOMAIN, FALSE,
        dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

    //Actions
    add_action( 'admin_menu', 'jsl3_facebook_wall_feed_ap' );
    add_action( 'activate_' . JSL3_FWF_PLUGIN_NAME . '/' .
        JSL3_FWF_PLUGIN_NAME . '.php', array( &$jsl3_fwf, 'init' ) );
    add_action( 'widgets_init',
        create_function( '',
            "return register_widget( '" . JSL3_FWF_WIDGET . "' );" ) );
    add_action( 'wp_enqueue_scripts', array( &$jsl3_fwf, 'enqueue_style' ) );
    add_action( JSL3_FWF_SCHED_HOOK, array( &$jsl3_fwf, 'renew_token' ) );

    //Filters
    add_filter( 'plugin_action_links', 'jsl3_fwf_plugin_action_links', 10, 2 );
    //add_filter( 'cron_schedules', 'jsl3_fwf_more_schedules' );
    
    //Shortcode
    add_shortcode( JSL3_FWF_SHORTCODE, array( &$jsl3_fwf, 'shortcode_handler' ) );

    //Create initial schedule
    if ( ! wp_next_scheduled( JSL3_FWF_SCHED_HOOK ) )
        wp_schedule_event( time() + 86400, JSL3_FWF_CRON_SCHED, JSL3_FWF_SCHED_HOOK );
}

?>
