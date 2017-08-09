<?php
/**
 * This Template is used for managing captcha type settings.
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
      $font_value = isset($meta_data_array["text_font"]) ? stripslashes(htmlspecialchars_decode($meta_data_array["text_font"])) : "";
      $signature_font_value = isset($meta_data_array["signature_font"]) ? stripslashes(htmlspecialchars_decode($meta_data_array["signature_font"])) : "";

      if ($meta_data_array["captcha_type_text_logical"] == "text_captcha") {
         $font_css = get_fonts_captcha_booster("http://fonts.googleapis.com/css?family=" . captcha_booster_UrlEncode($font_value));
         $signature_font_css = get_fonts_captcha_booster("http://fonts.googleapis.com/css?family=" . captcha_booster_UrlEncode($signature_font_value));
         preg_match_all("#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#", $font_css, $match);
         preg_match_all("#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#", $signature_font_css, $match_signature);
         foreach ($match as $val => $key) {
            if ($val == 0) {
               $arr = $key;
            }
         }
         foreach ($match_signature as $value => $key) {
            if ($value == 0) {
               $arr_sign = $key;
            }
         }
         $font_url = get_fonts_captcha_booster($arr[0]);
         $font_url_signature = get_fonts_captcha_booster($arr_sign[0]);
         file_put_contents(CAPTCHA_BOOSTER_DIR_PATH . "/fonts/font.ttf", $font_url);
         file_put_contents(CAPTCHA_BOOSTER_DIR_PATH . "/fonts/font-signature.ttf", $font_url_signature);
      }
      $captcha_type_update = wp_create_nonce("captcha_booster_file");
      $border_style = explode(",", isset($meta_data_array["border_style"]) ? esc_attr($meta_data_array["border_style"]) : "");
      $signature_style = explode(",", isset($meta_data_array["signature_style"]) ? esc_attr($meta_data_array["signature_style"]) : "");
      $arithmetic_actions = explode(",", isset($meta_data_array["arithmetic_actions"]) ? esc_attr($meta_data_array["arithmetic_actions"]) : "");
      $relational_actions = explode(",", isset($meta_data_array["relational_actions"]) ? esc_attr($meta_data_array["relational_actions"]) : "");
      $arrange_order = explode(",", isset($meta_data_array["arrange_order"]) ? esc_attr($meta_data_array["arrange_order"]) : "");
      $text_style = explode(",", isset($meta_data_array["text_style"]) ? esc_attr($meta_data_array["text_style"]) : "");
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
                  <?php echo $cpb_captcha_booster_type_breadcrumb; ?>
               </span>
            </li>
         </ul>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="portlet box vivid-green">
               <div class="portlet-title">
                  <div class="caption">
                     <i class="icon-custom-layers"></i>
                     <?php echo $cpb_captcha_booster_type_breadcrumb; ?>
                  </div>
               </div>
               <div class="portlet-body form">
                  <form id="ux_frm_text_captcha">
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
                              <?php echo $cpb_captcha_booster_type_breadcrumb; ?> :
                              <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_captcha_booster_type_tooltip; ?>" data-placement="right"></i>
                              <span class="required" aria-required="true">*</span>
                           </label>
                           <select name="ux_ddl_captcha_type" id="ux_ddl_captcha_type" class="form-control" onchange="change_captcha_type_captcha_booster();">
                              <option value="text_captcha"><?php echo $cpb_captcha_booster_text_captcha; ?></option>
                              <option value="logical_captcha" selected="selected"><?php echo $cpb_captcha_booster_logical_captcha; ?></option>
                           </select>
                        </div>
                        <div id="ux_div_text_captcha" style="display:none;">
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cpb_captcha_booster_character_title; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_captcha_booster_character_tooltip; ?>" data-placement="right"></i>
                                       <span class="required" aria-required="true">*</span>
                                    </label>
                                    <input type="text" class="form-control" name="ux_txt_character" maxlength="4" onfocus="paste_only_digits_captcha_booster(this.id);" id="ux_txt_character" value="<?php echo isset($meta_data_array["captcha_characters"]) ? (stripslashes(htmlspecialchars_decode(urldecode($meta_data_array["captcha_characters"])))) : "4"; ?>" placeholder="<?php echo $cpb_captcha_booster_character_placeholder; ?>">
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cpb_captcha_booster_string_type_title; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_captcha_booster_string_type_tooltip; ?>" data-placement="right"></i>
                                       <span class="required" aria-required="true">*</span>
                                    </label>
                                    <select name="ux_ddl_alphabets" id="ux_ddl_alphabets" class="form-control">
                                       <option value="alphabets_and_digits"><?php echo $cpb_captcha_booster_alphabets_digits; ?></option>
                                       <option value="only_alphabets"><?php echo $cpb_captcha_booster_only_alphabets; ?></option>
                                       <option value="only_digits"><?php echo $cpb_captcha_booster_only_digits; ?></option>
                                    </select>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cpb_captcha_booster_text_case_title; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_captcha_booster_text_case_tooltip; ?>" data-placement="right"></i>
                                       <span class="required" aria-required="true">*</span>
                                    </label>
                                    <select name="ux_ddl_case" id="ux_ddl_case" class="form-control">
                                       <option value="upper_case"><?php echo $cpb_captcha_booster_upper_case; ?></option>
                                       <option value="lower_case"><?php echo $cpb_captcha_booster_lower_case; ?></option>
                                       <option value="random"><?php echo $cpb_captcha_booster_random_case; ?></option>
                                    </select>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cpb_captcha_booster_case_sensitive_title; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_captcha_booster_case_sensitive_tooltip; ?>" data-placement="right"></i>
                                       <span class="required" aria-required="true">*</span>
                                    </label>
                                    <select name="ux_ddl_case_disable" id="ux_ddl_case_disable" class="form-control">
                                       <option value="enable"><?php echo $cpb_enable; ?></option>
                                       <option value="disable"><?php echo $cpb_disable; ?></option>
                                    </select>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cpb_captcha_booster_width_title; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_captcha_booster_width_tooltip; ?>" data-placement="right"></i>
                                       <span class="required" aria-required="true">*</span>
                                    </label>
                                    <input type="text" class="form-control" name="ux_txt_width" id="ux_txt_width" maxlength="4" onfocus="paste_only_digits_captcha_booster(this.id);" value="<?php echo isset($meta_data_array["captcha_width"]) ? (stripslashes(htmlspecialchars_decode(urldecode($meta_data_array["captcha_width"])))) : "180"; ?>" placeholder="<?php echo $cpb_captcha_booster_width_placeholder; ?>">
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cpb_captcha_booster_height_title; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_captcha_booster_height_tooltip; ?>" data-placement="right"></i>
                                       <span class="required" aria-required="true">*</span>
                                    </label>
                                    <input type="text" class="form-control" name="ux_txt_height" id="ux_txt_height" maxlength="4" onfocus="paste_only_digits_captcha_booster(this.id);" value="<?php echo isset($meta_data_array["captcha_height"]) ? (stripslashes(htmlspecialchars_decode(urldecode($meta_data_array["captcha_height"])))) : "60"; ?>" placeholder="<?php echo $cpb_captcha_booster_height_placeholder; ?>">
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cpb_captcha_booster_background_title; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_captcha_booster_background_tooltip; ?>" data-placement="right"></i>
                                       <span class="required" aria-required="true">* ( <?php echo $cpb_premium; ?> )</span>
                                    </label>
                                    <select name="ux_ddl_background" id="ux_ddl_background" class="form-control">
                                       <option value="bg1.gif" disabled="disabled"><?php echo $cpb_captcha_booster_background_pattern1; ?></option>
                                       <option value="bg2.gif" disabled="disabled"><?php echo $cpb_captcha_booster_background_pattern2; ?></option>
                                       <option value="bg3.jpg" disabled="disabled"><?php echo $cpb_captcha_booster_background_pattern3; ?></option>
                                       <option value="bg4.jpg" selected="selected"><?php echo $cpb_captcha_booster_background_pattern4; ?></option>
                                       <option value="bg5.jpg" disabled="disabled"><?php echo $cpb_captcha_booster_background_pattern5; ?></option>
                                       <option value="bg6.png" disabled="disabled"><?php echo $cpb_captcha_booster_background_pattern6; ?></option>
                                       <option value="bg7.gif" disabled="disabled"><?php echo $cpb_captcha_booster_background_pattern7; ?></option>
                                       <option value="bg8.gif" disabled="disabled"><?php echo $cpb_captcha_booster_background_pattern8; ?></option>
                                       <option value="bg9.gif" disabled="disabled"><?php echo $cpb_captcha_booster_background_pattern9; ?></option>
                                       <option value="bg10.gif" disabled="disabled"><?php echo $cpb_captcha_booster_background_pattern10; ?></option>
                                       <option value="bg11.gif" disabled="disabled"><?php echo $cpb_captcha_booster_background_pattern11; ?></option>
                                       <option value="bg12.gif" disabled="disabled"><?php echo $cpb_captcha_booster_background_pattern12; ?></option>
                                       <option value="bg13.gif" disabled="disabled"><?php echo $cpb_captcha_booster_background_pattern13; ?></option>
                                       <option value="bg14.gif" disabled="disabled"><?php echo $cpb_captcha_booster_background_pattern14; ?></option>
                                       <option value="bg15.gif" disabled="disabled"><?php echo $cpb_captcha_booster_background_pattern15; ?></option>
                                       <option value="bg16.gif" disabled="disabled"><?php echo $cpb_captcha_booster_background_pattern16; ?></option>
                                       <option value="bg17.jpg" disabled="disabled"><?php echo $cpb_captcha_booster_background_pattern17; ?></option>
                                       <option value="bg18.png" disabled="disabled"><?php echo $cpb_captcha_booster_background_pattern18; ?></option>
                                    </select>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cpb_captcha_booster_text_style_title; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_captcha_booster_text_style_tooltip; ?>" data-placement="right"></i>
                                       <span class="required" aria-required="true">* ( <?php echo $cpb_premium; ?> )</span>
                                    </label>
                                    <div class="input-icon-custom right">
                                       <select class="form-control custom-input-medium input-inline valid" id="ux_ddl_text_style_value" name="ux_ddl_text_style[]">
                                          <?php
                                          for ($flag = 0; $flag <= 99; $flag++) {
                                             if ($flag < 10) {
                                                ?>
                                                <option disabled="disabled" value="<?php echo $flag; ?>">0<?php echo $flag; ?> Px</option>
                                                <?php
                                             } else {
                                                $disable = $flag == 20 ? "" : "disabled=disabled";
                                                ?>
                                                <option <?php echo $disable; ?> value="<?php echo $flag; ?>"><?php echo $flag; ?> Px</option>
                                                <?php
                                             }
                                          }
                                          ?>
                                       </select>
                                       <input type="text" name="ux_ddl_text_style[]" disabled="disabled" id="ux_ddl_text_color"  class="form-control custom-input-medium input-inline valid" onblur="check_color_captcha_booster('#ux_ddl_text_color')" onfocus="cpb_colorpicker(this.id, this.value)" value="<?php echo isset($text_style[1]) ? ($text_style[1]) : "#cccccc"; ?>" placeholder="<?php echo $cpb_color_code; ?>">
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cpb_captcha_booster_text_font_title; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_captcha_booster_text_font_tooltip; ?>" data-placement="right"></i>
                                       <span class="required" aria-required="true">* ( <?php echo $cpb_premium; ?> )</span>
                                    </label>
                                    <select name="ux_ddl_text_font" id="ux_ddl_text_font" class="form-control">
                                       <?php
                                       foreach ($web_fonts_list as $key => $value) {
                                          $text = "";
                                          if ($value != "Roboto Condensed") {
                                             $text = "disabled";
                                          }
                                          ?>
                                          <option value="<?php echo $key; ?>" <?php
                                          if (isset($text)) {
                                             echo $text;
                                          }
                                          ?>><?php echo $value; ?></option>
                                                  <?php
                                               }
                                               ?>
                                    </select>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cpb_captcha_booster_border_style_title; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_captcha_booster_border_style_tooltip; ?>" data-placement="right"></i>
                                       <span class="required" aria-required="true">*</span>
                                    </label>
                                    <div class="input-icon-custom right">
                                       <select class="form-control input-width-25 input-inline" id="ux_ddl_border_style_value" name="ux_txt_border_style[]">
                                          <?php
                                          for ($flag = 0; $flag <= 99; $flag++) {
                                             if ($flag < 10) {
                                                ?>
                                                <option value="<?php echo $flag; ?>">0<?php echo $flag; ?> Px</option>
                                                <?php
                                             } else {
                                                ?>
                                                <option value="<?php echo $flag; ?>"><?php echo $flag; ?> Px</option>
                                                <?php
                                             }
                                          }
                                          ?>                                           </select>
                                       <select class="form-control input-width-27 input-inline" name="ux_txt_border_style[]" id="ux_ddl_border_style">
                                          <option value="solid"><?php echo $cpb_captcha_booster_border_solid; ?></option>
                                          <option value="dotted" ><?php echo $cpb_captcha_booster_border_dotted; ?></option>
                                          <option value="dashed"><?php echo $cpb_captcha_booster_border_dashed; ?></option>
                                       </select>
                                       <input type="text" name="ux_txt_border_style[]" id="ux_txt_border_text"  class="form-control input-normal input-inline" onblur="check_color_captcha_booster('#ux_txt_border_text')" onfocus="cpb_colorpicker(this.id, this.value)" value="<?php echo isset($meta_data_array["border_style"]) ? esc_attr($border_style[2]) : "#cccccc"; ?>" placeholder="<?php echo $cpb_color_code; ?>">
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cpb_captcha_booster_lines_title; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_captcha_booster_lines_tooltip; ?>" data-placement="right"></i>
                                       <span class="required" aria-required="true">*</span>
                                    </label>
                                    <input type="text" class="form-control" name="ux_txt_line" onfocus="paste_only_digits_captcha_booster(this.id);" id="ux_txt_line" maxlength="4" value="<?php echo isset($meta_data_array["lines"]) ? (stripslashes(htmlspecialchars_decode(urldecode($meta_data_array["lines"])))) : "" ?>" placeholder="<?php echo $cpb_captcha_booster_lines_placeholder; ?>" onblur="check_value_captcha_booster('#ux_txt_line');">
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cpb_captcha_booster_lines_color_title; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_captcha_booster_lines_color_tooltip; ?>" data-placement="right"></i>
                                       <span class="required" aria-required="true">*</span>
                                    </label>
                                    <input type="text" class="form-control" name="ux_txt_color" id="ux_txt_color" onblur="check_color_captcha_booster('#ux_txt_color');" onfocus="cpb_colorpicker(this.id, this.value)" value="<?php echo isset($meta_data_array["lines_color"]) ? (stripslashes(htmlspecialchars_decode(urldecode($meta_data_array["lines_color"])))) : "#cc1f1f" ?>" placeholder="<?php echo $cpb_color_code; ?>">
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cpb_captcha_booster_noise_level_title; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_captcha_booster_noise_level_tooltip; ?>" data-placement="right"></i>
                                       <span class="required" aria-required="true">*</span>
                                    </label>
                                    <input type="text" class="form-control" name="ux_txt_noise_level" id="ux_txt_noise_level" maxlength="4" onfocus="paste_only_digits_captcha_booster(this.id);" value="<?php echo isset($meta_data_array["noise_level"]) ? (stripslashes(htmlspecialchars_decode(urldecode($meta_data_array["noise_level"])))) : "" ?>" placeholder="<?php echo $cpb_captcha_booster_noise_level_placeholder; ?>" onblur="check_value_captcha_booster('#ux_txt_noise_level');">
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cpb_captcha_booster_noise_color_title; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_captcha_booster_noise_color_tooltip; ?>" data-placement="right"></i>
                                       <span class="required" aria-required="true">*</span>
                                    </label>
                                    <input type="text" class="form-control" name="ux_txt_noise_color" id="ux_txt_noise_color" onblur="check_color_captcha_booster('#ux_txt_noise_color');" onfocus="cpb_colorpicker(this.id, this.value)" value="<?php echo isset($meta_data_array["noise_color"]) ? (stripslashes(htmlspecialchars_decode(urldecode($meta_data_array["noise_color"])))) : "#cc1f1f" ?>" placeholder="<?php echo $cpb_color_code; ?>">
                                 </div>
                              </div>
                           </div>
                           <div class="form-group">
                              <label class="control-label">
                                 <?php echo $cpb_captcha_booster_text_transparency_title; ?> :
                                 <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_captcha_booster_text_transparency_tooltip; ?>" data-placement="right"></i>
                                 <span class="required" aria-required="true">*</span>
                              </label>
                              <input type="text" class="form-control" name="ux_txt_transperancy" id="ux_txt_transperancy" maxlength="4" onfocus="paste_only_digits_captcha_booster(this.id);" value="<?php echo isset($meta_data_array["text_transperancy"]) ? stripslashes(htmlspecialchars_decode(urldecode($meta_data_array["text_transperancy"]))) : "" ?>" placeholder="<?php echo $cpb_captcha_booster_text_transparency_placeholder; ?>" onblur="check_value_captcha_booster('#ux_txt_transperancy');">
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cpb_captcha_booster_signature_text_title; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_captcha_booster_signature_text_tooltip; ?>" data-placement="right"></i>
                                       <span class="required" aria-required="true">* ( <?php echo $cpb_premium; ?> )</span>
                                    </label>
                                    <input type="text" class="form-control" name="ux_txt_signature_text" id="ux_txt_signature_text" disabled="disabled" value= "Captcha Booster" placeholder="<?php echo $cpb_captcha_booster_signature_text_palceholder; ?>">
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cpb_captcha_booster_signature_style_title; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_captcha_booster_signature_style_tooltip; ?>" data-placement="right"></i>
                                       <span class="required" aria-required="true">* ( <?php echo $cpb_premium; ?> )</span>
                                    </label>
                                    <div class="input-icon-custom right">
                                       <select class="form-control custom-input-medium input-inline valid" name="ux_txt_signature_style[]" id="ux_ddl_signature_style_value">
                                          <?php
                                          for ($flag = 0; $flag <= 99; $flag++) {
                                             if ($flag < 10) {
                                                $disable_sign = $flag == 8 ? "" : "disabled=disabled";
                                                ?>
                                                <option <?php echo $disable_sign; ?>value="<?php echo $flag; ?>">0<?php echo $flag; ?> Px</option>
                                                <?php
                                             } else {
                                                ?>
                                                <option disabled="disabled" value="<?php echo $flag; ?>"><?php echo $flag; ?> Px</option>
                                                <?php
                                             }
                                          }
                                          ?>
                                       </select>
                                       <input name="ux_txt_signature_style[]" id="ux_txt_style_text" type="text" disabled="disabled" class="form-control custom-input-medium input-inline valid" onblur="check_color_captcha_booster('#ux_txt_style_text');" onfocus="cpb_colorpicker(this.id, this.value)" value="<?php echo isset($meta_data_array["signature_style"]) ? esc_attr($signature_style[1]) : "#cccccc" ?>" placeholder="<?php echo $cpb_color_code; ?>">
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cpb_captcha_booster_signature_font_title; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_captcha_booster_signature_font_tooltip; ?>" data-placement="right"></i>
                                       <span class="required" aria-required="true">* ( <?php echo $cpb_premium; ?> )</span>
                                    </label>
                                    <select name="ux_ddl_sign_font" id="ux_ddl_sign_font" class="form-control">
                                       <?php
                                       foreach ($web_fonts_list as $key => $value) {
                                          $text = "";
                                          if ($value != "Roboto Condensed") {
                                             $text = "disabled";
                                          }
                                          ?>
                                          <option value="<?php echo $key; ?>" <?php
                                          if (isset($text)) {
                                             echo $text;
                                          }
                                          ?>><?php echo $value; ?></option>
                                                  <?php
                                               }
                                               ?>
                                    </select>
                                 </div>
                              </div>
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <label class="control-label">
                                       <?php echo $cpb_captcha_booster_shadow_color_title; ?> :
                                       <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_captcha_booster_shadow_color_tooltip; ?>" data-placement="right"></i>
                                       <span class="required" aria-required="true">*</span>
                                    </label>
                                    <input type="text" class="form-control" name="ux_txt_shadow_color" id="ux_txt_shadow_color" onblur="check_color_captcha_booster('#ux_txt_shadow_color');" onfocus="cpb_colorpicker(this.id, this.value)" value="<?php echo isset($meta_data_array["text_shadow_color"]) ? (stripslashes(htmlspecialchars_decode(urldecode($meta_data_array["text_shadow_color"])))) : "#c722c7" ?>" placeholder="<?php echo $cpb_color_code; ?>">
                                 </div>
                              </div>
                           </div>
                        </div>
                        <div id="ux_div_logical_captcha" style="display:block;">
                           <div class="form-group">
                              <label class="control-label">
                                 <?php echo $cpb_captcha_booster_mathematical_title; ?> :
                                 <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_captcha_booster_mathematical_tooltip; ?>" data-placement="right"></i>
                                 <span class="required" aria-required="true">*</span>
                              </label>
                              <div class="row">
                                 <div class="col-md-4">
                                    <input type="radio" id="ux_rdl_radio_arithmetic"  value="arithmetic" class="form-control" name="ux_rdl_mathematical_captcha" <?php echo $meta_data_array["mathematical_operations"] == "arithmetic" ? "checked='checked'" : ""; ?> onclick="change_mathematical_captcha_booster('arithmetic');"><?php echo $cpb_captcha_booster_arithmetic; ?>
                                 </div>
                                 <div class="col-md-4">
                                    <input type="radio" id="ux_rdl_radio_relational" value="relational" class="form-control" name="ux_rdl_mathematical_captcha" <?php echo $meta_data_array["mathematical_operations"] == "relational" ? "checked='checked'" : ""; ?> onclick="change_mathematical_captcha_booster('relational');"><?php echo $cpb_captcha_booster_relational; ?>
                                    <span style="color:red !important;">( <?php echo $cpb_premium; ?> )</span>
                                 </div>
                                 <div class="col-md-4">
                                    <input type="radio" id="ux_rdl_radio_arrange_order" value="arrange_order"class="form-control" name="ux_rdl_mathematical_captcha" <?php echo $meta_data_array["mathematical_operations"] == "arrange_order" ? "checked='checked'" : ""; ?> onclick="change_mathematical_captcha_booster('arrange_order');"><?php echo $cpb_captcha_booster_arrange_title; ?>
                                    <span style="color:red !important;">( <?php echo $cpb_premium; ?> )</span>
                                 </div>
                              </div>
                           </div>
                           <div id="ux_div_arithmetic_captcha" style="display:block;">
                              <label class="control-label">
                                 <?php echo $cpb_captcha_booster_arithmetic_title; ?> :
                                 <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_captcha_booster_arithmetic_tooltip; ?>" data-placement="right"></i>
                                 <span class="required" aria-required="true">*</span>
                              </label>
                              <table class="table table-striped table-bordered table-margin-top" id="ux_tbl_arithmetic">
                                 <thead>
                                    <tr>
                                       <th class="control-label">
                                          <input type="checkbox" class="custom-chkbox-operation" name="ux_chk_arithmetic_action" id="ux_chk_addition_action" value="1" <?php echo isset($arithmetic_actions["0"]) && $arithmetic_actions["0"] == "1" ? "checked=checked" : ""; ?>><?php echo $cpb_captcha_booster_addition; ?>
                                       </th>
                                       <th class="control-label">
                                          <input type="checkbox" class="custom-chkbox-operation" name="ux_chk_arithmetic_action" id="ux_chk_subtraction_action" value="1" <?php echo isset($arithmetic_actions["1"]) && $arithmetic_actions["1"] == "1" ? "checked=checked" : ""; ?>><?php echo $cpb_captcha_booster_subtraction; ?>
                                       </th>
                                       <th class="control-label">
                                          <input type="checkbox" class="custom-chkbox-operation" name="ux_chk_arithmetic_action" id="ux_chk_multiplication_action" value="1" <?php echo isset($arithmetic_actions["2"]) && $arithmetic_actions["2"] == "1" ? "checked=checked" : ""; ?>><?php echo $cpb_captcha_booster_multiplication; ?>
                                       </th>
                                       <th class="control-label">
                                          <input type="checkbox" class="custom-chkbox-operation" name="ux_chk_arithmetic_action" id="ux_chk_division_action" value="1" <?php echo isset($arithmetic_actions["3"]) && $arithmetic_actions["3"] == "1" ? "checked=checked" : ""; ?>><?php echo $cpb_captcha_booster_division; ?>
                                       </th>
                                    </tr>
                                 </thead>
                              </table>
                           </div>
                           <div id="ux_div_relational_captcha" style="display:none;">
                              <label class="control-label">
                                 <?php echo $cpb_captcha_booster_relational_title; ?> :
                                 <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_captcha_booster_relational_tooltip; ?>" data-placement="right"></i>
                                 <span class="required" aria-required="true">*</span>
                              </label>
                              <table class="table table-striped table-bordered table-margin-top" id="ux_tbl_relational">
                                 <thead>
                                    <tr>
                                       <th class="control-label">
                                          <input type="checkbox" class="form-control" name="ux_chk_relational_action" id="ux_chk_largest_action" value="1" <?php echo isset($relational_actions["0"]) && $relational_actions["0"] == "1" ? "checked=checked" : ""; ?>><?php echo $cpb_captcha_booster_largest_number; ?>
                                          <span style="color:red !important;">( <?php echo $cpb_premium; ?> )</span>
                                       </th>
                                       <th class="control-label">
                                          <input type="checkbox" class="form-control" name="ux_chk_relational_action" id="ux_chk_smallest_action" value="1" <?php echo isset($relational_actions["1"]) && $relational_actions["1"] == "1" ? "checked=checked" : ""; ?>><?php echo $cpb_captcha_booster_smallest_number; ?>
                                          <span style="color:red !important;">( <?php echo $cpb_premium; ?> )</span>
                                       </th>
                                    </tr>
                                 </thead>
                              </table>
                           </div>
                           <div id="ux_div_arrange_captcha" style="display:none;">
                              <label class="control-label">
                                 <?php echo $cpb_captcha_booster_arrange_title; ?> :
                                 <i class="icon-custom-question tooltips" data-original-title="<?php echo $cpb_captcha_booster_arrange_tooltip; ?>" data-placement="right"></i>
                                 <span class="required" aria-required="true">*</span>
                              </label>
                              <table class="table table-striped table-bordered table-margin-top" id="ux_tbl_arrange">
                                 <thead>
                                    <tr>
                                       <th class="control-label">
                                          <input type="checkbox" class="form-control" name="ux_chk_arrange_action" id="ux_chk_arrange_action" value="1" <?php echo isset($arrange_order["0"]) && $arrange_order["0"] == "1" ? "checked=checked" : ""; ?>><?php echo $cpb_captcha_booster_ascending_order; ?>
                                          <span style="color:red !important;">( <?php echo $cpb_premium; ?> )</span>
                                       </th>
                                       <th class="control-label">
                                          <input type="checkbox" class="form-control" name="ux_chk_arrange_action" id="ux_chk_order_action" value="1" <?php echo isset($arrange_order["1"]) && $arrange_order["1"] == "1" ? "checked=checked" : ""; ?>><?php echo $cpb_captcha_booster_descending_order; ?>
                                          <span style="color:red !important;">( <?php echo $cpb_premium; ?> )</span>
                                       </th>
                                    </tr>
                                 </thead>
                              </table>
                           </div>
                        </div>
                        <div class="line-separator"></div>
                        <div class="form-actions">
                           <div class="pull-right">
                              <input type="submit" class="btn vivid-green" name="ux_btn_save_change" id="ux_btn_save_change" value="<?php echo $cpb_save_changes; ?>">
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
                  <?php echo $cpb_captcha_booster_type_breadcrumb; ?>
               </span>
            </li>
         </ul>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="portlet box vivid-green">
               <div class="portlet-title">
                  <div class="caption">
                     <i class="icon-custom-layers"></i>
                     <?php echo $cpb_captcha_booster_type_breadcrumb; ?>
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