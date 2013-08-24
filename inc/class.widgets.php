<?php
	// ------------------------------------------------------------------------
	// WIDGETS
	// ------------------------------------------------------------------------
	//Called Via register_widget('DTS_View_Full_Website'); in dts_controller.php
	class DTS_View_Full_Website extends WP_Widget {
		//Widget constructor	
		function DTS_View_Full_Website() {
			$widget_ops = array('classname' => 'dts_view_full_website', 'description' => 'Add a link for mobile users' );
			$this->WP_Widget('dts_view_full_website', 'View Full Website', $widget_ops);
		}
		//Widget output
		function widget($args, $instance) {
			extract($args, EXTR_SKIP);
			$link_text = empty($instance['link_text']) ? ' ' : apply_filters('widget_link_text', $instance['link_text']);
			echo $before_widget;			
			link_to_full_website($link_text, $css_classes = array(), $echo = true);
			echo $after_widget;	
		}
		//Save widget options	
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['link_text'] = strip_tags($new_instance['link_text']);
			return $instance;
		}
		function form($instance) {
			//Output admin widget options form
			$instance 	= wp_parse_args( (array) $instance, array('link_text' => '') );
			$link_text	= $instance['link_text'];
			//set a default
			if ($link_text == "") :
				$link_text = __("View Full Website");
			endif;
			
			//Output our widget contents
			?>
				<p>
					<label for="<?php echo $this->get_field_id('link_text'); ?>">Link Text: <small><em>Ex: View Full Website</em></small>
						<input class="widefat" id="<?php echo $this->get_field_id('link_text'); ?>" name="<?php echo $this->get_field_name('link_text'); ?>" type="text" value="<?php echo esc_attr($link_text); ?>" />
					</label>
				</p>
			<?php
		}
	}
	
	//Called Via register_widget('DTS_Return_To_Mobile_Website');
	class DTS_Return_To_Mobile_Website extends WP_Widget {
		//Widget constructor	
		function DTS_Return_To_Mobile_Website() {
			$widget_ops = array('classname' => 'dts_return_to_mobile_website', 'description' => 'Add a link for mobile users to return to the mobile website' );
			$this->WP_Widget('dts_return_to_mobile_website', 'Return to Mobile Website', $widget_ops);
		}
		//Widget output
		function widget($args, $instance) {
			extract($args, EXTR_SKIP);
			$link_text = empty($instance['link_text']) ? ' ' : apply_filters('widget_link_text', $instance['link_text']);
			echo $before_widget;			
			link_back_to_device($link_text, $css_classes = array(), $echo = true);
			echo $after_widget;	
		}
		//Save widget options	
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['link_text'] = strip_tags($new_instance['link_text']);
			return $instance;
		}
		function form($instance) {
			//Output admin widget options form
			$instance 	= wp_parse_args( (array) $instance, array('link_text' => '') );
			$link_text	= $instance['link_text'];
			//set a default
			if ($link_text == "") :
				$link_text = __("Return to Mobile Website");
			endif;
			
			//Output our widget contents
			?>
				<p>
					<label for="<?php echo $this->get_field_id('link_text'); ?>">Link Text: <small><em>Ex: Return to Mobile Website</em></small>
						<input class="widefat" id="<?php echo $this->get_field_id('link_text'); ?>" name="<?php echo $this->get_field_name('link_text'); ?>" type="text" value="<?php echo esc_attr($link_text); ?>" />
					</label>
				</p>
			<?php
		}
	}
?>