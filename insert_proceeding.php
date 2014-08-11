<?
/**
 * insert_proceedings.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Process the form data for adding a proceeding to the database.
 *
 * $Id: insert_proceeding.php,v 1.1 2003/05/11 16:53:05 ruthc Exp $
 */

session_start();

require_once("html_output_fns.php"); 
require_once("user_auth_fns.php");
require_once("admin_fns.php");

$title   = $_POST["title"];
$subtitle= $_POST["subtitle"];
$pubid   = $_POST["pubid"];
$series  = $_POST["series"];
$editors = $_POST["editors"];
$isbn    = $_POST["isbn"];
$issn    = $_POST["issn"];
$volm    = $_POST["volm"];

do_html_header("Adding a proceeding");
if (check_admin_user())
{ 
  if (insert_proceeding($title, $subtitle, $pubid, $series, $editors, $isbn, 
                        $issn, $volm, $totpg, $url))
    echo "Proceeding '$title' was added to the database.<br>";
  else
    echo "Proceeding '$title' could not be added to the database.<br>";

  do_html_url("admin.php", "Back to administration menu");
}
else
  echo "You are not authorised to view this page."; 

do_html_footer();
?>
    
