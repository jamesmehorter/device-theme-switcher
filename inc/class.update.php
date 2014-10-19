<?php 
    // Bail if this file is being accessed directly
    defined( 'ABSPATH' ) OR exit;
    
    /**
     * Load the plugin update routines
     *
     * The update class checks the currently installed version and runs
     * any necessary update routines. This only occurs once per version,
     * and ONLY in the admin.
     */
    class DTS_Update extends DTS_Singleton {
        
        /**
         * Update the plugin
         *
         * This routine checks if the current plugin version (stored in a WordPress option)
         * is equal to the hardcoded plugin version in class.core.php. If the plugin and DB
         * versions do not match, run version updates per current version.
         * 
         * If the versions do not match the DB version is updated to match
         *
         * @uses  update_option
         */
        public function do_update () {   

            // fetch the current plugin version
            $current_version = DTS_Core::factory()->get_current_version();

            // determine if an update is required
            // Conduct any needed update routines
            if ( version_compare( $current_version, DTS_VERSION, '<' ) ) {

                /*
                    some type of thingy that runs through all available updates in order?

                    array of available update routines, array( '2.0.0', '2.6.0' )

                    loop through and run any that are needed? instead of the conditional logic below?
                 */


                /**
                 * 2.0.0 Update Routine
                 */
                if ( version_compare( $current_version, '2.0.0', '<' ) ) {

                    // Include the 2.0.0 update routine, which will automatically run it's code
                    //include( 'updates/2.0.0.php' );
                    echo "updating to 2.0.0";
                }
                
                /**
                 * 2.6.0 Update Routine
                 */
                if ( version_compare( $current_version, '2.0.0', '>=' ) &&  
                     version_compare( $current_version, '2.6.0', '<' ) ) {

                    // Include the 2.0.0 update routine
                    //include( 'updates/2.6.0.php' );
                    echo "updating to 2.6.0";
                }
                
                // Update the DB version to reflect the newly installed ersion of the plugin
                //update_option( 'dts_version', DTS_VERSION );

            } // if update

        } // function do_update

    } // Class DTS_Update
    

    // EOF