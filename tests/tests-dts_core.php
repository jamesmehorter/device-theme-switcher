<?php
	/**
	 * Tests against the DTS_Core class to verify it's legitimacy to
	 * the external world and QA all it's methods.
	 */
	class DTS_Core_Test_Cases extends WP_UnitTestCase {

		/**
		 * Test the does_need_install method
		 */
		function test_does_need_install () {
			$dts_core = DTS_Core::get_instance();

			// Reproduce conditions where dts has not been installed
			$installed_version = false;
			$this->assertTrue( $dts_core->does_need_install( $installed_version ) );

			// Reproduce any installed version
			// anything other than false
			$installed_version = 'anything';
			$this->assertFalse( $dts_core->does_need_install( $installed_version ) );
		} // function test_does_need_install

	} // class DTS_Core_Test_Cases


	// EOF