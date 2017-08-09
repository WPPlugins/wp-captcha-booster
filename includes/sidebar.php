<?php
/**
 * This file is used for displaying sidebar menus.
 *
 * @author  Tech Banker
 * @package wp-captcha-booster/includes
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
      ?>
      <div class="page-sidebar-wrapper-tech-banker">
         <div class="page-sidebar-tech-banker navbar-collapse collapse">
            <div class="sidebar-menu-tech-banker">
               <ul class="page-sidebar-menu-tech-banker" data-slide-speed="200">
                  <div class="sidebar-search-wrapper" style="padding:20px;text-align:center">
                     <a class="plugin-logo" href="<?php echo tech_banker_beta_url; ?>" target="_blank">
                        <img src="<?php echo plugins_url("assets/global/img/logo.png", dirname(__FILE__)); ?>" alt="Captcha Booster"/>
                     </a>
                  </div>
                  <li id="ux_li_captcha_setup">
                     <a href="javascript:;">
                        <i class="icon-custom-grid"></i>
                        <span class="title">
                           <?php echo $cpb_captcha_setup_menu; ?>
                        </span>
                     </a>
                     <ul class="sub-menu">
                        <li id="ux_li_captcha_type">
                           <a href="admin.php?page=cpb_captcha_booster">
                              <i class="icon-custom-layers"></i>
                              <span class="title">
                                 <?php echo $cpb_captcha_booster_type_breadcrumb; ?>
                              </span>
                           </a>
                        </li>
                        <li id="ux_li_error_message">
                           <a href="admin.php?page=cpb_error_message">
                              <i class="icon-custom-shield"></i>
                              <span class="title">
                                 <?php echo $cpb_error_message_common; ?>
                              </span>
                              <span class="badge">Pro</span>
                           </a>
                        </li>
                        <li id="ux_li_display_settings">
                           <a href="admin.php?page=cpb_display_settings">
                              <i class=icon-custom-paper-clip></i>
                              <span class="title">
                                 <?php echo $cpb_display_settings_title; ?>
                              </span>
                           </a>
                        </li>
                     </ul>
                  </li>
                  <li id="ux_li_logs">
                     <a href="javascript:;">
                        <i class="icon-custom-docs"></i>
                        <span class="title">
                           <?php echo $cpb_logs_menu; ?>
                        </span>
                     </a>
                     <ul class="sub-menu">
                        <li id="ux_li_live_traffic">
                           <a href="admin.php?page=cpb_live_traffic">
                              <i class=icon-custom-directions></i>
                              <span class="title">
                                 <?php echo $cpb_live_traffic_title; ?>
                              </span>
                           </a>
                        </li>
                        <li id="ux_li_recent_login_logs">
                           <a href="admin.php?page=cpb_login_logs">
                              <i class="icon-custom-clock"></i>
                              <span class="title">
                                 <?php echo $cpb_recent_login_log_title; ?>
                              </span>
                           </a>
                        </li>
                        <li id="ux_li_visitor_logs">
                           <a href="admin.php?page=cpb_visitor_logs">
                              <i class="icon-custom-users"></i>
                              <span class="title">
                                 <?php echo $cpb_visitor_logs_title; ?>
                              </span>
                           </a>
                        </li>
                     </ul>
                  </li>
                  <li id="ux_li_advance_security">
                     <a href="javascript:;">
                        <i class="icon-custom-lock"></i>
                        <span class="title">
                           <?php echo $cpb_advance_security_menu; ?>
                        </span>
                     </a>
                     <ul class="sub-menu">
                        <li id="ux_li_blocking_options">
                           <a href="admin.php?page=cpb_blocking_options">
                              <i class="icon-custom-ban"></i>
                              <span class="title">
                                 <?php echo $cpb_blocking_options; ?>
                              </span>
                           </a>
                        </li>
                        <li id="ux_li_manage_ip_addresses">
                           <a href="admin.php?page=cpb_manage_ip_addresses">
                              <i class="icon-custom-globe"></i>
                              <span class="title">
                                 <?php echo $cpb_manage_ip_addresses; ?>
                              </span>
                           </a>
                        </li>
                        <li id="ux_li_manage_ip_ranges">
                           <a href="admin.php?page=cpb_manage_ip_ranges">
                              <i class="icon-custom-wrench"></i>
                              <span class="title">
                                 <?php echo $cpb_manage_ip_ranges; ?>
                              </span>
                           </a>
                        </li>
                        <li id="ux_li_country_blocks">
                           <a href="admin.php?page=cpb_country_blocks">
                              <i class="icon-custom-target"></i>
                              <span class="title">
                                 <?php echo $cpb_advance_country_block; ?>
                              </span>
                              <span class="badge">Pro</span>
                           </a>
                        </li>
                     </ul>
                  </li>
                  <li id="ux_li_general_settings">
                     <a href="javascript:;">
                        <i class="icon-custom-paper-clip"></i>
                        <span class="title">
                           <?php echo $cpb_general_settings_menu; ?>
                        </span>
                     </a>
                     <ul class="sub-menu">
                        <li id="ux_li_alert_setup">
                           <a href="admin.php?page=cpb_alert_setup">
                              <i class="icon-custom-bell"></i>
                              <span class="title">
                                 <?php echo $cpb_alert_setup_menu; ?>
                              </span>
                              <span class="badge">Pro</span>
                           </a>
                        </li>
                        <li id="ux_li_other_settings">
                           <a href="admin.php?page=cpb_other_settings">
                              <i class="icon-custom-settings"></i>
                              <span class="title">
                                 <?php echo $cpb_other_settings_menu; ?>
                              </span>
                           </a>
                        </li>
                     </ul>
                  </li>
                  <li id="ux_li_email_templates">
                     <a href="admin.php?page=cpb_email_templates">
                        <i class="icon-custom-link"></i>
                        <span class="title">
                           <?php echo $cpb_email_templates_menu; ?>
                        </span>
                        <span class="badge">Pro</span>
                     </a>
                  </li>

                  <li id="ux_li_roles_capabilities">
                     <a href="admin.php?page=cpb_roles_and_capabilities">
                        <i class="icon-custom-users"></i>
                        <span class="title">
                           <?php echo $cpb_roles_and_capabilities_menu; ?>
                        </span>
                        <span class="badge">Pro</span>
                     </a>
                  </li>
                  <li id="ux_li_feature_requests">
                     <a href="admin.php?page=cpb_feature_requests">
                        <i class="icon-custom-call-out"></i>
                        <span class="title">
                           <?php echo $cpb_feature_requests; ?>
                        </span>
                     </a>
                  </li>
                  <li id="ux_li_system_information">
                     <a href="admin.php?page=cpb_system_information">
                        <i class="icon-custom-screen-desktop"></i>
                        <span class="title">
                           <?php echo $cpb_system_information_menu; ?>
                        </span>
                     </a>
                  </li>
                  <li id="ux_li_premium_editions">
                     <a href="admin.php?page=cpb_premium_editions">
                        <i class="icon-custom-briefcase"></i>
                        <span class="title">
                           <?php echo $cpb_upgrade; ?>
                        </span>
                     </a>
                  </li>
               </ul>
            </div>
         </div>
      </div>
      <div class="page-content-wrapper">
         <div class="page-content">
            <div style="margin-bottom:10px;">
               <a href="http://beta.tech-banker.com/products/captcha-booster/" target="_blank">
                  <img src="<?php echo plugins_url("assets/global/img/captcha-booster-banner.png", dirname(__FILE__)); ?>" title="Captcha Booster" style="width: 100%;">
               </a>
            </div>
            <div class="container-fluid page-header-container">
               <div class="row">
                  <div class="col-md-3 page-header-column">
                     <h4>Get Started</h4>
                     <a class="btn" href="#" target="_blank">Watch Booster Video!</a>
                     <p>or <a href="http://beta.tech-banker.com/products/captcha-booster/user-guide/" target="_blank">read documentation here</a></p>
                  </div>
                  <div class="col-md-3 page-header-column">
                     <h4>Go Premium</h4>
                     <ul>
                        <li><a href="http://beta.tech-banker.com/products/captcha-booster/" target="_blank">Features</a></li>
                        <li><a href="http://beta.tech-banker.com/products/captcha-booster/demos/" target="_blank">Online Demos</a></li>
                        <li><a href="http://beta.tech-banker.com/products/captcha-booster/pricing/" target="_blank">Pricing Plans</a></li>
                     </ul>
                  </div>
                  <div class="col-md-3 page-header-column">
                     <h4>User Guide</h4>
                     <ul>
                        <li><a href="http://beta.tech-banker.com/products/captcha-booster/user-guide/" target="_blank">Documentation</a></li>
                        <li><a href="https://wordpress.org/support/plugin/wp-captcha-booster" target="_blank">Support Question!</a></li>
                        <li><a href="http://beta.tech-banker.com/contact-us/" target="_blank">Contact Us</a></li>
                     </ul>
                  </div>
                  <div class="col-md-3 page-header-column">
                     <h4>More Actions</h4>
                     <ul>
                        <li><a href="https://wordpress.org/support/plugin/wp-captcha-booster/reviews/?filter=5" target="_blank">Rate Us!</a></li>
                        <li><a href="http://beta.tech-banker.com/products/" target="_blank">Our Other Products</a></li>
                        <li><a href="http://beta.tech-banker.com/services/" target="_blank">Our Other Services</a></li>
                     </ul>
                  </div>
               </div>
            </div>
            <?php
         }
      }