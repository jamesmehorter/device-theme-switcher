<?php
    /*
        Backwards compatibility for Device Theme Switcher v1.x URL $_GET Variables
        This code only assists users who have hardcoded ?dts_device= links into thier themes
        -----
        Captures the following $_GET variables and ports them to the new code logic: 
        ?dts_device=screen
        previously used allow the handheld/tablet user to view the full website
        ?dts_device=device
        previously used to allow the handheld/tablet user return to their device's assigned theme       
    */
    if (isset($_GET['dts_device'])) : 
        switch ($_GET['dts_device']) : 
            //'active' now represents the 'full website'
            //'active' theme as in the active theme set by the admin within wp-admin/themes.php
            case 'screen' : $_GET['theme'] = 'active'; break;
            case 'device' : 
                //The user's device is stored in a session while they browse the 'active' theme
                //Let's grab that and use it to modify the get var
                //This is a feature in the new code we can benefit from to preserve this legacy link form
                @session_start(); //Lets not toss a warning if the session was already started during this code execution
                if (isset($_SESSION['device-theme-switcher']['device'])) : 
                    $_GET['theme'] = $_SESSION['device-theme-switcher']['device'];
                    unset($_SESSION['dts_device']);
                endif;
            break;
        endswitch;
        unset($_GET['dts_device']);
    endif; 
