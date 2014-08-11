<?
/**
 * list_authors.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Display a page that takes you to author lists by surname initial.
 *
 * $Id: list_authors.php,v 1.3 2004/11/16 00:52:20 rivimey Exp $
 */

require_once("html_output_fns.php");

session_start();

do_html_header("List all Authors", "none");

do_para("Please select the link by the initial letter of the " .
  "author's surname, or All to list every author.");
echo "<p align=center>";
echo "&nbsp;<a href=\"show_auth.php?f=1&amp;s=a&amp;e=d\">A-C</a>&nbsp;/\n";
echo "&nbsp;<a href=\"show_auth.php?f=1&amp;s=d&amp;e=f\">D-F</a>&nbsp;/\n";
echo "&nbsp;<a href=\"show_auth.php?f=1&amp;s=g&amp;e=i\">G-I</a>&nbsp;/\n";
echo "&nbsp;<a href=\"show_auth.php?f=1&amp;s=j&amp;e=l\">J-L</a>&nbsp;/\n";
echo "&nbsp;<a href=\"show_auth.php?f=1&amp;s=m&amp;e=o\">M-O</a>&nbsp;/\n";
echo "&nbsp;<a href=\"show_auth.php?f=1&amp;s=p&amp;e=r\">P-R</a>&nbsp;/\n";
echo "&nbsp;<a href=\"show_auth.php?f=1&amp;s=s&amp;e=u\">S-U</a>&nbsp;/\n";
echo "&nbsp;<a href=\"show_auth.php?f=1&amp;s=v&amp;e=w\">V-W</a>&nbsp;/\n";
echo "&nbsp;<a href=\"show_auth.php?f=1&amp;s=x&amp;e=z\">X-Z</a>&nbsp;/\n";
echo "&nbsp;<a href=\"show_auth.php?f=1&amp;s=a&amp;e=z\">All</a>&nbsp;\n";
echo "</p>";
do_html_footer();
