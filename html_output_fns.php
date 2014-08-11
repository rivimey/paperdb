<?php
/**
 * html_output_fns.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 *
 *
 * $Id: html_output_fns.php,v 1.5 2004/11/27 15:37:03 rivimey Exp $
 */

require_once("/etc/paperdb/config.php");

//------------------------------------------------------------------------------------------------------------------------------
//  do_html_header
//
//  Write out the generic stuff that each page should have. Mostly, this is 
// going to be related to the rest of the website rather than specific to paperdb.
//
//------------------------------------------------------------------------------------------------------------------------------

function do_html_header($title, $robots = 'ALL') {
  global $siteName, $defaultCharset;
  ?>
  <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
  <html>
<head>
  <title><?= $siteName ?> - <?= $title ?></title>
  <meta name="robots" content="<?= $robots ?>">
  <meta http-equiv="Content-Type" content="text/html; charset=$defaultCharset">
  <?php

  // do local header stuff and any sidebars, etc. Minimum is "</head><body>"
  if (function_exists('local_html_header')) {
    local_html_header();
  }
  else {
    echo "</head><body>\n";
  }
  if ($title) {
    do_html_heading($title);
  }
}

//------------------------------------------------------------------------------------------------------------------------------
//  do_html_footer
//
//  Complete the html page. Called once for each page written.
//
//------------------------------------------------------------------------------------------------------------------------------

function do_html_footer() {
  // do local header stuff and any sidebars, etc.
  if (function_exists('local_html_footer')) {
    local_html_footer();
  }
  echo "</body></html>\n";
}

//------------------------------------------------------------------------------------------------------------------------------
//  do_html_heading
//
//  Write a heading 
//
//------------------------------------------------------------------------------------------------------------------------------

function do_html_heading($heading) {
  echo "<h1>$heading</h1>\n";
}

//------------------------------------------------------------------------------------------------------------------------------
//  do_para
//
//  Shortcut to write an html paragraph
//
//------------------------------------------------------------------------------------------------------------------------------

function do_para($text) {
  echo "<p>$text</p>\n";
}

//------------------------------------------------------------------------------------------------------------------------------
//  do_html_url
//
//  Write an a href link.
//
//------------------------------------------------------------------------------------------------------------------------------

function do_html_url($url, $name) {
  echo "<a href=\"$url\">$name</a>\n";
}

//------------------------------------------------------------------------------------------------------------------------------
//  display_button
//
//  
//
//------------------------------------------------------------------------------------------------------------------------------

function display_button($target, $alt) {
  echo "<button > <a href=\"/paperdb/$target\">$alt</a></button>\n";
}

//------------------------------------------------------------------------------------------------------------------------------
//  display_form_button
//
//  
//
//------------------------------------------------------------------------------------------------------------------------------

function display_form_button($image, $alt) {
  echo "<input type = image src=\"images/$image" . ".gif\"
           alt=\"$alt\" border=0 height = 50 width = 135>";
}
