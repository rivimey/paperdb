<?php
/**
 * insert_papers_form.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Functions that display forms containing info for/from the database.
 *
 * $Id: insert_papers_form.php,v 1.1 2003/05/11 16:53:05 ruthc Exp $
 */

require_once("compat_fns.php");
require_once("html_output_fns.php");
require_once("user_auth_fns.php");
require_once("form_output_fns.php");

session_start();


do_html_header("Add Paper");
if (check_admin_user()) {
  display_papers_form();

  echo "<ul><li>";
  do_html_url("admin.php", "Back to administration menu");
  echo "</ul>";
}
else {
  do_para("You are not authorized to enter the administration area.");
}

do_html_footer();
