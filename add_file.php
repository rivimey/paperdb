<?php
/**
 * add_file.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Process the form data submitted to add a file to the paper database
 *
 * $Id: add_file.php,v 1.6 2005/09/27 21:43:20 rivimey Exp $
 */

require_once("compat_fns.php");

session_start();

require_once("html_output_fns.php");
require_once("admin_fns.php");
require_once("paper_fns.php");

//-----------------------------------------------------------------------------
//  process_file
//
//  Upload the file from the user into the database, and then link the paper
// to that file, with appropriate messages.
//
//-----------------------------------------------------------------------------

function process_file($pos, $paperttl, $paperid, $name, $tnam, $type) {
  if ($name && $type) {
    $fileid = add_paper_file($name, $tnam, "No", $type);
    if (!$fileid) {
      echo "<p>Could not add paper file $name to the database.</p>";
    }
    else {
      echo "<p>File $name stored in database as <$fileid>.\n";

      if (link_file_to_paper($paperid, $fileid)) {
        echo "and linked to paper <$paperttl>.\n</p>";
      }
      else {
        echo "<p>Could not add paper file $name to the database.</p>";
      }
    }
  }
  else {
    //do_para("You did not send any data for the $pos file.");
  }
}

//-----------------------------------------------------------------------------

$num = $_POST['num'];
$paper = get_paper($num);

if (!$num) {
  do_html_header("Add file to paper");
  do_para("No paper?");
}
else {
  do_html_header("Add file to paper &quot;" . $paper['title'] . "&quot;");
  $name = $_FILES['filedata1']['name'];
  $tnam = $_FILES['filedata1']['tmp_name'];
  $type = $_POST['filetype1'];
  process_file("first", $paper['title'], $num, $name, $tnam, $type);

  $name = $_FILES['filedata2']['name'];
  $tnam = $_FILES['filedata2']['tmp_name'];
  $type = $_POST['filetype2'];
  process_file("second", $paper['title'], $num, $name, $tnam, $type);
}

echo "<p>Links:</p><ul><li>";
do_html_url("show_pap.php?f=1&amp;num=" . $num, "To paper details.");
echo "<li>";
do_html_url("insert_papers_form.php", "Add another paper.");
echo "<li>";
do_html_url("admin.php", "Back to administration menu");
echo "<li>";
do_html_url("index.php", "Back to main menu");
echo "</ul>";

do_html_footer();
