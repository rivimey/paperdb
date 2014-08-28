<?php
/**
 * show_editor.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Display the form that enables you to edit the paper details, or to add files.
 *
 * $Id: show_editor.php,v 1.4 2005/09/27 21:39:10 rivimey Exp $
 */

require_once("compat_fns.php");
require_once("html_output_fns.php");
require_once("paper_fns.php");
require_once("book_output_fns.php");

session_start();
$f = isset($_GET['f']) ? $_GET['f'] : 0;

if ($f > 0 && $f < 3) {
  if ($f == 1) {
    $s = isset($_GET['s']) ? $_GET['s'] : 'z';
    $e = isset($_GET['e']) ? $_GET['e'] : 'a';
    if (strlen($s) != 1 || strlen($e) != 1) {
      $s = "a";
      $e = "z";
    }
    $s = ord(strtolower($s));
    $e = ord(strtolower($e));

    do_html_header("Editor Index " . strtoupper(chr($s)) . " to " . strtoupper(chr($e)));

    $editorlist = get_editors();
    if ($editorlist) {
      echo "<p align=center>";
      echo "/&nbsp;<a href=\"show_editor.php?f=1&amp;s=a&amp;e=z\">All</a>&nbsp;/\n";
      echo "&nbsp;<a href=\"show_editor.php?f=1&amp;s=a&amp;e=d\">A-C</a>&nbsp;/\n";
      echo "&nbsp;<a href=\"show_editor.php?f=1&amp;s=d&amp;e=f\">D-F</a>&nbsp;/\n";
      echo "&nbsp;<a href=\"show_editor.php?f=1&amp;s=g&amp;e=i\">G-I</a>&nbsp;/\n";
      echo "&nbsp;<a href=\"show_editor.php?f=1&amp;s=j&amp;e=l\">J-L</a>&nbsp;/\n";
      echo "&nbsp;<a href=\"show_editor.php?f=1&amp;s=m&amp;e=o\">M-O</a>&nbsp;/\n";
      echo "&nbsp;<a href=\"show_editor.php?f=1&amp;s=p&amp;e=r\">P-R</a>&nbsp;/\n";
      echo "&nbsp;<a href=\"show_editor.php?f=1&amp;s=s&amp;e=u\">S-U</a>&nbsp;/\n";
      echo "&nbsp;<a href=\"show_editor.php?f=1&amp;s=v&amp;e=w\">V-W</a>&nbsp;/\n";
      echo "&nbsp;<a href=\"show_editor.php?f=1&amp;s=x&amp;e=z\">X-Z</a>&nbsp;/\n";
      echo "</p>";
      echo "<table>\n";
      echo "<tr><th>Name</th><th>Proceedings</th></tr>\n";
      foreach ($editorlist as $editor) {
        $nm = $editor["lastname"];
        $trans = array_flip(get_html_translation_table(HTML_ENTITIES));
        $nm = strtolower(strtr($nm, $trans));
        $onm = ord($nm);
        if ($onm == 216 || $onm == 248) /* sort oslash with o */ {
          $onm = ord("o");
        }
        if (($onm >= $s) && ($onm <= $e)) {
          echo "<tr><td>";
          echo "<a href=\"show_editor.php?f=2&amp;num=" . $editor["personid"] . "\">" . make_name($editor, 0) . "</a>\n";
          echo "</td><td>" . get_editor_papercount($editor["personid"]);
          echo "</td>";
          echo "</tr>";
        }
      }
      echo "</table>\n";
    }
    else {
      echo "<p> No editors in database. </p>\n";
    }
  }
  elseif ($f == 2) {
    $num = $_GET['num'];

    $editor = get_person_name($num);
    do_html_header("Proceedings that include $editor as an Editor", array('robots' => 'all'));
    $proceedings = get_proceedings_by_editor($num);

    echo "<b>Name:</b> $editor<br>";
    echo "<b>Proceedings:</b> <br>";

    foreach ($proceedings as $proceeding) {
      display_proceeding_details($proceeding, FALSE);

      // if logged in as admin, show edit links
      if (session_is_registered("admin_user")) {
        $proceedingid = $proceeding["proceedingid"];
        echo "<p align=right><small>";
        do_html_url("edit_proceeding.php?f=1&amp;num=$proceedingid", "Edit Proceeding");
        echo "</small></p>";
      }
    }
  }
  else {
    do_html_header("Undefined function $f", array('robots' => 'all'));
    echo "show_editor: Undefined function $f\n";
  }
}
else {
  do_html_header("Undefined op $f", array('robots' => 'all'));
  echo "show_editor: Undefined op $f\n";
}

do_html_footer();


