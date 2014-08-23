<?php
/**
 * index.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Paper Database front page.
 *
 * $Id: index.php,v 1.5 2005/09/27 21:41:07 rivimey Exp $
 */

require_once("compat_fns.php");

require_once('html_output_fns.php');
require_once('paper_fns.php');

// We need sessions, so start one
session_start();
do_html_header("Paper and Proceedings Database Server", "index,follow");

do_para("Welcome to the WoTUG conference proceedings database. You can use it to display the titles,\n"
  . "abstracts and in some cases the full text of the papers that have been presented at the WoTUG\n"
  . "conferences.\n");
do_para("Please select one of the following:");

echo "<ul>\n";
echo "<li>Please choose a proceeding to display: ";
$proc_array = get_proceedings(0);

// list of possible proceedings comes from database
echo "<form method=\"get\" action=\"show_proc.php\">\n";
flush();
echo "<input type=\"hidden\" name=\"f\" value=\"4\">\n";
echo "<select name=\"num\">\n";
foreach ($proc_array as $thisproc) {
  $str = $thisproc["title"];
  if (strlen($str) > 50) { // limit length to something shortish...
    $str = substr($str, 0, 48) . "...";
  }
  $num = $thisproc["proceedingid"];
  echo "<option value=\"$num\">$str</option>\n";
}
echo "</select> <input type=\"submit\" value=\"[Go]\"></form></li>\n";

echo "<li><a href=\"search.php\">Search for a paper</a></li>";
echo "<li><a href=\"list_proceeds.php?f=1\">Browse all proceedings</a>\n</li>";
echo "<li><a href=\"list_papers.php\">Browse all papers</a></li>\n";
echo "<li><a href=\"list_authors.php\">Browse all authors</a></li>\n";
echo "</ul>\n";

do_html_footer();

