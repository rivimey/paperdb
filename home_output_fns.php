<?php


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
    ?>
    <h2><?= $paper["title"] ?></h2>
    <p class="frontpage-authors">By <?= $auths ?></p>
    <div class="frontpage-abstract">
      <? if ($paper["abstract"] != "") {
        echo $paper["abstract"] . "\n";
      }
      ?></div><p align="right">
    <a href="/paperdb/show_pap.php?f=1&amp;num=<?= $num ?>">Complete record...</a>
  <?
  }
}

