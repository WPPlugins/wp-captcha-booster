<?php

/**
 * This file is used for unscheduling schedulers.
 *
 * @author Tech Banker
 * @package wp-captcha-booster/lib
 * @version 1.0.0
 */
if (!defined("ABSPATH")) {
   exit;
} //exit if accessed directly
if (defined("DOING_CRON") && DOING_CRON) {
   if (wp_verify_nonce($nonce_unblock_script, "unblock_script")) {
      if (strstr(scheduler_name, "ip_address_unblocker_")) {
         $meta_id = explode("ip_address_unblocker_", scheduler_name);
      } else {
         $meta_id = explode("ip_range_unblocker_", scheduler_name);
      }

      $where_parent = array();
      $where = array();
      $where_parent["id"] = $meta_id[1];
      $where["meta_id"] = $meta_id[1];

      $type = $wpdb->get_var
          (
          $wpdb->prepare
              (
              "SELECT type FROM " . captcha_booster() . " WHERE id=%d", $meta_id[1]
          )
      );

      if (esc_attr($type) != "") {
         $manage_ip = $wpdb->get_var
             (
             $wpdb->prepare
                 (
                 "SELECT meta_value FROM " . captcha_booster_meta() . " WHERE meta_id=%d AND meta_key=%s", $meta_id[1], $type
             )
         );

         $ip_address_data_array = unserialize($manage_ip);

         $wpdb->delete(captcha_booster(), $where_parent);
         $wpdb->delete(captcha_booster_meta(), $where);
      }
      wp_unschedule_captcha_booster(scheduler_name);
   }
}