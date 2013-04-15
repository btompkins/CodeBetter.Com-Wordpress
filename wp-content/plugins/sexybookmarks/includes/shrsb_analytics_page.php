<?php

/*
 * @desc Analytics Settings
 */

$shrsb_analytics = shrsb_analytics_set_options();

/*
 * @desc Set the analytics settings either from database or default
 */
function shrsb_analytics_set_options( $action = NULL ) {
    
    $option_name = 'ShareaholicAnalytics';
    
    $shrsb_analytics_default = array(
        'pubGaSocial'  => 0
        , 'pubGaKey' => ''
    );
    
    //Return default settings 
    if( $action == "reset" ) {
        delete_option($option_name);
        add_option($option_name,$shrsb_analytics_default);
        return $shrsb_analytics_default;
    }

    //Get the settings from the database
    $database_Settings =  get_option($option_name);

    if( $database_Settings ) {//got the settings in the database

        // Check only when upgrading
        if( SHRSB_UPGRADING ) {
            $need_to_update = false;

            //Check whether all the settings are present or not
            foreach( $shrsb_analytics_default as $k => $v ){
                if( !array_key_exists( $k, $database_Settings )) {
                    $database_Settings[$k] = $v;
                    $need_to_update = true;
                }
            }
            if( $need_to_update ) update_option( $option_name, $database_Settings );
        }
        return $database_Settings;

    } else {
        //Add the settings to the database
        add_option( $option_name, $shrsb_analytics_default );
        return $shrsb_analytics_default;
    }
}
?>