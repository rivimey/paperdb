<?php
/**
 * insert_proceeding_form.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Display the form used to add a new proceeding.
 *
 * $Id: insert_proceeding_form.php,v 1.1 2003/05/11 16:53:05 ruthc Exp $
 */

require_once("compat_fns.php");

session_start();

require_once("html_output_fns.php");
require_once("user_auth_fns.php");
require_once("form_output_fns.php");

do_html_header("Add Proceedings");
if (check_admin_user()) {
  display_proceeding_form();

  echo "<ul><li>";
  do_html_url("admin.php", "Back to administration menu");
  echo "</ul>";
}
else {
  do_para("You are not authorized to enter the administration area.");
}

do_html_footer();
