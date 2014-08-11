<?
/**
 * create_refs.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Create paper references where they don't exist already
 *
 * $Id: create_refs.php,v 1.1 2004/11/04 17:52:13 rivimey Exp $
 */

require_once("html_output_fns.php");
require_once("paper_fns.php");
require_once("admin_fns.php");

session_start();

do_html_header("Create Ref Texts for all Proceedings", "noindex");

$allrefs = array();

$proc_array = get_proceedings(1);
if ($proc_array) {
  foreach ($proc_array as $thisproc) {
    $procid = $thisproc["proceedingid"];
    $paper_array = get_papers_by_proceedingid($procid);

    if ($paper_array) {

      foreach ($paper_array as $thispaper) {
        $papid = $thispaper["paperid"];
        if (!isset($thispaper["reftext"]) || $thispaper["reftext"] == "") {
          $authors_array = get_authors_by_listid($papid);
          $auid = "";
          $i = 0;
          foreach ($authors_array as $author) {
            if (++$i > 2) {
              continue;
            }
            // $first = trim(html_entity_decode($author["firstname"]));
            $last = trim(html_entity_decode($author["lastname"]));

            // Some characters are better excluded.
            $last = str_replace("-", "", $last);
            $last = str_replace("'", "", $last);

            // Surname only:
            $auid .= substr($last, 0, 9);
          }
          // convert back to &ents; form
          $auid = htmlentities($auid);

          // go to 2 digit years - this is good enough I believe.
          $yr = substr($thisproc["pubyear"], 2, 2);

          // ...and this is it. Check that it hasn't been used already...
          $ref = $auid . $yr;

          if (isset($allpaps[$ref]) || isset($allpaps[$ref . "a"])) {

            if (!isset($allpaps[$ref . "a"])) {
              # make the other reference have an 'a' and unset original.
              $othid = $allpaps[$ref];
              $allpaps[$ref . "a"] = $othid;
              $allrefs[$othid] = $ref . "a";
              unset($allpaps[$ref]);
            }

            # find first ref not taken
            for ($i = ord("b"); isset($allpaps[$ref . chr($i)]) && $i < ord("z"); $i++) {
            }
            $ref .= chr($i);
          }

          $allrefs[$papid] = $ref;
          $allpaps[$ref] = $papid;

        }
        else {
          $allrefs[$papid] = "";
        }
      }
    }
  }

  $query = "";

  foreach ($proc_array as $thisproc) {

    $procid = $thisproc["proceedingid"];
    $paper_array = get_papers_by_proceedingid($procid);

    if ($paper_array) {
      foreach ($paper_array as $thispaper) {
        $papid = $thispaper["paperid"];
        if ($allrefs[$papid] != "") {
          echo $papid . " - " . $thispaper["title"] . " - " . $allrefs[$papid] . "<br>\n";
          echo $papid . " - " . $allrefs[$papid] . "<br>\n";
          $query = "update papers set reftext = " . sqlvalue($allrefs[$papid]) . " where paperid = " . sqlvalue($papid, "N") . "\n";
          if (generic_update($query)) {
            echo "Updated $papid with " . $allrefs[$papid] . " ok.<br>\n";
          }
          else {
            echo "Updated $papid with " . $allrefs[$papid] . " FAIL.<br>\n";
          }
        }
      }
    }
  }
}
else {
  ?>  <p> No proceedings available in database. </p>
<?php
}
do_html_footer();


?>

