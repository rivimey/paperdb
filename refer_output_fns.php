<?php
/**
 * refer_output_fns.php
 *
 * Copyright (c) 2000-2004 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Functions dealing with bibtex and refer.
 *
 * $Id: refer_output_fns.php,v 1.10 2005/08/12 21:04:44 rivimey Exp $
 */

require_once("user_auth_fns.php"); 
require_once("entities.php"); 

//-----------------------------------------------------------------------------
//  display_paper_as_refer
//
//  Determine the appropriate biblio function for a refer reference and
// call the helper function to do it.
//
//-----------------------------------------------------------------------------

function display_paper_as_refer($paper, $plain )
{
  if (is_array($paper)) {
    if ($plain) {
      $refer = "do_refer_text";
    }
    else {
      $refer = "do_refer_html";
    }
    display_paper_as_biblio($paper, $refer);
  }
}

//-----------------------------------------------------------------------------
// display_paper_as_bibtex
//
//  Determine the appropriate biblio function for a bibtex reference and
// call the helper function to do it.
//
//-----------------------------------------------------------------------------

function display_paper_as_bibtex($paper, $plain)
{
  if (is_array($paper)) {
    if ($plain) {
      $bibtex = "do_bibtex_text";
    }
    else {
      $bibtex = "do_bibtex_html";
    }
    display_paper_as_biblio($paper, $bibtex);
  }
}


//-----------------------------------------------------------------------------
// display_paper_as_biblio
//
//  Display either a single Bib record or Bib records for all proceedings
// this paper has appeared in.
//
//-----------------------------------------------------------------------------

function display_paper_as_biblio($paper, $biblio)
{
  $paperid = $paper["paperid"];
  $proceedings = get_proceedings_by_paperid($paperid);
  if (is_array($proceedings)) {
    foreach ($proceedings as $proc)
    {
      $biblio($paper, $proc);
    }
  }
  else
  {
    $biblio($paper, false);
  }
}


//-----------------------------------------------------------------------------
// do_refer
//
//  Display a paper as a Refer record. This version assumes that the reader is 
// converting the html that is encoded in the entries themselves, not just the
// obvious <br> tags, etc.
//
//-----------------------------------------------------------------------------

function do_refer_html($paper, $proc)
{
  # $proc may be 'false' if not available.

  $paperid = $paper["paperid"];

  echo "<br>\n";
  $str = $paper["title"];
  $str = XML2ReferHTML($str, 1);
  echo "%T ".$str."<br>\n";

  $authorlist = get_authors_by_listid($paperid);
  $auths = make_namelist($authorlist, ", ", "", 7);
  echo "%A ".$auths."<br>\n";

  if (is_array($proc))
  {
    $editorlist = get_editors_by_listid($proc["proceedingid"]);
    $edits = make_namelist($editorlist, ", ", "", 7);
    echo "%E ".$edits."<br>\n";

    $str = $proc["title"];
    $str = XML2ReferHTML($str, 1);
    echo "%B ".$str."<br>\n";
  }

  $str = $paper["abstract"];
  if ($str != "") {
    $str = XML2ReferHTML($str, 0);
    $str = wordwrap($str, 60, "<br>\n&nbsp;&nbsp;&nbsp;");
    echo "%X ".$str."<br>\n";
  }
  echo "<br>\n";
}

function do_refer_text($paper, $proc)
{
  # $proc may be 'false' if not available.

  $paperid = $paper["paperid"];

  echo "\n";
  $str = $paper["title"];
  $str = XML2ReferText($str, 1);
  echo "%T ".$str."\n";

  $authorlist = get_authors_by_listid($paperid);
  $auths = make_namelist($authorlist, ", ", "", 7);
  $auths = XML2ReferText($auths, 0);
  echo "%A ".$auths."\n";

  if (is_array($proc))
  {
    $editorlist = get_editors_by_listid($proc["proceedingid"]);
    $edits = make_namelist($editorlist, ", ", "", 7);
    $edits = XML2ReferText($edits, 0);
    echo "%E ".$edits."\n";

    $str = $proc["title"];
    $str = XML2ReferText($str, 1);
    echo "%B ".$str."\n";
  }

  $str = $paper["abstract"];
  if ($str != "") {
    $str = XML2ReferText($str, 0);
    $str = wordwrap($str, 60, "\n   ");
    echo "%X ".$str."\n";
  }
  echo "\n";
}

//-----------------------------------------------------------------------------
// do_bibtex
//
//  Display a paper as a BibTeX record. This version assumes that the reader is 
// converting the html that is encoded in the entries themselves, not just the
// obvious <br> tags, etc.
//
//-----------------------------------------------------------------------------

function to_bibtex_month($month)
{
if ($month == "1") return "jan";
else if ($month == "2") return "jan";
else if ($month == "3") return "feb";
else if ($month == "4") return "mar";
else if ($month == "5") return "may";
else if ($month == "6") return "jun";
else if ($month == "7") return "jul";
else if ($month == "8") return "aug";
else if ($month == "9") return "sep";
else if ($month == "10") return "oct";
else if ($month == "11") return "nov";
else if ($month == "12") return "dec";
else return $month;
}

function do_bibtex_html($paper, $proc)
{
  # $proc may be 'false' if not available.
  $br = "<br>\n";
  $spc = "&nbsp;&nbsp;";

  $paperid = $paper["paperid"];

  $str = XML2BibTexHTML($paper["reftext"], 0);
  echo "@InProceedings{".$str.",".$br;
  $str = XML2BibTexHTML($paper["title"], 1);
  echo $spc."title =        \"$str\",$br";

  $authorlist = get_authors_by_listid($paperid);
  $auths = make_namelist($authorlist, " and ", "", 6);
  $auths = XML2BibTexHTML($auths, 0);
  echo $spc."author=        \"$auths\",$br";

  if (is_array($proc))
  {
    $editorlist = get_editors_by_listid($proc["proceedingid"]);
    $edits = make_namelist($editorlist, " and ", "", 6);
    $edits = XML2BibTexHTML($edits, 0);
    echo $spc."editor=        \"".$edits."\",$br";

    $pages = get_paperinfo_by_paperid_and_procid($paperid, $proc["proceedingid"]);
    if (is_array($pages))
    {
      echo $spc."pages =        \"".$pages["firstpage"]."--".$pages["lastpage"]."\",$br";
    }
    $str = XML2BibTexHTML($proc["title"], 1);
    if ($str != "") {
        echo $spc."booktitle=     \"$str\",$br";
    }
    $str = $proc["isbn"];
    if ($str != "") {
        echo $spc."isbn=          \"$str\",$br";
    }
    $str = $proc["pubyear"];
    if ($str != "") {
        echo $spc."year=          \"$str\",$br";
    }
    $str = $proc["pubmonth"];
    if ($str != "") {
	$str = to_bibtex_month($str);
        echo $spc."month=         \"$str\",$br";
    }
  }

  $str = $paper["abstract"];
  if ($str != "") {
    $str = XML2BibTexHTML($str, 0);
    $str = wordwrap($str, 60, "<br>\n&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
    echo $spc."abstract=      \"$str\"$br";
  }
  echo "}$br";
}
                 

function do_bibtex_text($paper, $proc)
{
  # $proc may be 'false' if not available.
  $br = "\n";
  $spc = "  ";

  $paperid = $paper["paperid"];

  $str = XML2BibTexText($paper["reftext"], 0);
  echo "@InProceedings{".$str.",".$br;
  $str = XML2BibTexText($paper["title"], 1);
  echo $spc."title =        \"$str\",$br";

  $authorlist = get_authors_by_listid($paperid);
  $auths = make_namelist($authorlist, " and ", "", 6);
  $auths = XML2BibTexText($auths, 0);
  echo $spc."author=        \"$auths\",$br";

  if (is_array($proc))
  {
    $editorlist = get_editors_by_listid($proc["proceedingid"]);
    $edits = make_namelist($editorlist, " and ", "", 6);
    $edits = XML2BibTexText($edits, 0);
    echo $spc."editor=        \"".$edits."\",$br";

    $pages = get_paperinfo_by_paperid_and_procid($paperid, $proc["proceedingid"]);
    if (is_array($pages))
    {
      echo $spc."pages =        \"".$pages["firstpage"]."--".$pages["lastpage"]."\",$br";
    }

    $str = XML2BibTexText($proc["title"], 1);
    if ($str != "") {
        echo $spc."booktitle=     \"$str\",$br";
    }
    $str = $proc["isbn"];
    if ($str != "") {
        echo $spc."isbn=          \"$str\",$br";
    }
    $str = $proc["pubyear"];
    if ($str != "") {
        echo $spc."year=          \"$str\",$br";
    }
    $str = $proc["pubmonth"];
    if ($str != "") {
	$str = to_bibtex_month($str);
        echo $spc."month=         \"$str\",$br";
    }
  }

  $str = $paper["abstract"];
  if ($str != "") {
    $str = XML2BibTexText($str, 0);
    $str = wordwrap($str, 60, "\n     ");
    echo $spc."abstract=      \"$str\"$br";
  }
  echo "}$br";
}
                 

?>
