<?php

/**
 * Contains the UKI_Facebook_Wall_Feed class
 *
 * Contains the UKI_Facebook_Wall_Feed class.  See class desciption for more
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

// {{{ UKI_Facebook_Wall_Feed

/**
 * Loads and presents a Facebook Wall feed
 *
 * Loads the Facebook Wall feed. Then parses the JSON sent from Facebook.
 * Finally, prints out the Facebook Wall. This class was originally written
 * by Fedil Grogan.
 *
 * @category   WordPress_Plugin
 * @package    JSL3_FWF
 * @author     Fedil Grogan <fedil@ukneeq.com>
 * @author     Takanudo <fwf@takanudo.com>
 * @copyright  2011-2012
 * @license    http://www.php.net/license/3_01.txt  PHP License 3.01
 * @version    1.2
 * @link       http://takando.com/jsl3-facebook-wall-feed
 * @since      File available since Release 1.0
 */

class UKI_Facebook_Wall_Feed {
    // {{{ properties

    /**
     * Facebook ID
     *
     * ID of the Facebook profile from which to grab the feed.
     *
     * @var string
     */
    var $fb_id;
    
    /**
     * Limit of wall posts
     *
     * Holds the top number of facebook wall posts to get.
     *
     * @var int
     */
    var $fb_limit;
    
    /**
     * Access Token
     *
     * Access token received from Facebook that is used to get the Facebook
     * wall feed.
     *
     * @var string
     */
    var $access_token;
    
    /**
     * Facebook ID Only
     *
     * Determines if only posts made by this FacebookID are shown
     *
     * @var boolean
     */
    var $fb_id_only;
    
    /**
     * Facebook Privacy Settings
     *
     * Determines if all posts or only public posts are shown.
     *
     * @var string
     */
    var $fb_privacy;

    /**
     * Post count
     *
     * Counts the number of posts disaplyed.
     *
     * @var int
     */
    var $post_count;

    /**
     * Run thorough search
     *
     * Will make multiple class to facebook graph api to get posts.
     *
     * @var boolean
     */
    var $thorough;

    /**
     * Open links in a new window
     *
     * Will add 'target="_blank"' to all anchor tags
     *
     * @var boolean
     */
    var $new_win;

    // }}}
    // {{{ UKI_Facebook_Wall_Feed()

    /**
     * Constructor for this class
     *
     * Constructor initializes class variables.
     *
     * @param string  $id          the ID of the Facebook profile.
     * @param string  $app_id      the Facebook App ID (deprecated)
     * @param string  $app_secret  the Facebook App Secret (deprecated)
     * @param int     $limit       the number of posts to get from the wall
     * @param string  $token       the Facebook access token
     * @param boolean $id_only     determines if posts by other usres are shown
     * @param string  $privacy     determines if only public or all posts are
     *                             show
     * @param boolean $be_thorough determines if multiple calls to facebook
     *                             graph will be made
     * @param boolean $new_window  determines if links open in a new window
     *
     * @access public
     * @since Method available since Release 1.0
     */
    function UKI_Facebook_Wall_Feed(
        $id, $app_id = '', $app_secret = '', $limit = JSL3_FWF_WIDGET_LIMIT,
        $token, $id_only = FALSE, $privacy = 'All', $be_thorough = FALSE,
        $new_window = FALSE ) {

        $this->fb_id        = $id;
        $this->fb_limit     = $limit;
        $this->access_token = $token;
        $this->fb_id_only   = $id_only;
        $this->fb_privacy   = $privacy;
        $this->thorough     = $be_thorough;
        $this->new_win      = $new_window;
        $this->post_count   = 0;
        //echo 'Initializing (' . $this->fbID . ')...<br />';

    }
    
    // }}}
    // {{{ get_fb_wall_feed()

    /**
     * Gets the Facebook Wall feed
     *
     * Gets the Facebook Wall feed which is sent from Facebook as JSON.  JSON
     * is then decoded and the resulting array is stored.
     *
     * @return string the facebook wall as html
     *
     * @access public
     * @since Method available since Release 1.0
     */
    function get_fb_wall_feed() {
        //echo 'Contacting FaceBook...<br />';
        $id = $this->fb_id;
        $limit = $this->fb_limit;
        $token = 'access_token=' . $this->access_token;

        // start building facebool wall feed
        $result = '<div id="facebook_status_box">' .
                  '  <h2>' .
                  __( 'Facebook Status', JSL3_FWF_TEXT_DOMAIN ) . '</h2>' .
                  '  <div id="facebook_canvas">';

        // if limit is 0 then we are done
        if ( $limit == 0 ) {
            $result .=
                  '  </div>' .
                  '</div>';

            return $result;
        }

        // inital facebook graph call
        if ( $this->thorough )
            $fb_url = "https://graph.facebook.com/$id/feed?limit=100&$token";
        else
            $fb_url =
                "https://graph.facebook.com/$id/feed?limit=$limit&$token";
        
        // loop until we have reached the limit or have the entire feed
        do {
            
            // get the next page
            if ( isset( $json_feed[ 'paging' ] ) )
                $fb_url = $json_feed[ 'paging' ][ 'next' ];
            
            //error_log( $fb_url );
            $raw_feed = $this->get_json_feed( $fb_url );
            //error_log( $raw_feed );
            $raw_feed = str_replace( '\n', '\u003cbr \/\u003e', $raw_feed );
            //error_log( $raw_feed );
            $json_feed = json_decode( $raw_feed, TRUE );

            // get the data from the feed
            if ( isset( $json_feed[ 'data' ] ) ) {
                $result .=
                    $this->display_fb_wall_feed( $json_feed[ 'data' ] );
                
                // if we have reached the limit the exit
                if ( $this->post_count >= $limit )
                    break;
                $is_error = FALSE;
            
            // grab an error messages
            } elseif ( isset( $json_feed[ 'error' ] ) ) {
                $fb_feed = $json_feed[ 'error' ];
                $is_error = TRUE;
            
            // check if something else was sent from facebook
            } else {
                $fb_feed = json_encode( $json_feed );
                $is_error = TRUE;
            }
        
            // display error message
            if ( $is_error ) {
                $result .=
                  '    <div style="margin: 5px 0 15px; background-color: #FFEBE8; border-color: #CC0000; border-radius: 3px 3px 3px 3px; border-style: solid; border-width: 1px; padding: 0 0.6em;">' .
                  '      <strong>';
                if ( isset( $fb_feed[ 'type' ] ) )
                    $result .=
                        $fb_feed[ 'type' ] . ': ' . $fb_feed[ 'message' ];
                else
                    $result .= $fb_feed;
                $result .=
                  '      </strong>' .
                  '    </div>' .
                  '  </div>' .
                  '</div>';
            
                return $result;
            }

            // if not makeing a thorough search then exit before loop
            if ( ! $this->thorough )
                break;

        } while ( ! empty( $json_feed[ 'data' ] ) );
        
        $result .=
                  '  </div>' .
                  '</div>';

        return $result;
    
    } // End get_fb_wall_feed function
    
    // }}}
    // {{{ get_json_feed()

    /**
     * Gets the Facebook Wall feed
     *
     * Gets the Facebook Wall feed which is sent from Facebook as JSON.
     *
     * @param array $url the facebook graph url.
     *
     * @return string the raw facebook wall feed JSON
     *
     * @access public
     * @since Method available since Release 1.2
     */
    function get_json_feed( $url ) {
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $url );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        $result = curl_exec( $ch );
        curl_close( $ch );

        return $result;

    }

    // }}}
    // {{{ display_fb_wall_feed()

    /**
     * Displays the Facebook Wall feed
     *
     * Parses the Facebook Wall feed data and stored the data into a print
     * array. Calls print_fb_post() function to perform most of the  html
     * printing.
     *
     * @param array $fb_feed an array of Facebook Wall feed data.
     *
     * @return string the facebook wall as html
     *
     * @access public
     * @since Method available since Release 1.0
     */
    function display_fb_wall_feed( $fb_feed ) {

        $result = '';
        $target = '';
        if ( $this->new_win )
            $target = ' target="_blank"';

        // loop through each post in the feed
        for ( $i = 0; $i < count( $fb_feed ); $i++) {
            
            // exit the loop if we have reached the limit
            if ( $this->post_count >= $this->fb_limit )
                break;

            $print_array = array();

            $fb_id = $fb_feed[ $i ][ 'from' ][ 'id' ];

            // privacy check, if no privacy then assume public
            $privacy = 'Public';
            if ( isset( $fb_feed[ $i ][ 'privacy' ][ 'description' ] ) )
                $privacy = $fb_feed[ $i ][ 'privacy' ][ 'description' ];

            $privacy_good = FALSE;
            if ( $this->fb_privacy == 'All' )
                $privacy_good = TRUE;
            elseif ( $this->fb_privacy == $privacy )
                $privacy_good = TRUE;
                    
            // check to see if we are not getting posts by other facebook
            // friends
            $show_post = FALSE;
            if ( $this->fb_id_only ) {
                if ( $this->fb_id == $fb_id ) {
                    if ( $privacy_good )
                        $show_post = TRUE;
                }
            } else {
                if ( $privacy_good )
                    $show_post = TRUE;
            }
                
            // don't display posts without a message, name, caption,
            // or description they are just usually "is now friends with"
            // posts
            if ( $show_post && ( isset( $fb_feed[ $i ][ 'message' ] ) ||
                isset( $fb_feed[ $i ][ 'name' ] ) ||
                isset( $fb_feed[ $i ][ 'caption' ] ) ||
                isset( $fb_feed[ $i ][ 'description' ] ) ) ) {
                    
                $comment_link =
                    $this->fb_comment_link( $fb_feed[ $i ][ 'id' ] );
                $fb_photo  =
                    "http://graph.facebook.com/$fb_id/picture";
                $post_time =
                    $this->parse_fb_timestamp(
                        $fb_feed[ $i ][ 'created_time' ] );
                $fb_picture = NULL;
                if ( isset( $fb_feed[ $i ][ 'picture' ] ) )
                    $fb_picture = $fb_feed[ $i ][ 'picture' ];
                $fb_source = NULL;
                if ( isset( $fb_feed[ $i ][ 'source' ] ) )
                    $fb_source = $fb_feed[ $i][ 'source' ];
                $fb_link = NULL;
                if ( isset( $fb_feed[ $i ][ 'link' ] ) )
                    $fb_link = $fb_feed[ $i ][ 'link' ];
                $fb_likes = 0;
                if ( isset( $fb_feed[ $i ][ 'likes' ][ 'count' ] ) )
                    $fb_likes = $fb_feed[ $i ][ 'likes' ][ 'count' ];
                $fb_prop = FALSE;
                $fb_prop_name = NULL;
                $fb_prop_text = NULL;
                $fb_prop_href = NULL;
                if ( isset( $fb_feed[ $i ][ 'properties' ][ 0 ] ) ) {
                    $fb_prop = TRUE;
                    if ( isset(
                        $fb_feed[ $i ][ 'properties' ][ 0 ][ 'name' ] ) )
                        $fb_prop_name =
                            $fb_feed[ $i ][ 'properties' ][ 0 ][ 'name' ];
                    if ( isset(
                        $fb_feed[ $i ][ 'properties' ][ 0 ][ 'text' ] ) )
                        $fb_prop_text =
                            $fb_feed[ $i ][ 'properties' ][ 0 ][ 'text' ];
                    if ( isset(
                        $fb_feed[ $i ][ 'properties' ][ 0 ][ 'href' ] ) )
                        $fb_prop_href =
                            $fb_feed[ $i ][ 'properties' ][ 0 ][ 'href' ];
                }

                $result .=
                  '    <div class="fb_post">' .
                  '      <div class="fb_photoblock">' .
                  '        <div class="fb_photo">' .
                  '          <a href="http://www.facebook.com/profile.php?id=' . $fb_id . '"' . $target . '>' .
                  '            <img src="' . $fb_photo . '" alt="' . __( 'Facebook Profile Pic', JSL3_FWF_TEXT_DOMAIN ) . '" />' .
                  '          </a>' .
                  '        </div>' .
                  '        <div class="fb_photo_content">' .
                  '          <h5>' .
                  '            <a href="http://www.facebook.com/profile.php?id=' . $fb_id  . '"' . $target . '>' . $fb_feed[ $i ][ 'from' ][ 'name' ] . '</a>' .
                  '          </h5>' .
                  '          <div class="fb_time">';
                if ( isset( $fb_feed[ $i ][ 'icon' ] ) )
                    $result .=
                  '            <img class="fb_post_icon" src="' . $fb_feed[ $i ][ 'icon' ] . '" />';
                $result .= $post_time .
                  '          </div>' .
                  '        </div>' .
                  '      </div>' .
                  '      <div class="fb_msg">';
                if ( isset( $fb_feed[ $i ][ 'message' ] ) )
                    $result .= 
                  '        <p>' . $fb_feed[ $i ][ 'message' ] . '</p>';
                $result .= 
                  '        <div class="fb_link_post">';
                if ( isset( $fb_picture ) && isset( $fb_source ) )
                    $result .=
                  '          <a href="' . $fb_source . '"' . $target . '>';
                elseif ( isset( $fb_picture ) && isset( $fb_link ) )
                    $result .=
                  '          <a href="' . $fb_link . '"' . $target . '>';
                if ( isset( $fb_picture ) )
                    $result .=
                  '            <img src="' . $fb_picture . '" />';
                if ( isset( $fb_picture ) && ( isset( $fb_source ) ||
                    isset( $fb_link ) ) )
                    $result .=
                  '          </a>';
                if ( isset( $fb_feed[ $i ][ 'name' ] ) )
                    $result .=
                  '          <h6><a href="' . $fb_link . '"' . $target . '>' . $fb_feed[ $i ][ 'name' ] . '</a></h6>';
                if ( isset( $fb_feed[ $i ][ 'caption' ] ) )
                    $result .=
                  '          <p class="fb_cap">' . $fb_feed[ $i ][ 'caption' ] . '</p>';
                if ( isset( $fb_feed[ $i ][ 'description' ] ) )
                    $result .=
                  '          <p class="fb_desc">' . $fb_feed[ $i ][ 'description' ] . '</p>';
                if ( $fb_prop )
                    $result .=
                  '          <p class="fb_vid_length">';
                if ( isset( $fb_prop_name ) )
                    $result .= $fb_prop_name . ': ';
                if ( isset( $fb_prop_href ) )
                    $result .= '<a href="' . $fb_prop_href . '"' . $target . '>';
                if ( isset( $fb_prop_text ) )
                    $result .= $fb_prop_text;
                if ( isset( $fb_prop_href ) )
                    $result .= '</a>';
                if ( $fb_prop )
                    $result .=
                  '          </p>';
                $result .=
                  '        </div>' .
                  '      </div>' .
                  '      <div class="fb_commLink">' .
                  '        <span class="fb_likes">';
                if ( $fb_likes > 0 )
                    $result .=
                  '          <a class="tooltip" title="' . $fb_likes . ' ' . __( 'people like this', JSL3_FWF_TEXT_DOMAIN ) . '" href="#">' . $fb_likes . '</a>';
                $result .=
                  '        </span>' .
                  '        <span class="fb_comment">' .
                  '          <a href="' . $comment_link . '"' . $target . '>' . __( 'Comment', JSL3_FWF_TEXT_DOMAIN ) . '</a>' .
                  '        </span>' .
                  '      </div>' .
                  '      <div style="clear: both;"></div>' .
                  '    </div>';
                
                $this->post_count++;

            } // end if
            
        } // End for

        return $result;

    } // End display_fb_wall_feed function
    
    // }}}
    // {{{ fb_comment_link()

    /**
     * Forms a Facebook comment link
     *
     * Forms a Facebook comment link by parsing the ID of the post.
     *
     * @param string $fb_story_id the id of the post to be parsed.
     *
     * @return string the parsed comment link
     *
     * @access public
     * @since Method available since Release 1.0
     */
    function fb_comment_link( $fb_story_id ) {
        $link = 'http://www.facebook.com/permalink.php?';
        $split_id = explode( '_', $fb_story_id );
        $link .= 'id=' . $split_id[ 0 ] . '&story_fbid=' . $split_id[ 1 ];

        return $link;
    }

    // }}}
    // {{{ parse_fb_timestamp()

    /**
     * Forms a time stamp
     *
     * Adjusts the time stamp to local time.
     *
     * @param string $fb_time the time stamp of the post.
     *
     * @return string the parsed time stamp.
     *
     * @access public
     * @since Method available since Release 1.0
     */
    function parse_fb_timestamp( $fb_time ) {
        $time_stamp = explode( 'T', $fb_time );
        $date_str = $time_stamp[ 0 ];
        $date_str = date_format( date_create( $date_str ), 'l, F jS' );

        $time_arr = explode( ':', $time_stamp[ 1 ] );
        $time_hr = $time_arr[ 0 ] + get_option( 'gmt_offset' );
        if ( $time_hr < 0 )
            $time_hr = 24 + $time_hr;
        $time_str = $time_hr . ':' . $time_arr[ 1 ];

        return $date_str . ' ' . __( 'at', JSL3_FWF_TEXT_DOMAIN ) . ' ' . $time_str;
    }

    // }}}

} // End UKI_Facebook_Wall_Feed class

// }}}

?>
