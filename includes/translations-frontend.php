<?php

/**
 * This file is used for translation strings of frontend.
 *
 * @author  Tech Banker
 * @package wp-captcha-booster/includes
 * @version 1.0.0
 */
if (!defined("ABSPATH")) {
   exit;
} //exit if accessed directly
// mailer-class
$cpb_one_hour = __("1 Hour", "wp-captcha-booster");
$cpb_twelve_hours = __("12 Hours", "wp-captcha-booster");
$cpb_twenty_four_hours = __("24 Hours", "wp-captcha-booster");
$cpb_forty_eight_hours = __("48 Hours", "wp-captcha-booster");
$cpb_one_week = __("1 Week", "wp-captcha-booster");
$cpb_one_month = __("1 Month", "wp-captcha-booster");
$cpb_permanently = __("Permanently", "wp-captcha-booster");
$cpb_for = __("for ", "wp-captcha-booster");

//captcha-frontend
if (!defined("enter_captcha")) {
   define("enter_captcha", __("Enter Captcha Here", "wp-captcha-booster"));
}
//logical Captcha
if (!defined("captcha_booster_ascending_order")) {
   define("captcha_booster_ascending_order", __("Arrange in Ascending Order", "wp-captcha-booster"));
}
if (!defined("captcha_booster_descending_order")) {
   define("captcha_booster_descending_order", __("Arrange in Descending Order", "wp-captcha-booster"));
}
if (!defined("captcha_booster_seperate_numbers")) {
   define("captcha_booster_seperate_numbers", __(" (Use ',' to separate the numbers) :", "wp-captcha-booster"));
}
if (!defined("captcha_booster_larger_number")) {
   define("captcha_booster_larger_number", __("Which Number is Larger ", "wp-captcha-booster"));
}
if (!defined("captcha_booster_smaller_number")) {
   define("captcha_booster_smaller_number", __("Which Number is Smaller ", "wp-captcha-booster"));
}
if (!defined("captcha_booster_logical_error")) {
   define("captcha_booster_logical_error", __("ERROR", "wp-captcha-booster"));
}
if (!defined("captcha_booster_logical_or")) {
   define("captcha_booster_logical_or", __(" or ", "wp-captcha-booster"));
}
if (!defined("captcha_booster_encryption")) {
   define("captcha_booster_encryption", __("Encryption password is not set", "wp-captcha-booster"));
}
if (!defined("captcha_booster_decryption")) {
   define("captcha_booster_decryption", __("Decryption password is not set", "wp-captcha-booster"));
}
if (!defined("captcha_booster_arithemtic")) {
   define("captcha_booster_arithemtic", __("Solve"));
}