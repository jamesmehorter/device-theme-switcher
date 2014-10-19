<?php 
    /**
     * Load the plugin update routines
     *
     * The update class checks the currently installed version and runs
     * any necessary update routines. This only occurs once per version,
     * and ONLY in the admin.
     */
    class DTS_Update {
    class DTS_Update extends DTS_Singleton {
        
        /**
         * Update the plugin
         *
         * This routine checks if the current plugin version (stored in a WordPress option)
         * is equal to the hardcoded plugin version in class.core.php. If the plugin and DB
         * versions do not match, run version updates per current version.
         * 
         * If the versions do not match the DB version is updated to match
         */
        static function do_update () {   
            //fetch the current plugin version
            $current_version = DTS_Update::get_current_version();

            //determine if an update is required
            if ($current_version != DTS_VERSION) :

                //Conduct any needed update routines
                
                //Update pre version 2.0
                if ($current_version == 1) include('updates/2.0.php');
                
                //Update pre version 2.4
                if ($current_version < 2.6) include('updates/2.6.php');
                
                //Update the DB version to reflect the plugin files version
                update_option('dts_version', DTS_VERSION);
            endif;
        }//update

        /**
         * Determine the current plugin version
         *
         * This function grabs the version stored in a WordPress option, however,
         * pre version 2.0 we never stored the plugin version in an option, so if 
         * there is no stored version we specify it below as '1'
         * 
         * @return decimal plugin version ex. 2.4
         */
        static function get_current_version () {
            //check for the dts_version option (New in Version 2.0)
            $current_version = get_option('dts_version');
            
            //If there is no current version we'll just reference this as 'version 1'
            if (empty($current_version)) $current_version = 1;

            //return the currently installed plugin version
            return intval($current_version) ;
        }//determine_previous_version
    }//DTS_Update