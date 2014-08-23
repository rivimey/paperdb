<?php

require_once("compat_fns.php");

//------------------------------------------------------------------------------------------------------------------------------
// display_paper_frontpage
// 
//  Called from the index.php on the home page to display a paper extract. Code
// on the front page  selects a paper randomly and this fn displays it.
// 
//------------------------------------------------------------------------------------------------------------------------------

function display_paper_frontpage($paper) {
  // display all details about this proceeding
  // Get the names of the authors of this paper.
  $auths = "";
  if (is_array($paper) && is_array($authorlist = get_authors_by_listid($paper["paperid"]))) {
    $auths = make_namelist($authorlist, ", ", "[No authors recorded]", 1);
  }
  if (is_array($paper)) {
    // $url = $paper["paper_url"];
    $num = $paper["paperid"];
    do_html_heading($paper["title"], 2);
    do_para("By $auths", "class=\"frontpage-authors\"");
    echo "<div class=\"frontpage-abstract\">";
    if ($paper["abstract"] != "") {
      echo $paper["abstract"] . "\n";
    }
    echo "</div>";
    $link = " <a href = \"/paperdb/show_pap.php?f=1&amp;num=$num\" >Complete record ...</a>";
    do_para($link, "align = \"right\"");
  }
}

