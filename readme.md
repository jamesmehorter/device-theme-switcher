![Screenshot](https://raw.github.com/jamesmehorter/device-theme-switcher/master/banner-772x250.jpg)

# Introducing Device Theme Switcher

Device Theme Switcher is a WordPress plugin that uses the [MobileESP PHP library created by Anthony Hand](http://code.google.com/p/mobileesp/). Device Theme Switcher creates a new page in your WordPress Admin; 'Appearance > Device Themes', where you can set one theme for handheld devices, another theme for tablet devices, and yet another theme for low support devices. Normal computer visitors are given the active theme set in 'Appearance > Themes'. WordPress child themes are supported. 

'Handheld' devices include Android, BlackBerry, iPod, iPhone, Windows Mobile, and other various 'hand held' smart phones. 'Tablet' devices include iPad, Android tablets, Kindle Fire and other large-screen hand helds. 'Low Support' devices include those which have poor CSS & Javascript rendering capabilities. Often these is older devices.

## How do I use it?

Install the plugin from either the [WordPress Plugin Repository](http://wordpress.org/extend/plugins/device-theme-switcher/) or grab the latest bundled-zip here on github. Then set your handheld and tablet themes under Appearance > Device Themes. Computer users will be given the theme you specify in Appearance > Themes, as usual. However, now handheld users will see the handheld theme and tablet users will be given the tablet theme. Using WordPress child themes is supported.

#### How do Menus and Widgets work?!

In order to use different widgets or menus in different themes you must register the menu location and/or sidebar in *both your handheld and computer themes*. Then you must populate the different menus/widgets while the default/computer theme is active, and simply only output the sidebar/menu you want in each theme's files. That's it! You should only set your handheld theme as the active theme if you're developing/debugging and want to view the handheld theme on a computer.


The following can be used in your themes..

##### View Full Website

    <?php if (class_exists('DTS')) DTS::generate_link_to_full_website() ?>


##### Return to Mobile Website

    <?php if (class_exists('DTS')) DTS::generate_link_back_to_mobile() ?>

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

##### DTS CLass

The DTS Class contains all the current device theme switcher settings and the current user device. You can access the DTS Class anywhere in themes. This could be helpful if for instance, you want one theme to power all devices and are willing to write your own code logic with conditionals and such. 

    <?php 
        //Create a new instance of 
        $dts = new DTS ;
        
        //See what's in there..
        print_r($dts) ;

        /*
        DTS Object
        (
            [device] => computer (Possible values: computer, tablet, handheld, and low_support)
            [handheld_theme] => Array
                (
                    [name] => WordPress Classic
                    [template] => classic
                    [stylesheet] => classic
                )

            [tablet_theme] => Array
                (
                    [name] => WordPress Default
                    [template] => default
                    [stylesheet] => default
                )

            [low_support_theme] => Array
                (
                    [name] => WordPress Default
                    [template] => default
                    [stylesheet] => default
                )

            [default_template] => classic
            [default_stylesheet] => classic
            [device_theme] => Array
                (
                )

        )
        */

        //use it..
        if ($dts->device == 'tablet') do_something() ;
    ?>

### Changelog 

* _Version 1.9_
    * NEW - Made the Admin UI more presentable and WordPressy
    * NEW - DTS Class access for use in themes; obtain info on the current user's device and saved dts settings.
    * FIX - Numerous code rewrites to improve overall performance, redundancy, and improve extensibility. 
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

This plugin is based on the [concepts provided by Jonas Vorwerk's Mobile theme switcher plugin](http://www.jonasvorwerk.com/), and [Jeremy Arntz's Mobile Theme Switcher plugin](http://www.jeremyarntz.com/).