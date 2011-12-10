=== JSL3 Facebook Wall Feed ===
Contributors: Takanudo
Donate link: http://takanudo.com/jsl3-facebook-wall-feed
Tags: facebook, wall, profile, page, feed
Requires at least: 3.2.1
Tested up to: 3.3
Stable tag: 1.1

Displays your Facebook wall as a widget or through shortcode on a post or page.

== Description ==

Displays your Facebook wall as a widget or through shortcode on a post or page. Makes use of Fedil Grogan's [Facebook Wall Feed for WordPress](http://fedil.ukneeq.com/2011/06/23/facebook-wall-feed-for-wordpress-updated) code and changes suggested by [Daniel Westergren](http://danielwestergren.se).

== Installation ==

1. Extract the zip file to the '/wp-content/plugins/' directory.

1. **Activate** the plugin through the 'Plugins' menu in WordPress.

== Frequently Asked Questions ==

= Where can I find support for this plugin? =

You can find support on the
[JSL3 Facebook Wall Feed](http://takanudo.com/jsl3-facebook-wall-feed) page.
Just add a comment and I will do my best to help you.

= How do I use shortcode to add the Facebook Wall Feed to a post or page?

Switch to HTML view and add the following:

[jsl3_fwf]

To limit the number of posts displayed add the 'limit' attribute:

[jsl3_fwf limit="1"]

= How do I get rid of the 'Facebook Status' box?

To remove the 'Facebook Status' box add the following to the bottom of the
style sheet on the settings page for the plugin.

/* Remove Facebook Status */
#facebook_status_box h2
{
display: none;
}

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

14. Add the shortcode **[jsl3_fwf]** or **[jsl3_fwf limit="1"]** to the
    **HTML** view of a post or page.

15. View your Facebook Wall Feed on your WordPress post or page.

== Changelog ==

= 1.1 =
* Fixed a PHP Notice error when displaying video posts.
* Added shortcode capability.
* Added a property to limit posts to only the user (posts by other users are not displayed).
* Added a privacy setting to limit the feed to only public posts.
* Added contextual help.
* Added better error handling.

= 1.0 =
* This is the initial version.

== Upgrade Notice ==

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

1. Add the shortcode **[jsl3_fwf]** or **[jsl3_fwf limit="1"]** to the
   **HTML** view of a post or page.

1. View your Facebook Wall Feed on your WordPress post or page.
