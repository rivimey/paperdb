<?php
/**
 * list_proceeds.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * $Id: list_proceeds.php,v 1.6 2005/09/27 21:38:06 rivimey Exp $
 */

require_once("compat_fns.php");
require_once("html_output_fns.php");
require_once("misc_fns.php");
require_once("paper_fns.php");
require_once("refer_output_fns.php");

//-----------------------------------------------------------------------------
//
//
//-----------------------------------------------------------------------------

function mysql_to_unix($mysqldate) {
  list ($date, $time) = explode(' ', $mysqldate);
  list ($Yr, $Mo, $Dy) = explode('-', $date);
  list ($H, $M, $S) = explode(':', $time);
  //echo "$H, $M, $S, $Mo, $Dy, $Yr";
  return mktime($H, $M, $S, $Mo, $Dy, $Yr);
}

//-----------------------------------------------------------------------------
//
//
//-----------------------------------------------------------------------------

function get_lastmod($lm) {
  $lastmod = mysql_to_unix($lm);
  $now = time();
  if ($lastmod > $now) {
    $lastmod = $now;
  }
  return strftime("%a, %d %b %Y %H:%M:%S %Z", $lastmod);
}

//-----------------------------------------------------------------------------
//  list_proceedings
//
//  List proceedings in year order with hyperlink to details in English,
// BibTex and Refer formats.
//
//-----------------------------------------------------------------------------

function list_proceedings() {
  do_html_header("List all Proceedings", "noindex");

  $proc_array = get_proceedings(1);
  if ($proc_array) {
    echo "<table cellpadding=\"2\">\n";
    echo "<tr><th>Title</th><th>Year</th><th colspan=\"2\">Formats</th></tr>\n";
    foreach ($proc_array as $thisproc) {
      echo "<tr><td>";
      echo "<a href=\"show_proc.php?f=1&amp;num=" . $thisproc["proceedingid"] . "\">" . $thisproc["title"] . "</a>\n";
      echo "</td><td>";
      if (isset($thisproc["pubyear"])) {
        echo $thisproc["pubyear"];
      }
      echo "</td><td>";
      echo " <a href=\"show_proc.php?f=2&amp;num=" . $thisproc["proceedingid"] . "\">BibTEX</a>\n";
      echo "</td><td>";
      echo " <a href=\"show_proc.php?f=3&amp;num=" . $thisproc["proceedingid"] . "\">Refer</a>\n";
      echo "</td></tr>\n\n";
    }
    echo "</table>\n";
  }
  else {
    echo "<p>No proceedings available in database.</p>\n";
  }
}

//-----------------------------------------------------------------------------
//  list_proceedings_bibtex
//
//  List proceedings in year order as BibTeX Inproceedings records.
//
//-----------------------------------------------------------------------------

function list_proceedings_bibtex($plain) {
  $lm = get_allpapers_last_modified();
  header("Last-Modified: " . get_lastmod($lm));

  $proc_array = get_proceedings(1);
  foreach ($proc_array as $thisproc) {
    $paper_array = get_papers_by_proceedingid($thisproc["proceedingid"]);
    foreach ($paper_array as $thispaper) {
      display_paper_as_bibtex($thispaper, $plain); // display plain text or as web page
    }
  }
}

//-----------------------------------------------------------------------------
//  list_proceedings_refer
//
//  List proceedings in year order as BibTeX Inproceedings records.
//
//-----------------------------------------------------------------------------

function list_proceedings_refer($plain) {
  $lm = get_allpapers_last_modified();
  header("Last-Modified: " . get_lastmod($lm));

  $proc_array = get_proceedings(1);
  foreach ($proc_array as $thisproc) {
    $paper_array = get_papers_by_proceedingid($thisproc["proceedingid"]);
    foreach ($paper_array as $thispaper) {
      display_paper_as_refer($thispaper, $plain); // display plain text or as web page
    }
  }
}

//-----------------------------------------------------------------------------
//  text_headers
//-----------------------------------------------------------------------------

function text_headers($name) {
  header("Cache-Control: public");
  header("Content-Disposition: inline; filename=$name");
}

//-----------------------------------------------------------------------------

session_start();
global $siteName, $defaultCharset;

$f = isset($_GET['f']) ? $_GET['f'] : $_POST['f'];

if ($f > 0 && $f < 6) {
  if ($f == 1) { // list of proceedings, index style
    list_proceedings();
  }
  elseif ($f == 2) { // list of proceedings, long-format html BibTeX
    do_html_header("List Proceedings BibTeX", array('robots' => 'all'));
    text_headers("");
    list_proceedings_bibtex(FALSE);
  }
  elseif ($f == 3) { // list of proceedings, long-format html BibTeX
    do_html_header("List Proceedings Refer", array('robots' => 'all'));
    text_headers("");
    list_proceedings_refer(FALSE);
  }
  elseif ($f == 4) { // list of proceedings, long-format plain text BibTeX
    header("Content-Type: text/plain; charset=$defaultCharset");
    text_headers("wotug.bib");
    list_proceedings_bibtex(TRUE);
  }
  elseif ($f == 5) { // list of proceedings, long-format plain text Refer
    header("Content-Type: text/plain; charset=$defaultCharset");
    text_headers("wotug.rf");
    list_proceedings_refer(TRUE);
  }

}
else {
  do_html_header("Refer", array('robots' => 'all'));
  echo "list_proceeds: unimplemented function $f\n";
}

// no footer for plain-text output
if (!($f == 4 || $f == 5)) {
  do_html_footer();
}
