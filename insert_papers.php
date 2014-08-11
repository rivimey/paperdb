<?
/**
 * insert_papers.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Various back-end administration functions, only used by the
 * administrator (not web-users!).
 *
 * $Id: insert_papers.php,v 1.4 2005/09/27 21:38:06 rivimey Exp $
 */


require_once("html_output_fns.php");
require_once("user_auth_fns.php");
require_once("admin_fns.php");

session_start();

$filedata1_filename = $_POST['filedata1_filename'];
$filedata2_filename = $_POST['filedata2_filename'];
$filetype1 = $_POST['filetype1'];
$filetype2 = $_POST['filetype2'];
$procids = $_POST['procid'];
$title = $_POST['title'];
$papers = $_POST['papers'];
$authors = $_POST['authors'];
$paper_url = $_POST['paper_url'];
$reftext = $_POST['reftext'];
$abstract = $_POST['abstract'];

do_html_header("Adding a paper");
if (check_admin_user())
{ 
  $papers = array();
  $paperid = false;
  if ($title != "")
  {
    if ($filedata1_filename != "") {
      $papers[0] = add_paper_file($filedata1_filename, "No", $filetype1);
      if (!$papers[0])
        echo "Could not add paper file $filedata1_filename to the database.<br>";
    }
    if ($filedata2_filename != "") {
      $papers[1] = add_paper_file($filedata2_filename, "No", $filetype2);
      if (!$papers[1])
        echo "Could not add paper file $filedata2_filename to the database.<br>";
    }

    $paperid = insert_paper($procids, $title, $papers, $authors, $paper_url, $reftext, $abstract);
    if($paperid == false)
      echo "Paper '$title' could not be added to the database.<br>";
    else
      echo "Paper '$title' was added to the database with id $paperid.<br>";
  } 

  echo "<ul><li>";
  if ($paperid != false) {
    do_html_url("show_pap.php?f=1&amp;num=".$paperid, "To paper details.");
    echo "<li>";
    do_html_url("edit_paper.php?f=2&amp;num=".$paperid, "Add file to paper.");
    echo "<li>";
  }
  do_html_url("insert_papers_form.php", "Add another paper.");
  echo "<li>";
  do_html_url("admin.php", "Back to administration menu");
  echo "</ul>";

}
else 
  echo "You are not authorised to view this page."; 

do_html_footer();

?>
