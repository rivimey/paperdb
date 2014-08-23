<?php
/**
 * show_proc.php
 *
 * Copyright (c) 2000-2004 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Display a complete proceedings in one of a number of formats..
 *
 * $Id show_proc.php,v 1.9 2004/11/27 15:37:03 rivimey Exp $
 */

require_once("compat_fns.php");

/* change calls with id= to calls with num= */
if (isset($_GET['id']) || isset($_POST['id'])) {

  // get the id value, which should be a proc number
  $id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];

  // there should be a function number too...
  if ((isset($_GET['f']) || isset($_POST['f'])) && ($id >= 0 && $id <= 9999999)) {

    $f = isset($_GET['f']) ? $_GET['f'] : $_POST['f'];

    $relative_url = "show_proc.php?f=$f&amp;num=$id";

  }
  else {
    // shouldn't happen...
    $relative_url = "show_proc.php?f=1&amp;num=$id";
  }

  header("Location: http://" . $_SERVER['HTTP_HOST']
    . rtrim(dirname($_SERVER['PHP_SELF']), '/\\')
    . "/" . $relative_url);

  header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
}

require_once('html_output_fns.php');
require_once('paper_fns.php');
require_once('refer_output_fns.php');
require_once('book_output_fns.php');

session_start();

$f = isset($_GET['f']) ? $_GET['f'] : $_POST['f'];

if ($f > 0 && $f < 7) {
  if (isset($_GET['num']) || isset($_POST['num'])) {
    $num = isset($_GET['num']) ? $_GET['num'] : $_POST['num'];
  }
  else {
    do_html_header("Refer", "noindex,nofollow");
    echo "show_proc: $f requires an id number\n";
    do_html_footer();
    exit;
  }

  $nopapers = "There are no papers recorded for this proceeding.";
  if ($f == 2 || $f == 3 || $f == 5 || $f == 6) {
    $proc = get_proceeding($num);
    $papers = get_papers_by_proceedingid($num);
  }

  if ($f == 2 || $f == 3) {
    $eol = "<br>\n\n";
  }
  elseif ($f == 5 || $f == 6) {
    $eol = "\n\n";
  }

  if ($maintain_stats and $f >= 1 and $f <= 5) {
    update_proceeding_accessed($num);
  }

  if ($f == 1) { // list papers in short html format
    do_html_header("Proceedings details", "index,nofollow");
    $book = get_proceeding($num);
    display_book_details($book, 0);
  }
  elseif ($f == 2) { // list BibTeX records for all papers
    do_html_header("BibTeX Proceedings details", "index,nofollow");
    if ($papers == FALSE) {
      echo $nopapers . $eol;
    }
    else {
      echo "<p>BibTex html text dump of PaperDB database.</p>\n";
      echo "<p>PaperDB is GPL Software, see: http://paperdb.sourceforge.net.</p>\n";
      foreach ($papers as $paper) {
        do_bibtex_html($paper, $proc, FALSE);
      }
    }
  }
  elseif ($f == 3) { // list Refer records for all papers
    do_html_header("Refer Proceedings details", "index,nofollow");
    if ($papers == FALSE) {
      echo $nopapers . $eol;
    }
    else {
      foreach ($papers as $paper) {
        do_refer_html($paper, $proc);
      }
    }
  }
  elseif ($f == 4) { // list proceedings in long html format with papers
    do_html_header("Proceedings details", "index,nofollow");
    $book = get_proceeding($num);
    display_book_details($book, 1);
  }
  elseif ($f == 5) { // list BibTeX records for all papers, plain text only
    header("Content-Type: text/plain;");
    if ($papers == FALSE) {
      echo $nopapers . $eol;
    }
    else {
      echo "BibTex plain text dump of PaperDB database.\n";
      echo "PaperDB is GPL Software, see: http://paperdb.sourceforge.net.\n\n";
      foreach ($papers as $paper) {
        do_bibtex_text($paper, $proc, FALSE);
      }
    }
    exit;
  }
  elseif ($f == 6) { // list Refer records for all papers, plain text only
    header("Content-Type: text/plain;");
    if ($papers == FALSE) {
      echo $nopapers . $eol;
    }
    else {
      foreach ($papers as $paper) {
        do_refer_text($paper, $proc);
      }
    }
    exit;
  }

  // if logged in as admin, show links
  if (session_is_registered("admin_user")) {
    echo "<p>Links:</p><ul><li>";
    do_html_url("insert_papers_form.php", "Add a Paper");
    echo "<li>";
    do_html_url("edit_proceeding.php?f=1&amp;num=$num", "Edit Proceeding");
    echo "<li>";
    do_html_url("admin.php", "Back to administration menu");
    echo "</ul>";
  }

}
else {
  do_html_header("Refer", "noindex,nofollow");
  echo "show_proc: unimplemented function $f\n";
}

do_html_footer();

