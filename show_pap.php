<?php
/**
 * show_pap.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Display the paper in one of a number of formats..
 *
 * $Id: show_pap.php,v 1.7 2005/09/27 21:32:20 rivimey Exp $
 */

require_once("compat_fns.php");

/* change calls with id= to calls with num= */
if (isset($_GET['id']) || isset($_POST['id'])) {

  // get the id value, which should be a paper number
  $id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];

  // there should be a function number too...
  if ((isset($_GET['f']) || isset($_POST['f'])) && ($id >= 0 && $id <= 9999999)) {

    $f = isset($_GET['f']) ? $_GET['f'] : $_POST['f'];

    $relative_url = "show_pap.php?f=$f&amp;num=$id";

  }
  else {
    // shouldn't happen...
    $relative_url = "show_pap.php?f=1&amp;num=$id";
  }

  header("Location: http://" . $_SERVER['HTTP_HOST']
    . rtrim(dirname($_SERVER['PHP_SELF']), '/\\')
    . "/" . $relative_url);

  header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
}

require_once("paper_fns.php");
require_once("html_output_fns.php");
require_once("refer_output_fns.php");
require_once("paper_output_fns.php");
require_once("book_output_fns.php");

session_start();
$f = isset($_GET['f']) ? $_GET['f'] : $_POST['f'];

if ($f > 0 && $f < 6 && (isset($_GET['num']) || isset($_POST['num']))) {

  $num = isset($_GET['num']) ? $_GET['num'] : $_POST['num'];

  $paper = get_paper($num);
  if ($f == 4 || $f == 5) {
    header("Content-Type: text/plain");
    header("Content-Disposition: inline");
  }
  else {
    do_html_header("Paper Details", "index,follow");
  }

  if ($maintain_stats and $f >= 1 and $f <= 5) {
    update_paper_accessed($num);
  }

  if ($f == 1) {
    display_paper_details($paper);
  }
  elseif ($f == 2) {
    display_paper_as_bibtex($paper, FALSE); // display html text
  }
  elseif ($f == 3) {
    display_paper_as_refer($paper, FALSE); // display html text
  }
  elseif ($f == 4) {
    display_paper_as_bibtex($paper, TRUE); // display plain text
    exit;
  }
  elseif ($f == 5) {
    display_paper_as_refer($paper, TRUE); // display plain text
    exit;
  }
}
else {
  do_html_header("Paper details", "nofollow");
  do_para("show_pap: Unimplemented function");
}

// if logged in as admin, show add, delete, edit cat links
if (session_is_registered("admin_user")) {
  echo "<p>Links:</p><ul><li>";
  do_html_url("edit_paper.php?f=2&amp;num=$num", "Add File to This Paper");
  echo "<li>";
  do_html_url("edit_paper.php?f=1&amp;num=$num", "Edit This Paper");
  echo "<li>";
  do_html_url("insert_papers_form.php", "Add New Paper,");
  echo "<li>";
  do_html_url("insert_person_form.php", "Add New Person,");
  echo "</ul>";
}

do_html_footer();

