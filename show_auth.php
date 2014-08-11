<?php
/**
 * show_auth.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Display the form that enables you to edit the paper details, or to add files.
 *
 * $Id: show_auth.php,v 1.8 2005/09/27 21:32:20 rivimey Exp $
 */

require_once("html_output_fns.php");
require_once("paper_fns.php");
require_once("book_output_fns.php");
require_once("paper_output_fns.php");

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

    do_html_header("Author Index " . strtoupper(chr($s)) . " to " . strtoupper(chr($e)), "index,follow");

    $authorlist = get_authors();
    if ($authorlist) {
      echo "<p align=center>";
      echo "/&nbsp;<a href=\"show_auth.php?f=1&amp;s=a&amp;e=z\">All</a>&nbsp;/\n";
      echo "&nbsp;<a href=\"show_auth.php?f=1&amp;s=a&amp;e=d\">A-C</a>&nbsp;/\n";
      echo "&nbsp;<a href=\"show_auth.php?f=1&amp;s=d&amp;e=f\">D-F</a>&nbsp;/\n";
      echo "&nbsp;<a href=\"show_auth.php?f=1&amp;s=g&amp;e=i\">G-I</a>&nbsp;/\n";
      echo "&nbsp;<a href=\"show_auth.php?f=1&amp;s=j&amp;e=l\">J-L</a>&nbsp;/\n";
      echo "&nbsp;<a href=\"show_auth.php?f=1&amp;s=m&amp;e=o\">M-O</a>&nbsp;/\n";
      echo "&nbsp;<a href=\"show_auth.php?f=1&amp;s=p&amp;e=r\">P-R</a>&nbsp;/\n";
      echo "&nbsp;<a href=\"show_auth.php?f=1&amp;s=s&amp;e=u\">S-U</a>&nbsp;/\n";
      echo "&nbsp;<a href=\"show_auth.php?f=1&amp;s=v&amp;e=w\">V-W</a>&nbsp;/\n";
      echo "&nbsp;<a href=\"show_auth.php?f=1&amp;s=x&amp;e=z\">X-Z</a>&nbsp;/\n";
      echo "</p>";
      echo "<table>\n";
      echo "<tr><th>Name</th><th>Papers</th></tr>\n";
      foreach ($authorlist as $author) {
        $nm = $author["lastname"];
        $trans = array_flip(get_html_translation_table(HTML_ENTITIES));
        $nm = strtolower(strtr($nm, $trans));
        $onm = ord($nm);
        if ($onm == 216 || $onm == 248) /* sort oslash with o */ {
          $onm = ord("o");
        }
        if (($onm >= $s) && ($onm <= $e)) {
          echo "<tr><td>";
          echo "<a href=\"show_auth.php?f=2&amp;num=" . $author["personid"] . "\">" . make_name($author, 0) . "</a>\n";
          echo "</td><td>" . get_author_papercount($author["personid"]);
          echo "</td>";
          echo "</tr>";
        }
      }
      echo "</table>\n";
    }
    else {
      echo "<p> No authors in database. </p>\n";
    }
  }
  elseif ($f == 2) {
    $num = $_GET['num'];

    $author = get_person_name($num);
    do_html_header("Papers that include $author as an Author", "index,nofollow");
    $papers = get_papers_by_author($num);

    echo "<b>Name:</b> $author<br>";
    echo "<b>Papers:</b> <br>";

    foreach ($papers as $paper) {
      display_paper_verbose($paper, TRUE);

      // if logged in as admin, show edit links
      if (session_is_registered("admin_user")) {
        $paperid = $paper["paperid"];
        echo "<p align=right><small>";
        do_html_url("edit_paper.php?f=2&amp;num=$paperid", "Add File to Paper");
        echo "<br>";
        do_html_url("edit_paper.php?f=1&amp;num=$paperid", "Edit Paper");
        echo "</small></p>";
      }
    }
  }
  else {
    do_html_header("Undefined function $f", "noindex,nofollow");
    echo "show_auth: Undefined function $f\n";
  }
}
else {
  do_html_header("Undefined op $f", "nofollow");
  echo "show_auth: Undefined op $f\n";
}

do_html_footer();

