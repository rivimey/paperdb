<?php
/**
 * admin.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Process an administrator login request.
 *
 * $Id: admin.php,v 1.3 2004/11/04 17:52:12 rivimey Exp $
 */

session_start();

require_once("html_output_fns.php");
require_once("user_auth_fns.php");
require_once("admin_fns.php");

if (!session_is_registered("admin_user")) {
  if (!isset($_POST['username']) || !isset($_POST['passwd'])) {
    header("Location: login.php");
    exit(0);
  }
  $username = $_POST['username'];
  $passwd = $_POST['passwd'];

  if (isset($username) && isset($passwd)) {
    if (login($username, $passwd)) {
      // if they are in the database register the user id
      $admin_user = $username;
      session_register("admin_user");
    }
    else {
      // unsuccessful login
      do_html_header("Problem:");
      do_para("You could not be logged in.");
      do_para("You must be logged in to view this page.");
      do_html_url("login.php", "Login");
      do_html_footer();
      exit;
    }
  }
}

do_html_header("Administration");
if (check_admin_user()) {
  display_admin_menu();
}
else {
  echo "You are not authorized to enter the administration area.";
}

do_html_footer();
?>
