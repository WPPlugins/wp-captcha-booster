<?php

/**
 * This file contains code for remove tables and options at uninstall.
 *
 * @author	Tech Banker
 * @package wp-captcha-booster/lib
 * @version 1.0.0
 */
if (!defined('WP_UNINSTALL_PLUGIN')) {
   die;
} else {
   if (!current_user_can("manage_options")) {
      return;
   } else {
      $captcha_booster_version_number = get_option("captcha_booster_version_number");
      if ($captcha_booster_version_number != "") {
         global $wp_version, $wpdb;
         $other_settings = $wpdb->get_var
             (
             $wpdb->prepare
                 (
                 "SELECT meta_value FROM " . $wpdb->prefix . "captcha_booster_meta
                             WHERE meta_key = %s ", "other_settings"
             )
         );
         $other_settings_data = unserialize($other_settings);

         if ($other_settings_data["remove_tables_at_uninstall"] == "enable") {


            $wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "captcha_booster");
            $wpdb->query("DROP TABLE IF EXISTS " . $wpdb->prefix . "captcha_booster_meta");

            delete_option("captcha_booster_version_number");
            delete_option("captcha-booster-wizard-set-up");
            delete_option("cpbo_tech_banker_site_id");
            delete_option("captcha_option");
         }
      }
   }
}
