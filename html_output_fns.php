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

function do_html_header($title, $options = array()) {
  global $siteName, $defaultCharset;

  $defaultOptions = array(
    'robots' => 'ALL',
    'paper' => null,
    'charset' => $defaultCharset,
    'sitename' => $siteName,
    'content' => 'text/html',
  );
  $options = array_merge($defaultOptions, $options);

  echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">";
  echo "<html><head>";
  echo "<title>" . $options['$title'] . " - " . $options['sitename'] . "</title>";
  echo "<meta name=\"robots\" content=\"" . $options['robots'] . "\">";
  echo "<meta http-equiv=\"Content-Type\" content=\"" . $options['content'] . "; charset=" . $options['charset'] . "\">";

  if ($options['paper']) {
    do_paper_metatags($title, $options['paper']);
  }

  // do local header stuff and any sidebars, etc. Minimum is "</head><body>"
  if (function_exists('local_html_header')) {
    local_html_header();
  }
  else {
    echo "</head><body>\n";
  }
  if ($options['$title']) {
    do_html_heading($options['$title']);
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

