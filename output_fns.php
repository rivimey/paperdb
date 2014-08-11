/**
* output_fns.php
*
* Copyright (c) 2000-2004 Ruth Ivimey-Cook
* Licensed under the GNU GPL. For full terms see the file COPYING.
*
* Process an administrator login request.
*
* $Id: output_fns.php,v 1.4 2005/09/27 21:33:53 rivimey Exp $
*/

<?php

require_once("misc_fns.php"); // namelist fns

//------------------------------------------------------------------------------------------------------------------------------
// display_proceedings
//
//  Display the title (as a link) of each proceeding in the given
// array of proceedings.
//
//------------------------------------------------------------------------------------------------------------------------------

function display_proceedings($proceeding_array) {
  //display all proceedings in the array passed in
  if (!is_array($proceeding_array)) {
    echo "No proceedings in this category are listed.";
  }
  else {
    //create table
    echo "<table width = \"100%\" border = 0>";

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


//------------------------------------------------------------------------------------------------------------------------------
// display_paper_link
//
//  Display a paper as a title and link to the paper, using paperinfo(firstpg,lastpg)
// to calculate the number of pages in the article if possible.
//
//------------------------------------------------------------------------------------------------------------------------------

function display_paper_link($paperinfo, $paper) {
  // Get the names of the authors of this paper.
  $auths = "";
  if (is_array($authorlist = get_authors_by_listid($paper["paperid"]))) {
    $auths = make_namelist($authorlist, ", ", "No authors recorded.", 1);
  }

  // Try to find any files associated with the paper.
  $flinks = "";
  $files = get_paper_file_ids($paper["paperid"]);
  if (is_array($files)) {
    $flinks = "<span>[";
    $num = count($files) - 1;
    for ($count = 0; $count <= $num; $count++) {
      $fid = $files[$count]["fileid"];
      $fty = $files[$count]["filetype"];
      $flinks .= "<a href=\"send_file.php?num=$fid\">$fty</a>";
      if ($count < $num) {
        $flinks .= ", ";
      }
    }
    $flinks .= "]</span>";
  }

  // if we have the paperinfo (proceedings details like page start,end) then
  // use it to print the number of pages of the paper. Otherwise, just print name
  // and author.
  if (is_array($paperinfo)) {
    $totpgs = $paperinfo["lastpage"] - $paperinfo["firstpage"] + 1;
    echo "<li><i><a href=\"show_pap.php?f=1&amp;num=" . $paper["paperid"] . "\">" . $paper["title"] . "</a></i>";
    echo "&nbsp;" . $auths . "," . $totpgs . " pages $flinks ";
  }
  else {
    echo "<li><i><a href=\"show_pap.php?f=1&amp;num=" . $paper["paperid"] . "\">" . $paper["title"] . "</a></i>";
    echo "&nbsp;" . $auths . ", $flinks ";
  }

  // if logged in as admin, show add paper link
  if (session_is_registered("admin_user")) {
    do_html_url("edit_paper.php?f=2&amp;num=" . $paper["paperid"], "Add file...");
  }
  echo "</li>\n";
}


//------------------------------------------------------------------------------------------------------------------------------
// display_book_details
//
//   Given info on a proceeding and a flag, display information about the proceeding
// including the title, editors and papers presented. Papers are displayed as links
// (title & href) unless the flag is true.
//
//------------------------------------------------------------------------------------------------------------------------------

function display_book_details($proceeding, $verb) {
  // display all details about this proceeding
  if (is_array($proceeding)) {
    // papers is from "paperlist"
    $papers = get_paperinfo_by_proceedingid($proceeding["proceedingid"]);

    echo "<b>Title:</b> ";
    echo $proceeding["title"] . "<br>\n";
    if (!empty($proceeding["subtitle"])) {
      echo "Subtitle: ";
      echo $proceeding["subtitle"] . "<br>\n";
    }
    echo "<b>Editors:</b> ";
    $editorlist = get_editors_by_listid($proceeding["proceedingid"]);
    echo make_namelist($editorlist, ", ", "No editors recorded.", 1);

    echo "<br>\n";
    if (!empty($proceeding["isbn"])) {
      echo "<b>ISBN:</b> ";
      echo $proceeding["isbn"] . "<br>\n";
    }

    if (!empty($proceeding["issn"])) {
      echo "<b>ISSN:</b> ";
      echo $proceeding["issn"] . "<br>\n";
    }

    echo "<b>Papers:</b>\n";
    if ($papers != false) {
      if ($verb) {
        foreach ($papers as $paperinfo) {
          $paper = get_paper($paperinfo["paperid"]);
          display_paper_verbose($paper);
        }
      }
      else {
        echo "<ul>\n";
        foreach ($papers as $paperinfo) {
          $paper = get_paper($paperinfo["paperid"]);
          display_paper_link($paperinfo, $paper);
        }
        echo "</ul>\n";
      }
    }
    else {
      echo "No papers found.";
    }
    echo "<br>\n";

    return true;
  }
  else {
    echo "display_book_details: passed non-array<br>\n";
    return false;
  }
}


//------------------------------------------------------------------------------------------------------------------------------
//  display_file_details
//
//   For a given paper, create a comma separated list of hrefs to the stored files for
// that paper, if any.
//
//------------------------------------------------------------------------------------------------------------------------------

function display_file_details($paper) {
  $fids = get_paper_file_ids($paper["paperid"]);
  if (is_array($fids)) {
    echo "<b>Files:</b> ";
    foreach ($fids as $fid) {
      echo "<a href=\"send_file.php?num=" . $fid['fileid'] . "\">" . $fid['filetype'] . "</a>\n";
    }
  }
}
