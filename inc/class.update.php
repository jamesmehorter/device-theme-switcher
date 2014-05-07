<?php 
    /**
     * Load the plugin update routines
     *
     * The update class checks the currently installed version and runs
     * any necessary update routines. This only occurs once per version,
     * and ONLY in the admin.
     */
    class DTS_Update {
        
        static function do_update () {   
            //fetch the current plugin version
            $current_version = DTS_Update::get_current_version();

            //determine if an update is required
            if ($current_version != DTS_VERSION) :

                //Conduct any needed update routines
                
                //Update pre version 2.0
                if ($current_version == 1) include('updates/2.0.php');
                
                //Update pre version 2.4
                if ($current_version < 2.4) include('updates/2.4.php');
                
                //Update the DB version to reflect the plugin files version
                update_option('dts_version', DTS_VERSION);
            endif;
        }//update

        static function get_current_version () {
            //check for the dts_version option (New in Version 2.0)
            $current_version = get_option('dts_version');
            
            //If there is no current version we'll just reference this as 'version 1'
            if (empty($current_version)) $current_version = 1;

            //return the currently installed plugin version
            return $current_version ;
        }//determine_previous_version
    }//DTS_Update