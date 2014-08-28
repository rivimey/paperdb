<?php
/**
 * paper_output_fns.php
 *
 * Copyright (c) 2001-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Routines that display specific views of a paper, usually but not always
 * for a specific situation.
 *
 * $Id: paper_output_fns.php,v 1.11 2005/09/27 21:38:06 rivimey Exp $
 */

require_once("compat_fns.php");
require_once("paper_fns.php");
require_once("misc_fns.php");
require_once("html_output_fns.php");
require_once("file_output_fns.php");

/**
 * Output the metatags for the paper.
 */
function do_paper_metatags($title, $paper) {
  $paperid = $paper["paperid"];

  echo "<meta name=\"DC.Language\" content=\"en\" />";
  echo "<meta name=\"DC.Title\" content=\"" .  $title . "\" />";
  foreach ($authorlist as $item) {
    $author = make_name($item, 6);
    echo "<meta name=\"DC.contributor\" content=\"" .  $author . "\" />";
  }
  echo "<meta name=\"citation_title\" content=\"" .  $title . "\" />";
  $authorlist = get_authors_by_listid($paper["paperid"]);
  foreach ($authorlist as $item) {
    $author = make_name($item, 6);
    echo "<meta name=\"citation_author\" content=\"" .  $author . "\" />";
  }
  echo "<meta name=\"citation_access\" content=\"all\" />";
  echo "<meta name=\"og:type\" content=\"article\" />";

  $proceedings = get_proceedings_by_paperid($paperid);
  if (is_array($proceedings)) {
    foreach ($proceedings as $proc) {
      $pages = get_paperinfo_by_paperid_and_procid($paperid, $proc["proceedingid"]);
      echo "<meta name=\"citation_journal\" content=\"" .  $proc['title'] . "\">";
      echo "<meta name=\"citation_firstpage\" content=\"" .  $pages["firstpage"] . "\">";
      echo "<meta name=\"citation_publication_date\" content=\"" . $proc["pubyear"]  . "\">";
    }
    echo "</tr>\n";
  }
}

//--------------------------------------------------------------------------------------
//   display_paper_link
//
//  Display a paper as a title and link to the paper, using paperinfo(firstpg,lastpg)
// to calculate the number of pages in the article if possible.
//
//--------------------------------------------------------------------------------------

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
    echo '[';
    do_html_url("edit_paper.php?f=2&amp;num=" . $paper["paperid"], "Add file,");
    do_html_url("edit_paper.php?f=1&amp;num=" . $paper["paperid"], "Edit paper");
    //do_html_url("insert_person_form.php", "Add new person,");
    echo ']';
  }
  echo "</li>\n";
}


//--------------------------------------------------------------------------------------
//   display_paper_verbose
//
//  Display a paper in a table format for use in the long book list page
//
//--------------------------------------------------------------------------------------

function display_paper_verbose($paper, $proceedingstoo = FALSE) {
  // display all details about this proceeding
  // Get the names of the authors of this paper.
  $auths = "";
  if (is_array($paper) && is_array($authorlist = get_authors_by_listid($paper["paperid"]))) {
    $auths = make_namelist($authorlist, ", ", "No authors recorded.", 1);
  }
  if (is_array($paper)) {
    $url = $paper["paper_url"];
    $num = $paper["paperid"];
    $comments = get_comments_by_paper($num);

    ?>
    <hr size="1">
    <table cellpadding="2" cellspacing="1" border="0" width="100%">
    <tr>
      <td width="100">Title:</td>
      <td colspan="3"><b><?= $paper["title"] ?></b>
        <?php  if (session_is_registered("admin_user")) {
          do_html_url("edit_paper.php?f=1&amp;num=$num", "Edit");
        }
        ?>
      </td>
    </tr>
    <tr>
      <td>Authors:</td>
      <td colspan="3"><?= $auths ?></td>
    </tr>
    <?php if ($paper["paper_url"] != "") { ?>
      <tr>
        <td valign="top">URL:</td>
        <td colspan="3"><a href="<?= $url ?>"><?= $url ?></a></td>
      </tr>
    <?php } ?>
    <?php if ($paper["abstract"] != "") { ?>
      <tr>
        <td valign="top">Abstract:</td>
        <td colspan="3" class="abstract"><?= $paper["abstract"] ?></td>
      </tr>
    <?php } ?>
    <tr>
      <td>Bibliography:</td>
      <td>
        Web page:<a href="show_pap.php?f=2&amp;num=<?= $num ?>">BibTEX</a>,
        <a href="show_pap.php?f=3&amp;num=<?= $num ?>">Refer</a><br />
        Plain text: <a href="show_pap.php?f=4&amp;num=<?= $num ?>">BibTEX</a>,
        <a href="show_pap.php?f=5&amp;num=<?= $num ?>">Refer</a>
      </td>
    </tr>
    <?php
    if ($proceedingstoo) {
      $proceedings = get_proceedings_by_paperid($paper["paperid"]);
      if (is_array($proceedings)) {
        echo "<tr><td>In Proceeding:</td>";
        foreach ($proceedings as $proc) {
          echo "<td>";
          display_proceeding_details($proc, $paper["paperid"], FALSE);
          echo "</td>\n";
        }
        echo "</tr>\n";
      }
    }
    $flinks = "";
    $files = get_paper_file_ids($paper["paperid"]);
    if (is_array($files) || session_is_registered("admin_user")) {
      $flinks = "<tr><td valign=\"top\">Files:</td><td colspan=\"3\"><span>";
      $num = count($files) - 1;
      for ($count = 0; $count <= $num; $count++) {
        $fid = $files[$count]["fileid"];
        $fty = $files[$count]["filetype"];
        $flinks .= "<a href=\"send_file.php?num=$fid\">$fty</a>";
        if ($count < $num) {
          $flinks .= ", ";
        }
      }
      $flinks .= "</span>";
      echo "$flinks</td></tr>\n";
    }
    echo "</table>\n";
  }
}


//--------------------------------------------------------------------------------------
//   display_paper_details
//
//  Display a paper with Authors, abstract etc. and details of the files (PS, PDF etc)
// stored.
//
//--------------------------------------------------------------------------------------

function display_paper_details($paper) {
  if (is_array($paper)) {
    do_para("<b>" . $paper["title"] . "</b>");
    echo "<p><b>Authors:</b> ";

    $authorlist = get_authors_by_listid($paper["paperid"]);
    $proceedings = get_proceedings_by_paperid($paper["paperid"]);

    echo make_namelist($authorlist, ", ", "No authors recorded.", 0);
    echo "</p>\n";

    if ($paper["paper_url"] != "") {
      echo "<p><b>Paper URL:</b> ";
      echo $paper["paper_url"] . "</p>\n";
    }
    echo "<p><b>Abstract:</b></p>";
    echo wordwrap($paper["abstract"], 80) . "\n";
    echo "\n";

    if (is_array($proceedings)) {
      echo "<p><b>Proceedings:</b></p>";
      foreach ($proceedings as $proc) {
        display_proceeding_details($proc, $paper["paperid"], TRUE);
      }
    }
    else {
      do_para("<i>[There are no proceedings associated with this paper.]</i>");
    }
    display_file_details($paper);

    do_para("<b>This record in other formats:</b>");
    echo "Web page: <a href=\"show_pap.php?f=2&amp;num=" . $paper["paperid"] . "\">BibTEX</a>, \n";
    echo "<a href=\"show_pap.php?f=3&amp;num=" . $paper["paperid"] . "\">Refer</a><br>\n";
    echo "Plain text: <a href=\"show_pap.php?f=4&amp;num=" . $paper["paperid"] . "\">BibTEX</a>, \n";
    echo "<a href=\"show_pap.php?f=5&amp;num=" . $paper["paperid"] . "\">Refer</a><br>\n";
  }
}
