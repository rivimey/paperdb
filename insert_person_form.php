<?
/**
 * insert_person_form.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Display a form to add new people to the database. 
 *
 * $Id: insert_person_form.php,v 1.2 2003/07/05 17:05:40 rivimey Exp $
 */


// include function files for this application
require_once("html_output_fns.php");
require_once("user_auth_fns.php");
require_once("form_output_fns.php");
session_start();

do_html_header("Add Person");
if (check_admin_user())
{
  display_person_form();

  echo "<ul><li>";
  do_html_url("admin.php", "Back to administration menu");
  echo "</ul>";
}
else
  echo "You are not authorized to enter the administration area.";

do_html_footer();

?>
