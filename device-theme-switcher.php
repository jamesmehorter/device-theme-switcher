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
	
	//Include our external devicer switcher display file and class
	include('views/admin-plugin-display.php');
		//Now we can initilize our Device Switcher
		$dts = new device_theme_switcher() ;
?>