<?
/**
 * show_pap.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Display the paper in one of a number of formats..
 *
 * $Id: show_pap.php,v 1.7 2005/09/27 21:32:20 rivimey Exp $
 */

require_once("paper_fns.php");
require_once("html_output_fns.php");
require_once("refer_output_fns.php");
require_once("paper_output_fns.php");
require_once("book_output_fns.php");

session_start();
$f = isset($_GET['f']) ? $_GET['f'] : $_POST['f'];
  
if ($f > 0 && $f < 6 ) {
    $num = isset($_GET['num']) ? $_GET['num'] : $_POST['num'];

    $paper = get_paper($num);
    if ($f == 4 || $f == 5) {
        header("Content-Type: text/plain");
        header("Content-Disposition: inline");
    }
    else {
        do_html_header("Paper Details", "index,follow");
    }

    if ($maintain_stats and $f >= 1 and $f <= 5) {
        update_paper_accessed($num);
    }
    
    if ($f == 1) {
        display_paper_details($paper);
    }
    elseif ($f == 2) {
        display_paper_as_bibtex($paper, False);	// display html text
    }
    elseif ($f == 3) {
        display_paper_as_refer($paper, False);	// display html text
    }
    elseif ($f == 4) {
        display_paper_as_bibtex($paper, True);	// display plain text
        exit;
    }
    elseif ($f == 5) {
        display_paper_as_refer($paper, True);	// display plain text
        exit;
    }
}
else {
    do_html_header("Paper details", "nofollow");
    echo "show_pap: Unimplemented function\n";
}

    // if logged in as admin, show add, delete, edit cat links
if(session_is_registered("admin_user")) {
    echo "<p>Links:</p><ul><li>";
    do_html_url("edit_paper.php?f=2&amp;num=$num", "Add File to This Paper");
    echo "<li>";
    do_html_url("edit_paper.php?f=1&amp;num=$num", "Edit This Paper");
    echo "<li>";
    do_html_url("insert_papers_form.php", "Add New Paper,");
    echo "<li>";
    do_html_url("insert_person_form.php", "Add New Person,");
    echo "</ul>";
}

do_html_footer();
?>
