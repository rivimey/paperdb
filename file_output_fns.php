<?php


//------------------------------------------------------------------------------------------------------------------------------
//  display_file_details
//
//   For a given paper, create a comma separated list of hrefs to the stored files for
// that paper, if any.
//
//------------------------------------------------------------------------------------------------------------------------------

function display_file_details($paper) {
  $fids = get_paper_file_ids($paper["paperid"]);
  if (is_array($fids)) {
    echo "<b>Files:</b> ";
    foreach ($fids as $fid) {
      echo "<a href=\"send_file.php?num=" . $fid['fileid'] . "\">" . $fid['filetype'] . "</a>\n";
    }
  }
}
