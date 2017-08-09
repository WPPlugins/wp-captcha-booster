<?php

/**
 * This file is used for managing data in database.
 *
 * @author  Tech Banker
 * @package wp-captcha-booster/lib
 * @version 1.0.0
 */
if (!defined("ABSPATH")) {
   exit;
} //exit if accessed directly
if (!is_user_logged_in()) {
   return;
} else {
   $access_granted = false;
   foreach ($user_role_permission as $permission) {
      if (current_user_can($permission)) {
         $access_granted = true;
         break;
      }
   }
   if (!$access_granted) {
      return;
   } else {

      function get_captcha_booster_unserialize_data($manage_data) {
         $unserialize_complete_data = array();
         foreach ($manage_data as $value) {
            $unserialize_data = unserialize($value->meta_value);

            $unserialize_data["meta_id"] = $value->meta_id;
            array_push($unserialize_complete_data, $unserialize_data);
         }
         return $unserialize_complete_data;
      }

      if (isset($_REQUEST["param"])) {
         $obj_dbHelper_captcha_booster = new dbHelper_captcha_booster();
         switch (esc_attr($_REQUEST["param"])) {
            case "wizard_captcha_booster":
               if (wp_verify_nonce(isset($_REQUEST["_wp_nonce"]) ? esc_attr($_REQUEST["_wp_nonce"]) : "", "captcha_booster_check_status")) {
                  $type = isset($_REQUEST["type"]) ? esc_attr($_REQUEST["type"]) : "";
                  update_option("captcha-booster-wizard-set-up", $type);
                  if ($type == "opt_in") {
                     $plugin_info_captcha_booster = new plugin_info_captcha_booster();
                     global $wp_version;
                     $theme_details = array();
                     if ($wp_version >= 3.4) {
                        $active_theme = wp_get_theme();
                        $theme_details["theme_name"] = strip_tags($active_theme->Name);
                        $theme_details["theme_version"] = strip_tags($active_theme->Version);
                        $theme_details["author_url"] = strip_tags($active_theme->{"Author URI"});
                     }
                     $plugin_stat_data = array();
                     $plugin_stat_data["plugin_slug"] = "wp-captcha-booster";
                     $plugin_stat_data["type"] = "standard_edition";
                     $plugin_stat_data["version_number"] = captcha_booster_version_number;
                     $plugin_stat_data["status"] = $type;
                     $plugin_stat_data["event"] = "activate";
                     $plugin_stat_data["domain_url"] = site_url();
                     $plugin_stat_data["wp_language"] = defined("WPLANG") && WPLANG ? WPLANG : get_locale();
                     $plugin_stat_data["email"] = get_option("admin_email");
                     $plugin_stat_data["wp_version"] = $wp_version;
                     $plugin_stat_data["php_version"] = esc_html(phpversion());
                     $plugin_stat_data["mysql_version"] = $wpdb->db_version();
                     $plugin_stat_data["max_input_vars"] = ini_get("max_input_vars");
                     $plugin_stat_data["operating_system"] = PHP_OS . "  (" . PHP_INT_SIZE * 8 . ") BIT";
                     $plugin_stat_data["php_memory_limit"] = ini_get("memory_limit") ? ini_get("memory_limit") : "N/A";
                     $plugin_stat_data["extensions"] = get_loaded_extensions();
                     $plugin_stat_data["plugins"] = $plugin_info_captcha_booster->get_plugin_info_captcha_booster();
                     $plugin_stat_data["themes"] = $theme_details;
                     $url = tech_banker_stats_url . "/wp-admin/admin-ajax.php";
                     $response = wp_safe_remote_post($url, array
                         (
                         "method" => "POST",
                         "timeout" => 45,
                         "redirection" => 5,
                         "httpversion" => "1.0",
                         "blocking" => true,
                         "headers" => array(),
                         "body" => array("data" => serialize($plugin_stat_data), "site_id" => get_option("cpbo_tech_banker_site_id") != "" ? get_option("cpbo_tech_banker_site_id") : "", "action" => "plugin_analysis_data")
                     ));
                     if (!is_wp_error($response)) {
                        $response["body"] != "" ? update_option("cpbo_tech_banker_site_id", $response["body"]) : "";
                     }
                  }
               }
               break;

            case "captcha_type_module":
               if (wp_verify_nonce(isset($_REQUEST["_wp_nonce"]) ? esc_attr($_REQUEST["_wp_nonce"]) : "", "captcha_booster_file")) {
                  parse_str(isset($_REQUEST["data"]) ? base64_decode($_REQUEST["data"]) : "", $captcha_type_data);
                  $arithmetic = isset($_REQUEST["arithmetic"]) ? json_decode(stripcslashes($_REQUEST["arithmetic"])) : "";

                  $update_text_captcha = array();
                  $where = array();

                  $update_text_captcha["captcha_type_text_logical"] = esc_attr($captcha_type_data["ux_ddl_captcha_type"]);
                  $update_text_captcha["captcha_characters"] = intval($captcha_type_data["ux_txt_character"]);
                  $update_text_captcha["captcha_type"] = esc_attr($captcha_type_data["ux_ddl_alphabets"]);
                  $update_text_captcha["text_case"] = esc_attr($captcha_type_data["ux_ddl_case"]);
                  $update_text_captcha["case_sensitive"] = esc_attr($captcha_type_data["ux_ddl_case_disable"]);
                  $update_text_captcha["captcha_width"] = intval($captcha_type_data["ux_txt_width"]);
                  $update_text_captcha["captcha_height"] = intval($captcha_type_data["ux_txt_height"]);
                  $update_text_captcha["captcha_background"] = "bg4.jpg";
                  $update_text_captcha["border_style"] = esc_attr(implode(",", $captcha_type_data["ux_txt_border_style"]));
                  $update_text_captcha["lines"] = intval($captcha_type_data["ux_txt_line"]);
                  $update_text_captcha["lines_color"] = esc_attr($captcha_type_data["ux_txt_color"]);
                  $update_text_captcha["noise_level"] = intval($captcha_type_data["ux_txt_noise_level"]);
                  $update_text_captcha["noise_color"] = esc_attr($captcha_type_data["ux_txt_noise_color"]);
                  $update_text_captcha["text_transperancy"] = intval($captcha_type_data["ux_txt_transperancy"]);
                  $update_text_captcha["signature_text"] = "Captcha Booster";
                  $update_text_captcha["signature_style"] = "8,#cccccc";
                  $update_text_captcha["signature_font"] = "Roboto:100";
                  $update_text_captcha["text_shadow_color"] = esc_attr($captcha_type_data["ux_txt_shadow_color"]);
                  $update_text_captcha["mathematical_operations"] = esc_attr($captcha_type_data["ux_rdl_mathematical_captcha"]);
                  $update_text_captcha["arithmetic_actions"] = esc_attr(implode(",", $arithmetic));
                  $update_text_captcha["relational_actions"] = "1,1";
                  $update_text_captcha["arrange_order"] = "1,1";
                  $update_text_captcha["text_style"] = "20,#000000";
                  $update_text_captcha["text_font"] = "Roboto Condensed";

                  $update_data = array();
                  $where["meta_key"] = "captcha_type";
                  $update_data["meta_value"] = serialize($update_text_captcha);
                  $obj_dbHelper_captcha_booster->updateCommand(captcha_booster_meta(), $update_data, $where);
               }
               break;

            case "captcha_display_settings_module":
               if (wp_verify_nonce(isset($_REQUEST["_wp_nonce"]) ? esc_attr($_REQUEST["_wp_nonce"]) : "", "captcha_booster_settings")) {
                  $checkbox_array = isset($_REQUEST["checkbox_array"]) ? json_decode(stripcslashes($_REQUEST["checkbox_array"])) : "";
                  $update_display_settings_array = array();
                  $update_display_settings_array["settings"] = esc_attr(implode(",", $checkbox_array));

                  $where = array();
                  $update_data = array();
                  $where["meta_key"] = "display_settings";
                  $update_data["meta_value"] = serialize($update_display_settings_array);
                  $obj_dbHelper_captcha_booster->updateCommand(captcha_booster_meta(), $update_data, $where);
               }
               break;

            case "captcha_log_delete_module":
               if (wp_verify_nonce(isset($_REQUEST["_wp_nonce"]) ? esc_attr($_REQUEST["_wp_nonce"]) : "", "captcha_selected_logs_delete")) {
                  $where = array();
                  $meta_id = isset($_REQUEST["meta_id"]) ? intval($_REQUEST["meta_id"]) : 0;
                  $where["meta_id"] = $meta_id;
                  $where_parent["id"] = $meta_id;
                  $obj_dbHelper_captcha_booster->deleteCommand(captcha_booster_meta(), $where);
                  $obj_dbHelper_captcha_booster->deleteCommand(captcha_booster(), $where_parent);
               }
               break;

            case "captcha_blocking_options_module":
               if (wp_verify_nonce(isset($_REQUEST["_wp_nonce"]) ? esc_attr($_REQUEST["_wp_nonce"]) : "", "captcha_booster_options")) {
                  parse_str(isset($_REQUEST["data"]) ? base64_decode($_REQUEST["data"]) : "", $blocking_option_data);
                  $update_captcha_type = array();
                  $where = array();

                  $update_captcha_type["auto_ip_block"] = esc_attr($blocking_option_data["ux_ddl_auto_ip"]);
                  $update_captcha_type["maximum_login_attempt_in_a_day"] = intval($blocking_option_data["ux_txt_login"]);
                  $update_captcha_type["block_for_time"] = esc_attr($blocking_option_data["ux_ddl_blocked_for"]);

                  $update_blocking_options_data = array();
                  $where["meta_key"] = "blocking_options";
                  $update_blocking_options_data["meta_value"] = serialize($update_captcha_type);
                  $obj_dbHelper_captcha_booster->updateCommand(captcha_booster_meta(), $update_blocking_options_data, $where);
               }
               break;

            case "captcha_manage_ip_address_module":
               if (wp_verify_nonce(isset($_REQUEST["_wp_nonce"]) ? esc_attr($_REQUEST["_wp_nonce"]) : "", "captcha_manage_ip_address")) {
                  parse_str(isset($_REQUEST["data"]) ? base64_decode($_REQUEST["data"]) : "", $advance_security_data);
                  $ip = isset($_REQUEST["ip_address"]) ? ip2long(json_decode(stripslashes($_REQUEST["ip_address"]))) : 0;
                  $ip_address = long2ip($ip);
                  $get_ip = get_ip_location_captcha_booster($ip_address);
                  $blocked_for = esc_attr($advance_security_data["ux_ddl_hour"]);
                  $location = $get_ip->country_name == "" && $get_ip->city == "" ? "" : $get_ip->country_name == "" ? "" : $get_ip->city == "" ? $get_ip->country_name : $get_ip->city . ", " . $get_ip->country_name;

                  $ip_address_count = $wpdb->get_results
                      (
                      $wpdb->prepare
                          (
                          "SELECT meta_value FROM " . captcha_booster_meta() . " WHERE meta_key = %s", "block_ip_address"
                      )
                  );
                  foreach ($ip_address_count as $data) {
                     $ip_address_unserialize = unserialize($data->meta_value);
                     if ($ip == $ip_address_unserialize["ip_address"]) {
                        echo "1";
                        die();
                     }
                  }
                  $ip_address_ranges_data = $wpdb->get_results
                      (
                      $wpdb->prepare
                          (
                          "SELECT meta_value FROM " . captcha_booster_meta() . " WHERE meta_key = %s", "block_ip_range"
                      )
                  );
                  $ip_exists = false;
                  foreach ($ip_address_ranges_data as $data) {
                     $ip_range_unserialized_data = unserialize($data->meta_value);
                     $data_range = explode(",", esc_attr($ip_range_unserialized_data["ip_range"]));
                     if ($ip >= $data_range[0] && $ip <= $data_range[1]) {
                        $ip_exists = true;
                        break;
                     }
                  }
                  if ($ip_exists == true) {
                     echo 1;
                  } else {
                     $ip_address_parent_id = $wpdb->get_var
                         (
                         $wpdb->prepare
                             (
                             "SELECT id FROM " . captcha_booster() . " WHERE type=%s", "advance_security"
                         )
                     );
                     $insert_manage_ip_address = array();
                     $insert_manage_ip_address["type"] = "block_ip_address";
                     $insert_manage_ip_address["parent_id"] = isset($ip_address_parent_id) ? intval($ip_address_parent_id) : 0;
                     $last_id = $obj_dbHelper_captcha_booster->insertCommand(captcha_booster(), $insert_manage_ip_address);

                     $insert_manage_ip_address = array();
                     $insert_manage_ip_address["ip_address"] = $ip;
                     $insert_manage_ip_address["blocked_for"] = $blocked_for;
                     $insert_manage_ip_address["location"] = isset($location) ? esc_html($location) : "";
                     $insert_manage_ip_address["comments"] = esc_attr($advance_security_data["ux_txtarea_comments"]);
                     $insert_manage_ip_address["date_time"] = CAPTCHA_BOOSTER_LOCAL_TIME;
                     $insert_manage_ip_address["meta_id"] = $last_id;

                     $insert_data = array();
                     $insert_data["meta_id"] = $last_id;
                     $insert_data["meta_key"] = "block_ip_address";
                     $insert_data["meta_value"] = serialize($insert_manage_ip_address);
                     $obj_dbHelper_captcha_booster->insertCommand(captcha_booster_meta(), $insert_data);

                     if ($blocked_for != "permanently") {
                        $cron_name = "ip_address_unblocker_" . $last_id;
                        wp_schedule_captcha_booster($cron_name, $blocked_for);
                     }
                  }
               }
               break;

            case "captcha_delete_ip_address_module":
               if (wp_verify_nonce(isset($_REQUEST["_wp_nonce"]) ? esc_attr($_REQUEST["_wp_nonce"]) : "", "captcha_manage_ip_address_delete")) {
                  $where = array();
                  $where_parent = array();
                  $id = isset($_REQUEST["id"]) ? intval($_REQUEST["id"]) : 0;
                  $where_parent["id"] = $id;
                  $where["meta_id"] = $id;
                  $cron_name = "ip_address_unblocker_" . $where["meta_id"];
                  wp_unschedule_captcha_booster($cron_name);
                  $obj_dbHelper_captcha_booster->deleteCommand(captcha_booster_meta(), $where);
                  $obj_dbHelper_captcha_booster->deleteCommand(captcha_booster(), $where_parent);
               }
               break;

            case "captcha_manage_ip_ranges_module":
               if (wp_verify_nonce(isset($_REQUEST["_wp_nonce"]) ? esc_attr($_REQUEST["_wp_nonce"]) : "", "captcha_manage_ip_ranges")) {
                  parse_str(isset($_REQUEST["data"]) ? base64_decode($_REQUEST["data"]) : "", $ip_range_data);
                  $start_ip_range = isset($_REQUEST["start_range"]) ? ip2long(json_decode(stripslashes($_REQUEST["start_range"]))) : 0;
                  $end_ip_range = isset($_REQUEST["end_range"]) ? ip2long(json_decode(stripslashes($_REQUEST["end_range"]))) : 0;
                  $blocked_for = esc_attr($ip_range_data["ux_ddl_blocked"]);
                  $get_ip = get_ip_location_captcha_booster(long2ip($start_ip_range));
                  $location = $get_ip->country_name == "" && $get_ip->city == "" ? "" : $get_ip->country_name == "" ? "" : $get_ip->city == "" ? $get_ip->country_name : $get_ip->city . ", " . $get_ip->country_name;

                  $ip_address_range_data = $wpdb->get_results
                      (
                      $wpdb->prepare
                          (
                          "SELECT meta_value FROM " . captcha_booster_meta() . " WHERE meta_key = %s", "block_ip_range"
                      )
                  );
                  $ip_exists = false;
                  foreach ($ip_address_range_data as $data) {
                     $ip_range_unserialized_data = unserialize($data->meta_value);
                     $data_range = explode(",", esc_attr($ip_range_unserialized_data["ip_range"]));
                     if (($start_ip_range >= $data_range[0] && $start_ip_range <= $data_range[1]) || ($end_ip_range >= $data_range[0] && $end_ip_range <= $data_range[1])) {
                        echo 1;
                        $ip_exists = true;
                        break;
                     } elseif (($start_ip_range <= $data_range[0] && $start_ip_range <= $data_range[1]) && ($end_ip_range >= $data_range[0] && $end_ip_range >= $data_range[1])) {
                        echo 1;
                        $ip_exists = true;
                        break;
                     }
                  }

                  if ($ip_exists == false) {
                     $ip_range_parent_id = $wpdb->get_var
                         (
                         $wpdb->prepare
                             (
                             "SELECT id FROM " . captcha_booster() . " WHERE type=%s", "advance_security"
                         )
                     );
                     $insert_manage_ip_range = array();
                     $insert_manage_ip_range["type"] = "block_ip_range";
                     $insert_manage_ip_range["parent_id"] = isset($ip_range_parent_id) ? intval($ip_range_parent_id) : 0;
                     $last_id = $obj_dbHelper_captcha_booster->insertCommand(captcha_booster(), $insert_manage_ip_range);

                     $insert_manage_ip_range = array();
                     $insert_manage_ip_range["ip_range"] = $start_ip_range . "," . $end_ip_range;
                     $insert_manage_ip_range["blocked_for"] = $blocked_for;
                     $insert_manage_ip_range["location"] = isset($location) ? esc_html($location) : "";
                     $insert_manage_ip_range["comments"] = esc_attr($ip_range_data["ux_txtarea_manage_ip_range"]);
                     $insert_manage_ip_range["date_time"] = CAPTCHA_BOOSTER_LOCAL_TIME;
                     $insert_manage_ip_range["meta_id"] = $last_id;

                     $insert_data = array();
                     $insert_data["meta_id"] = $last_id;
                     $insert_data["meta_key"] = "block_ip_range";
                     $insert_data["meta_value"] = serialize($insert_manage_ip_range);
                     $obj_dbHelper_captcha_booster->insertCommand(captcha_booster_meta(), $insert_data);

                     if ($blocked_for != "permanently") {
                        $cron_name = "ip_range_unblocker_" . $last_id;
                        wp_schedule_captcha_booster($cron_name, $blocked_for);
                     }
                  }
               }
               break;

            case "captcha_delete_ip_range_module":
               if (wp_verify_nonce(isset($_REQUEST["_wp_nonce"]) ? esc_attr($_REQUEST["_wp_nonce"]) : "", "captcha_manage_ip_ranges_delete")) {
                  $where = array();
                  $where_parent = array();
                  $id = isset($_REQUEST["id"]) ? intval($_REQUEST["id"]) : 0;
                  $where_parent["id"] = $id;
                  $where["meta_id"] = $id;
                  $cron_name = "ip_range_unblocker_" . $where["meta_id"];
                  wp_unschedule_captcha_booster($cron_name);
                  $obj_dbHelper_captcha_booster->deleteCommand(captcha_booster_meta(), $where);
                  $obj_dbHelper_captcha_booster->deleteCommand(captcha_booster(), $where_parent);
               }
               break;

            case "captcha_type_email_templates_module":
               if (wp_verify_nonce(isset($_REQUEST["_wp_nonce"]) ? esc_attr($_REQUEST["_wp_nonce"]) : "", "captcha_type_email_templates")) {
                  $templates = isset($_REQUEST["data"]) ? esc_attr($_REQUEST["data"]) : "";
                  $email_templates_data = $wpdb->get_results
                      (
                      $wpdb->prepare
                          (
                          "SELECT * FROM " . captcha_booster_meta() .
                          " WHERE meta_key=%s", $templates
                      )
                  );

                  $email_template_data_unseralize = get_captcha_booster_unserialize_data($email_templates_data);
                  echo json_encode($email_template_data_unseralize);
               }
               break;

            case "captcha_booster_other_settings_module":
               if (wp_verify_nonce(isset($_REQUEST["_wp_nonce"]) ? esc_attr($_REQUEST["_wp_nonce"]) : "", "captcha_booster_other_settings")) {
                  parse_str(isset($_REQUEST["data"]) ? base64_decode($_REQUEST["data"]) : "", $update_array);
                  $update_captcha_type = array();
                  $where = array();

                  $update_captcha_type["remove_tables_at_uninstall"] = esc_attr($update_array["ux_ddl_remove_tables"]);
                  $update_captcha_type["live_traffic_monitoring"] = esc_attr($update_array["ux_ddl_live_traffic_monitoring"]);
                  $update_captcha_type["visitor_logs_monitoring"] = esc_attr($update_array["ux_ddl_visitor_log_monitoring"]);
                  $update_captcha_type["ip_address_fetching_method"] = esc_attr($update_array["ux_ddl_ip_address_fetching_method"]);
                  $update_data = array();
                  $where["meta_key"] = "other_settings";
                  $update_data["meta_value"] = serialize($update_captcha_type);
                  $obj_dbHelper_captcha_booster->updateCommand(captcha_booster_meta(), $update_data, $where);
               }
               break;
         }
         die();
      }
   }
}