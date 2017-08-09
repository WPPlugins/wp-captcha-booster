<?php
/**
 * This Template is used for saving error messages.
 *
 * @author  Tech Banker
 * @package wp-captcha-booster/views/captcha-setup
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
   } elseif (captcha_setup_captcha_booster == "1") {
      ?>
      <div class="page-bar">
         <ul class="page-breadcrumb">
            <li>
               <i class="icon-custom-home"></i>
               <a href="admin.php?page=cpb_captcha_booster">
                  <?php echo $cpb_captcha_booster_breadcrumb; ?>
               </a>
               <span>></span>
            </li>
            <li>
               <a href="admin.php?page=cpb_captcha_booster">
                  <?php echo $cpb_captcha_setup_menu; ?>
               </a>
               <span>></span>
            </li>
            <li>
               <span>
                  <?php echo $cpb_error_message_common; ?>
               </span>
            </li>
         </ul>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="portlet box vivid-green">
               <div class="portlet-title">
                  <div class="caption">
                     <i class="icon-custom-shield"></i>
                     <?php echo $cpb_error_message_common; ?>
                  </div>
               </div>
               <div class="portlet-body form">
                  <form id="ux_frm_error_message">
                     <div class="form-body">
                        <?php
                        if ($cpb_message_translate_help != "") {
                           ?>
                           <div class="note note-danger">
                              <h4 class="block">
                                 <?php echo $cpb_important_disclaimer; ?>
                              </h4>
                              <strong><?php echo $cpb_message_translate_help; ?><br/><?php echo $cpb_kindly_click; ?><a href="javascript:void(0);" data-popup-open="ux_open_popup_translator" class="custom_links" onclick="show_pop_up_captcha_booster();"><?php echo $cpb_message_translate_here; ?></a></strong>
                           </div>
                           <?php
                        }
                        ?>
                        <div class="form-group">
                           <label class="control-label">
                              <?php echo $cpb_error_message_login_failure_title ?> :
                              <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_error_message_login_failure_tooltip; ?>" data-placement="right"></i>
                              <span class="required" aria-required="true"> * <?php echo "( " . $cpb_premium . " )"; ?></span>
                           </label>
                           <textarea class="form-control" name="ux_txt_attempts" id="ux_txt_attempts"  placeholder="<?php echo $cpb_error_message_login_failure_placeholder; ?>"><?php echo isset($error_messages_unserialize_data["for_login_attempts_error"]) ? esc_html($error_messages_unserialize_data["for_login_attempts_error"]) : ""; ?></textarea>
                        </div>
                        <div class="form-group">
                           <label class="control-label">
                              <?php echo $cpb_error_message_invalid_captcha_title; ?> :
                              <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_error_message_invalid_captcha_tooltip; ?>" data-placement="right"></i>
                              <span class="required" aria-required="true"> * <?php echo "( " . $cpb_premium . " )"; ?></span>
                           </label>
                           <textarea class="form-control" name="ux_txt_invalid" id="ux_txt_invalid"  placeholder="<?php echo $cpb_error_message_invalid_captcha_placeholder; ?>"><?php echo isset($error_messages_unserialize_data["for_invalid_captcha_error"]) ? esc_html($error_messages_unserialize_data["for_invalid_captcha_error"]) : ""; ?></textarea>
                        </div>
                        <div class="form-group">
                           <label class="control-label">
                              <?php echo $cpb_error_message_blocked_ip_address_title; ?> :
                              <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_error_message_blocked_ip_address_tooltip; ?>" data-placement="right"></i>
                              <span class="required" aria-required="true"> * <?php echo "( " . $cpb_premium . " )"; ?></span>
                           </label>
                           <textarea class="form-control" name="ux_txt_ip_address" id="ux_txt_ip_address"  placeholder="<?php echo $cpb_error_message_blocked_ip_address_placeholder; ?>"><?php echo isset($error_messages_unserialize_data["for_blocked_ip_address_error"]) ? esc_html($error_messages_unserialize_data["for_blocked_ip_address_error"]) : ""; ?></textarea>
                        </div>
                        <div class="form-group">
                           <label class="control-label">
                              <?php echo $cpb_error_message_empty_captcha_title; ?>
                              <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_error_message_empty_captcha_tooltip; ?>" data-placement="right"></i>
                              <span class="required" aria-required="true"> * <?php echo "( " . $cpb_premium . " )"; ?></span>
                           </label>
                           <textarea class="form-control" name="ux_txt_empty" id="ux_txt_empty"  placeholder="<?php echo $cpb_error_message_empty_captcha_placeholder; ?>"><?php echo isset($error_messages_unserialize_data["for_captcha_empty_error"]) ? esc_html($error_messages_unserialize_data["for_captcha_empty_error"]) : ""; ?></textarea>
                        </div>
                        <div class="form-group">
                           <label class="control-label">
                              <?php echo $cpb_error_message_blocked_ip_range_title; ?> :
                              <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_error_message_blocked_ip_range_tooltip; ?>" data-placement="right"></i>
                              <span class="required" aria-required="true"> * <?php echo "( " . $cpb_premium . " )"; ?></span>
                           </label>
                           <textarea class="form-control" name="ux_txt_ip_range" id="ux_txt_ip_range"  placeholder="<?php echo $cpb_error_message_blocked_ip_range_placeholder; ?>"><?php echo isset($error_messages_unserialize_data["for_blocked_ip_range_error"]) ? esc_html($error_messages_unserialize_data["for_blocked_ip_range_error"]) : ""; ?></textarea>
                        </div>
                        <div class="form-group">
                           <label class="control-label">
                              <?php echo $cpb_error_messages_blocked_country_label; ?> :
                              <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_error_messages_blocked_country_tooltip; ?>"data-placement="right"></i>
                              <span class="required" aria-required="true"> * <?php echo "( " . $cpb_premium . " )"; ?></span>
                           </label>
                           <textarea class="form-control" name="ux_txt_blocked_country" id="ux_txt_blocked_country"  placeholder="<?php echo $cpb_error_messages_blocked_country_placeholder; ?>"><?php echo isset($error_messages_unserialize_data["for_blocked_country_error"]) ? trim(htmlspecialchars(htmlspecialchars_decode($error_messages_unserialize_data["for_blocked_country_error"]))) : ""; ?></textarea>
                        </div>
                        <div class="line-separator"></div>
                        <div class="form-actions">
                           <div class="pull-right">
                              <input type="submit" class="btn vivid-green" name="ux_btn_save_changes" id="ux_btn_save_changes" value="<?php echo $cpb_save_changes; ?>">
                           </div>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <?php
   } else {
      ?>
      <div class="page-bar">
         <ul class="page-breadcrumb">
            <li>
               <i class="icon-custom-home"></i>
               <a href="admin.php?page=cpb_captcha_booster">
                  <?php echo $cpb_captcha_booster_breadcrumb; ?>
               </a>
               <span>></span>
            </li>
            <li>
               <a href="admin.php?page=cpb_captcha_booster">
                  <?php echo $cpb_captcha_setup_menu; ?>
               </a>
               <span>></span>
            </li>
            <li>
               <span>
                  <?php echo $cpb_error_message_common; ?>
               </span>
            </li>
         </ul>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="portlet box vivid-green">
               <div class="portlet-title">
                  <div class="caption">
                     <i class="icon-custom-shield"></i>
                     <?php echo $cpb_error_message_common; ?>
                  </div>
               </div>
               <div class="portlet-body form">
                  <div class="form-body">
                     <strong><?php echo $cpb_user_access_message; ?></strong>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php
   }
}