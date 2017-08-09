<?php
/**
 * This Template is used for managing Blocking Options.
 *
 * @author  Tech Banker
 * @package wp-captcha-booster/views/advance-security
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
   } elseif (advance_security_captcha_booster == "1") {
      $captcha_type_block = wp_create_nonce("captcha_booster_options");
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
               <a href="admin.php?page=cpb_blocking_options">
                  <?php echo $cpb_advance_security_menu; ?>
               </a>
               <span>></span>
            </li>
            <li>
               <span>
                  <?php echo $cpb_blocking_options; ?>
               </span>
            </li>
         </ul>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="portlet box vivid-green">
               <div class="portlet-title">
                  <div class="caption">
                     <i class="icon-custom-ban"></i>
                     <?php echo $cpb_blocking_options; ?>
                  </div>
               </div>
               <div class="portlet-body form">
                  <form id="ux_frm_blocking_options">
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
                              <?php echo $cpb_blocking_options_title; ?> :
                              <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_blocking_options_tooltip; ?>" data-placement="right"></i>
                              <span class="required" aria-required="true">*</span>
                           </label>
                           <select name="ux_ddl_auto_ip" id="ux_ddl_auto_ip" class="form-control" onchange="change_auto_ip_block_captcha_booster();">
                              <option value="enable"><?php echo $cpb_enable; ?></option>
                              <option value="disable"><?php echo $cpb_disable; ?></option>
                           </select>
                        </div>
                        <div id="ux_div_auto_ip">
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cpb_blocking_options_login_attempts_title; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_blocking_options_login_attempts_tooltip; ?>" data-placement="right"></i>
                                       <span class="required" aria-required="true">*</span>
                                    </label>
                                    <input type="text" class="form-control" name="ux_txt_login" id="ux_txt_login" maxlength="4" value="<?php echo isset($blocking_options_unserialized_data["maximum_login_attempt_in_a_day"]) ? intval($blocking_options_unserialized_data["maximum_login_attempt_in_a_day"]) : "" ?>"  onfocus="paste_only_digits_captcha_booster(this.id);" placeholder="<?php echo $cpb_blocking_options_login_attempts_placeholder; ?>">
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cpb_block_for_title; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_block_for_tooltip; ?>" data-placement="right"></i>
                                       <span class="required" aria-required="true">*</span>
                                    </label>
                                    <div class="input-icon-custom right">
                                       <select name="ux_ddl_blocked_for" id="ux_ddl_blocked_for" class="form-control">
                                          <option value="1Hour"><?php echo $cpb_one_hour; ?></option>
                                          <option value="12Hour"><?php echo $cpb_twelve_hours; ?></option>
                                          <option value="24hours"><?php echo $cpb_twenty_four_hours; ?></option>
                                          <option value="48hours"><?php echo $cpb_forty_eight_hours; ?></option>
                                          <option value="week"><?php echo $cpb_one_week; ?></option>
                                          <option value="month"><?php echo $cpb_one_month; ?></option>
                                          <option value="permanently"><?php echo $cpb_permanently; ?></option>
                                       </select>
                                    </div>
                                 </div>
                              </div>
                           </div>
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
               <a href="admin.php?page=cpb_blocking_options">
                  <?php echo $cpb_advance_security_menu; ?>
               </a>
               <span>></span>
            </li>
            <li>
               <span>
                  <?php echo $cpb_blocking_options; ?>
               </span>
            </li>
         </ul>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="portlet box vivid-green">
               <div class="portlet-title">
                  <div class="caption">
                     <i class="icon-custom-ban"></i>
                     <?php echo $cpb_blocking_options; ?>
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