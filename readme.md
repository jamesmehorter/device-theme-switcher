# Device Theme Switcher

[Installation](https://github.com/jamesmehorter/device-theme-switcher/tree/2.0#installation) | [Features](https://github.com/jamesmehorter/device-theme-switcher/tree/2.0#features) | [Screenshots](https://github.com/jamesmehorter/device-theme-switcher/tree/2.0#screenshots) | [FAQ](https://github.com/jamesmehorter/device-theme-switcher/tree/2.0#faq) | [Changelog](https://github.com/jamesmehorter/device-theme-switcher/tree/2.0#changelog) | [Credits](https://github.com/jamesmehorter/device-theme-switcher/tree/2.0#credits)

WordPress plugin that let's you set one theme for all handheld devices and another theme for all tablet devices. Normal computer visitors are given the active theme set in 'Appearance > Themes'.

'Handheld' devices include Android, BlackBerry, iPod, iPhone, Windows Mobile, and other various 'hand held' smart phones. 'Tablet' devices include iPad, Android tablets, Kindle Fire and other large-screen hand helds. 'Low Support' devices include those which have poor CSS & Javascript rendering capabilities-often these are older devices.

## Installation
Install the plugin from the [WordPress Plugin Repository](http://wordpress.org/extend/plugins/device-theme-switcher/) Then set your handheld and tablet themes under Appearance > Device Themes. Computer users will be given the theme you specify in Appearance > Themes, as usual. However, now handheld users will see the handheld theme and tablet users will be given the tablet theme. Using WordPress child themes is supported.

## Features

#### URL Switching - __NEW__ *in Version 2.0!*

Your device themes can be easily accessed to 'test' and see what other devices see.

    www.mywebsite.com/?theme=handheld
    www.mywebsite.com/?theme=tablet
    www.mywebsite.com/?theme=low_support
    www.mywebsite.com/?theme=active

#### Template Tags - __NEW__ *in Version 2.0!*
    <?php
        //View Full Website
        link_to_full_website($link_text = "View Full Website", $css_classes = array(), $echo = true);

        //Return to Mobile Website
        link_back_to_device($link_text = "Return to Mobile Website", $css_classes = array(), $echo = true);

The anchor tags that output both have a CSS class: 'dts-link'. The 'View Full Website' anchor tag also has a class of 'to-full-website' and the 'Return to the Mobile Website' link has an additional class of 'back-to-mobile'. This CSS can be used anywhere in your theme or style.css file.

##### Link Styling Example

    <style type="text/css">
        .dts-link {
            font-size: 1.5em ;
        }
            .dts-link.to-full-website {
                color: red ;
            }
            .dts-link.back-to-mobile {
                color: blue ;
            }
    </style>

#### DTS Class - __NEW__ *in Version 2.0!*

The DTS Class contains all the current device theme switcher settings and the current user device. You can access the DTS Class anywhere in themes. This could be helpful if for instance, you want one theme to power all devices and are willing to write your own code logic with conditionals and such. 

    <?php 
        //Access the device theme switcher object anywhere in your theme or plugin
        global $dts
        
        //See what's in there..
        print_r($dts) ;

        DTS_Switcher Object
        (
            [handheld_theme] => Array
                (
                    [name] => Responsive
                    [template] => responsive
                    [stylesheet] => responsive
                )
            [tablet_theme] => Array
                (
                    [name] => Twenty Twelve
                    [template] => twentytwelve
                    [stylesheet] => twentytwelve
                )
            [low_support_theme] => Array
                (
                    [name] => WordPress Default
                    [template] => default
                    [stylesheet] => default
                )
            [active_theme] => Array
                (
                    [name] => Responsive
                    [template] => responsive
                    [stylesheet] => responsive
                )
            [device] => active (Possible values: active, handheld, tablet, and low_support)
            [theme_override] => tablet (I was given the active theme [I'm on a computer] and I used a url parameter [site.com?theme=tablet] to view the tablet theme)
        )

        //use it..
        if ($dts->device == 'tablet') do_something() ;

## Screenshots

![View of the Device Theme Switcher settings](https://raw.github.com/jamesmehorter/device-theme-switcher/2.0/assets/screenshot-1-large.jpg "View of the Device Theme Switcher settings")
![View of the Device Theme Switcher advanced settings & help items.](https://raw.github.com/jamesmehorter/device-theme-switcher/2.0/assets/screenshot-2-large.jpg "View of the Device Theme Switcher advanced settings & help items")
![View of the two Device Theme Switcher Widgets and their settings](https://raw.github.com/jamesmehorter/device-theme-switcher/2.0/assets/screenshot-3-large.jpg "View of the two Device Theme Switcher Widgets and their settings")

## FAQ

#### How do Menus and Widgets work?!
Keep in mind, DTS simply changes which theme is delivered to the user-but WordPress still thinks the active theme is 'active' the whole time. This means you need to have your `register_nav_menu()` or `register_widget()` functions in all your themes! See below for details and examples.

#### How do I show the same menu in each theme?
Simply place you `register_nav_menu('my-menu-location', 'My Menu Location Name');` function in both of your primary/active and handheld/tablet theme functions.php files. Then, while your primary theme is 'active' go into Appearance > Menus-create your menu and assign it to the menu location-and populate it with some menu items. That's it!

#### How do I show one menu in my active theme and a different menu in my handheld/tablet theme?
Register a menu location for each theme, and place your `register_nav_menus()` code in each theme. E.g.:

###### In each theme's functions.php file:

    register_nav_menus(array(
        'active-menu-location' => 'Active Theme Menu Location',
        'handheld-menu-location' => 'Handheld Theme Menu Location',
        'tablet-menu-location' => 'Tablet Theme Menu Location',
    ));

Then, while your primary theme is 'active' go into Appearance > Menus-create your 3 menus-assign each one to their designated menu location-and populate each with some menu items. 

###### Lastly we just need to display each menu in each theme:

Active theme header.php:
    
    wp_nav_menu(array('theme_location' => 'active-menu-location'));

Handheld theme header.php
    
    wp_nav_menu(array('theme_location' => 'handheld-menu-location'));

Tablet theme header.php
    
    wp_nav_menu(array('theme_location' => 'tablet-menu-location'));

That's it-the important part is that you register each location in each theme!

#### How can I display the same sidebar in each theme?
Place the same `register_sidebar()` function in each theme's functions.php file, and add your widgets to your sidebar while the primary theme is 'active'.

#### How can I display a different sidebar in each theme?
Place the same 3 `register_sidebar()` functions in each theme's functions.php file, and add your widgets to each sidebar while the primary theme is 'active'. E.g:

###### In each theme's functions.php file:

    register_sidebar(array('name' => 'Active Theme Sidebar'));
    register_sidebar(array('name' => 'Handheld Theme Sidebar'));
    register_sidebar(array('name' => 'Tablet Theme Sidebar'));

###### Then, while your primary theme is 'active' go into Appearance > Widgets-and assign some widgets to each sidebar. Lastly we just need to display each sidebar in each theme:

Active theme page.php:
    
    dynamic_sidebar('Active Theme Sidebar');

Handheld theme page.php
    
    dynamic_sidebar('Handheld Theme Sidebar');

Tablet theme page.php
    
    dynamic_sidebar('Tablet Theme Sidebar');

## Changelog 

* _Version 2.0 - Released 08/24/2013_
    * Complete code rewrites to improve overall performance, redundancy, and improve extensibility. 
    * NEW - DTS Class access for use in themes; obtain info on the current user's device and saved dts settings. Examples in the FAQ.
    * NEW - URL Switching - Easily check what other devices see. Examples in the FAQ.
    * NEW - Session Timeout, so users who visit the 'Desktop' theme are bumped back to their device theme after 15 minutes
    * NEW - Optional Settings section in the WordPress admin in Appearance > Device Themes Allows users to override the session timeout. Also moved the low-support theme setting to this section. This new section also a
    * NEW - Help & Support section in the WordPress admin in Appearance > Device Themes
    * Included the latest version of MobileESP ~ Thanks Anthony!!

* _Version 1.9_
    * Note: DTS Version 1.9 was not released to the public
    * NEW - Made the Admin UI more presentable and WordPressy
    * FIX - Included a pull request from Tim Broder (https://github.com/broderboy) which adds support for Varnish Device Detect (https://github.com/varnish/varnish-devicedetect). Thanks Tim!!

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

This plugin is powered by the [MobileESP PHP library created by Anthony Hand](http://blog.mobileesp.com/). 

This plugin is based on the [concepts provided by Jonas Vorwerk's Mobile theme switcher plugin](http://www.jonasvorwerk.com/) , and [Jeremy Arntz's Mobile Theme Switcher plugin](http://www.jeremyarntz.com/).

Copyright (C) 2013 James Mehorter