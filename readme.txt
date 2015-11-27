=== Plugin Name ===
Plugin Name: Device Theme Switcher
Contributors: jamesmehorter
Requires at least: 3.0
Tested up to: 4.3.1
Stable tag: 3.0.0
Tags: Theme, Switch, Change, Mobile, Mobile Theme, Handheld, Tablet, iPad, iPhone, Android, Blackberry, Tablet Theme, Different Themes, Device Theme
Author URI: http://www.jamesmehorter.com/
Plugin URI: https://github.com/jamesmehorter/device-theme-switcher/wiki
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Assign separate themes for handheld and tablet devices under Appearance > Device Themes

== Description ==

Device Theme Switcher is a WordPress plugin which delivers one of your WordPress themes to handheld visitors and another theme to tablet visitors. Computer visitors are given the active theme in 'Appearance > Themes'.

[Features](https://github.com/jamesmehorter/device-theme-switcher/wiki/2-Features) | [FAQ](https://github.com/jamesmehorter/device-theme-switcher/wiki/3-FAQ) | [Contributing](https://github.com/jamesmehorter/device-theme-switcher/wiki/4-Contributing)

== Screenshots ==

1. View of the Device Theme Switcher settings.
2. View of the two Device Theme Switcher Widgets and their settings.

== Installation ==

= How to Install =

Install and activate in your WordPress Dashboard 'Plugins > Add New' section by searching for 'Device Theme Switcher'.

After activation you'll have a new menu: 'Appearance > Device Themes'--where you set which theme is given to which visitor device.

== Changelog ==

= Version 3.0.0 - Released 10/23/2015 =
* New banner and icon! :)
* FIX - Corrected an issue with the plugin update code which caused some user's to not receive the complete update routine.
* FIX - Added rel="nofollow" to the device switcher <a> links which the plugin creates. Thanks ljmac for this! https://wordpress.org/support/topic/please-make-theme-switcher-link-no-follow?replies=2
* FIX - Corrected a PHP recursion found by Jonathan McLean (https://github.com/jmclean)
* FIX - Updated widget classes per WP_Widget deprecated use PHP notice
* IMPROVEMENT - Rewrote several aspects of the plugin to improve testability, which ultimately improves the codebase architecture and readability.
* IMPROVEMENT - Updated to the latest [MobileESP version](https://github.com/ahand/mobileesp)
* IMPROVEMENT - Introduced initial phpunit tests for the plugin.
* IMPROVEMENT - Moved the massive Github readme into sections in a new Github wiki.
* IMPROVEMENT - Implemented Travis on-the-fly testing integration for Github.
* IMPROVEMENT - Implemented Grunt for SASS & JS complilation, and automated *.pot file creation for translators.

= Version 2.9.2 - Released 12/19/2014 =
* FIX - Completely removed the use of a singleton base class. It just doesn't work for PHP <= 5.2 which we must support.

= Version 2.9.1 - Released 12/18/2014 =
* FIX - Removed use of Late Static Binding which is a PHP 5.3 feature, though WordPress supports back to PHP 5.2.4. Thank you Craig S. for pointing this out!

= Version 2.9.0 - Released 12/18/2014 =
* IMPROVRMENT - Rewrites/Modifications throughout the plugin code to improve overall stability, maintainability, and adherence to WordPress Coding Standards.
* IMPROVEMENT - Rewrote the updating routine to be far more stable, logical, and to allow 3 digit version numbers (ex: 2.9.0).
* IMPROVEMENT - Added initial .pot for i18n. Users can now provide translations via .po files by using the .pot.
* FIX - Corrected an issue reported by QStudio (https://wordpress.org/support/topic/small-bug-in-28?replies=2#post-6238900) whereby the plugin update routine would fail and run on each page load. Ouch. Thanks QStudio!

= Version 2.8 - Released 5/18/2014 =
* FIX - Removed an empty space (whitespace) preceding <?php which caused numerous issues for people by PHP throwing PHP Warning: Cannot modify header information - headers already sent by (output started at /my-home/wp-content/plugins/device-theme-switcher/inc/class.switcher.php:1) in /my-home/wp-content/plugins/device-theme-switcher/inc/class.switcher.php on line 176 -- thanks @jontroth!

= Version 2.7 - Released 05/11/2014 =
* IMPROVEMENT - Ensure the ?theme= GET variables added to each link via DTS_Switcher::build_html_link() preserve the existing GET variables already in place.
* FIX - An issue where the DTS Widgets would produce a PHP Warning

= Version 2.6 - Released 05/8/2014 =
* FIX - An issue where the 2.4php update script wouldn't load properly on some setups

= Version 2.5 - Released 05/7/2014 =
* FIX - An issue where dts_cookie_name would not be set properly on update

= Version 2.4 - Released 05/10/2014 =
* IMPROVEMENT - Replaced the use of PHP Sessions with Cookies (This is gonna fix a lot of past issues!)
* IMPROVEMENT - Refactored all plugin code to contain proper Docblock commenting and be more legible
* FIX - Corrected an issue where X UA Device fallback was handheld, not active. Thanks @Dachande663!
* FIX - Corrected an issue where DTS_Switcher would not execute during an ajax request to admin_ajax. Thanks Ray @ qStudio!
* REMOVAL - Removed the Dashboard Right Now DTS Output, it wasn't that cool to begin with..

= Version 2.3 - Released 09/16/2013 =
* FIX - Corrected an issue which caused a PHP error to be thrown under odd conditions. Thanks Davis Wuolle!

= Version 2.2 - Released 08/28/2013 =
* FIX - Corrected an issue which caused folks running WordPress Version Pre 3.4 to not see themes in the admin theme select lists. Thanks jstroem for 346b5ec (https://github.com/jstroem/device-theme-switcher/commit/346b5ec1582539b621ef4583153a7dbc6c9423b9)

= Version 2.1 - Released 08/26/2013 =
* FIX - Corrected an issue (http://wordpress.org/support/topic/version-20-compatibilities-problems-with-jonradio-multiple-themes) where plugins which modify pre_option_template or pre_option_stylesheet would be overridden by Device Theme Switcher. Thanks EmuZone for pointing this out!
* FIX - Corrected an issue with the Admin Dashboard 'Right Now' to properly display the active device themes.

= Version 2.0 - Released 08/24/2013 =
* Complete code rewrites to improve overall performance, redundancy, and improve extensibility.
* NEW - DTS Class access for use in themes; obtain info on the current user's device and saved dts settings. Examples in the FAQ.
* NEW - URL Switching - Easily check what other devices see. Examples in the FAQ.
* NEW - Session Timeout, so users who visit the 'Desktop' theme are bumped back to their device theme after 15 minutes
* NEW - Optional Settings section in the WordPress admin in Appearance > Device Themes Allows users to override the session timeout. Also moved the low-support theme setting to this section. This new section also a
* NEW - Help & Support section in the WordPress admin in Appearance > Device Themes
* Included the latest version of MobileESP ~ Thanks Anthony!

= Version 1.9 =
* Note: DTS Version 1.9 was not released to the public
* NEW - Made the Admin UI more presentable and WordPressy
* FIX - Included a pull request from Tim Broder (https://github.com/broderboy) which adds support for Varnish Device Detect (https://github.com/varnish/varnish-devicedetect). Thanks Tim!!

= Version 1.8 =
* Updated the Kindle detection for a wider range of support

= Version 1.7 =
* Updated the plugin to provide backwards compatible support for WordPress < v3.4 (Pre the new Themes API)
* Added a 3rd theme selection option for older/non-compliant devices, so theme authors can also supply a text-only version to those devices if they like.
* Revised some language in the plugin readme file

= Version 1.6 =
* Updated the plugin to use the new Theme API within WordPress 3.4
* Updated MobileESP Library to the latest verion (April 23, 2012) which adds support for BlackBerry Curve Touch, e-Ink Kindle, and Kindle Fire in Silk mode. And fixed many other bugs.
* Updated the Device Theme Switcher widgets so they only display to the devices they should, e.g. The 'View Full Website' widget will only display in the handheld theme.
* Revised readme language and added a WordPress Plugin Repository banner graphic.

= Version 1.5 =
* Modified the way themes are deliveried so the process is more stable for users with odd WordPress setups, by detecting where their theme folders are located instead of assuming wp-content/themes

= Version 1.4 =
* Updated to the latest version of the MobileESP library which now detects some newer phones like the BlackBerry Bold Touch (9900 and 9930)

= Version 1.3 =
* Changed the admin page to submit to admin_url() for those who have changed /wp-admin/
* Added a warning suppresor to session_start() in case another plugin has already called it
* Updated language in the WordPress readme file

= Version 1.2 =
* Added the handheld and tablet theme choices to the WordPress Dashboard Right Now panel
* Update both GitHub and WordPress readme files to be better styled and versed
* Added two wigets for users to put in their themes
* Coding and efficiency improvments

= Version 1.1 =
* Bug fixes
* Efficency improvements

= Version 1.0 =
* First Public Stable Release

== Upgrade Notice ==

= 2.0 =
Major Improvements, New Features, and even prettier!

== Credits ==

This plugin is powered by the MobileESP PHP library created by Anthony Hand (http://blog.mobileesp.com/).

This plugin is based on the concepts provided by Jonas Vorwerk's (http://www.jonasvorwerk.com/) Mobile theme switcher plugin, and Jeremy Arntz's (http://www.jeremyarntz.com/) Mobile Theme Switcher plugin.

Copyright (C) 2015 James Mehorter