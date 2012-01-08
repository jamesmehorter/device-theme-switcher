# Introducing Device Theme Switcher

Device Theme Switcher is a WordPress plugin that uses the [MobileESP PHP library created by Anthony Hand](http://code.google.com/p/mobileesp/)

## How do I use it?

Install the plugin from either the [WordPress Plugin Repository](http://wordpress.org/extend/plugins/device-theme-switcher/) or grab the latest bundled-zip here on github. Then set your handheld and tablet themes under Appearance > Device Themes. Computer users will be given the theme you specify in Appearance > Themes, as usual. However, now handheld users will see the handheld theme and tablet users will be given the tablet theme. Using WordPress child themes is supported.

## But I want a <em>'View Full Website'</em> link! 

There are 2 widgets created by this plugin for just that! Check under Appearance > Widgets, and make sure you have a sidebar registered. 

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