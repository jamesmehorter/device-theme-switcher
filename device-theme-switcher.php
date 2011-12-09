<?php
	/* 
		Plugin Name: Device Theme Switcher
		Plugin URI: http://www.picadesign.com.com
		Description: Plugin that allows you to set a separate theme for handheld and tablet devices
		Author: James Mehorter @ Pica Design
		Version: 0.1
		Author URI: http://www.jamesmehorter.com
	*/
	
	
	//$isiPad = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
	
	//Generate and output the plugin settings page
	include('views/admin-plugin-display.php');
?>