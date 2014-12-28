<?php
	/**
	 * Tests against the DTS_Core class to verify it's legitimacy to
	 * the external world and QA all it's methods.
	 */
	class DTS_Core_Test_Cases extends WP_UnitTestCase {

		/**
		 * Reproduce conditions to test the following
		 * DTS_Core class methods:
		 *
		 * activate
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
		 * Test the activate method
		 */
		function test_activate () {

		} // function test_activate


		/**
		 * Test the does_need_install method
		 */
		function test_does_need_install () {

			// Fetch an instance of the DTS_Core class
			$dts_core = DTS_Core::get_instance();

			// Reproduce conditions where dts has not been installed
			$installed_version = false;
			$this->assertTrue( $dts_core->does_need_install( $installed_version ) );

			// Reproduce any installed version
			// anything other than false
			$installed_version = 'anything';
			$this->assertFalse( $dts_core->does_need_install( $installed_version ) );

		} // function test_does_need_install


		/**
		 * Test the install method
		 */
		function test_install () {

		} // function test_install


		/**
		 * Test the uninstall method
		 */
		function test_uninstall () {

		} // function test_uninstall


		/**
		 * Test the hook_into_wordpress method
		 */
		function test_hook_into_wordpress () {

		} // function test_hook_into_wordpress


		/**
		 * Test the build_cookie_name method
		 */
		function test_build_cookie_name () {

		} // function test_build_cookie_name


		/**
		 * Test the get_installed_version method
		 */
		function test_get_installed_version () {

		} // function test_get_installed_version


		/**
		 * Test the does_need_update method
		 */
		function test_does_need_update () {

		} // function test_does_need_update


		/**
		 * Test the update method
		 */
		function test_update () {

		} // function test_update

	} // class DTS_Core_Test_Cases


	// EOF