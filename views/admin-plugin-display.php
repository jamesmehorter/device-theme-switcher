<?php
	/**
		Device Theme Switcher Controller Class
	*/
	class device_theme_switcher {
		//Construct our class
		public function __construct () {
			add_action('admin_menu', array($this, 'create_menu'));
			add_action('admin_init', array($this, 'register_settings'));
		}
		
			//Create the admin menu page
			public function create_menu () {
				add_options_page( __('Device Theme Switcher'), __('Device Themes'), 'administrator', 'device-theme-switcher', array($this, 'generate_settings_page'));
				
			}
			
			//Register our plugin settings in the wp-options table
			public function register_settings() {
				register_setting('dts_settings', 'device_theme');
			}
			
			//Generate and output the plugin settings page
			public function generate_settings_page() {
				//Gather data that we'll use on the plugin settings page
				$this->themeList = get_themes();
				$this->themeList = array_keys($this->themeList); 
				natcasesort($this->themeList);
				$device_theme = get_option('device_theme');
				?>
                <!-- This is the html page that displays on the plugin settings page -->
                <div id="dts_settings_page_wrapper">
                	<h3>Select your device-specific themes below:</h3>
                    <form method="post" action="options.php">
                    	<?php settings_fields('dts_settings'); ?>
                    	<table>
                        	<tr>
                            	<td>
			                    	<label for="device_theme[handheld]"><?php _e("Handheld Theme:") ?></label>
                                </td><td>
                                    <select name="device_theme[handheld]">
                                        <?php foreach ($this->themeList as $key => $theme) : ?>
                                            <option name="<?php echo $theme ?>" <?php selected($theme, $device_theme['handheld']) ?>><?php echo $theme ?></option>
                                        <?php endforeach ?>
                                    </select>
								</td>						
                        	</tr><tr>
                            	<td>
			                       	<label for="device_theme[tablet]"><?php _e("Tablet Theme:") ?></label>
                               	</td><td>
                                    <select name="device_theme[tablet]">
                                        <?php foreach ($this->themeList as $key => $theme) : ?>
                                            <option name="<?php echo $theme ?>" <?php selected($theme, $device_theme['tablet']) ?>><?php echo $theme ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </td>
                            </tr>
                                <td colspan="2" align="right">
	                                <br /><br />
                                    <input type="submit" value="<?php _e("Save Device Themes") ?>" />
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
				<?php 
			} //End Plugin Settings Page Output 
	} //End device_theme_switcher class
?>