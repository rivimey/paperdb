<?php
/**
 * search.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Display and process the search form.
 *
 * $Id: search.php,v 1.6 2005/09/27 21:33:53 rivimey Exp $
 */

require_once("compat_fns.php");
require_once("paper_fns.php");
require_once("html_output_fns.php");
require_once("book_output_fns.php");
require_once("paper_output_fns.php");

session_start();
db_connect(); // for mysql_real_escape_string()

do_html_header("Search results");

if (isset($_POST['goButton']) && $_POST['goButton'] = "Search") {

  $num = $_POST['num'];
  $fr = isset($_POST['fr']) ? $_POST['fr'] : 0;
  $tables = array();
  $wnum = 0;
  $where = array();
  $proc_paper_link = 0;
  $pers_paper_link = 0;
  for ($i = 1; $i <= $num; $i++) {
    if (isset($_POST['what'][$i])) {
      $t = $_POST['text'][$i];
      $w = $_POST['what'][$i];

      if ($w == 1) { // title
        $where[$wnum++] = "papers.title REGEXP " . sqlvalue($t, "A");
        $tables["papers"] = 1;
      }
      elseif ($w == 2) { // author surname
        $where[$wnum++] = "people.lastname REGEXP " . sqlvalue($t, "A");
        $pers_paper_link = 1;
      }
      elseif ($w == 3) { // author firstname
        $where[$wnum++] = "people.firstname REGEXP " . sqlvalue($t, "A");
        $pers_paper_link = 1;
      }
      elseif ($w == 4) { // abstract
        $where[$wnum++] = "papers.abstract REGEXP " . sqlvalue($t, "A");
        $tables["papers"] = 1;
      }
      elseif ($w == 5) { // reftext
        $where[$wnum++] = "papers.reftext REGEXP " . sqlvalue($t, "A");
        $tables["papers"] = 1;
      }
      elseif ($w == 6) { // year
        $where[$wnum++] = "proceedings.pubyear = " . sqlvalue($t, "N");
        $proc_paper_link = 1;
      }
      elseif ($w == 7) { // isbn
        $where[$wnum++] = "proceedings.isbn REGEXP " . sqlvalue($t, "A");
        $proc_paper_link = 1;
      }
      elseif ($w == 8) { // files attached
        $where[$wnum++] = "paperfilelist.paperid = papers.paperid";
        $tables["paperfilelist"] = 1;
        $tables["papers"] = 1;
      }
    }
  }
  if ($proc_paper_link) {
    $where[$wnum++] = "paperlist.proceedingid = proceedings.proceedingid and papers.paperid = paperlist.paperid";
    $tables["paperlist"] = 1;
    $tables["papers"] = 1;
    $tables["proceedings"] = 1;
  }
  if ($pers_paper_link) {
    $where[$wnum++] = "people.personid = authorlist.authorid and authorlist.authors = papers.paperid";
    $tables["papers"] = 1;
    $tables["authorlist"] = 1;
    $tables["people"] = 1;
  }

  // read the tables array and create a comma-separated list of the tables
  // required by the query. Each table is only listed once.
  {
    reset($tables);
    $tablestr = "";
    $done = FALSE;
    while (!$done) {
      $tablename = key($tables);
      $tablestr .= "$tablename";

      $done = (next($tables) == FALSE);
      if (!$done) {
        $tablestr .= ",";
      }
    }
    while (!$done) {
      ;
    }
  }

  // read the where array and turn it into a series of bracketed
  // SQL expressions: '(papers.title REGEXP "string") and ( ... )'
  {
    reset($where);
    $condstr = "";
    do {
      $cond = current($where);
      $condstr .= "( $cond )";

      $done = (next($where) == FALSE);
      if (!$done) {
        $condstr .= " and ";
      }
    } while (!$done);
  }


  // add the preamble to the conditional strings, and run the query. We
  // need to end up with thean array of papers.paperid values.
  $query = "select distinct papers.paperid from $tablestr where $condstr";

  $result = get_list_query($query);

  // display the results of the query
  if (is_array($result)) {
    $cnt = count($result);
    $i = 0;
    $sr = $fr + 1;
    $er = ($cnt < ($fr + 60)) ? $cnt : $fr + 60;
    if ($cnt == 1) {
      echo "<p>Found one matching paper, displaying ";
    }
    else {
      echo "<p>Found $cnt matching papers, displaying $sr to $er ";
    }
    if ($cnt < 20) {
      echo "verbosely";
    }
    echo ":</p>";
    foreach ($result as $elem) {
      if ($i >= $fr && $i < $er) {
        $paperid = $elem['paperid'];
        $paper = get_paper($paperid);
        if ($cnt < 20) // only display verbosely if there are few records matching...
        {
          display_paper_verbose($paper);
        }
        else {
          display_paper_link(0, $paper);
        }
      }
      if ($i >= $er) {
        break;
      }
      $i++;
    }
  }
  else {
    do_para("Sorry, No results found for query.");
  }
}

{
  echo "<br>";

  $fr = isset($_POST['fr']) ? $_POST['fr'] : 0;
  $num = isset($_POST['num']) ? $_POST['num'] : 1;
  if (isset($_POST['moreButton']) && $_POST['moreButton'] == "More") {
    $num = $num + 1;
  }
  if (isset($_POST['fewButton']) && $_POST['fewButton'] == "Fewer") {
    $num = $num - 1;
  }

  echo "<form method=\"post\" action=\"search.php\">\n";
  echo "<input type=\"hidden\" name=\"num\" value=\"$num\">\n";
  echo "<input type=\"hidden\" name=\"fr\" value=\"$fr\">\n"; // first record
  echo "<table>";
  for ($i = 1; $i <= $num; $i++) {
    $v = isset($_POST['text'][$i]) ? $_POST['text'][$i] : "";
    $w = isset($_POST['what'][$i]) ? $_POST['what'][$i] : "";

    echo "<tr>";
    echo "<td>\n";
    echo "<select name=\"what[$i]\">\n";
    echo "<option value=\"1\"";
    if ($w == 1) {
      echo " selected";
    }
    echo ">Title</option>\n";
    echo "<option value=\"2\"";
    if ($w == 2) {
      echo " selected";
    }
    echo ">Author Surname</option>\n";
    echo "<option value=\"3\"";
    if ($w == 3) {
      echo " selected";
    }
    echo ">Author Firstname</option>\n";
    echo "<option value=\"4\"";
    if ($w == 4) {
      echo " selected";
    }
    echo ">Abstract</option>\n";
    echo "<option value=\"5\"";
    if ($w == 5) {
      echo " selected";
    }
    echo ">Ref No</option>\n";
    echo "<option value=\"6\"";
    if ($w == 6) {
      echo " selected";
    }
    echo ">Pub. Date</option>\n";
    echo "<option value=\"7\"";
    if ($w == 7) {
      echo " selected";
    }
    echo ">ISBN</option>\n";
    echo "<option value=\"8\"";
    if ($w == 8) {
      echo " selected";
    }
    echo ">Has File Attachments</option>\n";
    echo "</select>";
    echo "</td><td><input type=\"text\" name=\"text[$i]\" size=\"60\" maxlength=\"120\" value=\"" . htmlentities($v) . "\"></td>\n";
  }
  echo "<tr><td valign=\"bottom\" rowspan=\"2\" align=\"left\">";
  echo "<input type=\"submit\" name=\"goButton\" value=\"Search\"></td></tr>\n";
  echo "<tr><td valign=\"bottom\"  align=\"right\"><input type=\"submit\" name=\"moreButton\" value=\"More\">\n";
  if ($num > 1) {
    echo "<input type=\"submit\" name=\"fewButton\" value=\"Fewer\">";
  }
  echo "</td></tr>\n";
  echo "</table></form>";

}

do_html_footer();
