<?php
/**
 * file_output_fns.php
 *
 * Copyright (c) 2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Functions returning information about files associated with papers.
 *
 */


//------------------------------------------------------------------------------------------------------------------------------
//  display_file_links
//
//   Echo a comma separated list of hrefs to files for a paper.
// If there are any files, the prefix and suffix are included before and after the list.
//
//------------------------------------------------------------------------------------------------------------------------------

function display_file_links($paper, $verbose, $prefix = "", $suffix = "") {
  // This is much more complex than it really needs to be; the plan is to get
  // rid of embedded files eventually.

  $links = get_paper_links($paper["paperid"], $verbose);
  $files = get_paper_file_ids($paper["paperid"]);

  if (is_array($links) || is_array($files)) {
    echo $prefix;
  }

  if (is_array($links)) {
    $num = count($links) - 1;
    for ($count = 0; $count <= $num; $count++) {
      $href = $links[$count]["href"];
      $title = $links[$count]["title"];
      $type = $links[$count]["filetype"];
      if ($type) {
        if ($verbose) {
          $title = "$title ($type)";
        }
        else {
          // This matches existing behaviour: just show "PDF" in short lists.
          $title = $type;
        }
      }
      echo "<a href=\"" . htmlentities($href) . "\">" . htmlentities($title) . "</a>";
      if ($count < $num || is_array($files)) {
        echo ", ";
      }
    }
  }

  if (is_array($files)) {
    $num = count($files) - 1;
    for ($count = 0; $count <= $num; $count++) {
      $fid = $files[$count]["fileid"];
      $fty = $files[$count]["filetype"];
      echo "<a href=\"send_file.php?num=$fid\">$fty</a>";
      if ($count < $num) {
        echo ", ";
      }
    }
  }

  if (is_array($links) || is_array($files)) {
    echo $suffix;
  }
}
