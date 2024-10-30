=== Plugin Name ===
MetaRedirect

Contributors:      codecide
Plugin Name:       MetaRedirect
Plugin URI:        https://plugins.codecide.net/plugin/metaredirect
Tags:              automation, redirection
Author URI:        https://codecide.net/
Author:            Codecide
Donate link:       https://www.redcross.org/donate/donation
Requires PHP:      5.4
Requires at least: 4.5
Tested up to:      5.6
Stable tag:        1.0.0
Version:           1.0.0

== Description ==
MetaRedirect is a lightweight module that allows any post to be redirected to a URL address stored in a custom (meta) field. You can use MetaRedirect in cases where your posts need to act as pass-through for other web destinations, either internal or external. This plugin does not have any side-effects and uses a minimal amount of resources.

The module can be disabled from the settings page. *Important:* If you leave the _Enabled_ box unchecked, the plugin will have no effect at all. 

You can configure MetaRedirect to use any custom field to hold the value of the target URL. 

You can set up the redirection to either permanent (301), or temporary (302). Using the former setting, the client browsers or search engines that hit your post's page will _remember_ the redirection and perform it automatically the next time they visit your post. If you use a temporary redirection instead, those clients will request the redirection anew. In other words, only use Permanent (301) redirections if you are sure that the target will never change.

You can further configure the redirection's trigger to be permanent or provisional. A permanent trigger will enforce the redirection automatically. A provisional trigger will only perform the redirection when a preset query parameter (see below) is found in the requested URL. For example, if you set the trigger parameter to _jump_, the post's URL will be redirected if, and only if, it contains "?jump", otherwise nothing will happen. 

The trigger parameter is a string used to trigger provisional redirections (see above). URL parameters appear after the question mark ("?") in the URL. Though query parameters typically consists of key/value pairs separated by an equal sign (e.g., _key=value_) the MetaRedirect provisional trigger only requires the key to be present (the left side of the =) and will ignore any associated value. 

Finally, you can set the redirection to append custom URL parameters to the target These additional parameters need to be URL-encoded key/value pairs; the parameters will be merged with the original URL seamlessly. 

== Installation ==

Install the plugin and configure the redirections as needed in the MetaRedirect settings page: /wp-admin/admin.php?page=metaredirect_settings. 

_Note_ that the plugin is *not* enabled by default. Check the _Enabled_ box in the settings page to turn it on.

General information about installing WordPress plugins can be found [here](https://codex.wordpress.org/Managing_Plugins#Installing_Plugins)

== Upgrade Notice ==
= 1.0 =
This is the first (and so far, only) publicly available version.

== Screenshots ==
1. The configuration screen.

== Changelog ==

= 1.0 =
* Initial release.

== Roadmap ==
- Redirection tracking report 
- Redirected posts listing page 

== Frequently Asked Questions ==
= Is there a way to use multiple configurations? =
Not at this time. The feature can be added to the roadmap if and when there's a demand. Leave a note in the comments if this is one of your requirements.
= Is it really free? =
Yes, this plugin is completely free. 

== Donations ==
None needed. 
