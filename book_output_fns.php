<?php
/**
 * book_output_fns.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Functions displaying proceedings.
 *
 * $Id: book_output_fns.php,v 1.8 2005/09/27 21:45:41 rivimey Exp $
 */

require_once("compat_fns.php");
require_once("misc_fns.php");
require_once("paper_output_fns.php");
require_once("paper_fns.php");

//--------------------------------------------------------------------------------------
//   display_proceedings
//
//  Display a table consisting of the titles of the known proceedings each of which
// is an href to the book-display page of show_book.php.
//
//--------------------------------------------------------------------------------------

function display_proceedings($proceeding_array) {
  //display all proceedings in the array passed in
  if (!is_array($proceeding_array)) {
    echo "No proceedings in this category are listed.";
  }
  else {
    //create table
    echo "<table width = \"100%\" border = \"0\">";

    //create a table row for each proceeding
    foreach ($proceeding_array as $row) {
      echo "<tr><td>";
      do_html_url("show_book.php?f=4&amp;num=" . ($row["proceedingid"]), $row["title"]);
      echo "</td><td>";
      echo $row["editors"];
      echo "</td></tr>";
    }
    echo "</table>";
  }
  echo "<hr>";
}

//--------------------------------------------------------------------------------------
// display_proceeding_details
//
//  Display proceeding information associated with a paper for
// reference purposes.
//
//--------------------------------------------------------------------------------------

function display_proceeding_details($proc, $paperid, $extrainfo = TRUE) {
  $pages = get_paperinfo_by_paperid_and_procid($paperid, $proc["proceedingid"]);

  echo "<p><i><a href=\"show_proc.php?f=1&amp;num=" . $proc["proceedingid"] . "\">" . $proc["title"] . "</a></i>, ";
  if ($extrainfo) {
    $editorlist = get_editors_by_listid($proc["proceedingid"]);
    $edits = make_namelist($editorlist, ", ", "No editors recorded.", 1);
    echo $edits . ", \n";

    echo " " . $proc["pubyear"] . ", ";
    if (is_array($pages)) {
      echo "pp " . $pages["firstpage"] . " - " . $pages["lastpage"] . " ";
    }
    echo "<!-- pubid =" . $proc["publisherid"] . " -->\n";
    $publisher = get_publisher_name($proc["publisherid"]);
    if (isset ($publisher)) {
      echo " published by " . $publisher;
    }
  }
  echo "</p>\n";
}


//--------------------------------------------------------------------------------------
// display_book_details
//
//   Given info on a proceeding and a flag, display information about the proceeding
// including the title, editors and papers presented. Papers are displayed as links
// (title & href) unless the flag is true.
//
//--------------------------------------------------------------------------------------

function display_book_details($proceeding, $verb) {
  // display all details about this proceeding
  if (is_array($proceeding)) {
    // papers is from "paperlist"
    $papers = get_paperinfo_by_proceedingid($proceeding["proceedingid"]);

    echo "<b>Title:</b> ";
    echo $proceeding["title"];
    // if logged in as admin, show add, delete, edit cat links
    if (session_is_registered("admin_user")) {
      do_html_url("edit_proceeding.php?f=1&amp;num=" . $proceeding["proceedingid"], " Edit");
    }

    echo "<br>\n";
    if (!empty($proceeding["subtitle"])) {
      echo "Subtitle: ";
      echo $proceeding["subtitle"] . "<br>\n";
    }
    echo "<b>Editors:</b> ";
    $editorlist = get_editors_by_listid($proceeding["proceedingid"]);
    echo make_namelist($editorlist, ", ", "No editors recorded.", 1);
    echo "<br>\n";

    echo "<!-- pubid =" . $proceeding["publisherid"] . " -->\n";
    $publisher = get_publisher_name($proceeding["publisherid"]);
    if (isset ($publisher)) {
      echo "<b>Publisher:</b> ";
      echo $publisher . "<br>\n";
    }

    if (!empty($proceeding["isbn"])) {
      echo "<b>ISBN:</b> ";
      echo $proceeding["isbn"] . "<br>\n";
    }

    if (!empty($proceeding["issn"])) {
      echo "<b>ISSN:</b> ";
      echo $proceeding["issn"] . "<br>\n";
    }

    if ($papers != FALSE) {
      if ($verb) {
        $lasttype = "";
        foreach ($papers as $paperinfo) {
          $paper = get_paper($paperinfo["paperid"]);
          if ($paper["itemtype"] != $lasttype) {
            // Yuck -- display_paper_verbose does this.
            if ($lasttype != "") {
              echo "<hr size=\"1\">\n";
            }

            $lasttype = $paper["itemtype"];
            echo "<h3>" . itemtype_label($lasttype) . "s</h3>\n";
          }
          display_paper_verbose($paper);
        }
      }
      else {
        echo "<b>Items:</b>\n";
        echo "<ul>\n";
        foreach ($papers as $paperinfo) {
          $paper = get_paper($paperinfo["paperid"]);
          display_paper_link($paperinfo, $paper);
        }
        echo "</ul>\n";
      }
    }
    else {
      echo "No items found.";
    }
    echo "<br>\n";

    return TRUE;
  }
  else {
    echo "display_book_details: passed non-array<br>\n";
    return FALSE;
  }
}
