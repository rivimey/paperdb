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
require_once("compat_fns.php");

//------------------------------------------------------------------------------------------------------------------------------
//  do_html_header
//
//  Write out the generic stuff that each page should have. Mostly, this is 
// going to be related to the rest of the website rather than specific to paperdb.
//
//------------------------------------------------------------------------------------------------------------------------------

function do_html_header($title, $robots = 'ALL', $paper = null) {
  global $siteName, $defaultCharset;

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <title><?= $title ?> - <?= $siteName ?></title>
  <meta name="robots" content="<?= $robots ?>">
  <meta http-equiv="Content-Type" content="text/html; charset=$defaultCharset">
<?php
  if ($paper) {
    do_paper_metatags($title, $paper);
  }

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

/**
 * Complete the html page. Called once for each page written.
 */
function do_html_footer() {
  // do local header stuff and any sidebars, etc.
  if (function_exists('local_html_footer')) {
    local_html_footer();
  }
  echo "</body></html>\n";
}

/**
 * Write a heading
 *
 * @param $heading
 */
function do_html_heading($heading, $level = "1") {
  echo "<h$level>$heading</h$level>\n";
}

/**
 * Shortcut to write an html paragraph
 * @param $text
 */
function do_para($text, $attrs = "") {
  if (!empty($attrs) > "") {
    $attrs = " " . $attrs;
  }
  echo "<p$attrs>$text</p>\n";
}

/**
 * Write an a href link.
 *
 * @param $url
 * @param $name
 */
function do_html_url($url, $name) {
  echo "<a href=\"$url\">$name</a>\n";
}


/**
 * Write a Button tag with the indicated url
 *
 * @param $target
 * @param $alt
 */
function display_button($target, $alt) {
  echo "<button > <a href=\"/paperdb/$target\">$alt</a></button>\n";
}


/**
 * Write an Input tag
 *
 * @param $image
 * @param $alt
 */
function display_form_button($image, $alt) {
  echo "<input type = image src=\"images/$image" . ".gif\"
           alt=\"$alt\" border=0 height = 50 width = 135>";
}

