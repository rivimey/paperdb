<?php

// include function files for this application

require_once("html_output_fns.php");
require_once("admin_fns.php");
require_once("user_auth_fns.php");

session_start();

if ($username && $passwd) // they have just tried logging in
{
  if (register($username, $passwd)) {
    // if they are added to the database ok log them in
    $admin_user = $username;
    session_register("admin_user");
  }
  else {
    // unsuccessful login
    do_html_header("Problem:");
    echo "You could not be logged in.
            You must be logged in to view this page.<br>";
    do_html_url("login.php", "Login");
    do_html_footer();
    exit;
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
