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
 * @version    1.1
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
 * @version    1.1
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
     * Parsed JSON of the Facebook Wall feed
     *
     * Holds the array of a parsed JSON object of the Facebook Wall feed.
     *
     * @var array
     */
    var $fb_wall_feed;
    
    /**
     * App Secret
     *
     * Secret string of the Facebook App that will access the Facebook
     * profile.
     *
     * @var string
     */
    var $app_secret;
    
    /**
     * App ID
     *
     * ID string of the Facebook App that will access the Facebook
     * profile.
     *
     * @var string
     */
    var $app_id;
    
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

    // }}}
    // {{{ UKI_Facebook_Wall_Feed()

    /**
     * Constructor for this class
     *
     * Constructor initializes class variables.
     *
     * @param string $id          the ID of the Facebook profile.
     * @param string $app_id      the Facebook App ID
     * @param string $app_secret  the Facebook App Secret
     * @param int    $limit       the number of posts to get from the wall
     * @param string $token       the Facebook access token
     *
     * @access public
     * @since Method available since Release 1.0
     */
    function UKI_Facebook_Wall_Feed( $id, $app_id, $app_secret, $limit,
        $token, $id_only, $privacy ) {
        $this->fb_id        = $id;
        $this->app_id       = $app_id;
        $this->app_secret   = $app_secret;
        $this->fb_limit     = $limit;
        $this->access_token = $token;
        $this->fb_id_only   = $id_only;
        $this->fb_privacy   = $privacy;
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
     * @access public
     * @since Method available since Release 1.0
     */
    function get_fb_wall_feed() {
        //echo 'Contacting FaceBook...<br />';
        $id = $this->fb_id;
        //$secret = $this->app_secret;
        //$client_id = $this->app_id;
        $limit = $this->fb_limit;

        // Make call to get authentication token
        //$cht = curl_init();
        //curl_setopt( $cht, CURLOPT_URL,
        //    "https://graph.facebook.com/oauth/access_token?" .
        //   "grant_type=client_credentials&client_id=$client_id&" .
        //    "client_secret=$secret" );
        //curl_setopt( $cht, CURLOPT_RETURNTRANSFER, 1 );
        //$token = curl_exec( $cht );
        //$t_file = get_template_directory() . '/fb_access_token.txt';
        //$token_file = fopen( $t_file, 'r' );
        //$token = fread( $token_file, filesize( $t_file ) );
        $token = 'access_token=' . $this->access_token;
        //fclose( $token_file ); 
        //curl_close( $cht );

        // Now make call to get the wall feed
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL,
            "https://graph.facebook.com/$id/feed?limit=$limit&$token" );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        $result = curl_exec( $ch );
        $this->fb_wall_feed = json_decode( $result, TRUE );
        //error_log( $result );
        //print_r( $this->fb_wall_feed );
        curl_close( $ch );
    } // End get_fb_wall_feed function
    
    // }}}
    // {{{ display_fb_wall_feed()

    /**
     * Displays the Facebook Wall feed
     *
     * Parses the Facebook Wall feed data and stored the data into a print
     * array. Calls print_fb_post() function to perform most of the  html
     * printing.
     *
     * @return string the facebook wall as html
     *
     * @access public
     * @since Method available since Release 1.0
     */
    function display_fb_wall_feed() {
        if ( isset( $this->fb_wall_feed[ 'data' ] ) ) {
            $fb_feed = $this->fb_wall_feed[ 'data' ];
            $is_error = FALSE;
        } else {
            $fb_feed = $this->fb_wall_feed[ 'error' ];
            $is_error = TRUE;
        }

        $result = '<div id="facebook_status_box">' .
                  '  <h2>Facebook Status</h2>' .
                  '  <div id="facebook_canvas">';

        if ( $is_error ) {
            $result .=
                  '    <div style="margin: 5px 0 15px; background-color: #FFEBE8; border-color: #CC0000; border-radius: 3px 3px 3px 3px; border-style: solid; border-width: 1px; padding: 0 0.6em;">' .
                  '      <strong>' .
                  $fb_feed[ 'type' ] . ': ' . $fb_feed[ 'message' ] .
                  '      </strong>' .
                  '    </div>' .
                  '  </div>' .
                  '</div>';
            
            return $result;
        }

        // loop through each post in the feed
        for ( $i = 0; $i < count( $fb_feed ); $i++) {
            $print_array = array();

            // parse status messages
            if ($fb_feed[ $i ][ 'type' ] == 'status') {
                //$fb_msg = NULL;
                //if ( isset( $fb_feed[ $i ][ 'message' ] ) ) {
                //    $fb_msg = $fb_feed[ $i ][ 'message' ];
                //    $fb_id = $fb_feed[ $i ][ 'from' ][ 'id' ];
                //    $fb_name = $fb_feed[ $i ][ 'from' ][ 'name' ];
                //    $fb_photo = "http://graph.facebook.com/$fb_id/picture";
                //    $fb_time = $fb_feed[ $i ][ 'created_time' ];
                //    $fb_story_id = $fb_feed[ $i ][ 'id' ];
                //}

                $print_array[ 'fb_msg' ] = NULL;
                if ( isset( $fb_feed[ $i ][ 'message' ] ) ) {
                    $print_array[ 'fb_msg' ] = $fb_feed[ $i ][ 'message' ];
                    $print_array[ 'fb_id' ] = $fb_feed[ $i ][ 'from' ][ 'id' ];
                    $print_array[ 'fb_name' ] =
                        $fb_feed[ $i ][ 'from' ][ 'name' ];
                    $print_array[ 'fb_photo' ] =
                        'http://graph.facebook.com/' .
                        $print_array[ 'fb_id' ] . '/picture';
                    $print_array[ 'fb_time' ] =
                        $this->parse_fb_timestamp(
                            $fb_feed[ $i ][ 'created_time' ] );
                    $print_array[ 'fb_story_id' ] = $fb_feed[ $i ][ 'id' ];
                    $print_array[ 'fb_icon' ] = NULL;
                    if ( isset( $fb_feed[ $i ][ 'icon' ] ) )
                        $print_array[ 'fb_icon' ] = $fb_feed[ $i ][ 'icon' ];
                    $print_array[ 'post_type' ] = 'status';
                    $print_array[ 'likes' ] = 0;
                    if ( isset( $fb_feed[ $i ][ 'likes' ][ 'count' ] ) )
                        $print_array[ 'likes' ] =
                            $fb_feed[ $i ][ 'likes' ][ 'count' ];

                    $privacy = 'Public';
                    if ( isset(
                        $fb_feed[ $i ][ 'privacy' ][ 'description' ] ) )
                        $privacy =
                            $fb_feed[ $i ][ 'privacy' ][ 'description' ];

                    $show_post = FALSE;
                    if ( $this->fb_privacy == 'All' )
                        $show_post = TRUE;
                    elseif ( $this->fb_privacy == $privacy )
                        $show_post = TRUE;
                    
                    if ( $this->fb_id_only ) {
                        if ( $this->fb_id == $print_array[ 'fb_id' ] ) {
                            if ( $show_post )
                                $result .=
                                    $this->print_fb_post( $print_array );
                        }
                    } else {
                        if ( $show_post )
                            $result .= $this->print_fb_post( $print_array );
                    }
                    //$this->print_fb_post( $fb_story_id, $fb_photo, $fb_id, $fb_name, $fb_msg, $this->parse_fb_timestamp( $fb_time ) );
                }
            
            // parse link and video messages
            } elseif ( $fb_feed[ $i ][ 'type' ] == 'link' ||
                $fb_feed[ $i ][ 'type' ] == 'video' ) {
                //$fb_msg = NULL;
                //if ( isset( $fb_feed[ $i ][ 'message' ] ) )
                //    $fb_msg = $fb_feed[ $i ][ 'message' ];
                //$fb_id = $fb_feed[ $i ][ 'from' ][ 'id' ];
                //$fb_name = $fb_feed[ $i ][ 'from' ][ 'name' ];
                //$fb_photo = "http://graph.facebook.com/$fb_id/picture";
                //$fb_time = $fb_feed[ $i ][ 'created_time' ];
                //$fb_story_id = $fb_feed[ $i ][ 'id' ];

                $print_array[ 'fb_msg' ] = NULL;
                if ( isset( $fb_feed[ $i ][ 'message' ] ) )
                    $print_array[ 'fb_msg' ] = $fb_feed[ $i ][ 'message' ];
                $print_array[ 'fb_id' ] = $fb_feed[ $i ][ 'from' ][ 'id' ];
                $print_array[ 'fb_name' ] = $fb_feed[ $i ][ 'from' ][ 'name' ];
                $print_array[ 'video_length' ] = NULL;
                if ( $fb_feed[ $i ][ 'type' ] == 'video' ) {
                    if ( isset( $fb_feed[ $i ][ 'properties' ] ) )
                        $print_array[ 'video_length' ] =
                            $fb_feed[ $i ][ 'properties' ][ 0 ][ 'text' ];
                }
                $print_array[ 'fb_photo' ] =
                    'http://graph.facebook.com/' .
                    $print_array[ 'fb_id' ] . '/picture';
                $print_array[ 'fb_time' ] =
                    $this->parse_fb_timestamp(
                        $fb_feed[ $i ][ 'created_time' ] );
                $print_array[ 'fb_story_id' ] = $fb_feed[ $i ][ 'id' ];
                $print_array[ 'post_type' ] =
                    ($fb_feed[ $i ][ 'type' ] ) == 'link' ? 'link' : 'video';
                if ( isset( $fb_feed[ $i ][ 'icon' ] ) )
                    $print_array[ 'fb_icon' ] = $fb_feed[ $i ][ 'icon' ];

                $print_array[ 'picture' ] = $fb_feed[ $i ][ 'picture' ];
                $print_array[ 'link' ] = $fb_feed[ $i ][ 'link' ];
                $print_array[ 'link_name' ] = NULL;
                if ( isset( $fb_feed[ $i ][ 'name' ] ) )
                    $print_array[ 'link_name' ] = $fb_feed[ $i ][ 'name' ];
                $print_array[ 'link_caption' ] = NULL;
                if ( isset( $fb_feed[ $i ][ 'caption' ] ) )
                    $print_array[ 'link_caption' ] =
                        $fb_feed[ $i ][ 'caption' ];
                $print_array[ 'link_description' ] = NULL;
                if ( isset( $fb_feed[ $i ][ 'description' ] ) )
                    $print_array[ 'link_description' ] =
                        $fb_feed[ $i ][ 'description' ];
                $print_array[ 'source' ] = NULL;
                if ( isset( $fb_feed[ $i ][ 'source' ] ) )
                    $print_array[ 'source' ] = $fb_feed[ $i][ 'source' ];
                $print_array[ 'likes' ] = 0;
                if ( isset( $fb_feed[ $i ][ 'likes' ][ 'count' ] ) )
                    $print_array[ 'likes' ] =
                        $fb_feed[ $i ][ 'likes' ][ 'count' ];

                $privacy = 'Public';
                if ( isset( $fb_feed[ $i ][ 'privacy' ][ 'description' ] ) )
                    $privacy = $fb_feed[ $i ][ 'privacy' ][ 'description' ];

                $show_post = FALSE;
                if ( $this->fb_privacy == 'All' )
                    $show_post = TRUE;
                elseif ( $this->fb_privacy == $privacy )
                    $show_post = TRUE;
                    
                if ( $this->fb_id_only ) {
                    if ( $this->fb_id == $print_array[ 'fb_id' ] ) {
                        if ( $show_post )
                            $result .= $this->print_fb_post( $print_array );
                    }
                } else {
                    if ( $show_post )
                        $result .= $this->print_fb_post( $print_array );
                }
            
            // parse photo messages
            } elseif ($fb_feed[ $i ][ 'type' ] == 'photo' ) {
                $print_array[ 'fb_msg' ] = NULL;
                if ( isset( $fb_feed[ $i ][ 'message' ] ) )
                    $print_array[ 'fb_msg' ] = $fb_feed[ $i ][ 'message' ];
                $print_array[ 'fb_id' ] = $fb_feed[ $i ][ 'from' ][ 'id' ];
                $print_array[ 'fb_name' ] = $fb_feed[ $i ][ 'from' ][ 'name' ];
                $print_array[ 'fb_photo' ] =
                    'http://graph.facebook.com/' .
                    $print_array[ 'fb_id' ] . '/picture';
                $print_array[ 'fb_time' ] =
                    $this->parse_fb_timestamp(
                        $fb_feed[ $i ][ 'created_time' ] );
                $print_array[ 'fb_story_id' ] = $fb_feed[ $i ][ 'id' ];
                $print_array[ 'post_type' ] = 'photo';
                $print_array[ 'fb_icon' ] = $fb_feed[ $i ][ 'icon' ];

                $print_array[ 'picture' ] = NULL;
                if ( isset( $fb_feed[ $i ][ 'picture' ] ) )
                    $print_array[ 'picture' ] = $fb_feed[ $i ][ 'picture' ];
                $print_array[ 'link' ] = $fb_feed[ $i ][ 'link' ];
                $print_array[ 'link_name' ] = NULL;
                if ( isset( $fb_feed[ $i ][ 'name' ] ) )
                    $print_array[ 'link_name' ] = $fb_feed[ $i ][ 'name' ];
                $print_array[ 'likes' ] = 0;
                if ( isset( $fb_feed[ $i ][ 'likes' ][ 'count' ] ) )
                    $print_array[ 'likes' ] =
                        $fb_feed[ $i ][ 'likes' ][ 'count' ];
                $print_array[ 'description' ] = NULL;
                if ( isset( $fb_feed[ $i ][ 'description' ] ) )
                    $print_array[ 'description' ] =
                        $fb_feed[ $i ][ 'description' ];
                if ( isset( $fb_feed[ $i ][ 'caption' ] ) )
                    $print_array[ 'caption' ] =
                        $fb_feed[ $i ][ 'caption' ];

                $privacy = 'Public';
                if ( isset( $fb_feed[ $i ][ 'privacy' ][ 'description' ] ) )
                    $privacy = $fb_feed[ $i ][ 'privacy' ][ 'description' ];

                $show_post = FALSE;
                if ( $this->fb_privacy == 'All' )
                    $show_post = TRUE;
                elseif ( $this->fb_privacy == $privacy )
                    $show_post = TRUE;
                    
                if ( $this->fb_id_only ) {
                    if ( $this->fb_id == $print_array[ 'fb_id' ] ) {
                        if ( $show_post )
                            $result .= $this->print_fb_post( $print_array );
                    }
                } else {
                    if ( $show_post )
                        $result .= $this->print_fb_post( $print_array );
                }
            } // End if
        } // End for

        $result .= '  </div>' .
                   '</div>';

        return $result;

    } // End display_fb_wall_feed function
    
    // }}}
    // {{{ print_fb_post()

    /**
     * Prints the Facebook Wall feed
     *
     * Prints out Facebook posts.  The different types of posts inlcude:
     * status, link, photo, video.
     *
     * @param array $fb_info an array of Facebook Wall feed data.
     *
     * @return string the facebook posts as html
     *
     * @access public
     * @since Method available since Release 1.0
     */
    //function print_fb_post( $fb_story_id, $fb_photo, $fb_id, $fb_name, $fb_msg, $post_time )
    function print_fb_post( $fb_info ) {
        // begin printing the post
        $fb_msg = $fb_info[ 'fb_msg' ];
        $fb_id = $fb_info[ 'fb_id' ];
        $fb_name = $fb_info[ 'fb_name' ];
        $fb_photo = $fb_info[ 'fb_photo' ];
        $fb_time = $fb_info[ 'fb_time' ];
        $fb_story_id = $fb_info[ 'fb_story_id' ];
        $fb_likes = $fb_info[ 'likes' ];
        $post_time = $fb_info[ 'fb_time' ];
        //if ( $fb_info[ 'fb_icon' ] != '')
        $post_icon = NULL;
        if ( isset( $fb_info[ 'fb_icon' ] ) )
            $post_icon = '<img class="fb_post_icon" src="' .
                $fb_info[ 'fb_icon' ] . '" />';

        $comment_link = $this->fb_comment_link( $fb_story_id );

        $result =
                    '<div class="fb_post">' .
                    '  <div class="fb_photoblock">' .
                    '    <div class="fb_photo">' .
                    '      <a href="http://www.facebook.com/profile.php?id=' . $fb_id . '">' .
                    '        <img src="' . $fb_photo . '" alt="Facebook Profile Pic" />' .
                    '      </a>' .
                    '    </div>' .
                    '    <div class="fb_photo_content">' .
                    '      <h5>' .
                    '        <a href="http://www.facebook.com/profile.php?id=' . $fb_id  . '">' . $fb_name . '</a>' .
                    '      </h5>' .
                    '      <div class="fb_time">';
        if ( isset( $post_icon ) ) $result .= $post_icon;
        $result .= $post_time .
                    '      </div>' .
                    '    </div>' .
                    '  </div>' .
                    '  <div class="fb_msg">';
        if ( isset( $fb_msg ) ) $result .= "<p>$fb_msg</p>";
        
        // print out the photo
        if ( $fb_info[ 'post_type' ] == 'link' ||
            $fb_info[ 'post_type' ] == 'photo' ||
            $fb_info[ 'post_type' ] == 'video' ) {
            $fb_picture = $fb_info[ 'picture' ]; 
            $fb_link = $fb_info[ 'link' ]; 
            $fb_description = NULL;
            if ( isset( $fb_info[ 'link_description' ] ) )
                $fb_description = $fb_info[ 'link_description' ]; 
            $fb_link_name = $fb_info[ 'link_name' ]; 
            $fb_caption = NULL; 
            if ( isset( $fb_info[ 'link_caption' ] ) )
                $fb_caption = $fb_info[ 'link_caption' ];
            $fb_source = NULL;
            if ( isset( $fb_info[ 'source' ] ) )
                $fb_source = $fb_info[ 'source' ];
            $fb_desc = NULL;
            if ( isset( $fb_info[ 'description' ] ) )
                $fb_desc = $fb_info[ 'description' ];
            $fb_cap = NULL;
            if ( isset( $fb_info[ 'caption' ] ) )
                $fb_cap = $fb_info[ 'caption' ];
            $fb_video_length = NULL;
            if ( isset( $fb_info[ 'video_length' ] ) )
                $fb_video_length = $fb_info[ 'video_length' ];

            $result .= 
                    '    <div class="fb_link_post">';

            if ( isset( $fb_picture ) && isset( $fb_source ) )
                $result .=
                    '      <a href="' . $fb_source . '">';
            elseif ( isset( $fb_picture ) && isset( $fb_link ) )
                $result .=
                    '      <a href="' . $fb_link . '">';

            if ( isset( $fb_picture ) )
                $result .=
                    '        <img src="' . $fb_picture . '" />';
            if ( isset( $fb_picture ) && ( isset( $fb_source ) ||
                isset( $fb_link ) ) )
                $result .=
                    '      </a>';
            if ( isset( $fb_link_name ) )
                $result .=
                    '      <h6><a href="' . $fb_link . '">' . $fb_link_name . '</a></h6>';
            if ( isset( $fb_video_length ) )
                $result .=
                    '      <p class="fb_vid_length">Length: <strong>' . $fb_video_length . '</strong></p>';
            if ( isset( $fb_cap ) )
                $result .=
                    '      <p class="fb_cap">' . $fb_cap . '</p>';
            if ( isset( $fb_desc ) )
                $result .=
                    '      <p class="fb_desc">' . $fb_desc . '</p>';
            if ( isset( $fb_caption ) )
                $result .=
                    '      <p class="fb_link_caption"><a href="http://' . $fb_caption . '">' . $fb_caption . '</a></p>';
            if ( isset( $fb_description ) )
                $result .=
                    "      <p>$fb_description</p>";
            $result .=
                    '    </div>';
        }

        // print out the comment part of the post
        $result .=
                    '  </div>' .
                    '  <div class="fb_commLink">' .
                    '    <span class="fb_likes">';
        if ( $fb_likes > 0 )
            $result .=
                    '      <a class="tooltip" title="' . $fb_likes . ' people like this" href="#">' . $fb_likes . '</a>';
        $result .=
                    '    </span>' .
                    '    <span class="fb_comment">' .
                    '      <a href="' . $comment_link . '">Comment</a>' .
                    '    </span>' .
                    '  </div>' .
                    '  <div style="clear: both;"></div>' .
                    '</div>';

        return $result;
    } // End print_fb_post function

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

        //return "Posted: $time_str $date_str";
        return "$date_str at $time_str";
    }

    // }}}

} // End UKI_Facebook_Wall_Feed class

// }}}

?>
