# Introducing Device Theme Switcher

Device Theme Switcher is a WordPress plugin that uses the [MobileESP PHP library created by Anthony Hand](http://code.google.com/p/mobileesp/)

## How do I use it?

Install the plugin from either the [WordPress Plugin Repository](http://wordpress.org/extend/plugins/device-theme-switcher/) or grab the latest bundled-zip here on github. Then set your handheld and tablet themes under Appearance > Device Themes. Computer users will be given the theme you specify in Appearance > Themes, as usual. However, now handheld users will see the handheld theme and tablet users will be given the tablet theme. Using WordPress child themes is supported.

## But I want a <em>'View Full Website'</em> link! 

This plugin creates two widgets for doing just that! Or you can use the template tags below. NOTE: If you plan to use the widgets OR a menu specifically for one theme (i.e. a menu that only shows in your handheld theme) you must register the sidebar and/or menu in both your handheld and computer themes. This is because when delivering a handheld theme, behind-the-scenes WordPress still thinks your computer theme is active, so the sidebar / menus must exist in both themes. 

## Uh, template functions? 

<strong>View Full Website</strong> 

    <?php if (class_exists('Device_Theme_Switcher')) : Device_Theme_Switcher::generate_link_to_full_website(); endif; ?>


<strong>Return to Mobile Website</strong>

    <?php if (class_exists('Device_Theme_Switcher')) : Device_Theme_Switcher::generate_link_back_to_mobile(); endif; ?>


## Notes
The users chosen theme is stored in a PHP Session, so the user can browse around your website prior to clicking 'Return to mobile website'. You can use the theme links anywhere in your themes, like in header.php or footer.php. 

The anchor tags that output both have a CSS class: 'dts-link'. The 'View Full Website' anchor tag also has a class of 'to-full-website' and the 'Return to the Mobile Website' link has an additional class of 'back-to-mobile'.

_Styling Example_

    .dts-link {
        font-size: 1.5em ;
    }
        .dts-link.to-full-website {
            color: red ;
        }
        .dts-link.back-to-mobile {
    	    color: blue ;
        }

## Changelog 

* _Version 1.8_
    * Updated the Kindle detection for a wider range of support

* _Version 1.7_
    * Updated the plugin to provide backwards compatible support for WordPress < v3.4 (Pre the new Themes API)
    * Added a 3rd theme selection option for older/non-compliant devices, so theme authors can also supply a text-only version to those devices if they like. 
    * Revised some language in the plugin readme file

* _Version 1.6_
    * Updated the plugin to use the new Theme API within WordPress 3.4
    * Updated MobileESP Library to the latest verion (April 23, 2012) which adds support for BlackBerry Curve Touch, e-Ink Kindle, and Kindle Fire in Silk mode. And fixed many other bugs. 
    * Updated the Device Theme Switcher widgets so they only display to the devices they should, e.g. The 'View Full Website' widget will only display in the handheld theme. 
    * Revised readme language and added a WordPress Plugin Repository banner graphic. 

* _Version 1.5_
    * Modified the way themes are deliveried so the process is more stable for users with odd WordPress setups, by detecting where their theme folders are located instead of assuming wp-content/themes

* _Version 1.4_
    * Updated to the latest version of the MobileESP library which now detects some newer phones like the BlackBerry Bold Touch (9900 and 9930)

* _Version 1.3_
    * Changed the admin page to submit to admin_url() for those who have changed /wp-admin/ 
    * Added a warning suppresor to session_start() in case another plugin has already called it
    * Updated language in the WordPress readme file

* _Version 1.2_
	* Added the handheld and tablet theme choices to the WordPress Dashboard Right Now panel
	* Update both GitHub and WordPress readme files to be better styled and versed
	* Added two wigets for users to put in their themes
	* Coding and efficiency improvements
* _Version 1.1_
	* Bug fixes
    * Efficiency improvements
* _Version 1.0_
	* First Public Stable Release

## Credits

This plugin is based on the [concepts provided by Jonas Vorwerk's Mobile theme switcher plugin](http://www.jonasvorwerk.com/), and [Jeremy Arntz's Mobile Theme Switcher plugin](http://www.jeremyarntz.com/).