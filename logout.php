<?php
/**
 * logout.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Log out the administrator
 *
 * $Id: logout.php,v 1.2 2004/11/16 00:52:20 rivimey Exp $
 */

// include function files for this application
require_once("html_output_fns.php");
require_once("admin_fns.php");
require_once("user_auth_fns.php");

session_start();

$was_in = session_is_registered("admin_user");
$result_unreg = session_unregister("admin_user");
$result_dest = session_destroy();

// start output html
do_html_header("Logging Out");

if ($was_in) {
  if ($result_unreg && $result_dest) {
    // if they were logged in and are now logged out
    do_para("Administrator now logged out.");

    do_html_url("login.php", "Login");
  }
  else {
    // they were logged in and could not be logged out
    do_para("Could not log you out.");
  }
}
else {
  // if they weren't logged in but came to this page somehow
  do_para("You were not logged in, and so have not been logged out.");
  do_html_url("login.php", "Login");
}

do_html_footer();
