<?php

/**
 * Contains the JSL3_Facebook_Wall_Feed class
 *
 * Contains the JSL3_Facebook_Wall_Feed class.  See class desciption for more
 * information.
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
 * @author     Fedil Grogan <fedil@ukneeq.com>
 * @copyright  2011-2012
 * @license    http://www.gnu.org/licenses/gpl.html  GNU General Public License 3
 * @version    1.2
 * @link       http://takando.com/jsl3-facebook-wall-feed
 * @since      File available since Release 1.0
 */

// {{{ JSL3_Facebook_Wall_Feed

/**
 * Creates the admin page for the JSL3 Facebook Wall Feed plugin
 *
 * Creates an admin page that gathers the required information for accessing a
 * facebook app. Once the information is submitted, permissions are set on the
 * facebook app. Then an access token is requested from facebook. The admin
 * page also give the administrator the ability to modify the style sheet that
 * is applied to the associated widget. Also contains short code
 * functionality.
 *
 * @category   WordPress_Plugin
 * @package    JSL3_FWF
 * @author     Takanudo <fwf@takanudo.com>
 * @author     Fedil Grogan <fedil@ukneeq.com>
 * @copyright  2011-2012
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    1.2
 * @link       http://takando.com/jsl3-facebook-wall-feed
 * @since      File available since Release 1.0
 */

if ( ! class_exists( 'JSL3_Facebook_Wall_Feed' ) ) {
    class JSL3_Facebook_Wall_Feed {

        // {{{ properties

        /**
         * WordPress options name
         *
         * Admin properties such as Facebook ID, App ID, App Secret, Access
         * token, Session, and Stylesheet are stored under this name in the
         * in the WordPress database. This value is set in constants.php.
         *
         * @var string
         */
        var $admin_options_name = JSL3_FWF_ADMIN_OPTIONS;

        // }}}
        // {{{ JSL3_Facebook_Wall_Feed()

        /**
         * Constructor for this class
         *
         * Constructor does not do anything at this point.
         *
         * @access public
         * @since Method available since Release 1.0
         */
        function JSL3_Facebook_Wall_Feed() {
            
        }

        // }}}
        // {{{ init()

        /**
         * Initializes the admin options
         *
         * Calls get_admin_options() function to initialize the admin option
         * properties.
         *
         * @access public
         * @since Method available since Release 1.0
         */
        function init() {
            $this->get_admin_options();
        }

        // }}}
        // {{{ reset_style_fn()

        /**
         * Loads the default stylesheet
         *
         * Opens the default stylesheet and reads the contents into a string
         * which is then returned.
         *
         * @return string the default stylesheet.
         *
         * @access public
         * @since Method available since Release 1.0
         */
        function reset_style_fn() {
            $css_path = JSL3_FWF_PLUGIN_DIR . '/css/' . UKI_FWF_NAME . '.css';
            $css = fopen( $css_path, 'r' );
            $style = fread( $css, filesize( $css_path ) );
            fclose( $css );

            return $style;
        }

        // }}}
        // {{{ get_admin_options()
        
        /**
         * Returns an array of admin options
         *
         * Sets the admin options to default values. Then checks if the admin
         * options already exist in the WordPress database.  If they do, then
         * the database values are loaded and returned.
         *
         * @return array the admin options.
         *
         * @access public
         * @since Method available since Release 1.0
         */
        function get_admin_options() {
            // set default values
            $jsl3_fwf_admin_options = array(
                'fb_id'      => '',
                'app_id'     => '',
                'app_secret' => '', 
                'token'      => '',
                'session'    => '',
                'style'      => '',
                'fb_id_only' => FALSE,
                'privacy'    => 'All' );

            // get default stylesheet
            $jsl3_fwf_admin_options[ 'style' ] = $this->reset_style_fn();

            // get admin options from WordPress database
            $dev_options = get_option( $this->admin_options_name );
            
            /*
             * if admin options exist in the WordPress database then replace
             * the default values.
             */
            if ( ! empty( $dev_options ) ) {
                foreach ( $dev_options as $key => $option )
                    $jsl3_fwf_admin_options[ $key ] = $option;
            }

            // store values back in the WordPress database
            update_option( $this->admin_options_name,
                $jsl3_fwf_admin_options );
            
            return $jsl3_fwf_admin_options;
        }

        // }}}
        // {{{ setting_select_fn()

        /**
         * Prints an html drop down menu with associated label
         *
         * Prints an html drop down menu with an id and name set to the passed
         * in option.  An associated label is created from the passed in
         * label.  The options and values of the select box are passed in via
         * an array.
         *
         * @param string $label the label for the checkbox.
         * @param string $option the id and name of the checkbox.
         * @param array $select_options the options and values for the select
         *
         * @access public
         * @since Method available since Release 1.1
         */
        function setting_select_fn( $label, $option, $select_options ) {
            $dev_options = $this->get_admin_options();
?>
<tr valign="top">
  <th scope="row">
    <label for="<?php echo $option; ?>"><?php echo $label; ?></label>
  </th>
  <td>
    <select id="<?php echo $option; ?>" name="<?php echo $option; ?>">
<?php
            foreach ($select_options as $key => $value) {
?>
      <option value="<?php echo $key; ?>"<?php if ( $dev_options[ $option ] == $key ) echo ' selected="selected"'; ?>><?php echo $value; ?></option>
<?php
            }
?>
    </select>
  </td>
</tr>
<?php
        }

        // }}}
        // {{{ setting_checkbox2_fn()

        /**
         * Prints an html checkbox with associated label
         *
         * Prints an html checkbox with an id and name set to the passed in
         * option.  An associated label and legend is created from the passed
         * in label and legend..
         *
         * @param string $label the label for the checkbox.
         * @param string $option the id and name of the checkbox.
         * @param string $legend the legend for the checkbox.
         *
         * @access public
         * @since Method available since Release 1.1
         */
        function setting_checkbox2_fn( $label, $option, $legend ) {
            $dev_options = $this->get_admin_options();
?>
<tr valign="top">
  <th scope="row"><?php echo $legend; ?></th>
  <td>
    <fieldset>
      <legend class="screen-reader-text">
        <span><?php echo $legend; ?></span>
      </legend>
      <label for="<?php echo $option; ?>">
        <input type="checkbox" id="<?php echo $option; ?>" name="<?php echo $option; ?>" value="1" <?php if ( $dev_options[ $option ] ) echo 'checked="checked" '; ?>/>
        <?php echo $label; ?>
      </label>
    </fieldset>
  </td>
</tr>
<?php
        }

        // }}}
        // {{{ setting_checkbox_fn()

        /**
         * Prints an html checkbox with associated label
         *
         * Prints an html checkbox with an id and name set to the passed in
         * option.  An associated label is created form the passed in label.
         *
         * @param string $label the label for the checkbox.
         * @param string $option the id and name of the checkbox.
         *
         * @access public
         * @since Method available since Release 1.0
         */
        function setting_checkbox_fn( $label, $option ) {
?>
<p>
  <label for="<?php echo $option; ?>">
    <input type="checkbox" id="<?php echo $option; ?>" name="<?php echo $option; ?>" />
    <?php echo $label; ?>
  </label>
</p>
<?php
        }

        // }}}
        // {{{ setting_textarea_fn()

        /**
         * Prints an html textarea with associated label and checkbox
         *
         * Prints an html textarea with an id and name set to the passed in
         * option.  An associated label is created form the passed in label.
         * An associated legen is created from the passed in legend. A
         * checkbox is also created with the textarea by calling the
         * setting_checkbox_fn() function.
         *
         * @param string $label the label for the textarea.
         * @param string $option the id and name of the textarea.
         * @param string $legend the legend for the textarea.
         *
         * @access public
         * @since Method available since Release 1.0
         */
        function setting_textarea_fn( $label, $option, $legend ) {
            $dev_options = $this->get_admin_options();
?>
<tr valign="top">
  <th scope="row"><?php echo $legend; ?></th>
  <td>
    <fieldset>
      <legend class="screen-reader-text">
        <span><?php echo $legend;  ?></span>
      </legend>
      <p>
        <label for="<?php echo $option; ?>"><?php echo $label;  ?></label>
      </p>
      <p>
        <textarea id="<?php echo $option; ?>" name="<?php echo $option; ?>" class="large-text code" cols="50" rows="10"><?php echo apply_filters( 'format_to_edit', stripslashes( $dev_options[ $option ] ) );  ?></textarea>
      </p>
      <?php $this->setting_checkbox_fn( __('Tick this box if you wish to reset the style to default.', JSL3_FWF_TEXT_DOMAIN ), 'reset_style' ); ?>
    </fieldset>
  </td>
</tr>
<?php
        }

        // }}}
        // {{{ setting_text_fn()

        /**
         * Prints an html text box with associated label
         *
         * Prints an html text box with an id and name set to the passed in
         * option.  An associated label is created form the passed in label.
         *
         * @param string $label the label for the textarea.
         * @param string $option the id and name of the textarea.
         *
         * @access public
         * @since Method available since Release 1.0
         */
        function setting_text_fn( $label, $option ) {
            $dev_options = $this->get_admin_options();
?>
<tr valign="top">
  <th scope="row"><label for="<?php echo $option; ?>"><?php echo $label; ?></label></th>
  <td><input id="<?php echo $option; ?>" name="<?php echo $option; ?>" class="regular-text" type="text" value="<?php echo apply_filters( 'format_to_edit', $dev_options[ $option ] ); ?>" /></td>
</tr>
<?php
        }

        // }}}
        // {{{ setting_hidden_fn()

        /**
         * Prints an html hidden field with associated label
         *
         * Prints an html hidden field with an id and name set to the passed in
         * option.  An associated label is created form the passed in label.
         *
         * @param string $label the label for the textarea.
         * @param string $option the id and name of the textarea.
         *
         * @access public
         * @since Method available since Release 1.0
         */
        function setting_hidden_fn( $label, $option ) {
            $dev_options = $this->get_admin_options();
?>
<tr valign="top">
  <th scope="row"><label for="<?php echo $option; ?>"><?php echo $label; ?></label></th>
  <td>
    <input id="<?php echo $option; ?>" name="<?php echo $option; ?>" type="hidden" value="<?php echo apply_filters( 'format_to_edit', $dev_options[ $option ] ); ?>" />
    <?php echo $dev_options[ $option ]; ?>
  </td>
</tr>
<?php
        }

        // }}}
        // {{{ saved_settings_fn()

        /**
         * Prints an html "Saved Settings" dialog box
         *
         * Prints an html "Saved Settings" dialog box at the top of the page
         * to alert the user that the settings have been saved.
         *
         * @access public
         * @since Method available since Release 1.0
         */
        function saved_settings_fn() {
?>
<div id="setting-error-settings_updated" class="updated settings-error">
  <p>
    <strong><?php _e( 'Settings saved.', JSL3_FWF_TEXT_DOMAIN ); ?></strong>
  </p>
</div>
<?php
        }

        // }}}
        // {{{ error_msg_fn()

        /**
         * Prints an html error message dialog box
         *
         * Prints an html error message dialog box at the top of the page
         * to alert the user that an passed in error has occurred.
         *
         * @param string $msg the error message
         *
         * @access public
         * @since Method available since Release 1.0
         */
        function error_msg_fn( $msg ) {
?>
<div id="setting-error-invalid_csrf" class="error settings-error">
  <p>
    <strong><?php echo $msg; ?></strong>
  </p>
</div>
<?php
        }

        // }}}
        // {{{ print_admin_page()

        /**
         * Prints out the admin page
         *
         * Prints out the admin page and handles the post back of data. Calls
         * the set_access() function to set permission for access to the
         * Facebook app.  Calls get_token() to request an access token from
         * Facebook.
         *
         * @access public
         * @since Method available since Release 1.0
         */
        function print_admin_page() {
            $dev_options = $this->get_admin_options();
            //$is_changed = FALSE;

            // check to see if a post-back has occured
            if ( isset( $_POST[ 'update_jsl3_fwf_settings' ] ) ) {

                // store the Facebook ID if it has been changed
                if ( isset( $_POST[ 'fb_id' ] ) ) {
                    //if ( $_POST[ 'fb_id' ] != $dev_options[ 'fb_id' ] ) {
                        //$is_changed = TRUE;
                        $dev_options[ 'fb_id' ] =
                            apply_filters( 'content_save_pre',
                                trim( $_POST[ 'fb_id' ] ) );
                    //}
                }

                // store the App ID if it has been changed
                if ( isset( $_POST[ 'app_id' ] ) ) {
                    //if ( $_POST[ 'app_id' ] != $dev_options[ 'app_id' ] ) {
                        //$is_changed = TRUE;
                        $dev_options[ 'app_id' ] =
                            apply_filters( 'content_save_pre',
                                trim( $_POST[ 'app_id' ] ) );
                    //}
                }
                
                // store the App Secret if it has been changed
                if ( isset( $_POST[ 'app_secret' ] ) ) {
                    //if ( $_POST[ 'app_secret' ] !=
                    //    $dev_options[ 'app_secret' ] ) {
                        //$is_changed = TRUE;
                        $dev_options[ 'app_secret' ] =
                            apply_filters( 'content_save_pre',
                                trim( $_POST[ 'app_secret' ] ) );
                    //}
                }
                
                // store the access token if it has been changed
                if ( isset( $_POST[ 'token' ] ) ) {
                    //if ( $_POST['token'] != $dev_options[ 'token' ] ) {
                        //$is_changed = TRUE;
                        $dev_options[ 'token' ] =
                            apply_filters( 'content_save_pre',
                                $_POST[ 'token' ] );
                    //}
                }
                
                // store the stylesheet
                if ( isset( $_POST[ 'style' ] ) ) {
                    $dev_options[ 'style' ] =
                        apply_filters( 'content_save_pre', $_POST[ 'style' ] );
                }
                
                // reset the stylesheet back to the default
                if ( isset( $_POST[ 'reset_style' ] ) &&
                    $_POST[ 'reset_style' ] == 'on' ) {
                    $dev_options[ 'style' ] = $this->reset_style_fn();
                }

                // store facebook id posts only
                if ( isset( $_POST[ 'fb_id_only' ] ) &&
                    ( $_POST[ 'fb_id_only' ] == '1' ) )
                        $dev_options[ 'fb_id_only' ] = TRUE;
                else
                    $dev_options[ 'fb_id_only' ] = FALSE;

                // store the privacy setting
                if ( isset( $_POST[ 'privacy' ] ) ) {
                    $dev_options[ 'privacy' ] =
                        apply_filters( 'content_save_pre',
                            $_POST[ 'privacy' ] );
                }

                // store the admin options back to the WordPress database
                update_option( $this->admin_options_name, $dev_options );
                
                /*
                 * if the admin options have been changed then we probably
                 * need to request permission for the Facebook app
                 */
                //if ( $is_changed ) {
                    $dev_options[ 'session' ] = $this->set_access();

                    update_option( $this->admin_options_name, $dev_options );
                //}

                // notify the user that the settings have been changed
                //$this->saved_settings_fn();
            
            } // end if

            /*
             * if there is a code value in the query string then assume that
             * we have just come back from requesting permission for the
             * Facebook app and now need to request the access token
             */
            if ( ! empty( $_REQUEST[ 'code' ] ) ) {
                $dev_options[ 'token' ] = $this->get_token();

                update_option( $this->admin_options_name, $dev_options );

                // don't display the saved setting dialog twice
                //if ( ! isset( $_POST[ 'update_jsl3_fwf_settings' ] ) )
                if ( isset( $dev_options[ 'token' ] ) )
                    $this->saved_settings_fn();
            }

            // time to print the admin page
            $sel_options = array(
                'All'    => __( 'Show all wall posts', JSL3_FWF_TEXT_DOMAIN ),
                'Public' => __( 'Show only wall posts labeled public',
                    JSL3_FWF_TEXT_DOMAIN ) );
?>
<div class=wrap>
  <h2><?php _e( 'JSL3 Facebook Wall Feed', JSL3_FWF_TEXT_DOMAIN ); ?></h2>
  <?php _e( 'For configuration and usage assistance click "Help" in the upper right hand corner of this page or go to the <a href="http://takanudo.com/jsl3-facebook-wall-feed">JSL3 Facebook Wall Feed</a> page.', JSL3_FWF_TEXT_DOMAIN ); ?>
  <form method="post" action="<?php echo $_SERVER[ 'REQUEST_URI' ]; ?>">
    <table class="form-table">
      <tbody>
        <?php $this->setting_text_fn( __( 'Facebook ID', JSL3_FWF_TEXT_DOMAIN ), 'fb_id' ); ?>
        <?php $this->setting_text_fn( __( 'App ID', JSL3_FWF_TEXT_DOMAIN ), 'app_id' ); ?>
        <?php $this->setting_text_fn( __( 'App Secret', JSL3_FWF_TEXT_DOMAIN ), 'app_secret' ); ?>
        <?php $this->setting_hidden_fn( __( 'Access Token', JSL3_FWF_TEXT_DOMAIN ), 'token' ); ?>
        <?php $this->setting_checkbox2_fn( __( 'Only show posts made by this Facebook ID', JSL3_FWF_TEXT_DOMAIN ), 'fb_id_only', __( 'Facebook ID Only', JSL3_FWF_TEXT_DOMAIN ) ); ?>
        <?php $this->setting_select_fn( __( 'Privacy', JSL3_FWF_TEXT_DOMAIN ), 'privacy', $sel_options ); ?>
        <?php $this->setting_textarea_fn( __( 'Modify the style sheet for the Facebook wall feed.', JSL3_FWF_TEXT_DOMAIN ), 'style', __( 'Style', JSL3_FWF_TEXT_DOMAIN ) ); ?>
      </tbody>
    </table>
    <p class="submit">
      <input id="submit" type="submit" name="update_jsl3_fwf_settings" class="button-primary" value="<?php _e( 'Save Changes', JSL3_FWF_TEXT_DOMAIN ) ?>" />
    </p>
  </form>
</div>
<div style="text-align: center;">
<?php _e( 'Donate to Tak', JSL3_FWF_TEXT_DOMAIN ); ?>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="YQT4472ZPCLXN">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="<?php _e( 'PayPal - The safer, easier way to pay online!', JSL3_FWF_TEXT_DOMAIN ); ?>">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</div>
<div style="text-align: center;">
<?php _e( 'Donate to Fedil Grogan', JSL3_FWF_TEXT_DOMAIN ); ?>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<input type="hidden" name="hosted_button_id" value="SDLFWRVWURBDQ">
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="<?php _e( 'PayPal - The safer, easier way to pay online!', JSL3_FWF_TEXT_DOMAIN ); ?>">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1"><br />
</form>
</div>
<?php
        }//End print_admin_page function

        // }}}
        // {{{ enqueue_style()

        /**
         * Prints out the widget stylesheet
         *
         * Prints out the widget stylesheet if we are not on the admin page.
         *
         * @access public
         * @since Method available since Release 1.0
         */
        function enqueue_style() {
            if ( ! is_admin() ) {
                $dev_options = $this->get_admin_options();
?>
<style type="text/css">
<?php echo stripslashes( $dev_options[ 'style' ] ); ?>
</style>
<?php
            }
        }

        // }}}
        // {{{ set_access()

        /**
         * Requests access to the Facebook App
         *
         * Redirects browser to the Facebook Request for Permission page so
         * that the widget can gain access to Facebook.  A session string is
         * stored for later verification.  This code was taken from Fedil
         * Grogan's set_access.php file.
         *
         * @return string the session string.
         *
         * @access public
         * @since Method available since Release 1.0
         */
        function set_access() {
            $dev_options = $this->get_admin_options();
            // CSRF protection
            $session = md5( uniqid( rand(), TRUE ) );

            $dialog_url =
                "http://www.facebook.com/dialog/oauth?" .
                "scope=read_stream,offline_access,manage_pages,user_status&" .
                "client_id=" . $dev_options[ 'app_id' ] . "&state=$session&" .
                "redirect_uri=" . get_bloginfo('wpurl') .
                "/wp-admin/admin.php?page=" . JSL3_FWF_SLUG;
            $dialog_url = "<script>location.href = '$dialog_url';</script>";
            echo $dialog_url;

            return $session;
        }

        // }}}
        // {{{ get_token()

        /**
         * Requests access token from Facebook
         *
         * Requests access token from Facebook.  The access token is used by
         * the widget to request a facebook wall feed.  A check is made
         * comparing the session state in the query string against the stored
         * session string.
         *
         * @return string the access token.
         *
         * @access public
         * @since Method available since Release 1.0
         */
        function get_token() {
            $dev_options = $this->get_admin_options();
            $code = $_REQUEST[ 'code' ];
            $access_token = NULL;

            // check for a matching session
            if ( $_REQUEST[ 'state' ] == $dev_options[ 'session' ] ) {
                $token_url =
                    "https://graph.facebook.com/oauth/access_token" .
                    "?client_id=" . $dev_options[ 'app_id' ] .
                    "&client_secret=" . $dev_options[ 'app_secret' ] .
                    "&code=$code&redirect_uri=" . get_bloginfo('wpurl') .
                    "/wp-admin/admin.php?page=" . JSL3_FWF_SLUG;

                //$response = file_get_contents($token_url);
                $ch = curl_init();

                curl_setopt( $ch, CURLOPT_URL, $token_url );
                curl_setopt( $ch, CURLOPT_HEADER, 0 );

                ob_start();

                curl_exec( $ch );
                curl_close( $ch );
                $response = ob_get_contents();

                ob_end_clean();

                $params = NULL;
                parse_str( $response, $params );

                if ( isset( $params[ 'access_token' ] ) )
                    $access_token = $params[ 'access_token' ];
                else
                    $this->error_msg_fn(
                        __( 'No access token returned.  Please double check you have correct Facebook ID, App ID, and App Secret.', JSL3_FWF_TEXT_DOMAIN ) );
            
            // if the session doesn't match alert the user
            } else {
                $this->error_msg_fn(
                    __( 'The state does not match. You may be a victim of CSRF.', JSL3_FWF_TEXT_DOMAIN ) );
            }

            return $access_token;
        } // End get_token function

        // }}}
        // {{{ shortcode_handler()

        /**
         * Displays the facebook wall where shortcode appears.
         *
         * Displays the facebook wall as html where shortcode appears.
         * Shortcode exmaples:
         * [jsl3_fwf] - Displays default wall
         * [jsl3_fwf limit="50"] - Displays the default wall with 50 posts.
         *
         * @param array $atts an associative array of attributes
         *
         * @return string the html replacing the shortcode.
         *
         * @access public
         * @since Method available since Release 1.1
         */
        function shortcode_handler( $atts ) {
            extract( shortcode_atts(
                array( 'limit' => JSL3_FWF_WIDGET_LIMIT ), $atts ) );

            $dev_options = $this->get_admin_options();
            $feed = new UKI_Facebook_Wall_Feed(
                $dev_options[ 'fb_id' ],
                $dev_options[ 'app_id' ],
                $dev_options[ 'app_secret' ],
                $limit,
                $dev_options[ 'token' ],
                $dev_options[ 'fb_id_only' ],
                $dev_options[ 'privacy' ] );
            return $feed->get_fb_wall_feed();

            //return $feed->display_fb_wall_feed();
        }

        // }}}


    } // End JSL_Facebook_wall_feed class

} //End if

// }}}

?>
