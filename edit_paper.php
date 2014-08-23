<?php
/**
 * edit_paper.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Display the form that enables you to edit the paper details, or to add files.
 *
 * $Id: edit_paper.php,v 1.12 2005/09/27 21:45:41 rivimey Exp $
 */

require_once("compat_fns.php");
require_once('user_auth_fns.php');
require_once('misc_fns.php');
require_once('html_output_fns.php');
require_once('paper_fns.php');
require_once('admin_fns.php');
require_once('form_output_fns.php');
require_once('book_output_fns.php');

//-----------------------------------------------------------------------------
//  prompt_fp_lp
//
//  Emit the code that creates a table for the first page/last page
// prompts for proceeding info.
//
//-----------------------------------------------------------------------------

function prompt_fp_lp($title, $num) {
  echo "<table bgcolor=\"#ffe88e\">";
  echo "<tr bgcolor=\"#ffd84c\"><td colspan=\"2\">Proceeding \"" . $title . "\"</td></tr>\n";
  echo "<tr><td><b>First Page:</b> <input type=\"text\" name=\"procid_fp[" . $num . "]\" size=\"4\"><br />\n";
  echo "</td><td>";
  echo "<b>Last Page:</b>  <input type=\"text\" name=\"procid_lp[" . $num . "]\" size=\"4\"></td></tr>\n";
  echo "</table>";
}

//-----------------------------------------------------------------------------
//  collect_paper_info
//
//  Examine the information collected in the first form and summarise the
// changes made to the paper. If necessary, for example with author ordering,
// prompt for more information. 
//
//-----------------------------------------------------------------------------

function collect_paper_info($num) {
  $paper = get_paper($num);
  if (!is_array($paper)) {
    do_html_header("Editing paper: ", "none");
    do_para("Couldn't find a paper for id=$num");
    do_html_footer();
    exit(0);
  }

  do_html_header("Editing paper: \"" . $paper["title"] . "\"", "none");

  do_para("You have selected the following updates to this paper. Some of them " .
    "might require additional information before they can be actioned. Please " .
    "check the following information carefully.");
  echo "<form action=\"edit_paper.php\" method=\"post\">\n";

  echo "  <input type=\"hidden\" name=\"num\" value=\"" . $num . "\">\n";
  echo "  <input type=\"hidden\" name=\"f\" value=\"4\">\n";

  /*******************************************
   * Update the paper information.
   */
  echo "<table bgcolor=\"#ffe88e\">";
  echo "<tr bgcolor=\"#ffd84c\"><td colspan=\"2\">Summary of paper updates</td></tr>\n";
  $update_papers = FALSE;
  $title = htmlentities(stripslashes($_POST['title']));
  if ($paper['title'] != $title) {
    $paper['title'] = $title;
    echo "<tr><td><b>Paper Title:</b></td>";
    $update_papers = TRUE;
    echo "<td><input type=\"text\" name=\"title\" value=\"" . htmlspecialchars($title) . "\" size=\"50\" readonly></td></tr>\n";
  }
  $abstract = htmlentities(stripslashes($_POST['abstract']));
  if ($paper['abstract'] != $abstract) {
    $paper['abstract'] = $abstract;
    echo "<tr><td><b>Abstract:</b></td>";
    $update_papers = TRUE;
    echo "<td><textarea name=\"abstract\" rows=\"5\" cols=\"50\" readonly>" . htmlspecialchars($abstract) . "</textarea></td></tr>\n";
  }
  $paper_url = stripslashes($_POST['paper_url']);
  if ($paper['paper_url'] != $_POST['paper_url']) {
    $paper['paper_url'] = $paper_url;
    echo "<tr><td><b>Paper URL</b></td>";
    $update_papers = TRUE;
    echo "<td><input type=\"text\" name=\"paper_url\" value=\"" . htmlspecialchars($paper_url) . "\" size=\"50\" readonly></td></tr>\n";
  }
  $reftext = stripslashes($_POST['reftext']);
  if ($paper['reftext'] != $reftext) {
    $paper['reftext'] = $reftext;
    echo "<tr><td><b>QuickRef Text:</b></td>";
    $update_papers = TRUE;
    echo "<td><input type=\"text\" name=\"reftext\" value=\"" . htmlspecialchars($reftext) . "\" size=\"50\" readonly></td></tr>\n";
  }
  if (!$update_papers) {
    echo "<tr><td colspan=\"2\">You have not changed the paper title, abstract, URL or RefText.</td></tr>\n";
  }
  echo "</table>\n";

  /*******************************************
   * Edit the paper proceedings mapping.
   */
  $old_procs = get_proceedings_by_paperid($num);
  $new_procs = $_POST['procid'];

  /* list of selected proceedings from database */
  echo "<input type=\"hidden\" name=\"n_procid\" value=\"" . count($new_procs) . "\">\n";
  $i = 0;
  foreach ($new_procs as $n_pr) {
    echo "<input type=\"hidden\" name=\"procid[" . $i++ . "]\" value=\"" . $n_pr . "\">\n";
  }

  /*
   * Look for proceedings the user has added and emit form elements for them,
   */
  do_para("Checking for new proceeding associations:");
  $done_explan = FALSE;
  if (isset($new_procs) && is_array($old_procs)) {
    do_para("new_procs set and old_procs is array (" . count($new_procs) . ").");
    foreach ($new_procs as $n_pr) {
      $found = FALSE;
      foreach ($old_procs as $o_pr) {
        if ($o_pr['proceedingid'] == $n_pr) {
          $found = TRUE;
        }
      }
      do_para("Looked for $n_pr in old_procs : $found.");
      if (!$found) {
        if (!$done_explan) {
          do_para("You have added one or more proceedings associations for this paper. " .
            "Please define where this paper appears in them.");
          $done_explan = TRUE;
        }
        $proceeding = get_proceeding($n_pr);
        prompt_fp_lp($proceeding['title'], $n_pr);
      }
    }
  }
  else {
    /*
     * one or both of the proceedings arrays not defined.. work out what next
     */
    if (!$done_explan) {
      do_para("You have added one or more proceedings associations for this paper. " .
        "Please define where this paper appears in them.");
      $done_explan = TRUE;
    }
    if (!is_array($old_procs) && isset($new_procs)) {
      foreach ($new_procs as $n_pr) {
        $proceeding = get_proceeding($n_pr);
        prompt_fp_lp($proceeding['title'], $n_pr);
      }
    }
  }

  /*******************************************
   * Edit the paper authors mapping.
   */
  $old_authors = get_authors_by_paperid($num); // can return false if no authors.
  $new_authors = $_POST['authors'];

  $i = 0;
  foreach ($new_authors as $n_au) {
    echo "<input readonly type=\"hidden\" name=\"authorid[" . $i++ . "]\" value=\"" . $n_au . "\">\n";
  }
  $n_auths = $i;
  $new_auths = FALSE;

  /* author was in new but not old: prompt for position (but only if more than one) */
  foreach ($new_authors as $n_au) {
    $found = FALSE;
    if (is_array($old_authors)) {
      foreach ($old_authors as $o_au) {
        if ($o_au['authorid'] == $n_au) {
          $found = TRUE;
          break;
        }
      }
    }
    if (!$found) {
      $new_auths = TRUE;
    }
  }
  if ($new_auths) {
    $i = 0;
    do_para("You have added authors. Please specify the ordering of the authors:");
    echo "<table bgcolor=\"#ffe88e\">\n";
    echo "<tr bgcolor=\"#ffd84c\"><td>Order</td><td>Author</td></tr>\n";
    for ($i = 0; $i < $n_auths; $i++) {
      echo "<tr><td>$i</td><td>\n";
      echo "<select name=\"authorord[" . $i . "]\">";
      foreach ($new_authors as $n_au) {
        $name = get_person_name($n_au);
        echo "    <option value=\"" . $n_au . "\">$name</option>\n";
      }
    }
    echo "</select>\n";
    echo "</td><td>\n";
    echo "</table>\n";
  }
  echo "<p><input type=\"submit\" value=\"Do Update\"></p>";
  echo "</form>";

  do_html_url("admin.php", "Back to administration menu");
}

//-----------------------------------------------------------------------------
//  do_edit_paper
//
//  
// 
//
//-----------------------------------------------------------------------------

function do_edit_paper($num) {
  global $maintain_stats;

  $paper = get_paper($num);
  if (!is_array($paper)) {
    do_html_header("Editing paper: ", "none");
    do_para("Couldn't find a paper for id=$num");
    do_html_footer();
    exit(0);
  }

  do_html_header("Editing paper: \"" . $paper["title"] . "\"", "none");

  /*******************************************
   * Update the paper information.
   */
  echo "<b>Checking for local paper updates:</b><br>";
  $update_papers = FALSE;
  if (isset($_POST['title']) && $paper['title'] != $_POST['title']) {
    $paper['title'] = $_POST['title'];
    echo "&nbsp;Updating Paper Title.<br>\n";
    $update_papers = TRUE;
  }
  if (isset($_POST['abstract']) && $paper['abstract'] != $_POST['abstract']) {
    $paper['abstract'] = $_POST['abstract'];
    echo "&nbsp;Updating Abstract.<br>\n";
    $update_papers = TRUE;
  }
  if (isset($_POST['paper_url']) && $paper['paper_url'] != $_POST['paper_url']) {
    $paper['paper_url'] = $_POST['paper_url'];
    echo "&nbsp;Updating Paper URL.<br>\n";
    $update_papers = TRUE;
  }
  if (isset($_POST['reftext']) && $paper['reftext'] != $_POST['reftext']) {
    $paper['reftext'] = $_POST['reftext'];
    echo "&nbsp;Updating QuickRef Text.<br>\n";
    $update_papers = TRUE;
  }
  if ($update_papers) {
    if (update_paper_details($num, $paper['title'], $paper['paper_url'], $paper['reftext'], $paper['abstract'])) {
      do_para("<b>Local paper updated.</b>");
    }
    else {
      do_para("Failed to update details.");
    }
  }

  /*******************************************
   * Edit the paper proceedings mapping.
   */
  $old_procs = get_proceedings_by_paperid($num);
  $new_procs = $_POST['procid'];

  echo "<b>Checking for proceedings updates:</b><br>";
  if (isset($new_procs) && is_array($old_procs)) {
    foreach ($old_procs as $o_pr) {
      $found = FALSE;
      foreach ($new_procs as $n_pr) {
        if ($o_pr['proceedingid'] == $n_pr) {
          $found = TRUE;
        }
      }
      if (!$found) {
        /* proceeding was in old but not new: delete from database */
        if (delete_proceeding_for_paper($num, $o_pr['proceedingid'])) {
          echo "&nbsp;Unassociate proceeding id " . $o_pr['proceedingid'] . "<br>\n";
        }
        else {
          echo "&nbsp;Failed to unassociate proceeding id " . $o_pr['proceedingid'] . " with paper.<br>\n";
        }
      }
    }
    if (isset($_POST['procid_fp'])) {
      $firstpgs = $_POST['procid_fp'];
      $lastpgs = $_POST['procid_lp'];
    }

    foreach ($new_procs as $n_pr) {
      $found = FALSE;
      foreach ($old_procs as $o_pr) {
        if ($o_pr['proceedingid'] == $n_pr) {
          $found = TRUE;
        }
      }
      if (!$found) {
        if (isset($_POST['procid_fp'])) {
          $firstpg = $firstpgs[$n_pr];
          $lastpg = $lastpgs[$n_pr];
        }
        else {
          $firstpg = 0;
          $lastpg = 0;
        }
        /* proceeding was in new but not old: insert into database */
        if (insert_proceeding_for_paper($num, $n_pr, $firstpg, $lastpg)) {
          echo "&nbsp;Associate proceeding id " . $n_pr . "<br>\n";
        }
        else {
          echo "&nbsp;Failed to associate proceeding id " . $n_pr . " with paper.<br>\n";
        }
      }
    }
  }
  else {
    /*
     * one or both of the proceedings arrays not defined.. work out what next
     */
    if (!is_array($old_procs)) { // no proceedings currently set
      if (isset($new_procs)) { // but have some now
        foreach ($new_procs as $n_pr) {
          if (insert_proceeding_for_paper($num, $n_pr, $_POST['procid_fp'][$n_pr], $_POST['procid_lp'][$n_pr])) {
            echo "&nbsp;Associate proceeding id " . $n_pr . "<br>\n";
          }
          else {
            echo "&nbsp;Failed to associate proceeding id " . $n_pr . " with paper.<br>\n";
          }
        }
      }
    }
    elseif (!isset($new_procs)) { // no proceedings defined but there used to be.
      foreach ($old_procs as $o_pr) {
        /* proceeding was in old but not new: delete from database */
        if (delete_proceeding_for_paper($num, $o_pr['proceedingid'])) {
          echo "&nbsp;Unassociate proceeding id " . $o_pr['proceedingid'] . "<br>\n";
        }
        else {
          echo "&nbsp;Failed to unassociate proceeding id " . $o_pr['proceedingid'] . " from paper.<br>\n";
        }
      }
    }
  }


  /*******************************************
   * Edit the paper authors mapping.
   */
  $old_authors = get_authors_by_paperid($num);
  $new_authors = $_POST['authorid'];

  if (isset($_POST['authorid'])) {
    if (is_array($old_authors)) {
      echo "<b>Removing authors to correct the ordering.</b><br>";
      foreach ($old_authors as $o_au) {
        $name = get_person_name($o_au['authorid']);
        if (delete_author_from_paper($num, $o_au['authorid'])) {
          echo "&nbsp;Remove author " . $name . " from paper.<br>\n";
        }
        else {
          echo "&nbsp;Failed to disassociate author id " . $name . " from paper.<br>\n";
        }
      }
    }

    echo "<b>Inserting the authors in order:</b><br>";
    for ($i = 0; $i < count($new_authors); $i++) {
      $aid = $new_authors[$i];
      $ordering[$aid] = $i;
#echo "&nbsp;Author $aid ordering[$aid] = $i.<br>\n";
    }
    foreach ($new_authors as $n_au) {
      /* author was in new but not old: insert into database */
      $name = get_person_name($n_au);
#echo "&nbsp;Insert Author $name into paper $num, $ordering[$n_au].<br>\n";
      if (insert_author_to_paper($num, $n_au, $ordering[$n_au])) {
        echo "&nbsp;Associated author " . $name . " for paper.<br>\n";
      }
      else {
        echo "&nbsp;Failed to associate author " . $name . " with paper.<br>\n";
      }
    }
  }

  if ($maintain_stats) {
    update_paper_modified($num);
  }

  do_para("<b>Update complete.</b>");

  echo "<p>Links:</p><ul><li>";
  do_html_url("show_pap.php?f=1&amp;num=" . $num, "To paper details.");
  echo "<li>";
  do_html_url("admin.php", "Back to administration menu");
  echo "<li>";
  do_html_url("index.php", "Back to main menu");
  echo "</ul>";
}

//---------------------------------------------------------------------------

session_start();

$f = isset($_GET['f']) ? $_GET['f'] : $_POST['f'];
$num = isset($_GET['num']) ? $_GET['num'] : $_POST['num'];;

if (!$num) {
  do_html_header("No paper...", "none");
  do_para("No paper specified\n");
  do_html_footer();
  exit;
}

if (check_admin_user()) {
  if ($f == 1) {
    if (isset($num)) {
      $paper = get_paper($num);
      if (is_array($paper)) {
        do_html_header("Updating paper \"" . $paper["title"] . "\"", "none");
        display_papers_form($paper);
        do_html_url("admin.php", "Back to administration menu");
      }
    }
  }
  elseif ($f == 2) {
    $paper = get_paper($num);
    $auths = "";
    if (is_array($paper)) {
      $authorlist = get_authors_by_listid($paper["paperid"]);
      if (is_array($authorlist)) {
        $auths = make_namelist($authorlist, " and ", "No authors recorded.", 1);
      }
      do_html_header("Adding file to paper \"" . $paper["title"] . "\"", "none");
      display_addfile_form($paper, 1, $auths);
    }
  }
  elseif ($f == 3) { // f=3 => collect rest of info about paper/proceeding
    collect_paper_info($num);
  }
  elseif ($f == 4) { // f=4 => do edit
    do_edit_paper($num);
  }
  elseif ($f == 5) { // f=5 => delete single paper from db
    do_para("Not supported yet.");
  }
}
else {
  do_para("You are not authorised to view this page.");
}

do_html_footer();
