<?php
require_once("html_output_fns.php");
require_once("user_auth_fns.php");

session_start();
do_html_header("Changing password", "none");

check_admin_user();
if (!filled_out($HTTP_POST_VARS)) {
  do_para("You have not filled out the form completely.  Please try again.");
  do_html_url("admin.php", "Back to administration menu");
  do_html_footer();
  exit;
}
else {
  $old_passwd = $HTTP_POST_VARS['old_passwd'];
  $new_passwd = $HTTP_POST_VARS['new_passwd'];

  if ($new_passwd != $new_passwd2) {
    do_para("Passwords entered were not the same. Not changed.");
  }
  else if (strlen($new_passwd) > 16 || strlen($new_passwd) < 6) {
    do_para("New password must be between 6 and 16 characters. Try again.");
  }
  else {
    // attempt update
    if (change_password($admin_user, $old_passwd, $new_passwd)) {
      do_para("Password changed.");
    }
    else {
      do_para("Password could not be changed.");
    }
  }
}
do_html_url("admin.php", "Back to administration menu");
do_html_footer();
?>
