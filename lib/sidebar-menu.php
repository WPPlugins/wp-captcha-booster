<?php

/**
 * This file is used for creating sidebar menu.
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
      $flag = 0;
      $role_capabilities = $wpdb->get_var
          (
          $wpdb->prepare
              (
              "SELECT meta_value FROM " . captcha_booster_meta() . "
				WHERE meta_key = %s", "roles_and_capabilities"
          )
      );
      $roles_and_capabilities_unserialized_data = unserialize($role_capabilities);
      $capabilities = explode(",", esc_attr($roles_and_capabilities_unserialized_data["roles_and_capabilities"]));

      if (is_super_admin()) {
         $cpb_role = "administrator";
      } else {
         $cpb_role = check_user_roles_captcha_booster();
      }
      switch ($cpb_role) {
         case "administrator":
            $privileges = "administrator_privileges";
            $flag = $capabilities[0];
            break;

         case "author":
            $privileges = "author_privileges";
            $flag = $capabilities[1];
            break;

         case "editor":
            $privileges = "editor_privileges";
            $flag = $capabilities[2];
            break;

         case "contributor":
            $privileges = "contributor_privileges";
            $flag = $capabilities[3];
            break;

         case "subscriber":
            $privileges = "subscriber_privileges";
            $flag = $capabilities[4];
            break;

         default:
            $privileges = "other_privileges";
            $flag = $capabilities[5];
            break;
      }

      foreach ($roles_and_capabilities_unserialized_data as $key => $value) {
         if ($privileges == $key) {
            $privileges_value = $value;
            break;
         }
      }

      $full_control = explode(",", $privileges_value);
      if (!defined("full_control")) {
         define("full_control", "$full_control[0]");
      }
      if (!defined("captcha_setup_captcha_booster")) {
         define("captcha_setup_captcha_booster", "$full_control[1]");
      }
      if (!defined("logs_settings_captcha_booster")) {
         define("logs_settings_captcha_booster", "$full_control[2]");
      }
      if (!defined("advance_security_captcha_booster")) {
         define("advance_security_captcha_booster", "$full_control[3]");
      }
      if (!defined("general_settings_captcha_booster")) {
         define("general_settings_captcha_booster", "$full_control[4]");
      }
      if (!defined("email_templates_captcha_booster")) {
         define("email_templates_captcha_booster", "$full_control[5]");
      }
      if (!defined("roles_and_capabilities_captcha_booster")) {
         define("roles_and_capabilities_captcha_booster", "$full_control[6]");
      }
      if (!defined("system_information_captcha_booster")) {
         define("system_information_captcha_booster", "$full_control[7]");
      }
      $check_captcha_booster_wizard = get_option("captcha-booster-wizard-set-up");
      if ($flag == "1") {
         if ($check_captcha_booster_wizard) {
            add_menu_page($cpb_captcha_booster_breadcrumb, $cpb_captcha_booster_breadcrumb, "read", "cpb_captcha_booster", "", plugins_url("assets/global/img/icons.png", dirname(__FILE__)));
         } else {
            add_menu_page($cpb_captcha_booster_breadcrumb, $cpb_captcha_booster_breadcrumb, "read", "cpb_wizard_captcha_booster", "", plugins_url("assets/global/img/icons.png", dirname(__FILE__)));
            add_submenu_page($cpb_captcha_booster_type_breadcrumb, $cpb_captcha_booster_type_breadcrumb, "", "read", "cpb_wizard_captcha_booster", "cpb_wizard_captcha_booster");
         }
         add_submenu_page("cpb_captcha_booster", $cpb_captcha_booster_type_breadcrumb, $cpb_captcha_setup_menu, "read", "cpb_captcha_booster", $check_captcha_booster_wizard == "" ? "cpb_wizard_captcha_booster" : "cpb_captcha_booster");
         add_submenu_page($cpb_error_message_common, $cpb_error_message_common, "", "read", "cpb_error_message", $check_captcha_booster_wizard == "" ? "cpb_wizard_captcha_booster" : "cpb_error_message");
         add_submenu_page($cpb_display_settings_title, $cpb_display_settings_title, "", "read", "cpb_display_settings", $check_captcha_booster_wizard == "" ? "cpb_wizard_captcha_booster" : "cpb_display_settings");


         add_submenu_page("cpb_captcha_booster", $cpb_live_traffic_title, $cpb_logs_menu, "read", "cpb_live_traffic", $check_captcha_booster_wizard == "" ? "cpb_wizard_captcha_booster" : "cpb_live_traffic");
         add_submenu_page($cpb_recent_login_log_title, $cpb_recent_login_log_title, "", "read", "cpb_login_logs", $check_captcha_booster_wizard == "" ? "cpb_wizard_captcha_booster" : "cpb_login_logs");
         add_submenu_page($cpb_visitor_logs_title, $cpb_visitor_logs_title, "", "read", "cpb_visitor_logs", $check_captcha_booster_wizard == "" ? "cpb_wizard_captcha_booster" : "cpb_visitor_logs");

         add_submenu_page("cpb_captcha_booster", $cpb_blocking_options, $cpb_advance_security_menu, "read", "cpb_blocking_options", $check_captcha_booster_wizard == "" ? "cpb_wizard_captcha_booster" : "cpb_blocking_options");
         add_submenu_page($cpb_manage_ip_addresses, $cpb_manage_ip_addresses, "", "read", "cpb_manage_ip_addresses", $check_captcha_booster_wizard == "" ? "cpb_wizard_captcha_booster" : "cpb_manage_ip_addresses");
         add_submenu_page($cpb_manage_ip_ranges, $cpb_manage_ip_ranges, "", "read", "cpb_manage_ip_ranges", $check_captcha_booster_wizard == "" ? "cpb_wizard_captcha_booster" : "cpb_manage_ip_ranges");
         add_submenu_page($cpb_country_blocks_menu, $cpb_country_blocks_menu, "", "read", "cpb_country_blocks", $check_captcha_booster_wizard == "" ? "cpb_wizard_captcha_booster" : "cpb_country_blocks");

         add_submenu_page("cpb_captcha_booster", $cpb_alert_setup_menu, $cpb_general_settings_menu, "read", "cpb_alert_setup", $check_captcha_booster_wizard == "" ? "cpb_wizard_captcha_booster" : "cpb_alert_setup");
         add_submenu_page($cpb_other_settings_menu, $cpb_other_settings_menu, "", "read", "cpb_other_settings", $check_captcha_booster_wizard == "" ? "cpb_wizard_captcha_booster" : "cpb_other_settings");

         add_submenu_page("cpb_captcha_booster", $cpb_email_templates_menu, $cpb_email_templates_menu, "read", "cpb_email_templates", $check_captcha_booster_wizard == "" ? "cpb_wizard_captcha_booster" : "cpb_email_templates");


         add_submenu_page("cpb_captcha_booster", $cpb_roles_and_capabilities_menu, $cpb_roles_and_capabilities_menu, "read", "cpb_roles_and_capabilities", $check_captcha_booster_wizard == "" ? "cpb_wizard_captcha_booster" : "cpb_roles_and_capabilities");

         add_submenu_page("cpb_captcha_booster", $cpb_feature_requests, $cpb_feature_requests, "read", "cpb_feature_requests", $check_captcha_booster_wizard == "" ? "cpb_wizard_captcha_booster" : "cpb_feature_requests");

         add_submenu_page("cpb_captcha_booster", $cpb_system_information_menu, $cpb_system_information_menu, "read", "cpb_system_information", $check_captcha_booster_wizard == "" ? "cpb_wizard_captcha_booster" : "cpb_system_information");

         add_submenu_page("cpb_captcha_booster", $cpb_upgrade, $cpb_upgrade, "read", "cpb_premium_editions", "cpb_premium_editions");
      }

      /*
        Function Name: cpb_wizard_captcha_booster
        Parameters: No
        Description: This function is used for creating cpb_wizard_captcha_booster menu.
        Created On: 12-04-2017 16:46
        Created By: Tech Banker Team
       */

      function cpb_wizard_captcha_booster() {
         global $wpdb;
         $user_role_permission = get_users_capabilities_captcha_booster();
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php")) {
            include CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "views/wizard/wizard.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "views/wizard/wizard.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php";
         }
      }

      /*
        Function Name: cpb_captcha_booster
        Parameters: No
        Description: This function is used for creating cpb_captcha_booster menu.
        Created On: 25-06-2016 12:23
        Created By: Tech Banker Team
       */

      function cpb_captcha_booster() {
         global $wpdb;
         $user_role_permission = get_users_capabilities_captcha_booster();
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php")) {
            include CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "lib/web-fonts.php")) {
            $web_fonts_list = include_once CAPTCHA_BOOSTER_DIR_PATH . "lib/web-fonts.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "views/captcha-setup/captcha-type.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "views/captcha-setup/captcha-type.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php";
         }
      }

      /*
        Function Name: cpb_error_message
        Parameters: No
        Description: This function is used for creating cpb_error_message menu.
        Created On: 09-07-2016 5:57
        Created By: Tech Banker Team
       */

      function cpb_error_message() {
         global $wpdb;
         $user_role_permission = get_users_capabilities_captcha_booster();
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php")) {
            include CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "views/captcha-setup/error-mesage.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "views/captcha-setup/error-mesage.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php";
         }
      }

      /*
        Function Name: cpb_display_settings
        Parameters: No
        Description: This function is used for creating cpb_display_settings menu.
        Created On: 09-07-2016 5:57
        Created By: Tech Banker Team
       */

      function cpb_display_settings() {
         global $wpdb;
         $user_role_permission = get_users_capabilities_captcha_booster();
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php")) {
            include CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "views/captcha-setup/display-settings.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "views/captcha-setup/display-settings.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php";
         }
      }

      /*
        Function Name: cpb_alert_setup
        Parameters: No
        Description: This function is used for creating cpb_alert_setup menu.
        Created On: 10-07-2016 11:40
        Created By: Tech Banker Team
       */

      function cpb_alert_setup() {
         global $wpdb;
         $user_role_permission = get_users_capabilities_captcha_booster();
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php")) {
            include CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "views/general-settings/alert-setup.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "views/general-settings/alert-setup.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php";
         }
      }

      /*
        Function Name: cpb_live_traffic
        Parameters: No
        Description: This function is used for creating cpb_live_traffic menu.
        Created On: 11-07-2016 12:04
        Created By: Tech Banker Team
       */

      function cpb_live_traffic() {
         global $wpdb;
         $user_role_permission = get_users_capabilities_captcha_booster();
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php")) {
            include CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "views/logs/live-traffic.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "views/logs/live-traffic.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php";
         }
      }

      /*
        Function Name: cpb_login_logs
        Parameters: No
        Description: This function is used for creating cpb_login_logs menu.
        Created On: 11-07-2016 11:50
        Created By: Tech Banker Team
       */

      function cpb_login_logs() {
         global $wpdb;
         $user_role_permission = get_users_capabilities_captcha_booster();
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php")) {
            include CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "views/logs/login-logs.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "views/logs/login-logs.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php";
         }
      }

      /*
        Function Name: cpb_visitor_logs
        Parameters: No
        Description: This function is used for creating cpb_live_traffic menu.
        Created On: 11-07-2016 12:04
        Created By: Tech Banker Team
       */

      function cpb_visitor_logs() {
         global $wpdb;
         $user_role_permission = get_users_capabilities_captcha_booster();
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php")) {
            include CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "views/logs/visitor-logs.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "views/logs/visitor-logs.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php";
         }
      }

      /*
        Function Name: cpb_blocking_options
        Parameters: No
        Description: This function is used for creating cpb_blocking_options menu.
        Created On: 11-07-2016 12:06
        Created By: Tech Banker Team
       */

      function cpb_blocking_options() {
         global $wpdb;
         $user_role_permission = get_users_capabilities_captcha_booster();
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php")) {
            include CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "views/advance-security/blocking-options.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "views/advance-security/blocking-options.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php";
         }
      }

      /*
        Function Name: cpb_manage_ip_addresses
        Parameters: No
        Description: This function is used for creating cpb_manage_ip_addresses menu.
        Created On: 12-07-2016 12:08
        Created By: Tech Banker Team
       */

      function cpb_manage_ip_addresses() {
         global $wpdb;
         $user_role_permission = get_users_capabilities_captcha_booster();
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php")) {
            include CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "views/advance-security/manage-ip-addresses.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "views/advance-security/manage-ip-addresses.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php";
         }
      }

      /*
        Function Name: cpb_manage_ip_ranges
        Parameters: No
        Description: This function is used for creating cpb_manage_ip_ranges menu.
        Created On: 12-07-2016 12:10
        Created By: Tech Banker Team
       */

      function cpb_manage_ip_ranges() {
         global $wpdb;
         $user_role_permission = get_users_capabilities_captcha_booster();
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php")) {
            include CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "views/advance-security/manage-ip-ranges.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "views/advance-security/manage-ip-ranges.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php";
         }
      }

      /*
        Function Name: cpb_country_blocks
        Parameters: No
        Description: This function is used for creating cpb_country_blocks menu.
        Created On: 03-10-2016 12:28
        Created By: Tech Banker Team
       */

      function cpb_country_blocks() {
         global $wpdb;
         $user_role_permission = get_users_capabilities_captcha_booster();
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php")) {
            include CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "views/advance-security/country-blocks.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "views/advance-security/country-blocks.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php";
         }
      }

      /*
        Function Name: cpb_email_templates
        Parameters: No
        Description: This function is used for creating cpb_email_templates menu.
        Created On: 12-07-2016 12:15
        Created By: Tech Banker Team
       */

      function cpb_email_templates() {
         global $wpdb;
         $user_role_permission = get_users_capabilities_captcha_booster();
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php")) {
            include CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "views/email-templates/email-templates.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "views/email-templates/email-templates.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php";
         }
      }

      /*
        Function Name: cpb_other_settings
        Parameters: No
        Description: This function is used for creating cpb_other_settings menu.
        Created On: 13-07-2016 12:20
        Created By: Tech Banker Team
       */

      function cpb_other_settings() {
         global $wpdb;
         $user_role_permission = get_users_capabilities_captcha_booster();
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php")) {
            include CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "views/general-settings/other-settings.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "views/general-settings/other-settings.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php";
         }
      }

      /*
        Function Name: cpb_roles_and_capabilities
        Parameters: No
        Description: This function is used for creating cpb_roles_and_capabilities menu.
        Created On: 10-07-2016 11:45
        Created By: Tech Banker Team
       */

      function cpb_roles_and_capabilities() {
         global $wpdb;
         $user_role_permission = get_users_capabilities_captcha_booster();
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php")) {
            include CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "views/roles-and-capabilities/roles-and-capabilities.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "views/roles-and-capabilities/roles-and-capabilities.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php";
         }
      }

      /*
        Function Name: cpb_feature_requests
        Parameters: No
        Description: This function is used for creating cpb_feature_requests menu.
        Created On: 13-07-2016 12:20
        Created By: Tech Banker Team
       */

      function cpb_feature_requests() {
         global $wpdb;
         $user_role_permission = get_users_capabilities_captcha_booster();
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php")) {
            include CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "views/feature-requests/feature-requests.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "views/feature-requests/feature-requests.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php";
         }
      }

      /*
        Function Name: cpb_system_information
        Parameters: No
        Description: This function is used for creating cpb_system_information menu.
        Created On: 14-07-2016 09:50
        Created By: Tech Banker Team
       */

      function cpb_system_information() {
         global $wpdb;
         $user_role_permission = get_users_capabilities_captcha_booster();
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php")) {
            include CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "views/system-information/system-information.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "views/system-information/system-information.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php";
         }
      }

      /*
        Function Name: cpb_premium_editions
        Parameters: No
        Description: This function is used to create lab_premium_editions menu
        Created On: 13-04-2017 11:17
        Created By: tech-banker Team
       */

      function cpb_premium_editions() {
         global $wpdb;
         $user_role_permission = get_users_capabilities_captcha_booster();
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php")) {
            include CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/header.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/sidebar.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/queries.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "views/premium-editions/premium-editions.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "views/premium-editions/premium-editions.php";
         }
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/footer.php";
         }
      }

   }
}