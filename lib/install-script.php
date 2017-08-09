<?php

/**
 * This file is used for creating database on activation Hook.
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
   if (!current_user_can("manage_options")) {
      return;
   } else {
      /*
        Class Name: dbHelper_install_script_captcha_booster
        Parameters: No
        Description: This Class is used for Database Operations.
        Created On: 27-05-2016 4:09
        Created By: Tech Banker Team
       */

      class dbHelper_install_script_captcha_booster {
         /*
           Function Name: insertCommand
           Parameters: Yes($table_name,$data)
           Description: This Function is used for Insert data in database.
           Created On: 27-05-2016 4:10
           Created By: Tech Banker Team
          */

         function insertCommand($table_name, $data) {
            global $wpdb;
            $wpdb->insert($table_name, $data);
            return $wpdb->insert_id;
         }

         /*
           Function Name: updateCommand
           Parameters: Yes($table_name,$data,$where)
           Description: This function is used for Update data.
           Created On: 30-09-2016 14:42
           Created By: Tech Banker Team
          */

         function updateCommand($table_name, $data, $where) {
            global $wpdb;
            $wpdb->update($table_name, $data, $where);
         }

      }

      require_once ABSPATH . "wp-admin/includes/upgrade.php";
      $captcha_version_no = get_option("captcha_booster_version_number");

      /*
        Function Name: cpb_captcha_booster_parent
        Parameter: No
        Description: This function is used for creating "captcha_booster" table in database.
        Created On: 28-05-2016 5:34
        Created By: Tech Banker Team
       */

      function cpb_captcha_booster_parent() {
         global $wpdb;
         $obj_dbHelper_install_parent = new dbHelper_install_script_captcha_booster();
         $sql = "CREATE TABLE IF NOT EXISTS " . captcha_booster() . "
				(
					`id` int(10) NOT NULL AUTO_INCREMENT,
					`type` longtext NOT NULL,
					`parent_id` int(10) NOT NULL,
					PRIMARY KEY (`id`)
				)
				ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COLLATE= utf8_general_ci";
         dbDelta($sql);

         $data = "INSERT INTO " . captcha_booster() . " (`type`, `parent_id`) VALUES
				('captcha_setup', 0),
				('alert_setup', 0),
				('logs','0'),
				('roles_and_capabilities', 0),
				('advance_security', 0),
				('email_templates', 0),
				('other_settings', 0);";
         dbDelta($data);

         $parent_table_data = $wpdb->get_results
             (
             "SELECT * FROM " . captcha_booster()
         );

         foreach ($parent_table_data as $row) {
            switch (esc_attr($row->type)) {
               case "captcha_setup":
                  $insert_captcha_setup_array = array();
                  $insert_captcha_setup_array["captcha_type"] = isset($row->id) ? intval($row->id) : 0;
                  $insert_captcha_setup_array["error_message"] = isset($row->id) ? intval($row->id) : 0;
                  $insert_captcha_setup_array["display_settings"] = isset($row->id) ? intval($row->id) : 0;

                  $insert_captcha_setup_data = array();
                  foreach ($insert_captcha_setup_array as $key => $value) {
                     $insert_captcha_setup_data["type"] = $key;
                     $insert_captcha_setup_data["parent_id"] = $value;
                     $obj_dbHelper_install_parent->insertCommand(captcha_booster(), $insert_captcha_setup_data);
                  }
                  break;

               case "advance_security":
                  $insert_advance_security_array = array();
                  $insert_advance_security_array["blocking_options"] = isset($row->id) ? intval($row->id) : 0;
                  $insert_advance_security_array["country_blocks"] = isset($row->id) ? intval($row->id) : 0;

                  $insert_advance_security_data = array();
                  foreach ($insert_advance_security_array as $key => $value) {
                     $insert_advance_security_data["type"] = $key;
                     $insert_advance_security_data["parent_id"] = $value;
                     $obj_dbHelper_install_parent->insertCommand(captcha_booster(), $insert_advance_security_data);
                  }
                  break;
            }
         }
      }

      /*
        Function Name: cpb_captcha_booster_meta
        Parameter: No
        Description: This function is used for creating "captcha_booster_meta" table in database.
        Created On: 28-05-2016 5:33
        Created By: Tech Banker Team
       */

      function cpb_captcha_booster_meta() {
         global $wpdb;
         $obj_dbHelper_install_meta_table = new dbHelper_install_script_captcha_booster();
         $sql = "CREATE TABLE IF NOT EXISTS " . captcha_booster_meta() . "
				(
					`id` int(10) NOT NULL AUTO_INCREMENT,
					`meta_id` int(10) NOT NULL,
					`meta_key` varchar(200) NOT NULL,
					`meta_value` longtext NOT NULL,
					PRIMARY KEY (`id`)
				)
				ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 COLLATE= utf8_general_ci";
         dbDelta($sql);
         $admin_email = get_option("admin_email");
         $parent_table_data = $wpdb->get_results
             (
             "SELECT * FROM " . captcha_booster()
         );

         foreach ($parent_table_data as $row) {
            switch (esc_attr($row->type)) {
               case "captcha_type":
                  $captcha_type_array = array();
                  $captcha_type_array["captcha_type_text_logical"] = "logical_captcha";
                  $captcha_type_array["captcha_characters"] = "4";
                  $captcha_type_array["captcha_type"] = "alphabets_and_digits";
                  $captcha_type_array["text_case"] = "upper_case";
                  $captcha_type_array["case_sensitive"] = "enable";
                  $captcha_type_array["captcha_width"] = "180";
                  $captcha_type_array["captcha_height"] = "60";
                  $captcha_type_array["captcha_background"] = "bg4.jpg";
                  $captcha_type_array["border_style"] = "0,solid,#cccccc";
                  $captcha_type_array["lines"] = "2";
                  $captcha_type_array["lines_color"] = "#cc1f1f";
                  $captcha_type_array["noise_level"] = "5";
                  $captcha_type_array["noise_color"] = "#cc1f1f";
                  $captcha_type_array["text_transperancy"] = "10";
                  $captcha_type_array["signature_text"] = "Captcha Booster";
                  $captcha_type_array["signature_style"] = "8,#cccccc";
                  $captcha_type_array["signature_font"] = "Roboto:100";
                  $captcha_type_array["text_shadow_color"] = "#cfc6cf";
                  $captcha_type_array["mathematical_operations"] = "arithmetic";
                  $captcha_type_array["arithmetic_actions"] = "1,1,1,1";
                  $captcha_type_array["relational_actions"] = "1,1";
                  $captcha_type_array["arrange_order"] = "1,1";
                  $captcha_type_array["text_style"] = "20,#000000";
                  $captcha_type_array["text_font"] = "Roboto Condensed";

                  $captcha_type_data = array();
                  $captcha_type_data["meta_id"] = isset($row->id) ? intval($row->id) : 0;
                  $captcha_type_data["meta_key"] = "captcha_type";
                  $captcha_type_data["meta_value"] = serialize($captcha_type_array);
                  $obj_dbHelper_install_meta_table->insertCommand(captcha_booster_meta(), $captcha_type_data);
                  break;

               case "error_message":
                  $error_message_data = array();
                  $error_message_data["for_login_attempts_error"] = "<p>Your Maximum Login Attempts left : <strong>[login_attempts]</strong></p>";
                  $error_message_data["for_invalid_captcha_error"] = "You have entered an Invalid Captcha. Please try again.";
                  $error_message_data["for_blocked_ip_address_error"] = "<p>Your IP Address <strong>[ip_address]</strong> has been blocked by the Administrator for security purposes.</p>\r\n<p>Please contact the Website Administrator for more details.</p>";
                  $error_message_data["for_captcha_empty_error"] = "Your Captcha is Empty. Please enter Captcha and try again.";
                  $error_message_data["for_blocked_ip_range_error"] = "<p>Your IP Range <strong>[ip_range]</strong> has been blocked by the Administrator for security purposes.</p>\r\n<p>Please contact the Website Administrator for more details.</p>";
                  $error_message_data["for_blocked_country_error"] = "<p>Unfortunately, your country location <strong>[country_location]</strong> has been blocked by the Administrator for security purposes.</p><p>Please contact the website Administrator for more details.</p>";

                  $insert_error_message_data = array();
                  $insert_error_message_data["meta_id"] = isset($row->id) ? intval($row->id) : 0;
                  $insert_error_message_data["meta_key"] = "error_message";
                  $insert_error_message_data["meta_value"] = serialize($error_message_data);
                  $obj_dbHelper_install_meta_table->insertCommand(captcha_booster_meta(), $insert_error_message_data);
                  break;

               case "display_settings":
                  $display_setting_data = array();
                  $display_setting_data["settings"] = "1,0,1,0,1,0,1,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0";

                  $insert_display_settings_data = array();
                  $insert_display_settings_data["meta_id"] = isset($row->id) ? intval($row->id) : 0;
                  $insert_display_settings_data["meta_key"] = "display_settings";
                  $insert_display_settings_data["meta_value"] = serialize($display_setting_data);
                  $obj_dbHelper_install_meta_table->insertCommand(captcha_booster_meta(), $insert_display_settings_data);
                  break;

               case "alert_setup":
                  $alert_setup_data = array();
                  $alert_setup_data["email_when_a_user_fails_login"] = "disable";
                  $alert_setup_data["email_when_a_user_success_login"] = "disable";
                  $alert_setup_data["email_when_an_ip_address_is_blocked"] = "disable";
                  $alert_setup_data["email_when_an_ip_address_is_unblocked"] = "disable";
                  $alert_setup_data["email_when_an_ip_range_is_blocked"] = "disable";
                  $alert_setup_data["email_when_an_ip_range_is_unblocked"] = "disable";

                  $insert_alert_setup_data = array();
                  $insert_alert_setup_data["meta_id"] = isset($row->id) ? intval($row->id) : 0;
                  $insert_alert_setup_data["meta_key"] = "alert_setup";
                  $insert_alert_setup_data["meta_value"] = serialize($alert_setup_data);
                  $obj_dbHelper_install_meta_table->insertCommand(captcha_booster_meta(), $insert_alert_setup_data);
                  break;

               case "roles_and_capabilities":
                  $roles_and_capabilities_data = array();
                  $roles_and_capabilities_data["roles_and_capabilities"] = "1,1,1,0,0,0";
                  $roles_and_capabilities_data["administrator_privileges"] = "1,1,1,1,1,1,1,1,1,1";
                  $roles_and_capabilities_data["author_privileges"] = "0,0,1,1,0,1,0,0,1,0";
                  $roles_and_capabilities_data["editor_privileges"] = "0,0,1,0,0,1,0,1,0,0";
                  $roles_and_capabilities_data["contributor_privileges"] = "0,0,1,0,0,1,0,0,1,0";
                  $roles_and_capabilities_data["subscriber_privileges"] = "0,0,0,0,0,0,0,0,0,0";
                  $roles_and_capabilities_data["others_full_control_capability"] = "0";
                  $roles_and_capabilities_data["other_privileges"] = "0,0,0,0,0,0,0,0,0,0";
                  $roles_and_capabilities_data["show_captcha_booster_top_bar_menu"] = "enable";

                  $user_capabilities = get_others_capabilities_captcha_booster();
                  $other_roles_array = array();
                  $other_roles_access_array = array(
                      "manage_options",
                      "edit_plugins",
                      "edit_posts",
                      "publish_posts",
                      "publish_pages",
                      "edit_pages",
                      "read"
                  );
                  foreach ($other_roles_access_array as $role) {
                     if (in_array($role, $user_capabilities)) {
                        array_push($other_roles_array, $role);
                     }
                  }
                  $roles_and_capabilities_data["capabilities"] = $other_roles_array;

                  $insert_roles_and_capabilities_data = array();
                  $insert_roles_and_capabilities_data["meta_id"] = isset($row->id) ? intval($row->id) : 0;
                  $insert_roles_and_capabilities_data["meta_key"] = "roles_and_capabilities";
                  $insert_roles_and_capabilities_data["meta_value"] = serialize($roles_and_capabilities_data);
                  $obj_dbHelper_install_meta_table->insertCommand(captcha_booster_meta(), $insert_roles_and_capabilities_data);
                  break;

               case "blocking_options":
                  $blocking_options_data = array();
                  $blocking_options_data["auto_ip_block"] = "enable";
                  $blocking_options_data["maximum_login_attempt_in_a_day"] = "5";
                  $blocking_options_data["block_for_time"] = "1Hour";

                  $insert_blocking_options_data = array();
                  $insert_blocking_options_data["meta_id"] = isset($row->id) ? intval($row->id) : 0;
                  $insert_blocking_options_data["meta_key"] = "blocking_options";
                  $insert_blocking_options_data["meta_value"] = serialize($blocking_options_data);
                  $obj_dbHelper_install_meta_table->insertCommand(captcha_booster_meta(), $insert_blocking_options_data);
                  break;

               case "country_blocks":
                  $blocking_options_data = array();
                  $blocking_options_data["country_block_data"] = "";

                  $insert_blocking_options_data = array();
                  $insert_blocking_options_data["meta_id"] = isset($row->id) ? intval($row->id) : 0;
                  $insert_blocking_options_data["meta_key"] = "country_blocks";
                  $insert_blocking_options_data["meta_value"] = serialize($blocking_options_data);
                  $obj_dbHelper_install_meta_table->insertCommand(captcha_booster_meta(), $insert_blocking_options_data);
                  break;

               case "email_templates":
                  $email_templates_data = array();
                  $email_templates_data["template_for_user_success"] = "<p>Hi,</p><p>A login attempt has been successfully made to your website [site_url] from user <strong>[username]</strong> at [date_time] from IP Address <strong>[ip_address]</strong>.</p><p><u>Here is the detailed footprint at the Request</u> :-</p><p><strong>Username:</strong> [username]</p><p><strong>Date/Time:</strong> [date_time]</p><p><strong>website:</strong> [site_url]</p><p><strong>IP Address:</strong>[ip_address]</p><p><strong>Resource:</strong>[resource]</p><p>Thanks & Regards</p><p><strong>Technical Support Team</strong></p><p>[site_url]</p>";
                  $email_templates_data["template_for_user_failure"] = "<p>Hi,</p><p>An unsuccessful attempt to login at your website [site_url] was being made by user <strong>[username]</strong> at [date_time] from IP Address <strong>[ip_address]</strong>.</p><p><u>Here is the detailed footprint at the Request</u> :-</p><p><strong>Username:</strong> [username]</p><p><strong>Date/Time:</strong> [date_time]</p><p><strong>website:</strong> [site_url]<p><strong>IP Address:</strong> [ip_address]</p><strong>Resource:</strong>[resource]</p><p>Thanks & Regards</p><p><strong>Technical Support Team</strong></p><p>[site_url]</p>";
                  $email_templates_data["template_for_ip_address_blocked"] = "<p>Hi,</p><p>An IP Address <strong>[ip_address]</strong> has been Blocked <strong>[blocked_for]</strong> to your website [site_url].</p><p><u>Here is the detailed footprint at the Request</u> :-</p><p><strong>Date/Time:</strong> [date_time]</p><p><strong>website:</strong> [site_url]</p><p><strong>IP Address:</strong> [ip_address]</p><p><strong>Resource:</strong>[resource]</p><p>Thanks & Regards</p><p><strong>Technical Support Team</strong></p><p>[site_url]</p>";
                  $email_templates_data["template_for_ip_address_unblocked"] = "<p>Hi,</p><p>An IP Address <strong>[ip_address]</strong> has been Unblocked from your website [site_url] </p><p><u>Here is the detailed footprint at the Request</u> :-</p><p><strong>Date/Time:</strong> [date_time]</p><p><strong>website:</strong> [site_url]</p><p><strong>IP Address:</strong> [ip_address]</p><p>Thanks & Regards</p><p>Technical Support Team</p><p>[site_url]</p>";
                  $email_templates_data["template_for_ip_range_blocked"] = "<p>Hi,</p><p>An IP Range from <strong>[start_ip_range]</strong> to <strong>[end_ip_range]</strong> has been Blocked <strong>[blocked_for]</strong> to your website [site_url].</p><p><u>Here is the detailed footprint at the Request</u> :-</p><p><strong>Date/Time:</strong> [date_time]</p><p><strong>website:</strong> [site_url]</p><strong>IP Address:</strong> [ip_range]</p><p><strong>Resource:</strong>[resource]</p><p>Thanks & Regards</p><p><strong>Technical Support Team</strong></p><p>[site_url]</p>";
                  $email_templates_data["template_for_ip_range_unblocked"] = "<p>Hi,</p><p>An IP Range from <strong>[start_ip_range]</strong> to <strong>[end_ip_range]</strong> has been Unblocked from your website [site_url] .</p><p><u>Here is the detailed footprint at the Request</u> :-</p><p><strong>Date/Time:</strong> [date_time]</p><p><strong>website:</strong> [site_url]</p><p><strong>IP Address:</strong> [ip_range]</p><p>Thanks & Regards</p><p><strong>Technical Support Team</strong></p><p>[site_url]</p>";

                  $email_templates_message = array("Login Success Notification- Captcha Booster", "Login Failure Notification- Captcha Booster", "IP Address Blocked Notification - Captcha Booster", "IP Address Unblocked Notification - Captcha Booster", "IP Range Blocked Notification - Captcha Booster", "IP Range Unblocked Notification - Captcha Booster");
                  $count = 0;
                  foreach ($email_templates_data as $key => $value) {
                     $email_templates_data_array = array();
                     $email_templates_data_array["send_to"] = $admin_email;
                     $email_templates_data_array["email_cc"] = "";
                     $email_templates_data_array["email_bcc"] = "";
                     $email_templates_data_array["email_subject"] = $email_templates_message[$count];
                     $email_templates_data_array["email_message"] = $value;
                     $count++;

                     $insert_email_templates_data = array();
                     $insert_email_templates_data["meta_id"] = isset($row->id) ? intval($row->id) : 0;
                     $insert_email_templates_data["meta_key"] = $key;
                     $insert_email_templates_data["meta_value"] = serialize($email_templates_data_array);
                     $obj_dbHelper_install_meta_table->insertCommand(captcha_booster_meta(), $insert_email_templates_data);
                  }
                  break;

               case "other_settings":
                  $other_settings_data = array();
                  $other_settings_data["remove_tables_at_uninstall"] = "enable";
                  $other_settings_data["live_traffic_monitoring"] = "disable";
                  $other_settings_data["visitor_logs_monitoring"] = "disable";
                  $other_settings_data["ip_address_fetching_method"] = "";

                  $insert_other_settings_data = array();
                  $insert_other_settings_data["meta_id"] = isset($row->id) ? intval($row->id) : 0;
                  $insert_other_settings_data["meta_key"] = "other_settings";
                  $insert_other_settings_data["meta_value"] = serialize($other_settings_data);
                  $obj_dbHelper_install_meta_table->insertCommand(captcha_booster_meta(), $insert_other_settings_data);
                  break;
            }
         }
      }

      $obj_dbHelper_captcha_booster = new dbHelper_install_script_captcha_booster();
      switch ($captcha_version_no) {
         case "":
            cpb_captcha_booster_parent();
            cpb_captcha_booster_meta();
            break;

         default:
            global $wpdb;
            if ($captcha_version_no < "1.1.2") {
               $wpdb->query("DROP TABLE IF EXISTS " . captcha_booster());
               $wpdb->query("DROP TABLE IF EXISTS " . captcha_booster_meta());
            }
            if (count($wpdb->get_var("SHOW TABLES LIKE '" . captcha_booster() . "'")) == 0) {
               cpb_captcha_booster_parent();
            }
            if (count($wpdb->get_var("SHOW TABLES LIKE '" . captcha_booster_meta() . "'")) == 0) {
               cpb_captcha_booster_meta();
            } else {
               $other_settings_data = $wpdb->get_var
                   (
                   $wpdb->prepare
                       (
                       "SELECT meta_value FROM " . captcha_booster_meta() .
                       " WHERE meta_key=%s", "other_settings"
                   )
               );
               $other_settings_data_array = unserialize($other_settings_data);
               $where = array();
               $other_settings_array = array();
               $where["meta_key"] = "other_settings";
               $other_settings_array["meta_value"] = serialize($other_settings_data_array);
               $obj_dbHelper_captcha_booster->updateCommand(captcha_booster_meta(), $other_settings_array, $where);
               $display_settings_data = $wpdb->get_var
                   (
                   $wpdb->prepare
                       (
                       "SELECT meta_value FROM " . captcha_booster_meta() .
                       " WHERE meta_key = %s", "display_settings"
                   )
               );
               $settings_unserialize = unserialize($display_settings_data);
               $settings_explode = explode(",", $settings_unserialize["settings"]);
               $display_settings_array = array("0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0", "0");
               if (count($settings_explode) == 9) {
                  foreach ($settings_explode as $key => $value) {
                     switch ($key) {
                        case 0:
                           $value == "1" ? $display_settings_array[0] = "1" : $display_settings_array[0] = "0";
                           break;
                        case 1:
                           $value == "1" ? $display_settings_array[2] = "1" : $display_settings_array[2] = "0";
                           break;
                        case 2:
                           $value == "1" ? $display_settings_array[4] = "1" : $display_settings_array[4] = "0";
                           break;
                        case 3:
                           $value == "1" ? $display_settings_array[6] = "1" : $display_settings_array[6] = "0";
                           break;
                        case 4:
                           $value == "1" ? $display_settings_array[8] = "1" : $display_settings_array[8] = "0";
                           break;
                        case 5:
                           $value == "1" ? $display_settings_array[10] = "1" : $display_settings_array[10] = "0";
                           break;
                        case 6:
                           $value == "1" ? $display_settings_array[12] = "1" : $display_settings_array[12] = "0";
                           $value == "1" ? $display_settings_array[14] = "1" : $display_settings_array[14] = "0";
                           $value == "1" ? $display_settings_array[16] = "1" : $display_settings_array[16] = "0";
                           $value == "1" ? $display_settings_array[18] = "1" : $display_settings_array[18] = "0";
                           break;
                        case 7:
                           $value == "1" ? $display_settings_array[11] = "1" : $display_settings_array[11] = "0";
                           $value == "1" ? $display_settings_array[13] = "1" : $display_settings_array[13] = "0";
                           $value == "1" ? $display_settings_array[15] = "1" : $display_settings_array[15] = "0";
                           break;
                        case 8:
                           $value == "1" ? $display_settings_array[20] = "1" : $display_settings_array[20] = "0";
                           break;
                     }
                  }
                  $display_settings_unserialize_data = array();
                  $display_settings_unserialize_data["settings"] = esc_attr(implode(",", $display_settings_array));
                  $where = array();
                  $update_display_settings_data = array();
                  $where["meta_key"] = "display_settings";
                  $update_display_settings_data["meta_value"] = serialize($display_settings_unserialize_data);
                  $obj_dbHelper_captcha_booster->updateCommand(captcha_booster_meta(), $update_display_settings_data, $where);
               }
            }
            break;
      }
      update_option("captcha_booster_version_number", "2.0.1");
   }
}