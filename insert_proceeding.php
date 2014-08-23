<?php
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

require_once("compat_fns.php");

session_start();

require_once("html_output_fns.php");
require_once("user_auth_fns.php");
require_once("admin_fns.php");

do_html_header("Adding a proceeding");
if (check_admin_user()) {
  $bad = 0;

  if (!isset($_POST["title"]) || $_POST["title"] == "") {
    echo "Title not set<br>\n";
    $bad = 1;
    $title = "";
  }
  else {
    $title = $_POST["title"];
  }

  if (!isset($_POST["publisherid"])) {
    echo "Publisher not set<br>\n";
    $bad = 1;
    $pubid = "";
  }
  else {
    $pubid = $_POST["publisherid"];
  }

  if (!isset($_POST["subtitle"])) {
    $subtitle = "";
  }
  else {
    $subtitle = $_POST["subtitle"];
  }

  if (!isset($_POST["series"])) {
    $series = "";
  }
  else {
    $series = $_POST["series"];
  }

  if (!isset($_POST["isbn"])) {
    $isbn = "";
  }
  else {
    $isbn = $_POST["isbn"];
  }

  if (!isset($_POST["pubyear"])) {
    $pubyear = "";
  }
  else {
    $pubyear = $_POST["pubyear"];
  }

  if (!isset($_POST["pubmonth"])) {
    $pubmonth = "";
  }
  else {
    $pubmonth = $_POST["pubmonth"];
  }

  if (!isset($_POST["pubday"])) {
    $pubday = "";
  }
  else {
    $pubday = $_POST["pubday"];
  }

  if (!isset($_POST["issn"])) {
    $issn = "";
  }
  else {
    $issn = $_POST["issn"];
  }

  if (!isset($_POST["volume"])) {
    $volm = "";
  }
  else {
    $volm = $_POST["volume"];
  }

  if (!isset($_POST["totpages"])) {
    $totpg = "";
  }
  else {
    $totpg = $_POST["totpages"];
  }

  $editors = $_POST["editors"];
  $url = "";

  if (!$bad) {
    if (insert_proceeding($title, $subtitle, $pubid, $series, $editors, $isbn,
      $issn, $volm, $totpg, $url, $pubyear, $pubmonth, $pubday)
    ) {
      echo "Proceeding '$title' was added to the database.<br>";
    }
    else {
      echo "Proceeding '$title' could not be added to the database.<br>";
    }
  }
  else {
    echo "Proceeding was not added to the database.<br>";
  }

  do_html_url("admin.php", "Back to administration menu");
}
else {
  echo "You are not authorised to view this page.";
}

do_html_footer();
