<?php

/*
  Plugin Name: Captcha Booster
  Plugin URI: http://beta.tech-banker.com
  Description: Captcha Booster is a powerful and highly customized WordPress plugin that effectively protects your WP blogs from Spammers and Unauthorized Users.
  Author: Tech Banker
  Author URI: http://beta.tech-banker.com
  Version: 2.0.10
  License: GPLv3
  Text Domain: wp-captcha-booster
  Domain Path: /languages
 */

if (!defined("ABSPATH")) {
   exit;
} //exit if accessed directly
/* Constant Declaration */
if (!defined("CAPTCHA_BOOSTER_FILE")) {
   define("CAPTCHA_BOOSTER_FILE", plugin_basename(__FILE__));
}
if (!defined("CAPTCHA_BOOSTER_DIR_PATH")) {
   define("CAPTCHA_BOOSTER_DIR_PATH", plugin_dir_path(__FILE__));
}
if (!defined("CAPTCHA_BOOSTER_PLUGIN_DIRNAME")) {
   define("CAPTCHA_BOOSTER_PLUGIN_DIRNAME", plugin_basename(dirname(__FILE__)));
}
if (!defined("CAPTCHA_BOOSTER_LOCAL_TIME")) {
   define("CAPTCHA_BOOSTER_LOCAL_TIME", strtotime(date_i18n("Y-m-d H:i:s")));
}
if (is_ssl()) {
   if (!defined("tech_banker_url")) {
      define("tech_banker_url", "https://tech-banker.com");
   }
   if (!defined("tech_banker_beta_url")) {
      define("tech_banker_beta_url", "https://beta.tech-banker.com");
   }
   if (!defined("tech_banker_services_url")) {
      define("tech_banker_services_url", "https://tech-banker-services.org");
   }
} else {
   if (!defined("tech_banker_url")) {
      define("tech_banker_url", "http://tech-banker.com");
   }
   if (!defined("tech_banker_beta_url")) {
      define("tech_banker_beta_url", "http://beta.tech-banker.com");
   }
   if (!defined("tech_banker_services_url")) {
      define("tech_banker_services_url", "http://tech-banker-services.org");
   }
}
if (!defined("tech_banker_stats_url")) {
   define("tech_banker_stats_url", "http://stats.tech-banker-services.org");
}
if (!defined("captcha_booster_version_number")) {
   define("captcha_booster_version_number", "2.0.10");
}
$memory_limit_captcha_booster = intval(ini_get("memory_limit"));
if (!extension_loaded('suhosin') && $memory_limit_captcha_booster < 512) {
   @ini_set("memory_limit", "512M");
}

@ini_set("max_execution_time", 6000);
@ini_set("max_input_vars", 10000);

/*
  Function Name: install_script_for_captcha_booster
  Parameters: No
  Description: This function is used to create Tables in Database.
  Created On: 25-06-2016 12:35
  Created By: Tech Banker Team
 */

function install_script_for_captcha_booster() {
   global $wpdb;
   if (is_multisite()) {
      $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
      foreach ($blog_ids as $blog_id) {
         switch_to_blog($blog_id);
         $version = get_option("captcha_booster_version_number");
         if ($version < "2.0.1") {
            if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "lib/install-script.php")) {
               include CAPTCHA_BOOSTER_DIR_PATH . "lib/install-script.php";
            }
         }
         restore_current_blog();
      }
   } else {
      $version = get_option("captcha_booster_version_number");
      if ($version < "2.0.1") {
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "lib/install-script.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "lib/install-script.php";
         }
      }
   }
}

/*
  Function Name: captcha_booster
  Parameter: No
  Description: This function is used to return Parent Table name with prefix.
  Created On: 25-06-2016 12:32
  Created By: Tech Banker Team
 */

function captcha_booster() {
   global $wpdb;
   return $wpdb->prefix . "captcha_booster";
}

/*
  Function Name: captcha_booster_meta
  Parameter: No
  Description: This function is used to return Meta Table name with prefix.
  Created On: 25-06-2016 12:32
  Created By: Tech Banker Team
 */

function captcha_booster_meta() {
   global $wpdb;
   return $wpdb->prefix . "captcha_booster_meta";
}

/*
  Function Name: get_others_capabilities_captcha_booster
  Parameters: No
  Description: This function is used to get all the roles available in WordPress
  Created On: 24-10-2016 03:55
  Created By: Tech Banker Team
 */

function get_others_capabilities_captcha_booster() {
   $user_capabilities = array();
   if (function_exists("get_editable_roles")) {
      foreach (get_editable_roles() as $role_name => $role_info) {
         foreach ($role_info["capabilities"] as $capability => $values) {
            if (!in_array($capability, $user_capabilities)) {
               array_push($user_capabilities, $capability);
            }
         }
      }
   } else {
      $user_capabilities = array(
          "manage_options",
          "edit_plugins",
          "edit_posts",
          "publish_posts",
          "publish_pages",
          "edit_pages",
          "read"
      );
   }
   return $user_capabilities;
}

/*
  Function Name: check_user_roles_captcha_booster
  Parameters: No
  Description: This function is used for checking roles of different users.
  Created On: 24-10-2016 03:55
  Created By: Tech Banker Team
 */

function check_user_roles_captcha_booster() {
   global $current_user;
   $user = $current_user ? new WP_User($current_user) : wp_get_current_user();
   return $user->roles ? $user->roles[0] : false;
}

/*
  Function Name: captcha_booster_action_links
  Parameters: Yes
  Description: This function is used to create link for Pro Editions.
  Created On: 13-04-2017 11:19
  Created By: tech-banker Team
 */

function captcha_booster_action_links($plugin_link) {
   $plugin_link[] = "<a href=\"http://beta.tech-banker.com/products/captcha-booster/\" style=\"color: red; font-weight: bold;\" target=\"_blank\">Go Pro!</a>";
   return $plugin_link;
}

/*
  Function Name: captcha_booster_settings_action_links
  Parameters: Yes($action)
  Description: This function is used to create link for Plugin Settings.
  Created On: 02-05-2017 17:19
  Created By: tech-banker Team
 */

function captcha_booster_settings_action_links($action) {
   global $wpdb;
   $user_role_permission = get_users_capabilities_captcha_booster();
   $settings_link = '<a href = "' . admin_url('admin.php?page=cpb_captcha_booster') . '">' . "Settings" . '</a>';
   array_unshift($action, $settings_link);
   return $action;
}

$version = get_option("captcha_booster_version_number");
if ($version >= "2.0.1") {

   /*
     Function Name: get_users_capabilities_captcha_booster
     Parameters: No
     Description: This function is used to get users capabilities.
     Created On: 21-10-2016 15:21
     Created By: Tech Banker Team
    */

   function get_users_capabilities_captcha_booster() {
      global $wpdb;
      $capabilities = $wpdb->get_var
          (
          $wpdb->prepare
              (
              "SELECT meta_value FROM " . captcha_booster_meta() . "
					WHERE meta_key = %s", "roles_and_capabilities"
          )
      );
      $core_roles = array(
          "manage_options",
          "edit_plugins",
          "edit_posts",
          "publish_posts",
          "publish_pages",
          "edit_pages",
          "read"
      );
      $unserialized_capabilities = unserialize($capabilities);
      return isset($unserialized_capabilities["capabilities"]) ? $unserialized_capabilities["capabilities"] : $core_roles;
   }

   /*
     Function Name: backend_js_css_for_captcha_booster
     Parameters: No
     Description: This function is used for including js and css files for backend.
     Created On: 25-10-2016 17:03
     Created By: Tech Banker Team
    */

   if (is_admin()) {

      function backend_js_css_for_captcha_booster() {
         $pages_captcha_booster = array
             (
             "cpb_wizard_captcha_booster",
             "cpb_captcha_booster",
             "cpb_error_message",
             "cpb_display_settings",
             "cpb_live_traffic",
             "cpb_login_logs",
             "cpb_visitor_logs",
             "cpb_blocking_options",
             "cpb_manage_ip_addresses",
             "cpb_manage_ip_ranges",
             "cpb_country_blocks",
             "cpb_alert_setup",
             "cpb_other_settings",
             "cpb_email_templates",
             "cpb_roles_and_capabilities",
             "cpb_feature_requests",
             "cpb_system_information",
             "cpb_premium_editions"
         );
         if (in_array(isset($_REQUEST["page"]) ? esc_attr($_REQUEST["page"]) : "", $pages_captcha_booster)) {
            wp_enqueue_script("jquery");
            wp_enqueue_script("jquery-ui-datepicker");
            wp_enqueue_script("captcha-booster-custom.js", plugins_url("assets/global/plugins/custom/js/custom.js", __FILE__));
            wp_enqueue_script("captcha-booster-validate.js", plugins_url("assets/global/plugins/validation/jquery.validate.js", __FILE__));
            wp_enqueue_script("captcha-booster-datatables.js", plugins_url("assets/global/plugins/datatables/media/js/jquery.datatables.js", __FILE__));
            wp_enqueue_script("captcha-booster-fngetfilterednodes.js", plugins_url("assets/global/plugins/datatables/media/js/fngetfilterednodes.js", __FILE__));
            wp_enqueue_script("captcha-booster-toastr.js", plugins_url("assets/global/plugins/toastr/toastr.js", __FILE__));
            wp_enqueue_script("captcha-booster-colpick.js", plugins_url("assets/global/plugins/colorpicker/colpick.js", __FILE__));
            if (is_ssl()) {
               wp_enqueue_script("captcha-booster-maps_script.js", "https://maps.googleapis.com/maps/api/js?v=3&libraries=places&key=AIzaSyC4rVG7IsNk9pKUO_uOZuxQO4FmF6z03Ks");
            } else {
               wp_enqueue_script("captcha-booster-maps_script.js", "http://maps.googleapis.com/maps/api/js?v=3&libraries=places&key=AIzaSyC4rVG7IsNk9pKUO_uOZuxQO4FmF6z03Ks");
            }
            wp_enqueue_style("captcha-booster-simple-line-icons.css", plugins_url("assets/global/plugins/icons/icons.css", __FILE__));
            wp_enqueue_style("captcha-booster-components.css", plugins_url("assets/global/css/components.css", __FILE__));
            wp_enqueue_style("captcha-booster-custom.css", plugins_url("assets/admin/layout/css/captcha-booster-custom.css", __FILE__));
            if (is_rtl()) {
               wp_enqueue_style("captcha-booster-bootstrap.css", plugins_url("assets/global/plugins/custom/css/custom-rtl.css", __FILE__));
               wp_enqueue_style("captcha-booster-layout.css", plugins_url("assets/admin/layout/css/layout-rtl.css", __FILE__));
               wp_enqueue_style("captcha-booster-tech-banker-custom.css", plugins_url("assets/admin/layout/css/tech-banker-custom-rtl.css", __FILE__));
            } else {
               wp_enqueue_style("captcha-booster-bootstrap.css", plugins_url("assets/global/plugins/custom/css/custom.css", __FILE__));
               wp_enqueue_style("captcha-booster-layout.css", plugins_url("assets/admin/layout/css/layout.css", __FILE__));
               wp_enqueue_style("captcha-booster-tech-banker-custom.css", plugins_url("assets/admin/layout/css/tech-banker-custom.css", __FILE__));
            }
            wp_enqueue_style("captcha-booster-default.css", plugins_url("assets/admin/layout/css/themes/default.css", __FILE__));
            wp_enqueue_style("captcha-booster-toastr.min.css", plugins_url("assets/global/plugins/toastr/toastr.css", __FILE__));
            wp_enqueue_style("captcha-booster-jquery-ui.css", plugins_url("assets/global/plugins/datepicker/jquery-ui.css", __FILE__), false, "2.0", false);
            wp_enqueue_style("captcha-booster-datatables.foundation.css", plugins_url("assets/global/plugins/datatables/media/css/datatables.foundation.css", __FILE__));
            wp_enqueue_style("captcha-booster-colpick.css", plugins_url("assets/global/plugins/colorpicker/colpick.css", __FILE__));
            wp_enqueue_style("captcha-booster-premium-edition.css", plugins_url("assets/admin/layout/css/premium-edition.css", __FILE__));
         }
      }

      add_action("admin_enqueue_scripts", "backend_js_css_for_captcha_booster");
   }

   /*
     Function Name: js_frontend_for_captcha_booster
     Parameters: No
     Description: This function is used for including js files for frontend.
     Created On: 25-06-2016 12:36
     Created By: Tech Banker Team
    */

   function js_frontend_for_captcha_booster() {
      wp_enqueue_script("jquery");
      wp_enqueue_script("captcha-booster-front-end-script.js", plugins_url("assets/global/plugins/custom/js/front-end-script.js", __FILE__));
   }

   /*
     Function Name: create_sidebar_menu_for_captcha_booster
     Parameters: No
     Description: This function is used to create Admin Sidebar Menus.
     Created On: 25-06-2016 12:38
     Created By: Tech Banker Team
    */

   function create_sidebar_menu_for_captcha_booster() {
      global $wpdb, $current_user;
      $user_role_permission = get_users_capabilities_captcha_booster();
      if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php")) {
         include CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php";
      }
      if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "lib/sidebar-menu.php")) {
         include_once CAPTCHA_BOOSTER_DIR_PATH . "lib/sidebar-menu.php";
      }
   }

   /*
     Function name: create_topbar_menu_for_captcha_booster
     Parameters: No
     Description: This function is used to create Topbar Menus.
     Created On: 19-06-2016 12:38
     Created By: Tech Banker Team
    */

   function create_topbar_menu_for_captcha_booster() {
      global $wpdb, $current_user, $wp_admin_bar;
      $roles_and_capabilities = $wpdb->get_var
          (
          $wpdb->prepare
              (
              "SELECT meta_value FROM " . captcha_booster_meta() .
              " WHERE meta_key = %s", "roles_and_capabilities"
          )
      );
      $roles_and_capabilities_data = unserialize($roles_and_capabilities);

      if (esc_attr($roles_and_capabilities_data["show_captcha_booster_top_bar_menu"]) == "enable") {
         $user_role_permission = get_users_capabilities_captcha_booster();
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php")) {
            include CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php";
         }
         if (get_option("captcha-booster-wizard-set-up")) {
            if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "lib/admin-bar-menu.php")) {
               include_once CAPTCHA_BOOSTER_DIR_PATH . "lib/admin-bar-menu.php";
            }
         }
      }
   }

   /*
     Function Name: helper_file_for_captcha_booster
     Parameters: No
     Description: This function is used to create Class and Functions to perform operations.
     Created On: 25-06-2016 12:23
     Created By: Tech Banker Team
    */

   function helper_file_for_captcha_booster() {
      global $wpdb;
      $user_role_permission = get_users_capabilities_captcha_booster();
      if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "lib/helper.php")) {
         include_once CAPTCHA_BOOSTER_DIR_PATH . "lib/helper.php";
      }
   }

   /*
     Function Name: ajax_register_for_captcha_booster
     Parameters: No
     Description: This function is used to Register Ajax.
     Created On: 25-06-2016 12:32
     Created By: Tech Banker Team
    */

   function ajax_register_for_captcha_booster() {
      global $wpdb;
      $user_role_permission = get_users_capabilities_captcha_booster();
      if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php")) {
         include CAPTCHA_BOOSTER_DIR_PATH . "includes/translations.php";
      }
      if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "lib/action-library.php")) {
         include_once CAPTCHA_BOOSTER_DIR_PATH . "lib/action-library.php";
      }
   }

   /*
     Function Name: validate_ip_captcha_booster
     Parameters: No
     description: This function is used for validating ip address.
     Created on: 29-09-2015 10:56
     Created By: Tech Banker Team
    */

   function validate_ip_captcha_booster($ip) {
      if (strtolower($ip) === "unknown") {
         return false;
      }
      $ip = ip2long($ip);

      if ($ip !== false && $ip !== -1) {
         $ip = sprintf("%u", $ip);

         if ($ip >= 0 && $ip <= 50331647) {
            return false;
         }
         if ($ip >= 167772160 && $ip <= 184549375) {
            return false;
         }
         if ($ip >= 2130706432 && $ip <= 2147483647) {
            return false;
         }
         if ($ip >= 2851995648 && $ip <= 2852061183) {
            return false;
         }
         if ($ip >= 2886729728 && $ip <= 2887778303) {
            return false;
         }
         if ($ip >= 3221225984 && $ip <= 3221226239) {
            return false;
         }
         if ($ip >= 3232235520 && $ip <= 3232301055) {
            return false;
         }
         if ($ip >= 4294967040) {
            return false;
         }
      }
      return true;
   }

   /*
     Function Name: getIpAddress_for_captcha_booster
     Parameters: No
     Description: This function returns the IP Address of the user.
     Created On: 25-06-2016 12:32
     Created By: Tech Banker Team
    */

   function getIpAddress_for_captcha_booster() {
      static $ip = null;
      if (isset($ip)) {
         return $ip;
      }

      global $wpdb;
      $data = $wpdb->get_var
          (
          $wpdb->prepare
              (
              "SELECT meta_value FROM " . captcha_booster_meta() . "
					WHERE meta_key=%s", "other_settings"
          )
      );
      $other_settings_data = unserialize($data);

      switch ($other_settings_data["ip_address_fetching_method"]) {
         case "REMOTE_ADDR":
            if (isset($_SERVER["REMOTE_ADDR"])) {
               if (!empty($_SERVER["REMOTE_ADDR"]) && validate_ip_captcha_booster($_SERVER["REMOTE_ADDR"])) {
                  $ip = $_SERVER["REMOTE_ADDR"];
                  return $ip;
               }
            }
            break;

         case "HTTP_X_FORWARDED_FOR":
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && !empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
               if (strpos($_SERVER["HTTP_X_FORWARDED_FOR"], ",") !== false) {
                  $iplist = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"]);
                  foreach ($iplist as $ip_address) {
                     if (validate_ip_captcha_booster($ip_address)) {
                        $ip = $ip_address;
                        return $ip;
                     }
                  }
               } else {
                  if (validate_ip_captcha_booster($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                     $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
                     return $ip;
                  }
               }
            }
            break;

         case "HTTP_X_REAL_IP":
            if (isset($_SERVER["HTTP_X_REAL_IP"])) {
               if (!empty($_SERVER["HTTP_X_REAL_IP"]) && validate_ip_captcha_booster($_SERVER["HTTP_X_REAL_IP"])) {
                  $ip = $_SERVER["HTTP_X_REAL_IP"];
                  return $ip;
               }
            }
            break;

         case "HTTP_CF_CONNECTING_IP":
            if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
               if (!empty($_SERVER["HTTP_CF_CONNECTING_IP"]) && validate_ip_captcha_booster($_SERVER["HTTP_CF_CONNECTING_IP"])) {
                  $ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
                  return $ip;
               }
            }
            break;

         default:
            if (isset($_SERVER["HTTP_CLIENT_IP"])) {
               if (!empty($_SERVER["HTTP_CLIENT_IP"]) && validate_ip_captcha_booster($_SERVER["HTTP_CLIENT_IP"])) {
                  $ip = $_SERVER["HTTP_CLIENT_IP"];
                  return $ip;
               }
            }
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && !empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
               if (strpos($_SERVER["HTTP_X_FORWARDED_FOR"], ",") !== false) {
                  $iplist = explode(",", $_SERVER["HTTP_X_FORWARDED_FOR"]);
                  foreach ($iplist as $ip_address) {
                     if (validate_ip_captcha_booster($ip_address)) {
                        $ip = $ip_address;
                        return $ip;
                     }
                  }
               } else {
                  if (validate_ip_captcha_booster($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                     $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
                     return $ip;
                  }
               }
            }
            if (isset($_SERVER["HTTP_X_FORWARDED"])) {
               if (!empty($_SERVER["HTTP_X_FORWARDED"]) && validate_ip_captcha_booster($_SERVER["HTTP_X_FORWARDED"])) {
                  $ip = $_SERVER["HTTP_X_FORWARDED"];
                  return $ip;
               }
            }
            if (isset($_SERVER["HTTP_X_CLUSTER_CLIENT_IP"])) {
               if (!empty($_SERVER["HTTP_X_CLUSTER_CLIENT_IP"]) && validate_ip_captcha_booster($_SERVER["HTTP_X_CLUSTER_CLIENT_IP"])) {
                  $ip = $_SERVER["HTTP_X_CLUSTER_CLIENT_IP"];
                  return $ip;
               }
            }
            if (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
               if (!empty($_SERVER["HTTP_FORWARDED_FOR"]) && validate_ip_captcha_booster($_SERVER["HTTP_FORWARDED_FOR"])) {
                  $ip = $_SERVER["HTTP_FORWARDED_FOR"];
                  return $ip;
               }
            }
            if (isset($_SERVER["HTTP_FORWARDED"])) {
               if (!empty($_SERVER["HTTP_FORWARDED"]) && validate_ip_captcha_booster($_SERVER["HTTP_FORWARDED"])) {
                  $ip = $_SERVER["HTTP_FORWARDED"];
                  return $ip;
               }
            }
            if (isset($_SERVER["REMOTE_ADDR"])) {
               if (!empty($_SERVER["REMOTE_ADDR"]) && validate_ip_captcha_booster($_SERVER["REMOTE_ADDR"])) {
                  $ip = $_SERVER["REMOTE_ADDR"];
                  return $ip;
               }
            }
            break;
      }
      return "127.0.0.1";
   }

   /*
     Function name: get_ip_location_captcha_booster
     Parameters: yes ($ip_address)
     Description: This function returns the location of the IP Address.
     Created On: 25-06-2016 12:32
     Created By: Tech Banker Team
    */

   function get_ip_location_captcha_booster($ip_Address) {
      $core_data = '{"ip":"0.0.0.0","country_code":"","country_name":"","region_code":"","region_name":"","city":"","latitude":0,"longitude":0}';
      $apiCall = tech_banker_services_url . "/api/getipaddress.php?ip_address=" . $ip_Address;
      $jsonData = @file_get_contents($apiCall);
      return json_decode($jsonData);
   }

   /*
     Function Name: blocking_visitors_captcha_booster
     Parameters: no
     Description: This function is used to Block IP Address.
     Created On: 25-06-2016 12:32
     Created By: Tech Banker Team
    */

   function blocking_visitors_captcha_booster() {
      global $wpdb;
      $count_ip = 0;
      $flag = 0;
      $ip_address = getIpAddress_for_captcha_booster() == "::1" ? ip2long("127.0.0.1") : ip2long(getIpAddress_for_captcha_booster());
      $location = get_ip_location_captcha_booster(long2ip($ip_address));

      $error_message_data = $wpdb->get_var
          (
          $wpdb->prepare
              (
              "SELECT meta_value FROM " . captcha_booster_meta() . " WHERE meta_key = %s", "error_message"
          )
      );
      $error_message_unserialized_data = unserialize($error_message_data);

      $meta_values_ip_blocks = $wpdb->get_results
          (
          $wpdb->prepare
              (
              "SELECT meta_key,meta_value FROM " . captcha_booster_meta() .
              " WHERE meta_key IN(%s,%s)", "block_ip_address", "block_ip_range"
          )
      );
      foreach ($meta_values_ip_blocks as $data) {
         $ip_address_data_array = unserialize($data->meta_value);
         if ($data->meta_key == "block_ip_address") {
            if ($ip_address_data_array["ip_address"] == $ip_address) {
               $count_ip = 1;
               break;
            }
         } else {
            $ip_range_address = explode(",", $ip_address_data_array["ip_range"]);
            if ($ip_address >= $ip_range_address[0] && $ip_address <= $ip_range_address[1]) {
               $flag = 1;
               break;
            }
         }
      }
      if ($count_ip == 1 || $flag == 1) {
         if ($count_ip == 1) {
            $replace_address_data = str_replace("[ip_address]", long2ip($ip_address), $error_message_unserialized_data["for_blocked_ip_address_error"]);
            wp_die($replace_address_data);
         } else {
            $replace_range = str_replace("[ip_range]", long2ip($ip_range_address[0]) . "-" . long2ip($ip_range_address[1]), $error_message_unserialized_data["for_blocked_ip_range_error"]);
            wp_die($replace_range);
         }
      }
   }

   /*
     Function Name: wp_schedule_captcha_booster
     Parameters: Yes($cron_name,$blocked_time)
     Description: This function is used to Create Schedules.
     Created On: 02-07-2016 4:00
     Created By: Tech Banker Team
    */

   function wp_schedule_captcha_booster($cron_name, $blocked_time) {
      if (!wp_next_scheduled($cron_name)) {
         switch ($blocked_time) {
            case "1Hour":
               $this_time = 60 * 60;
               break;

            case "12Hour":
               $this_time = 12 * 60 * 60;
               break;

            case "24hours":
               $this_time = 24 * 60 * 60;
               break;

            case "48hours":
               $this_time = 2 * 24 * 60 * 60;
               break;

            case "week":
               $this_time = 7 * 24 * 60 * 60;
               break;

            case "month":
               $this_time = 30 * 24 * 60 * 60;
               break;
         }
      }
      wp_schedule_event(time() + $this_time, $blocked_time, $cron_name);
   }

   $scheulers = _get_cron_array();
   $current_scheduler = array();

   foreach ($scheulers as $value => $key) {
      $arr_key = array_keys($key);
      foreach ($arr_key as $value) {
         array_push($current_scheduler, $value);
      }
   }

   if (isset($current_scheduler[0])) {
      if (!defined("scheduler_name"))
         define("scheduler_name", $current_scheduler[0]);

      if (strstr($current_scheduler[0], "ip_address_unblocker_")) {
         add_action($current_scheduler[0], "unblock_script_captcha_booster");
      } elseif (strstr($current_scheduler[0], "ip_range_unblocker_")) {
         add_action($current_scheduler[0], "unblock_script_captcha_booster");
      }
   }

   /*
     Function Name: unblock_script_captcha_booster
     Parameters: no
     Description: This function is used to Unblock IP Address.
     Created On: 02-07-2016 4:20
     Created By: Tech Banker Team
    */

   function unblock_script_captcha_booster() {
      if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "lib/unblock-script.php")) {
         $nonce_unblock_script = wp_create_nonce("unblock_script");
         global $wpdb;
         include_once CAPTCHA_BOOSTER_DIR_PATH . "lib/unblock-script.php";
      }
   }

   /*
     Function Name: wp_unschedule_captcha_booster
     Parameters: Yes($cron_name)
     Description: This function is used to Unschedule a previously scheduled cron job.
     Created On: 04-07-2016 4:30
     Created By: Tech Banker Team
    */

   function wp_unschedule_captcha_booster($cron_name) {
      if (wp_next_scheduled($cron_name)) {
         $db_cron = wp_next_scheduled($cron_name);
         wp_unschedule_event($db_cron, $cron_name);
      }
   }

   /*
     Function name: captcha_booster_visitor_logs
     Parameters:no
     Description: This function is used to insert Visitor Logs data.
     Created On: 14-07-2016 10:52
     Created By: Tech Banker Team
    */

   function captcha_booster_visitor_logs() {
      if (!is_admin() && !defined("DOING_CRON")) {
         global $wpdb, $current_user;
         $username = $current_user->user_login;
         $parent_id = $wpdb->get_var
             (
             $wpdb->prepare
                 (
                 "SELECT id FROM " . captcha_booster() . "
						WHERE type = %s", "logs"
             )
         );
         $ip = getIpAddress_for_captcha_booster();
         $ip_address = $ip == "::1" ? ip2long("127.0.0.1") : ip2long($ip);
         $get_ip = get_ip_location_captcha_booster(long2ip($ip_address));

         $insert_live_traffic = array();
         $insert_live_traffic["type"] = "visitor_logs";
         $insert_live_traffic["parent_id"] = $parent_id;
         $wpdb->insert(captcha_booster(), $insert_live_traffic);

         $last_id = $wpdb->insert_id;

         $insert_live_traffic = array();
         $insert_live_traffic["username"] = $username;
         $insert_live_traffic["user_ip_address"] = $ip_address;
         $insert_live_traffic["resources"] = isset($_SERVER["REQUEST_URI"]) ? esc_attr($_SERVER["REQUEST_URI"]) : "";
         $insert_live_traffic["http_user_agent"] = isset($_SERVER["HTTP_USER_AGENT"]) ? esc_attr($_SERVER["HTTP_USER_AGENT"]) : "";
         $location = $get_ip->country_name == "" && $get_ip->city == "" ? "" : $get_ip->country_name == "" ? "" : $get_ip->city == "" ? $get_ip->country_name : $get_ip->city . ", " . $get_ip->country_name;
         $insert_live_traffic["location"] = $location;
         $insert_live_traffic["latitude"] = $get_ip->latitude;
         $insert_live_traffic["longitude"] = $get_ip->longitude;
         $insert_live_traffic["date_time"] = CAPTCHA_BOOSTER_LOCAL_TIME;
         $insert_live_traffic["meta_id"] = $last_id;

         $insert_data = array();
         $insert_data["meta_id"] = $last_id;
         $insert_data["meta_key"] = "visitor_logs_data";
         $insert_data["meta_value"] = serialize($insert_live_traffic);
         $wpdb->insert(captcha_booster_meta(), $insert_data);
      }
   }

   /*
     Function Name: cron_scheduler_for_intervals_captcha_booster
     Parameters: Yes($schedules)
     Description: This function is used to cron scheduler for intervals.
     Created On: 03-10-2016 17:12
     Created By: Tech Banker Team
    */

   function cron_scheduler_for_intervals_captcha_booster($schedules) {
      $schedules["1Hour"] = array("interval" => 60 * 60, "display" => "Every 1 Hour");
      $schedules["12Hour"] = array("interval" => 60 * 60 * 12, "display" => "Every 12 Hours");
      $schedules["Daily"] = array("interval" => 60 * 60 * 24, "display" => "Daily");
      $schedules["24hours"] = array("interval" => 60 * 60 * 24, "display" => "Every 24 Hours");
      $schedules["48hours"] = array("interval" => 60 * 60 * 48, "display" => "Every 48 Hours");
      $schedules["week"] = array("interval" => 60 * 60 * 24 * 7, "display" => "Every 1 Week");
      $schedules["month"] = array("interval" => 60 * 60 * 24 * 30, "display" => "Every 1 Month");
      return $schedules;
   }

   /*
     Function name:call_captcha_booster
     Parameter: no
     Description: This function is used to Manage Captcha Settings for frontend.
     Created On: 14-07-2016 11:01
     Created By: Tech Banker Team
    */

   function call_captcha_booster() {
      global $wpdb;
      $captcha_type = $wpdb->get_results
          (
          $wpdb->prepare
              (
              "SELECT * FROM " . captcha_booster_meta() . "
					WHERE meta_key = %s", "captcha_type"
          )
      );
      $captcha_array = array();
      foreach ($captcha_type as $row) {
         $captcha_array = unserialize($row->meta_value);
      }
      if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/common-functions.php")) {
         include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/common-functions.php";
      }
      if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/translations-frontend.php")) {
         include CAPTCHA_BOOSTER_DIR_PATH . "includes/translations-frontend.php";
      }
      if ($captcha_array["captcha_type_text_logical"] == "logical_captcha") {
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/logical-captcha.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/logical-captcha.php";
         }
      } elseif ($captcha_array["captcha_type_text_logical"] == "text_captcha") {
         if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "includes/text-captcha.php")) {
            include_once CAPTCHA_BOOSTER_DIR_PATH . "includes/text-captcha.php";
         }
         if (isset($_REQUEST["captcha_code"])) {
            if (file_exists(CAPTCHA_BOOSTER_DIR_PATH . "/includes/captcha-generate-code.php")) {
               include_once CAPTCHA_BOOSTER_DIR_PATH . "/includes/captcha-generate-code.php";
               die();
            }
         }
      }
   }

   /*
     Function Name: captcha_booster_UrlEncode
     Parameters:Yes($string)
     Description: This function is used to return the encoded string.
     Created On: 16-07-2016 10:50
     Created By: Tech Banker Team
    */

   function captcha_booster_UrlEncode($string) {
      $entities = array("%21", "%2A", "%27", "%28", "%29", "%3B", "%3A", "%40", "%26", "%3D", "%2B", "%24", "%2C", "%2F", "%3F", "%25", "%23", "%5B", "%5D");
      $replacements = array("!", "*", "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
      return str_replace($entities, $replacements, urlencode($string));
   }

   /*
     Function Name: plugin_load_textdomain_captcha_booster
     Parameters: No
     Description: This function is used to Load the plugin’s translated strings.
     Created On: 21-07-2016 04:50
     Created By: Tech Banker Team
    */

   function plugin_load_textdomain_captcha_booster() {
      load_plugin_textdomain("wp-captcha-booster", false, CAPTCHA_BOOSTER_PLUGIN_DIRNAME . "/languages");
   }

   /*
     Function Name: admin_functions_for_captcha_booster
     Parameters: No
     Description: This function is used to call functions on init hook.
     Created On: 25-07-2016 05:20
     Created By: Tech Banker Team
    */

   function admin_functions_for_captcha_booster() {
      install_script_for_captcha_booster();
      helper_file_for_captcha_booster();
   }

   /*
     Function Name: user_functions_for_captcha_booster
     Parameters: No
     Description: This function is used to call functions on init hook.
     Created On: 25-07-2016 05:20
     Created By: Tech Banker Team
    */

   function user_functions_for_captcha_booster() {
      plugin_load_textdomain_captcha_booster();
      js_frontend_for_captcha_booster();
      blocking_visitors_captcha_booster();
      global $wpdb;
      $other_settings_data = $wpdb->get_var
          (
          $wpdb->prepare
              (
              "SELECT meta_value FROM " . captcha_booster_meta() . " WHERE meta_key=%s", "other_settings"
          )
      );
      $other_settings_unserialized_data = unserialize($other_settings_data);
      if ($other_settings_unserialized_data["visitor_logs_monitoring"] == "enable" || $other_settings_unserialized_data["live_traffic_monitoring"] == "enable") {
         captcha_booster_visitor_logs();
      }
   }

   /*
     Function Name: deactivation_function_for_captcha_booster
     Description: This function is used for executing the code on deactivation.
     Parameters: No
     Created On: 12-04-2017 17:16
     Created By: Tech Banker Team
    */

   function deactivation_function_for_captcha_booster() {
      $type = get_option("captcha-booster-wizard-set-up");
      if ($type == "opt_in") {
         $plugin_info_captcha_booster = new plugin_info_captcha_booster();
         global $wp_version, $wpdb;
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
         $plugin_stat_data["event"] = "de-activate";
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

   /* Hooks */

   /* Add action for admin_functions_for_captcha_booster
     Description: This hook contains all admin_init functions.
     Created On: 27-07-2016 05:21
     Created By: Tech Banker Team
    */

   add_action("admin_init", "admin_functions_for_captcha_booster");

   /*
     Function Name: call_captcha_booster
     Parameters: No
     Description: This function is used for managing captcha on frontend.
     Created On: 24-10-2016 03:50
     Created By: Tech Banker Team
    */

   call_captcha_booster();

   /*
     add_action for create_sidebar_menu_for_captcha_booster
     Description: This hook is used for calling the function of sidebar menu in multisite case.
     Created On: 24-10-2016 03:50
     Created By: Tech Banker Team
    */

   add_action("network_admin_menu", "create_sidebar_menu_for_captcha_booster");

   /* Add action for create_sidebar_menu_for_captcha_booster
     Description: This hook is used for calling the function of sidebar menus.
     Created On: 25-06-2016 12:39
     Created By: Tech Banker Team
    */

   add_action("admin_menu", "create_sidebar_menu_for_captcha_booster");

   /* Add action for create_topbar_menu_for_captcha_booster
     Description: This hook is used for calling the function of top bar menu.
     Created On: 19-06-2016 12:38
     Created By: Tech Banker Team
    */

   add_action("admin_bar_menu", "create_topbar_menu_for_captcha_booster", 100);

   /* Add action for ajax_register_for_captcha_booster
     Description: This hook is used to calling the function of ajax register.
     Created On: 25-06-2016 12:32
     Created By: Tech Banker Team
    */

   add_action("wp_ajax_captcha_booster_action_library", "ajax_register_for_captcha_booster");

   /* add_action for user_functions_for_captcha_booster
     Description: This hook calling that function which contains function of init hook.
     Created On: 25-07-2016 05:20
     Created By: Tech Banker Team
    */

   add_action("init", "user_functions_for_captcha_booster");

   /*
     Add Filter for cron schedules
     Description: This hook is used for calling the function of cron schedulers jobs.
     Created On Date: 03-10-2016 17:40
     Created By: Tech Banker Team
    */

   add_filter("cron_schedules", "cron_scheduler_for_intervals_captcha_booster");

   /* register_deactivation_hook
     Description: This hook is used to sets the deactivation hook for a plugin.
     Created On: 12-04-2017 17:20
     Created by: Tech Banker Team
    */

   register_deactivation_hook(__FILE__, "deactivation_function_for_captcha_booster");
}
/*
  register_activation_hook
  Description: This hook is used for calling the function of install script
  Created On: 25-06-2016 11:33
  Created By: Tech Banker Team
 */

register_activation_hook(__FILE__, "install_script_for_captcha_booster");

/*
  add_action for install_script_for_captcha_booster
  Description: This hook is used for calling the function of install script
  Created On: 25-06-2016 11:33
  Created By: Tech Banker Team
 */

add_action("admin_init", "install_script_for_captcha_booster");

/* add_filter create Go Pro link for Captcha Booster
  Description: This hook is used for create link for premium Editions.
  Created On: 13-04-2017 11:20
  Created by: tech-banker Team
 */
add_filter("plugin_action_links_" . plugin_basename(__FILE__), "captcha_booster_action_links");

/* add_filter create Settings link for Captcha Booster
  Description: This hook is used for create link for Plugin Settings.
  Created On: 02-05-2017 17:12
  Created by: tech-banker Team
 */
add_filter("plugin_action_links_" . plugin_basename(__FILE__), "captcha_booster_settings_action_links", 10, 2);
/*
  Function Name: plugin_activate_captcha_booster
  Description: This function is used to add option.
  Parameters: No
  Created On: 26-04-2017 17:16
  Created By: Tech Banker Team
 */

function plugin_activate_captcha_booster() {
   add_option("captcha_booster_do_activation_redirect", true);
}

/*
  Function Name: captcha_booster_redirect
  Description: This function is used to redirect page.
  Parameters: No
  Created On: 26-04-2017 17:20
  Created By: Tech Banker Team
 */

function captcha_booster_redirect() {
   if (get_option("captcha_booster_do_activation_redirect", false)) {
      delete_option("captcha_booster_do_activation_redirect");
      wp_redirect(admin_url("admin.php?page=cpb_captcha_booster"));
      exit;
   }
}

/*
  register_activation_hook
  Description: This hook is used for calling the function plugin_activate_captcha_booster
  Created On: 26-04-2017 17:22
  Created By: Tech Banker Team
 */

register_activation_hook(__FILE__, "plugin_activate_captcha_booster");

/*
  add_action for captcha_booster_redirect
  Description: This hook is used for calling the function captcha_booster_redirect
  Created On: 26-04-2017 17:25
  Created By: Tech Banker Team
 */

add_action("admin_init", "captcha_booster_redirect");
