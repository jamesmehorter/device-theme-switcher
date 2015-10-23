<?php
	// DTS_VERSION constant for use anywhere in WordPress
	if ( ! defined ( 'DTS_VERSION' ) ) define( 'DTS_VERSION', '2.9.2' );

	/**
	 * Tests against the DTS_Core class to verify it's legitimacy to
	 * the external world and QA all it's methods.
	 */
	class DTS_Core_Test_Cases extends WP_UnitTestCase {

		/**
		 * Reproduce conditions to test the following
		 * DTS_Core class methods:
		 *
		 * do_activation
		 * does_need_install
		 * install
		 * uninstall
		 * hook_into_wordpress
		 * build_cookie_name
		 * get_installed_version
		 * does_need_update
		 * update
		 */

		/**
		 * Test the do_activation method's install functionality
		 *
		 * Activation should install when no install is present
		 *
		 * @return void
		 */
		function test_install () {
			// Make WP believe we're in site.com/wp-admin/
			if ( ! defined( 'WP_ADMIN' ) ) define( 'WP_ADMIN', true );

			// Run the plugin activation routine
			// The do_activation method is typically called statically via register_activation_hook
			// so we'll also call it statically
			// sending true argument to force a new instance of our singleton class to be created
			DTS_Core::do_activation( true );

			// Test that the 'dts_version' option was installed accurately
			$current_plugin_version = DTS_VERSION ;
			$installed_version = get_option( 'dts_version' );
			$this->assertEquals( $current_plugin_version, $installed_version );

			// Test that the plugin cookie name was installed accurately
			$dts_cookie_name = get_option( 'dts_cookie_name' );
			$this->assertEquals( DTS_Core::build_cookie_name( get_bloginfo( 'sitename' ) ), $dts_cookie_name );

			// Test that the plugin cookie lifetime was install accurately
			$dts_lifespan = get_option( 'dts_cookie_lifespan' );
			$this->assertEquals( 0, $dts_lifespan );

		} // function test_activate


		/**
		 * Test the self::do_activation() method's update functionality
		 *
		 * Activation should update when an update is available
		 *
		 * @return void
		 */
		function test_activation_update () {

			// Make WP believe we're in site.com/wp-admin/
			if ( ! defined( 'WP_ADMIN' ) ) define( 'WP_ADMIN', true );

			// Test update via activation

			// test by setting $installed_version to an old version number
			// ..running the update routine, and then testing for the new version
			$installed_version = '2.0';

			// Add the version to the database
            update_option( 'dts_version', $installed_version );

			// Run the plugin activation routine
			// The self::do_activation() method is typically called statically via register_activation_hook
			// so we'll also call it statically
			// sending true argument to force a new instance of our singleton class to be created
			DTS_Core::do_activation( true );

			// Fetch the plugin version to verify that it was updated
			$installed_version = get_option( 'dts_version' );
			$this->assertEquals( DTS_VERSION, $installed_version );

		} // function test_activate


		/**
		 * Test the does_need_update routine
		 *
		 * Should assert true if we do need an update
		 * Should assert return false if we do not need an update
		 */
		function test_does_need_update () {

			$dts_core = DTS_Core::get_instance( true );

			// Should assert true if we do need an update
			// Manually set the version lower then test our function
			update_option( 'dts_version', '2.0.0' );
            $dts_core->installed_version = $dts_core->get_installed_version();

			$this->assertTrue( $dts_core->does_need_update() );

			// Should assert return false if we do not need an update
			// Manually set the version equal to the current then test our function
			update_option( 'dts_version', DTS_VERSION );
			$dts_core->installed_version = $dts_core->get_installed_version();

			$this->assertFalse( $dts_core->does_need_update() );

		} // function test_does_need_update


		/**
		 * Test typical runtime execution update, which should occur
		 * whenever an admin loads an admin page
		 *
		 * test by setting $installed_version to an old version number
		 * ..running the update routine, and then testing for the new version
		 *
		 * @todo test for all incremental updates
		 * @todo For updates with an update routine test routine success/failure
		 *
		 * @return void
		 */
		function test_do_update () {

			// Make WP believe we're in site.com/wp-admin/
			if ( ! defined( 'WP_ADMIN' ) ) define( 'WP_ADMIN', true );

			// Add the version to the database
            update_option( 'dts_version', '2.0' );

			// Test update via typical runtime
			// sending true argument to force a new instance of our singleton class to be created
			$dts_core = DTS_Core::get_instance( true );

			// Fetch the plugin version to verify that it was updated
			$this->assertEquals( DTS_VERSION, get_option( 'dts_version' ) );

		} // function test_runtime_update

		/**
		 * Test the uninstall method
		 */
		function test_do_uninstall () {

			$dts_core = DTS_Core::get_instance( true );

			// Add all the site options we set
			// Test that they are properly removed
			add_option( 'dts_version', 1 );
			add_option( 'dts_cookie_name', 1 );
			add_option( 'dts_cookie_lifespan', 1 );
			add_option( 'dts_handheld_theme', 1 );
			add_option( 'dts_tablet_theme', 1 );
			add_option( 'dts_low_support_theme', 1 );
			add_option( 'widget_dts_view_full_website', 1 );
			add_option( 'widget_dts_return_to_mobile_website', 1 );

			$dts_core->do_uninstall();

			$this->assertFalse( get_option( 'dts_version' ) );
			$this->assertFalse( get_option( 'dts_cookie_name' ) );
			$this->assertFalse( get_option( 'dts_cookie_lifespan' ) );
			$this->assertFalse( get_option( 'dts_handheld_theme' ) );
			$this->assertFalse( get_option( 'dts_tablet_theme' ) );
			$this->assertFalse( get_option( 'dts_low_support_theme' ) );
			$this->assertFalse( get_option( 'widget_dts_view_full_website' ) );
			$this->assertFalse( get_option( 'widget_dts_return_to_mobile_website' ) );

		} // function test_uninstall


		/**
		 * @todo Test the hook_into_wordpress method
		 */
		function test_hook_into_wordpress () {

		} // function test_hook_into_wordpress


		/**
		 * Test the build_cookie_name method
		 */
		function test_build_cookie_name () {

			$dts_core = DTS_Core::get_instance( true );

			// Pass build_cookie_name a fake site name, ex: "James' Website"
			$cookie_name = $dts_core->build_cookie_name( "James' Website" );

			// Returned cookie name should contain three items when exploded on '-'
			//  the first item should equal the 'slugized' fake site name, ex: "jameswebsite"
			//  the last two should be 'alternate' and 'theme'
			//  e.g. 'jameswebsite-alternate-theme'
			$this->assertEquals( $cookie_name, 'jameswebsite-alternate-theme' );

		} // function test_build_cookie_name


		/**
		 * Test the get_installed_version method
		 */
		function test_get_installed_version () {

			// if there is no version in the DB false should be returned
			$this->assertFalse( DTS_Core::get_installed_version() );

			// Tf there is a version in the DB that version should be returned
			update_option( 'dts_version', '1.2.3' );

			$this->assertEquals( '1.2.3', DTS_Core::get_installed_version() );

		} // function test_get_installed_version


	} // class DTS_Core_Test_Cases


	// EOF