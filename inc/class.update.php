<?php 
    class DTS_Update {
        // ------------------------------------------------------------------------
        // INITIALIZATION
        // ------------------------------------------------------------------------
        static function init () {
            DTS_Update::update(DTS_Update::version_comparison());
        }//init
        // ------------------------------------------------------------------------
        // VERSION DETECTION & COMPARISON
        // ------------------------------------------------------------------------
        static function version_comparison () {
            $operation = "";
            //check for the dts_version option (New in Version 2.0)
            $previous_version = get_option('dts_version');
            if ($previous_version) : //if the user is running version 2.0+ 
                //see if the installed version matches the version in this code
                if ($previous_version == DTS_VERSION) : 
                    //do nothing - the versions are identical, no update needed
                else :
                    //Update the DB version
                    update_option('dts_version', DTS_VERSION);
                    $operation = 'update';
                endif;
            else : 
                //there is no dts_version option in the db
                //The user is running pre version 2.0 (no version was added to the db on activation before 2.0 )
                //add the version to the database
                add_option('dts_version', DTS_VERSION);
                $operation = "update";
                $previous_version = "1.x";
            endif;

            return array('operation' => $operation, 'previous_version' => $previous_version);
        }//version_comparison
        // ------------------------------------------------------------------------
        // UPDATE ROUTINE
        // ------------------------------------------------------------------------
        static function update ($update) {
            switch ($update['operation']) : 
                case 'update' : 
                    if ($update['previous_version'] == "1.x") : 
                        //This is an update from verion 1.x to 2.0
                        //We need to update the dts theme values stored in the database
                        //previously just the slug was kept, now we're storing a url encoded string of 3 values
                        include('updates/2.0.php');
                    endif;
                    //We don't need to add any update routines for 2.0+ at this time
                    //Only if we make a change in 2.1+ will we need to run an update script
                break;
            endswitch;
        }//update
        // ------------------------------------------------------------------------
        // UPDATE NOTICES
        // ------------------------------------------------------------------------
        static function update_notice () {
            echo get_transient('dts_updated_notice');
            delete_transient('dts_updated_notice');
        }//update_notice
    }//DTS_Update