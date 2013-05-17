<?php 
    class DTS_Update {
        static function init () {
            $operation = "";
            //check for the new dts_version option
            $previous_version = get_option('dts_version');
            if ($previous_version) : //if the user is running version 2.0+ 
                //echo "yes";
                //see if the installed version matches the version in this code
                //echo "Installed " . $previous_version . "<br />";
                //echo "Code Version" . DTS_VERSION;
                if ($previous_version == DTS_VERSION) : 
                    //do nothing - the versions are identical, no update needed
                else :
                    //Update the DB version
                    update_option('dts_version', DTS_VERSION);
                    $operation = 'update';
                endif;
            else : //if the user is running pre version 2.0 (before 2.0 no version was added during activation)
                //there is no dts_version option in the db
                //add the version to the database
                add_option('dts_version', DTS_VERSION);
                $operation = "update";
                $previous_version = "1.x";
            endif;

            switch ($operation) : 
                case 'update' : 
                    if ($previous_version == "1.x") : 
                        //This is an update from verion 1.x to 2.0
                        //We need to update the dts theme values stored in the database
                        //previously just the slug was kept, now we're storing a url encoded string of 3 values
                        include('updates/2.0.php');
                    endif;
                    //We don't need to add any update routines for 2.0+ at this time
                    //Only if we make a change in 2.1+ will we need to run an update script
                break;
            endswitch;
        }//init
    }//DTS_Update