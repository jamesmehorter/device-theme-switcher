<?php
	// Bail if this file is being accessed directly
	defined( 'ABSPATH' ) OR exit;

	/**
	 * Widget - View Full Website Link
	 *
	 * Option to specificy the link text, by default: 'View Full Website'
	 */
	class DTS_View_Full_Website extends WP_Widget {

		/**
		 * Construct our new widget
		 *
		 * @param   null
		 */
		function __construct() {

			// Build an instance of the new widget
			parent::__construct(
				'dts_view_full_website',
				__( 'View Full Website', 'device-theme-switcher' ),
				array( 'description' => __( 'Add a link for mobile users', 'device-theme-switcher' ), )
			);

		} // DTS_View_Full_Website

		/**
		 * Widget Output
		 *
		 * @param  array  $args     The widget arguments from the sidebar
		 * @param  object $instance The widget instance
		 * @return null
		 */
		function widget ( $args, $instance ) {

			if ( empty( $instance['link_text'] ) ) {
				$link_text =  ' ';
			} else {
				$link_text = apply_filters( 'dts_widget_to_full_website_link_text', $instance['link_text'] );
			}

			echo $args['before_widget'];

			DTS_Switcher::build_html_link( 'active', $link_text, array(), true );

			echo $args['after_widget'];

		} // function widget


		/**
		 * Save widget form data on update
		 *
		 * @param  array $new_instance The instance of the current widget data
		 * @param  array $old_instance The instance of the new widget data
		 * @return array               The new widget data
		 */
		function update ( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['link_text'] = strip_tags( $new_instance['link_text'] );
			return $instance;
		} // function update


		/**
		 * Display the widget edit form in the admin
		 *
		 * @param  $array $instance The widget data
		 * @return null
		 */
		function form ( $instance ) {

			//Output admin widget options form
			$instance 	= wp_parse_args( (array) $instance, array( 'link_text' => '' ) );
			$link_text	= $instance['link_text'];

			//set a default
			if ( $link_text == "" ) {
				$link_text = __( 'View Full Website', 'device-theme-switcher' );
			}

			//Output our widget contents ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id('link_text') ); ?>">
					<?php echo esc_html_e( 'Link Text:', 'device-theme-switcher' ); ?>
					<small><em><?php echo esc_html_e( 'Ex: View Full Website', 'device-theme-switcher' ); ?></em></small>
					<input
						class="widefat"
						id="<?php echo esc_attr( $this->get_field_id( 'link_text' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'link_text' ) ); ?>"
						type="text"
						value="<?php echo esc_attr( esc_attr( $link_text ) ); ?>" />

				</label>
			</p><?php
		} // function form

	} // class DTS_View_Full_Website


	/**
	 * Widget - Return to Device Link
	 *
	 * Option to specificy the link text, by default: 'Return to Mobile Website'
	 */
	class DTS_Return_To_Mobile_Website extends WP_Widget {

		/**
		 * Construct our new widget
		 *
		 * @param   null
		 */
		function __construct() {

			// Build an instance of the new widget
			parent::__construct(
				'dts_return_to_mobile_website',
				__( 'Return to Mobile Website', 'device-theme-switcher' ),
				array( 'description' => __( 'Add a link for mobile users to return to the mobile website', 'device-theme-switcher' ), )
			);

		} // function DTS_Return_To_Mobile_Website


		/**
		 * Widget Output
		 *
		 * @param  array  $args     The widget arguments from the sidebar
		 * @param  object $instance The widget instance
		 * @return null
		 */
		function widget( $args, $instance ) {

			if ( empty( $instance['link_text'] ) ) {
				$link_text =  ' ';
			} else {
				$link_text = apply_filters( 'dts_widget_to_device_website_link_text', $instance['link_text'] );
			}

			echo $args['before_widget'];

		    DTS_Switcher::build_html_link( 'device', $link_text, array(), true );

			echo $args['after_widget'];

		} // function widget


		/**
		 * Save widget form data on update
		 *
		 * @param  array $new_instance The instance of the current widget data
		 * @param  array $old_instance The instance of the new widget data
		 * @return array               The new widget data
		 */
		function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['link_text'] = strip_tags($new_instance['link_text']);
			return $instance;
		} // function update

		/**
		 * Display the widget edit form in the admin
		 *
		 * @param  $array $instance The widget data
		 * @return null
		 */
		function form( $instance ) {

			//Output admin widget options form
			$instance 	= wp_parse_args( (array) $instance, array( 'link_text' => '' ) );
			$link_text	= $instance['link_text'];

			//set a default
			if ( $link_text == "" ) {
				$link_text = __( 'Return to Mobile Website', 'device-theme-switcher' );
			}

			//Output our widget contents ?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_id('link_text') ); ?>">
					<?php echo esc_html_e( 'Link Text:', 'device-theme-switcher' ); ?>
					<small><em><?php echo esc_html_e( 'Ex: Return to Mobile Website', 'device-theme-switcher' ); ?></em></small>
					<input
						class="widefat"
						id="<?php echo esc_attr( $this->get_field_id( 'link_text' ) ); ?>"
						name="<?php echo esc_attr( $this->get_field_name( 'link_text' ) ); ?>"
						type="text"
						value="<?php echo esc_attr( esc_attr( $link_text ) ); ?>" />

				</label>
			</p><?php

		} // function form

	} // class DTS_Return_To_Mobile_Website


	// EOF