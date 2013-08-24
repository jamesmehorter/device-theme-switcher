<?php
    //Global functions for use in themes as template tags
    function link_to_full_website($link_text = "View Full Website", $css_classes = array(), $echo = true) {
        //Globals the $dts variable created on load
        //This variable is created in /dts-controller.php around line 70
        global $dts;
        return $dts->build_html_link('active', $link_text, $css_classes, $echo);
    }

    function link_back_to_device($link_text = "Return to Mobile Website", $css_classes = array(), $echo = true) {
        //Globals the $dts variable created on load
        //This variable is created in /dts-controller.php around line 70
        global $dts;
        return $dts->build_html_link('device', $link_text, $css_classes, $echo);
    }