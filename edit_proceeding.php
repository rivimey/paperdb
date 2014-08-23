<?php
/**
 * edit_proceeding.php
 *
 * Copyright (c) 2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Display the form that enables you to edit the paper details, or to add files.
 *
 * $Id: edit_proceeding.php,v 1.5 2005/09/27 21:44:46 rivimey Exp $
 */

require_once("compat_fns.php");
require_once('user_auth_fns.php');
require_once('misc_fns.php');
require_once('html_output_fns.php');
require_once('paper_fns.php');
require_once('admin_fns.php');
require_once('form_output_fns.php');


//------------------------------------------------------------------------------------------------------------------------------
//  do_edit_proceeding
//
//  
// 
//
//------------------------------------------------------------------------------------------------------------------------------

function do_edit_proceeding($num) {
  $proceeding = get_proceeding($num);
  if (!is_array($proceeding)) {
    do_html_header("Editing proceeding: ", "none");
    do_para("Couldn't find a proceeding for num=$num");
    do_html_footer();
    exit(0);
  }

  do_html_header("Editing proceeding: \"" . $proceeding["title"] . "\"", "none");

  $update_proceedings = FALSE;

  /*******************************************
   * Update the proceeding information.
   */
  echo "<b>Checking for local proceeding updates:</b><br>";
  $update_proceeding = FALSE;
  if (isset($_POST['title']) && $proceeding['title'] != $_POST['title']) {
    $proceeding['title'] = $_POST['title'];
    echo "&nbsp;Updating Title.<br>\n";
    $update_proceedings = TRUE;
  }
  if (isset($_POST['subtitle']) && $proceeding['subtitle'] != $_POST['subtitle']) {
    $proceeding['subtitle'] = $_POST['subtitle'];
    echo "&nbsp;Updating Subtitle.<br>\n";
    $update_proceedings = TRUE;
  }
  if (isset($_POST['series']) && $proceeding['series'] != $_POST['series']) {
    $proceeding['series'] = $_POST['series'];
    echo "&nbsp;Updating Series.<br>\n";
    $update_proceedings = TRUE;
  }
  if (isset($_POST['pubyear']) && $proceeding['pubyear'] != $_POST['pubyear']) {
    $proceeding['pubyear'] = $_POST['pubyear'];
    echo "&nbsp;Updating Year.<br>\n";
    $update_proceedings = TRUE;
  }
  if (isset($_POST['pubmonth']) && $proceeding['pubmonth'] != $_POST['pubmonth']) {
    $proceeding['pubmonth'] = $_POST['pubmonth'];
    echo "&nbsp;Updating Month.<br>\n";
    $update_proceedings = TRUE;
  }
  if (isset($_POST['pubday']) && $proceeding['pubday'] != $_POST['pubday']) {
    $proceeding['pubday'] = $_POST['pubday'];
    echo "&nbsp;Updating Day.<br>\n";
    $update_proceedings = TRUE;
  }
  if (isset($_POST['isbn']) && $proceeding['isbn'] != $_POST['isbn']) {
    $proceeding['isbn'] = $_POST['isbn'];
    echo "&nbsp;Updating ISBN.<br>\n";
    $update_proceedings = TRUE;
  }
  if (isset($_POST['issn']) && $proceeding['issn'] != $_POST['issn']) {
    $proceeding['issn'] = $_POST['issn'];
    echo "&nbsp;Updating ISSN Text.<br>\n";
    $update_proceedings = TRUE;
  }
  if (isset($_POST['totpages']) && $proceeding['totpages'] != $_POST['totpages']) {
    $proceeding['totpages'] = $_POST['totpages'];
    echo "&nbsp;Updating total page count Text.<br>\n";
    $update_proceedings = TRUE;
  }
  if (isset($_POST['volume']) && $proceeding['volume'] != $_POST['volume']) {
    $proceeding['volume'] = $_POST['volume'];
    echo "&nbsp;Updating Volume.<br>\n";
    $update_proceedings = TRUE;
  }
  if (isset($_POST['publisherid']) && $proceeding['publisherid'] != $_POST['publisherid']) {
    $proceeding['publisherid'] = $_POST['publisherid'];
    echo "&nbsp;Updating Publisher ID.<br>\n";
    $update_proceedings = TRUE;
  }

  /*******************************************
   * Do the main update
   */
  if ($update_proceedings) {
    if (update_proceeding_details($num, $proceeding)) {
      do_para("<b>Local proceeding updated.</b>");
    }
    else {
      do_para("Failed to update details.");
    }
  }

  /*******************************************
   * Edit the proceeding authors mapping.
   */
  $old_editors = get_editors_by_listid($num);
  $new_editors = $_POST['editors'];

  if (isset($_POST['editorid_0'])) {
    if (is_array($old_editors)) {
      echo "<b>Removing editors to correct the ordering.</b><br>";
      foreach ($old_editors as $o_ed) {
        if (delete_editor_from_proceeding($num, $o_ed['personid'])) {
          echo "&nbsp;Removed editor " . $o_ed['firstname'] . " " . $o_ed['lastname'] . " from proceeding.<br>\n";
        }
        else {
          echo "&nbsp;Failed to disassociate editor id " . $name . " from proceeding.<br>\n";
        }
      }
    }

    echo "<b>Inserting the editors in order:</b><br>";
    for ($i = 0; $i < count($new_editors); $i++) {
      $edid = $_POST['editorid_' . $i];
      $name = get_person_name($edid);
      insert_editor_to_proceeding($num, $edid, $i);
      echo "&nbsp;Inserted editor $name for proceeding at $i. [" . $_POST['editorid_' . $i] . "]<br>\n";
    }
  }

  do_para("<b>Update complete.</b>");

  echo "<p>Links:</p><ul><li>";
  do_html_url("show_proc.php?f=1&amp;num=" . $num, "To proceeding details.");
  echo "<li>";
  do_html_url("admin.php", "Back to administration menu");
  echo "<li>";
  do_html_url("index.php", "Back to main menu");
  echo "</ul>";
}

//------------------------------------------------------------------------------------------------------------------------------
//  collect_proc_info
//
//  
// 
//
//------------------------------------------------------------------------------------------------------------------------------

function collect_proc_info($num) {
  $proceeding = get_proceeding($num);
  if (!is_array($proceeding)) {
    do_html_header("Editing proceeding: ", "none");
    do_para("Couldn't find a proceeding for id=$num");
    do_html_footer();
    exit(0);
  }

  do_html_header("Editing proceeding: \"" . $proceeding["title"] . "\"", "none");

  do_para("You have selected the following updates to this proceeding. Some of them " .
    "might require additional information before they can be actioned. Please " .
    "check the following information carefully.");
  echo "<form action=\"edit_proceeding.php\" method=\"post\">\n";

  echo "  <input type=\"hidden\" name=\"num\" value=\"" . $num . "\">\n";
  echo "  <input type=\"hidden\" name=\"f\" value=\"4\">\n";

  /*******************************************
   * Update the proceeding information.
   */
  echo "<table bgcolor=\"#ffe88e\">";
  echo "<tr bgcolor=\"#ffd84c\"><td colspan=\"2\">Summary of proceeding updates</td></tr>\n";
  $update_proceedings = FALSE;
  $title = htmlentities(stripslashes($_POST['title']));
  if ($proceeding['title'] != $title) {
    $proceeding['title'] = $title;
    echo "<tr><td><b>Paper Title:</b></td>";
    $update_proceedings = TRUE;
    echo "<td><input type=\"text\" name=\"title\" value=\"" . htmlspecialchars($title) . "\" size=\"50\" readonly></td></tr>\n";
  }
  $subtitle = htmlentities(stripslashes($_POST['subtitle']));
  if ($proceeding['subtitle'] != $subtitle) {
    $proceeding['subtitle'] = $subtitle;
    echo "<tr><td><b>Subtitle:</b></td>";
    $update_proceedings = TRUE;
    echo "<td><input name=\"subtitle\" value=\"" . htmlspecialchars($subtitle) . "\" size=\"50\" readonly></td></tr>\n";
  }
  $isbn = stripslashes($_POST['isbn']);
  if ($proceeding['isbn'] != $_POST['isbn']) {
    $proceeding['isbn'] = $isbn;
    echo "<tr><td><b>ISBN</b></td>";
    $update_proceedings = TRUE;
    echo "<td><input type=\"text\" name=\"isbn\" value=\"" . htmlspecialchars($isbn) . "\" size=\"50\" readonly></td></tr>\n";
  }
  $issn = stripslashes($_POST['issn']);
  if ($proceeding['issn'] != $_POST['issn']) {
    $proceeding['issn'] = $issn;
    echo "<tr><td><b>ISBN</b></td>";
    $update_proceedings = TRUE;
    echo "<td><input type=\"text\" name=\"issn\" value=\"" . htmlspecialchars($issn) . "\" size=\"50\" readonly></td></tr>\n";
  }
  $publisherid = stripslashes($_POST['publisherid']);
  if ($proceeding['publisherid'] != $publisherid) {
    $proceeding['publisherid'] = $publisherid;
    echo "<tr><td><b>Publisher ID:</b></td>";
    $update_proceedings = TRUE;
    echo "<td><input type=\"text\" name=\"publisherid\" value=\"" . htmlspecialchars($publisherid) . "\" size=\"5\" readonly></td></tr>\n";
  }
  $volume = stripslashes($_POST['volume']);
  if ($proceeding['volume'] != $volume) {
    $proceeding['volume'] = $volume;
    echo "<tr><td><b>Series Volume:</b></td>";
    $update_proceedings = TRUE;
    echo "<td><input type=\"text\" name=\"volume\" value=\"" . htmlspecialchars($volume) . "\" size=\"5\" readonly></td></tr>\n";
  }
  $pubyear = stripslashes($_POST['pubyear']);
  if ($proceeding['pubyear'] != $pubyear) {
    $proceeding['pubyear'] = $pubyear;
    echo "<tr><td><b>Year:</b></td>";
    $update_proceedings = TRUE;
    echo "<td><input type=\"text\" name=\"pubyear\" value=\"" . htmlspecialchars($pubyear) . "\" size=\"5\" readonly></td></tr>\n";
  }
  $pubmonth = stripslashes($_POST['pubmonth']);
  if ($proceeding['pubmonth'] != $pubmonth) {
    $proceeding['pubmonth'] = $pubmonth;
    echo "<tr><td><b>Month:</b></td>";
    $update_proceedings = TRUE;
    echo "<td><input type=\"text\" name=\"pubmonth\" value=\"" . htmlspecialchars($pubmonth) . "\" size=\"5\" readonly></td></tr>\n";
  }
  $pubday = stripslashes($_POST['pubday']);
  if ($proceeding['pubday'] != $pubday) {
    $proceeding['pubday'] = $pubday;
    echo "<tr><td><b>Day:</b></td>";
    $update_proceedings = TRUE;
    echo "<td><input type=\"text\" name=\"pubday\" value=\"" . htmlspecialchars($pubday) . "\" size=\"5\" readonly></td></tr>\n";
  }
  $totpages = stripslashes($_POST['totpages']);
  if ($proceeding['totpages'] != $totpages) {
    $proceeding['totpages'] = $totpages;
    echo "<tr><td><b>Total Pages:</b></td>";
    $update_proceedings = TRUE;
    echo "<td><input type=\"text\" name=\"totpages\" value=\"" . htmlspecialchars($totpages) . "\" size=\"5\" readonly></td></tr>\n";
  }
  if (!$update_proceedings) {
    echo "<tr><td colspan=\"2\">You have not changed the proceeding details.</td></tr>\n";
  }
  echo "</table>\n";

  $old_editors = get_editors_by_listid($num); // can return false if no editors.
  $new_editors = $_POST['editors'];

  /* remember the new editors */
  $i = 0;
  foreach ($new_editors as $n_ed) {
    echo "<input readonly type=\"hidden\" name=\"editors[" . $i++ . "]\" value=\"" . $n_ed . "\">\n";
  }
  $n_eds = $i;
  $change_eds = FALSE;

  if (is_array($old_editors)) {
    /* editor was in new but not old: prompt for position (but only if more than one) */
    foreach ($new_editors as $n_ed) {
      $found = FALSE;
      foreach ($old_editors as $o_ed) {
        if ($o_ed['personid'] == $n_ed) {
          $found = TRUE;
          break;
        }
      }
      if (!$found) {
        $change_eds = TRUE;
        break;
      }
    }

    /* editor was in old but not new: prompt for position (but only if more than one) */
    foreach ($old_editors as $o_ed) {
      $found = FALSE;
      foreach ($new_editors as $n_ed) {
        if ($n_ed == $o_ed['personid']) {
          $found = TRUE;
          break;
        }
      }
      if (!$found) {
        $change_eds = TRUE;
        break;
      }
    }
  }

  if ($change_eds) {
    $i = 0;
    do_para("You have changed the editors. Please specify the ordering of the editors:");
    echo "<table bgcolor=\"#ffe88e\">\n";
    echo "<tr bgcolor=\"#ffd84c\"><td>Order</td><td>Editor</td></tr>\n";
    for ($i = 0; $i < $n_eds; $i++) {
      echo "<tr><td>$i</td><td>\n";
      echo "<select name=\"editorid_" . $i . "\">";
      foreach ($new_editors as $n_ed) {
        $name = get_person_name($n_ed);
        echo "    <option value=\"" . $n_ed . "\">$name</option>\n";
      }
      echo "</select>\n";
      echo "</td><td>\n";
    }
    echo "</table>\n";
  }
  echo "<p><input type=\"submit\" value=\"Do Update\"></p>";
  echo "</form>";

  do_html_url("admin.php", "Back to administration menu");
}

//------------------------------------------------------------------------------------------------------------------------------

session_start();

$f = isset($_GET['f']) ? $_GET['f'] : $_POST['f'];
$num = isset($_GET['num']) ? $_GET['num'] : $_POST['num'];;

if (!$num) {
  do_html_header("No proceeding...", "none");
  do_para("No proceeding specified\n");
  do_html_footer();
  exit;
}

if (check_admin_user()) {
  if ($f == 1) {
    $proceeding = get_proceeding($num);
    do_html_header("Updating proceeding \"" . $proceeding["title"] . "\"", "none");
    display_proceeding_form($proceeding);
    do_html_url("admin.php", "Back to administration menu");
  }
  elseif ($f == 3) { // f=3 => collect rest of info about proceeding
    collect_proc_info($num);
  }
  elseif ($f == 4) { // f=4 => do edit
    do_edit_proceeding($num);
  }
}
else {
  do_para("You are not authorised to view this page.");
}

do_html_footer();
