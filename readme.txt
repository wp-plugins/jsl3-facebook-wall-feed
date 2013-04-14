=== JSL3 Facebook Wall Feed ===
Contributors: Takanudo
Donate link: http://takanudo.com/jsl3-facebook-wall-feed
Tags: facebook, wall, profile, page, feed
Requires at least: 3.2.1
Tested up to: 3.5.1
Stable tag: 1.5.3

Displays your Facebook wall as a widget or through shortcode on a post or page.

== Description ==

Displays your Facebook wall as a widget or through shortcode on a post or page. Makes use of Fedil Grogan's [Facebook Wall Feed for WordPress](http://fedil.ukneeq.com/2011/06/23/facebook-wall-feed-for-wordpress-updated) code and changes suggested by [Daniel Westergren](http://danielwestergren.se) and [Neil Pie](http://www.neilpie.co.uk).

== Installation ==

1. Extract the zip file to the '/wp-content/plugins/' directory.

1. **Activate** the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= Where can I find support for this plugin? =

You can find support on the
[JSL3 Facebook Wall Feed](http://takanudo.com/jsl3-facebook-wall-feed) page.
Just add a comment and I will do my best to help you.

= How do I use shortcode to add the Facebook Wall Feed to a post or page? =

Switch to HTML view and add the following:

    [jsl3_fwf]

To limit the number of posts displayed add the 'limit' attribute:

    [jsl3_fwf limit="1"]

= Can I translate the plugin? =

I would be happy if you translated the plugin.  You can use the 'default.po'
file found in the 'wp-content/plugins/jsl3-facebook-wall-feed/languages'
directory.  Use [Poedit](http://www.poedit.net) to translate the plugin into
your language and then save the PO file using the text domain ('jsl3-fwf'),
language code and country code as the name.  For example, if you translate the
plugin into German, you should save the file as 'jsl3-fwf-de_DE.po'.  Finally,
place the translated PO file and its corresponding MO file in the
'wp-content/plugins/jsl3-facebook-wall-feed/languages' directory.

Let me know the URL of the site with the translated plugin by posting a comment
on the [JSL3 Facebook Wall Feed](http://takanudo.com/jsl3-facebook-wall-feed)
page.

= How do I find my Facebook ID? =

Click on any image in your facebook photos and note the URL. It should look
something like this: http://www.facebook.com/media/set/?set=a.123456789012.123456.XXXXXXXXX&type=3

The “XXXXXXXXX” is your Facebook ID. Even if you see more sets of numbers than the
above, the last set is your Facebook ID.

= How do I get rid of the 'Facebook Status' box? =

To remove the 'Facebook Status' box add the following to the bottom of the
style sheet on the settings page for the plugin:

    /* Remove Facebook Status */
    #facebook_status_box h2
    {
        display: none;
    }

= How can I adjust the width of the Facebook Wall Feed? =

To adjust the width of the Facebook Wall Feed add the following to the bottom
of the style sheet on the settings page for the plugin:

    /* Adjust width */
    #facebook_status_box
    {
        width: 225px;
    }

Change the number in front of "px" to one that fits for you.

= Why do comments look so bad? =

To fix the formatting of the comments, add the following to the bottom of the
style sheet on the settings page for the plugin:

    /* Format comments */
    #facebook_status_box .fb_msg p.fb_story
    {
        font-size: 10px;
        color: #999999;
    }
    #facebook_status_box .fb_post .fb_comments
    {
        background-color: #EDEFF4;
        font-size: 11px;
        border-bottom: 1px solid #e6e6fa;
        overflow: hidden;
        padding: 7px;
        margin: 0;
    }
    #facebook_status_box .fb_post .fb_comments p
    {
        font-size: 11px;
        margin: 0;
        padding: 0;
        float: left;
    }
    #facebook_status_box .fb_post .fb_comments a
    {
        color: #0A7A98;
        text-decoration: none;
    }
    #facebook_status_box .fb_post .fp_photo_content
    {
        width: 85%
    }

= What does the error "OAuthException: Error validating access token: The session has been invalidated because the user has changed the password" mean? =

It usually means you changed your Facebook password recently. Go to the
settings page for the plugin and click "Save Changes" to validate your session.

= What does the error "OAuthException: An access token is required to request this resource" mean? =

It usually means you do not have an access token.  Check that your App ID and
App Secret are correct and that there are no extra spaces in front or after
them.  Then click "Save Changes" on the settings page for the plugin.

= What does the error "Exception: No node specified" mean? =

It usually means you have not set your Facebook ID.  Check that you have
entered your Facebook ID on the settings page for the plugin. Then click "Save
Changes" on the settings page for the plugin.

= What does the error "Exception: SETTINGS: Unrecognized pref_type 0 for NullProfileSettings pref name default_non_connection_tab" mean? =

It usually means are using an incorrect Facebook ID.  Check that your Facebook
ID is correct.  Then click "Save Changes" on the settings page for the plugin.

= What does a pink box with a red outline with either ":" or "n: n" in it mean? =

It usually means you have some extra spaces in front or after your Facebook ID.
Remove the spaces and then click "Save Changes" on the settings page for the
plugin.

= What does "An error occurred with [Your App Name]. Please try again later" mean? =

This is a Facebook error and may also include the following message:

> API Error Code: 191
> API Error Description: The specified URL is not owned by the application
> Error Message: Invalid redirect_uri: Given URL is not allowed by the Application configuration. 

This error means that the App Domain and Site URL for your Facebook App do not
match the domain of the website where you are using the plugin. Go to
[https://developers.facebook.com/apps](https://developers.facebook.com/apps)
and click "Edit Settings". Under "Basic Info", change your "App Domain" to
match the domain of the website where the plugin is located. In the "Select how
your app integrates with Facebook" section, under "Website", change your "Site
URL" to match the URL of the website where the plugin is located.

= Why is my feed blank? =

First, it could mean you have an invalid value set for the "limit" in the
shortcode or the "number of wall posts to get" in the widget.  Please make sure
you have a valid number greater than zero with no spaces in front or after the
number.

Second, the JSL3 Facebook Wall Feed filters out status messages (stuff like
"person1 is now friends with person2").  If your wall feed contains many status
messages, facebook might not be sending any actual wall posts in your feed.
Checking the "Thorougness" option in the settings page of the plugin, will
force the JSL3 Facebook Wall Feed to continue contacting Facebook until actual
wall posts are found.  NOTE: This will slow down the feed.

== Screenshots ==

1. **Activate** the plugin through the 'Plugins' menu in WordPress.

2. Allow Developer to access your basic information.

3. Click **Create New App**.

4. Enter any **App Display Name** and **App Namespace**.

5. On your App page, enter your **App Domain**. Under **Select how your app
   integrates with Facebook** click **Website** and enter your **Site URL**.

6. Go to **JSL3 Facebook Wall Feed** under **Settings** on the Dashboard menu.

7. Enter your **Facebook ID**.

8. You will be redirected to Facebook. You may be prompted to **Log In** a
   couple of times.

9. Allow your Facebook App to have access to your Facebook profile.

10. You will be returned to the JSL3 Facebook Wall Feed settings page with your
    **Access Token**.

11. Drag the **JSL3 Facebook Wall Feed** widget to the sidebar of your choice.

12. Give the widget a title (or leave it blank) and enter how many posts you
    want to get from your wall.

13. Go check out your Facebook Wall Feed on your WordPress site.

14. Add the shortcode **`[jsl3_fwf]`** or **`[jsl3_fwf limit="1"]`** to the
    **HTML** view of a post or page.

15. View your Facebook Wall Feed on your WordPress post or page.

== Changelog ==

= 1.5.3 =
* Fixed a minor bug introduced in v1.5.2

= 1.5.2 =
* Fixed privacy setting to work with the change Facebook made to how they
  display privacy settings in the feed.
* Minor change to how the style sheet is enqueued into the header.

= 1.5.1 =
* Fixed a bug in the shortcode introduced in v1.5

= 1.5 =
* Added an option to disable the make_clickable() WordPress function added in
  v1.4.2
* The plugin will now notify the WordPress admin that their Facebook access
  token is about to expire a week from the expiration date.

= 1.4.2 =
* Added make_clickable() WordPress function to convert plain text URI to HTML
  links.

= 1.4.1 =
* Added CRON schedule to refresh expired tokens because Facebook no longer
  allows non-expiring tokens.
* Fixed 1 pixel images filtered through Facebook's safe_image.php file.
* Added ability to turn off displaying Facebook icons.
* Added additional security features.

= 1.3.1 =
* Made the feed validate XHTML 1.0 Strict.
* Made a cURL and allow_url_fopen check.
* Feed will now use the same locale as WordPress.
* Added ability to turn of SSL certificate verification.
* Added ability to display profile picture from Facebook pages with
  demographic restrictions.

= 1.2 =
* Added default.po file to support localization.
* Added thoroughness check.
* Added ability to show status messages.
* Added ability to show post comments.
* Added ability to open links in a new window or tab.
* Feed will now display a greater variety of wall posts.
* Accounted for newline character.

= 1.1 =
* Fixed a PHP Notice error when displaying video posts.
* Added shortcode capability.
* Added a property to limit posts to only the user (posts by other users
  are not displayed).
* Added a privacy setting to limit the feed to only public posts.
* Added contextual help.
* Added better error handling.

= 1.0 =
* This is the initial version.

== Upgrade Notice ==

= 1.5.3 =
Fixed a minor bug introduced in v1.5.2.

= 1.5.2 =
Facebook changed public privacy setting to be a blank entry, so I have
adjusted the plugin to account for that.  Also, for some users, the
style sheet would be embedded more than once.  This update should fix
that.

= 1.5.1 =
This is a minor shortcode bug fix.

= 1.5 =
This update adds an option to disable the make_clickable() WordPress function
added in v1.4.2.  Also, the automatic Facebook access token renewal added in
v1.4.1 never worked properly.  So now the plugin will now notify the WordPress
admin that their Facebook access token is about to expire a week from the
expiration date.  Renewing the token should simply be a matter of clicking
"Save Changes" on the settings page for the plugin.

= 1.4.2 =
This is a minor update that adds the make_clickable() WordPress function to
convert plain text URI to HTML links.

= 1.4.1 =
This update adds additional security features.  It also adds a CRON schedule
to refresh expired tokens because Facebook no longer allows non-expiring
tokens.

= 1.3.1 =
This update should validate under XHTML 1.0 Strict.  It also checks to see if
cURL is loaded or allow_url_fopen is on.  The feed will now use the same locale
setting that WordPress is using.

= 1.2 =
This upgrade provides support for localization.  Feel free to use the
'default.po' file in the 'languages' directory to create a translation of
the plugin.

= 1.1 =
This upgrade provides added security measures and better error handling.

= 1.0 =
This is the initial version.

== Configuration ==

1. [Create your Facebook App](https://developers.facebook.com/apps).

1. **Allow** Developer to access your basic information.

1. Click **Create New App**.

1. Enter any **App Display Name** and **App Namespace**. I suggest using the
   name of your blog. Agree to the Facebook Platform Policies and click
   **Continue**. You will be prompted with a security check.

1. On your App page, enter your **App Domain**. Under **Select how your app
   integrates with Facebook** click **Website** and enter your **Site URL**.
   Then save your changes.

1. Record your **App ID** and **App Secret**. You will need these later.

1. Go to **JSL3 Facebook Wall Feed** under **Settings** on the Dashboard menu.

1. Enter your **Facebook ID**. This is the number at the end of your Facebook
   profile URL. Enter the **App ID** and **App Secret** you recorded earlier.
   Click **Save Changes**.

1. You will be redirected to Facebook. You may be prompted to **Log In** a
   couple of times.

1. Allow your Facebook App to have access to your Facebook profile.

1. You will be returned to the JSL3 Facebook Wall Feed settings page with your
   **Access Token**.

== Widget Usage ==

1. Go to **Widgets** under **Appearance** on the Dashboard menu.

1. Drag the **JSL3 Facebook Wall Feed** widget to the sidebar of your choice.

1. Give the widget a title (or leave it blank) and enter how many posts you
   want to get from your wall. Then click **Save**.

1. Go check out your Facebook Wall Feed on your WordPress site.

== Shortcode Usage ==

1. Add the shortcode **`[jsl3_fwf]`** or **`[jsl3_fwf limit="1"]`** to the
   **HTML** view of a post or page.

1. View your Facebook Wall Feed on your WordPress post or page.
