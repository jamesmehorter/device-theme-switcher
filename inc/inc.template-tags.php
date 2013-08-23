<?php
    //Global fcuntions for use in themes as template tags
    //Globals the $dts variable created on load
    function link_to_full_website($link_text = "View Full Website", $css_classes = array(), $echo = true) {
        global $dts;
        return $dts->build_html_link('active', $link_text, $css_classes, $echo);
    }

    function link_back_to_device($link_text = "Return to Mobile Website", $css_classes = array(), $echo = true) {
        global $dts;
        return $dts->build_html_link('device', $link_text, $css_classes, $echo);
    }