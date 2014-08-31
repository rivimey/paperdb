<?php
/**
 * paper_fns.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Database access routines that understand the paper database itself.
 *
 * $Id: paper_fns.php,v 1.13 2005/09/27 21:39:10 rivimey Exp $
 */

require_once("compat_fns.php");
require_once("db_fns.php");

//--------------------------------------------------------------------------------------
//  get_list_query
//
//  Do an arbitrary query, returning an array result. Normally avoid,
// but used in the search query.
//
//--------------------------------------------------------------------------------------

function get_list_query($query) {
  // query database for a list of papers in this proceeding
  db_connect();
  $result = @mysql_query($query);
  if (!$result) {
    #echo "get_list_query: query \"$query\" failed.<br>\n";
    return FALSE;
  }
  $num = @mysql_num_rows($result);
  if ($num == 0) {
    return FALSE;
  }
  $result = db_result_to_array($result);
  return $result;
}

//--------------------------------------------------------------------------------------
//  get_proceedingid_for_paper
//
// Return the possible proceeding data for the given paperid.
//
//--------------------------------------------------------------------------------------

function get_proceedingid_for_paper($paperid) {
  // query database for a list of papers in this proceeding
  db_connect();
  $query = "select * from proceedings where paperlist.paperid = " . sqlvalue($paperid, "N") . " and paperlist.proceedingid = proceedings.proceedingid";
  $result = @mysql_query($query);
  if (!$result) {
    echo "get_proceedingid_for_paper: query \"$query\" failed.<br>\n";
    return FALSE;
  }
  $num = @mysql_num_rows($result);
  if ($num == 0) {
    echo "get_proceedingid_for_paper: no proceedings found for paper $paperid.<br>\n";
    return FALSE;
  }
  $result = db_result_to_array($result);
  return $result;
}

/**
 * Return the publication info associated with a proceeding id.
 *
 * @param $procid
 * @return array|bool|resource
 */
function get_paperinfo_by_proceedingid($procid) {
  // query database for a list of papers in this proceeding
  db_connect();
  $query = "select * from paperlist where proceedingid ='$procid' order by firstpage";
  $result = @mysql_query($query);
  if (!$result) {
    echo "get_paperinfo_by_proceedingid: query \"$query\" failed.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>";
    return FALSE;
  }
  $num = @mysql_num_rows($result);
  if ($num == 0) {
    //echo "get_paperinfo_by_proceedingid: no papers found.<br>\n";
    return FALSE;
  }
  $result = db_result_to_array($result);
  return $result;
}

/**
 * Return the editors of papers from the people database.
 *
 * Editors are those people who are referenced in the editorlist table, but
 * we must convert that table to normal form before returning it.
 *
 * @return array|bool
 */
function get_editors() {
  db_connect();

  $query = "drop table if exists list_of_eds";
  $editorlist = @mysql_query($query);
  if (!$editorlist) {
    echo "get_editors: query \"$query\" failed.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>";
    return FALSE;
  }

  $query = "create temporary table list_of_eds " .
    "select distinct editorlist.editorid from people,editorlist " .
    "where editorlist.editorid = people.personid";
  $editorlist = @mysql_query($query);

  if (!$editorlist) {
    echo "get_editors: query \"$query\" failed.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>";
    return FALSE;
  }

  $query = "select people.* from people,list_of_eds " .
    "where list_of_eds.editorid = people.personid " .
    "order by people.lastname, people.firstname";
  $editorlist = @mysql_query($query);
  if (!$editorlist) {
    echo "get_editors: query \"$query\" failed.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>";
    return FALSE;
  }

  $num_editors = @mysql_num_rows($editorlist);
  $result = db_result_to_array($editorlist);

  $query = "drop table list_of_eds";
  $editorlist = @mysql_query($query);
  if (!$editorlist) {
    echo "get_editors: query \"$query\" failed.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>";
    return FALSE;
  }

  if ($num_editors == 0) {
    echo "get_editors: no editors for listid $editors.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>";
    return FALSE;
  }
  return $result;
}

/**
 * Return the authors of papers from the people database.
 *
 * Authors are those people who are referenced in the authorlist table, but
 * we must convert that table to normal form before returning it.
 *
 * @return array|bool
 *   List of the current authors.
 */
function get_authors() {
  db_connect();

  $query = "drop table if exists list_of_auths";
  $authorlist = @mysql_query($query);
  if (!$authorlist) {
    echo "get_authors: query \"$query\" failed.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>";
    return FALSE;
  }

  $query = "create temporary table list_of_auths " .
    "select distinct authorlist.authorid from people,authorlist " .
    "where authorlist.authorid = people.personid";
  $authorlist = @mysql_query($query);

  if (!$authorlist) {
    echo "get_authors: query \"$query\" failed.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>";
    return FALSE;
  }

  $query = "select people.* from people,list_of_auths " .
    "where list_of_auths.authorid = people.personid " .
    "order by people.lastname, people.firstname";
  $authorlist = @mysql_query($query);
  if (!$authorlist) {
    echo "get_authors: query \"$query\" failed.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>";
    return FALSE;
  }

  $num_authors = @mysql_num_rows($authorlist);
  $result = db_result_to_array($authorlist);

  $query = "drop table list_of_auths";
  $authorlist = @mysql_query($query);
  if (!$authorlist) {
    echo "get_authors: query \"$query\" failed.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>";
    return FALSE;
  }

  if ($num_authors == 0) {
    echo "get_authors: no authors for listid $authors.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>";
    return FALSE;
  }
  return $result;
}

/**
 * Return the number of proceedings that an editor has been involved in
 * note: this is not necessarily as the primary author.
 *
 * @param $personid
 * @return bool|int
 */
function get_editor_papercount($personid) {
  // query database for a proceeding
  db_connect();
  $query = "select distinct editors from editorlist where editorid=" . sqlvalue($personid, "N") . " order by ordering";
  $result = @mysql_query($query);
  if (!$result) {
    echo "get_proceeding: query \"$query\" failed.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>";
    return FALSE;
  }
  $num = @mysql_num_rows($result);
  return $num;
}

/**
 * Return the number of papers that an author has been involved in
 * note: this is not necessarily as the primary author!
 *
 * @param $personid
 * @return bool|int
 */
function get_author_papercount($personid) {
  // query database for a proceeding
  db_connect();
  $query = "select distinct authors from authorlist where authorid=" . sqlvalue($personid, "N") . " order by ordering";
  $result = @mysql_query($query);
  if (!$result) {
    echo "get_proceeding: query \"$query\" failed.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>";
    return FALSE;
  }
  $num = @mysql_num_rows($result);
  return $num;
}

/**
 * Return the list of people's names for a given author list.
 *
 * The author list id is the same as the paper id.
 *
 * @param $author
 * @return array|bool|resource
 */
function get_authors_by_listid($author) {
  // query database for the authors names for an author-list id
  db_connect();
  $query = "select personid,title,firstname,lastname from people,authorlist " .
    "where authorlist.authors=" . sqlvalue($author, "N") . " and people.personid = authorlist.authorid " .
    "order by authorlist.ordering asc";
  $result = @mysql_query($query);
  if (!$result) {
    echo "get_authors_by_listid: query \"$query\" failed.<br>\n";
    return FALSE;
  }
  $num = @mysql_num_rows($result);
  if ($num == 0) {
    #echo "get_authors_by_listid: none found.<br>\n";
    return FALSE;
  }
  $result = db_result_to_array($result);
  return $result;
}

/**
 * Return the editors for a particular proceeding.
 *
 * Editors are those people who are referenced in the editorlist table, but we
 * must convert that table to normal form before returning it.
 *
 * @param $editors
 *   The id associated with the proceeding, which is also the key
 * value for the editorlist table defining who the editors are.
 *
 * @return array|bool|resource
 */
function get_editors_by_listid($editors) {
  // query database for the editors names for an editor-list id
  db_connect();
  $query = "select personid,title,firstname,lastname from people,editorlist " .
    "where editorlist.editors=" . sqlvalue($editors, "N") . " and people.personid = editorlist.editorid " .
    "order by editorlist.ordering asc";
  $result = @mysql_query($query);
  if (!$result) {
    echo "get_editors_by_listid: query \"$query\" failed.<br>\n";
    return FALSE;
  }
  $num = @mysql_num_rows($result);
  if ($num == 0) {
    #echo "get_editors_by_listid: none found.<br>\n";
    return FALSE;
  }
  $result = db_result_to_array($result);
  return $result;
}

/**
 * Return a list of the proceedings, in ascending order of date.
 *
 * @param bool $asc
 *   if TRUE, the list is returned in descending order.
 *
 * @return array|bool|resource
 */
function get_proceedings($asc = FALSE) {
  // query database for a list of categories
  db_connect();
  $query = "select title,proceedingid,pubyear from proceedings order by pubyear";
  if ($asc) {
    $query .= " asc";
  }
  else {
    $query .= " desc";
  }
  $query .= ",title";
  $result = @mysql_query($query);
  if (!$result) {
    echo "get_proceedings: query \"$query\" failed.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>";
    return FALSE;
  }
  $num = @mysql_num_rows($result);
  if ($num == 0) {
    #echo "get_proceedings: none found.<br>\n";
    return FALSE;
  }
  $result = db_result_to_array($result);
  return $result;
}

/**
 * Return the proceedings table info for a given proceedingid.
 *
 * @param $num
 * @return array|bool|resource
 */
function get_proceeding($num) {
  // query database for a proceeding
  db_connect();
  $query = "select * from proceedings where proceedingid=" . sqlvalue($num, "N");
  $result = @mysql_query($query);
  if (!$result) {
    echo "get_proceeding: query \"$query\" failed.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>\n";
    return FALSE;
  }
  $num = @mysql_num_rows($result);
  if ($num != 1) {
    echo "get_proceeding: query $query produced $num results (>1).<br>\n";
    return FALSE;
  }
  $result = @mysql_fetch_array($result);
  return $result;
}

/**
 * Get the IDs of any files associated with the indicated paper.
 *
 * @param $papid
 *   The paper ID of the paper.
 *
 * @return array|bool
 *    If there is no file, returns FALSE, otherwise return the id, filename, and type of
 * the requested files.
 */
function get_paper_file_ids($papid) {
  // query database for a proceeding
  db_connect();
  $query = "select paperfile.fileid,paperfile.filename,paperfile.filetype from paperfilelist, paperfile " .
    "where paperfilelist.paperid =" . sqlvalue($papid, "N") . " and " .
    "paperfilelist.fileid = paperfile.fileid " .
    "order by paperfile.created";

  $result = @mysql_query($query);
  if (!$result) {
    echo "get_paper_file_ids: query \"$query\" failed.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>\n";
    return FALSE;
  }
  $num = @mysql_num_rows($result);
  if ($num == 0) {
    //echo "get_paper_file_ids: no files.<br>\n";
    return FALSE;
  }
  $result = db_result_to_array($result);
  return $result;
}

/**
 * Return the file for a given file id.
 *
 * @param $num
 *   The fileid of the desired record.
 *
 * @return array|bool
 *   If there is no file, returns FALSE, otherwise return the file record
 * from the paperfile table.
 */
function get_file_by_id($num) {
  // query database for a proceeding
  db_connect();
  $query = "select * from paperfile where fileid = " . sqlvalue($num, "N");
  $result = @mysql_query($query);
  if (!$result) {
    echo "get_paper_file_ids: query failed.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>\n";
    return FALSE;
  }
  $num = @mysql_num_rows($result);
  if ($num == 0) {
    //echo "get_paper_file_ids: no files.<br>\n";
    return FALSE;
  }
  $result = @mysql_fetch_array($result);
  return $result;
}

/**
 * Return the papers that have as an author the personid provided.
 *
 * @param $num
 *   The authorlist ID of the desired record.
 *
 * @return array|bool
 *   If there is no list, returns FALSE, otherwise return the papers records
 * that include this author.
 */
function get_papers_by_author($num) {
  // query database for a proceeding
  db_connect();
  $query = "select papers.* from papers,authorlist " .
    "where authorlist.authorid = " . sqlvalue($num, "N") . " and authorlist.authors = papers.paperid " .
    "order by papers.paperid";
  $result = @mysql_query($query);
  if (!$result) {
    echo "get_papers_by_author: query \"$query\" failed.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>\n";
    return FALSE;
  }
  $num = @mysql_num_rows($result);
  if ($num == 0) {
    return FALSE;
  }
  $result = db_result_to_array($result);
  return $result;
}

//--------------------------------------------------------------------------------------
//  get_proceedings_by_editor
//
// Return the papers that have as an author the personid
// provided.
//
//--------------------------------------------------------------------------------------

function get_proceedings_by_editor($num) {
  // query database for a proceeding
  db_connect();
  $query = "select proceedings.* from proceedings,editorlist " .
    "where editorlist.editorid =" . sqlvalue($num, "N") . " and editorlist.editors = proceedings.proceedingid " .
    "order by proceedings.proceedingid";
  $result = @mysql_query($query);
  if (!$result) {
    echo "get_proceedings_by_editor: query \"$query\" failed.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>\n";
    return FALSE;
  }
  $num = @mysql_num_rows($result);
  if ($num == 0) {
    return FALSE;
  }
  $result = db_result_to_array($result);
  return $result;
}

//--------------------------------------------------------------------------------------
//  get_papers_by_proceedingid
//
// Return the papers that are included in the proceeding
// with the proceedingid provided.
//
//--------------------------------------------------------------------------------------

function get_papers_by_proceedingid($num) {
  db_connect();
  $query = "select papers.* from papers,paperlist where " .
    "paperlist.proceedingid =" . sqlvalue($num, "N") . " and paperlist.paperid = papers.paperid order by paperlist.firstpage";
  $result = @mysql_query($query);
  if (!$result) {
    echo "get_papers_by_proceedingid: query \"$query\" failed.<br>\n";
    return FALSE;
  }
  $num = @mysql_num_rows($result);
  if ($num == 0) {
    return FALSE;
  }
  $result = db_result_to_array($result);
  return $result;
}

//--------------------------------------------------------------------------------------
//  get_papers_and_proceedings
//
// Return the papers that are included in the proceeding
// with the proceedingid provided.
//
//--------------------------------------------------------------------------------------

function get_papers_and_proceedings($so) {
  db_connect();

  // validate the sort order value and define the field name.
  $sortfield['Title'] = "papertitle";
  $sortfield['Date'] = "pubyear";
  $sortfield['Proceeding'] = "proctitle";
  $sortfield['Pages'] = "pages";
  if (!isset ($sortfield[$so])) {
    return FALSE;
  }
  $fn = $sortfield[$so];

  // create a list that we can then sort nicely.
  $query =
    "select papers.paperid, greatest(ifnull(paperlist.lastpage, 99999) - ifnull(paperlist.firstpage, 99999) + 1, 1) as pages, " .
    "papers.title as 'papertitle', proceedings.title as 'proctitle', proceedings.pubyear " .
    "from papers, paperlist, proceedings " .
    "where paperlist.paperid =papers.paperid and proceedings.proceedingid = paperlist.proceedingid " .
    "order by $fn";
  $result = @mysql_query($query);
  if (!$result) {
    echo "get_papers_and_proceedings: query \"$query\" failed.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>";
    return FALSE;
  }

  $num = @mysql_num_rows($result);
  if ($num == 0) {
    return FALSE;
  }
  $result = db_result_to_array($result);
  return $result;
}

//--------------------------------------------------------------------------------------
//  get_proceedings_by_paperid
//
// Return the proceedings that have published the given paperid.
//
//--------------------------------------------------------------------------------------

function get_proceedings_by_paperid($num) {
  // query database for a proceeding
  db_connect();
  $query = "select proceedings.* from proceedings, paperlist where " .
    "paperlist.paperid =" . sqlvalue($num, "N") . " and paperlist.proceedingid = proceedings.proceedingid ";
  $result = @mysql_query($query);
  if (!$result) {
    echo "get_proceedings_by_paperid: query \"$query\" failed.<br>\n";
    return FALSE;
  }
  $num = @mysql_num_rows($result);
  if ($num == 0) {
    return FALSE;
  }
  $result = db_result_to_array($result);
  return $result;
}

//--------------------------------------------------------------------------------------
//  get_paperinfo_by_paperid_and_procid
//
// Return the paperinfo for a given proceeding/paper pair.
//
//--------------------------------------------------------------------------------------

function get_paperinfo_by_paperid_and_procid($paid, $prid) {
  // query database for a proceeding
  db_connect();
  $query = "select firstpage,lastpage from paperlist " .
    "where paperid=" . sqlvalue($paid, "N") . " and proceedingid=" . sqlvalue($prid, "N") . " order by firstpage";
  $result = @mysql_query($query);
  if (!$result) {
    echo "get_paperinfo_by_paperid_and_procid: query \"$query\" failed.<br>\n";
    return FALSE;
  }
  $num = @mysql_num_rows($result);
  if ($num != 1) {
    return FALSE;
  }
  $result = @mysql_fetch_array($result);
  return $result;
}

//--------------------------------------------------------------------------------------
//  get_publishers
//
// Return a list of publishers.
//
//--------------------------------------------------------------------------------------

function get_publishers() {
  // query database for a list of categories
  db_connect();
  $query = "select name, city, publisherid from publisherinfo order by name";
  $result = @mysql_query($query);
  if (!$result) {
    echo "get_publishers: query \"$query\" failed.<br>\n";
    return FALSE;
  }
  $num = @mysql_num_rows($result);
  if ($num == 0) {
    return FALSE;
  }
  $result = db_result_to_array($result);
  return $result;
}

//--------------------------------------------------------------------------------------
//  get_authors_by_paperid
//
// Return the author name for a particular paper (paperid).
//
//--------------------------------------------------------------------------------------

function get_authors_by_paperid($paperid) {
  // query database for a list of categories
  db_connect();
  $query = "select authorid from authorlist where authors=" . sqlvalue($paperid, "N") . " order by ordering";
  $result = @mysql_query($query);
  if (!$result) {
    echo "get_authors: query \"$query\" failed.<br>\n";
    return FALSE;
  }
  $num = @mysql_num_rows($result);
  if ($num == 0) {
    return FALSE;
  }
  $result = db_result_to_array($result);
  return $result;
}

//--------------------------------------------------------------------------------------
//  get_comments_by_paper
//
// Return the author name for a particular paper (authref).
//
//--------------------------------------------------------------------------------------

function get_comments_by_paper($paperid) {
  // query database for a list of categories
  db_connect();
  $query = "select * from comments where paperid=" . sqlvalue($paperid, "N") . " order by created,commentid desc";
  $result = @mysql_query($query);
  if (!$result) {
    echo "get_comments: query \"$query\" failed.<br>\n";
    return FALSE;
  }
  $num = @mysql_num_rows($result);
  if ($num == 0) {
    return FALSE;
  }
  $result = db_result_to_array($result);
  return $result;
}

//--------------------------------------------------------------------------------------
//  get_organisations
//
// Return the organisations in the database.
//
//--------------------------------------------------------------------------------------

function get_organisations() {
  // query database for a list of categories
  db_connect();
  $query = "select name, orgid, city from organisations order by name";
  $result = @mysql_query($query);
  if (!$result) {
    echo "get_organisations: query \"$query\" failed.<br>\n";
    return FALSE;
  }
  $num = @mysql_num_rows($result);
  if ($num == 0) {
    return FALSE;
  }
  $result = db_result_to_array($result);
  return $result;
}

//--------------------------------------------------------------------------------------
//  get_publisher_name
//
// Return the publisher name for the id.
//
//--------------------------------------------------------------------------------------

function get_publisher_name($pubid) {
  // query database for the name for a publisher id, return e.g. "IOS Press, Amsterdam"
  db_connect();
  $query = "select name, city from publisherinfo where publisherid = " . sqlvalue($pubid, "N");
  $result = @mysql_query($query);
  if (!$result) {
    return FALSE;
  }
  $num = @mysql_num_rows($result);
  if ($num != 1) {
    return FALSE;
  }
  $str = mysql_result($result, 0, "name");
  $str .= ", ";
  $str .= mysql_result($result, 0, "city");
  return $str;
}

//--------------------------------------------------------------------------------------
//  get_proceed_details
//
// 
//
//--------------------------------------------------------------------------------------

function get_proceed_details($procid) {
  // query database for the name for an id, return array of details.
  db_connect();
  $query = "select * from publisherinfo where proceedingid = " . sqlvalue($procid, "N");
  $result = @mysql_query($query);
  if (!$result) {
    return FALSE;
  }
  $num = @mysql_num_rows($result);
  if ($num != 1) {
    return FALSE;
  }
  $result = db_result_to_array($result);
  return $result;
}

//--------------------------------------------------------------------------------------
//  get_people
//
// Return the people in the database.
//
//--------------------------------------------------------------------------------------

function get_people() {
  db_connect();
  $query = "select title,firstname,lastname,personid from people order by lastname";
  $result = @mysql_query($query);
  if (!$result) {
    return FALSE;
  }
  $num = @mysql_num_rows($result);
  if ($num == 0) {
    return FALSE;
  }
  $result = db_result_to_array($result);
  return $result;
}

//--------------------------------------------------------------------------------------
//  get_person_name
//
// Return the name of a person given their id.
//
//--------------------------------------------------------------------------------------

function get_person_name($persid) {
  db_connect();
  $query = "select title,firstname,lastname from people where personid = " . sqlvalue($persid, "N");
  $result = @mysql_query($query);
  if (!$result) {
    return FALSE;
  }
  $num = @mysql_num_rows($result);
  if ($num != 1) {
    return FALSE;
  }
  $name = mysql_result($result, 0, "title");
  if ($name != "") {
    $name .= " ";
  }
  $fname = mysql_result($result, 0, "firstname");
  if ($fname != "") {
    $name .= $fname . " ";
  }
  $lname = mysql_result($result, 0, "lastname");
  $name .= $lname;
  return $name;
}

//--------------------------------------------------------------------------------------
//  get_person
//
// Return the name of a person given their id.
//
//--------------------------------------------------------------------------------------

function get_person($persid) {
  db_connect();
  $query = "select * from people where personid = " . sqlvalue($persid, "N");
  $result = @mysql_query($query);
  if (!$result) {
    return FALSE;
  }
  $num = @mysql_num_rows($result);
  if ($num != 1) {
    echo "get_person: more than one person matched query $query.<br>\n";
    return FALSE;
  }
  $result = @mysql_fetch_array($result);
  return $result;
}


//--------------------------------------------------------------------------------------
//  get_paper
//
// Return info for the paper id.
//
//--------------------------------------------------------------------------------------

function get_paper($num) {
  // query database for the paper with given id
  if (!$num || $num == "") {
    return FALSE;
  }

  db_connect();
  $query = "select * from papers where paperid = " . sqlvalue($num, "N");
  $result = @mysql_query($query);
  if (!$result) {
    echo "get_paper: query \"$query\" failed.<br>\n";
    return FALSE;
  }
  $num = @mysql_num_rows($result);
  if ($num != 1) {
    echo "get_paper: more than one paper matched query $query.<br>\n";
    return FALSE;
  }
  $result = @mysql_fetch_array($result);
  return $result;
}

//--------------------------------------------------------------------------------------
//  get_papers
//
// Return a list of papers in the database.
//
//--------------------------------------------------------------------------------------

function get_papers($sortorder) {
  if ($sortorder == "i" or $sortorder == "t" or $sortorder == "d") {

    $order["i"] = "paperid";
    $order["t"] = "title";
    $order["d"] = "date";

    db_connect();

    $query = "select * from papers order by " . $order[$sortorder];

    $result = @mysql_query($query);
    if (!$result) {
      echo "get_papers: query \"$query\" failed.<br>\n";
      return FALSE;
    }
    $num = @mysql_num_rows($result);
    if ($num == 0) {
      echo "get_papers: no papers matched query $query.<br>\n";
      return FALSE;
    }
    $result = db_result_to_array($result);
    return $result;
  }
  else {
    return FALSE;
  }
}

//--------------------------------------------------------------------------------------
//  update_paperfile_count
//
// The database keeps track of the number of downloads of each
// file (for interest mainly).
//
//--------------------------------------------------------------------------------------

function update_paperfile_count($num) {
  if (!$num || $num == "") {
    return FALSE;
  }

  db_connect();
  $query = "update paperfile set accessed = NOW(), accesses = accesses + 1 where fileid = " . sqlvalue($num, "N");
  $result = @mysql_query($query);
  if (!$result) {
    echo "get_paper: query \"$query\" failed.<br>\n";
    return FALSE;
  }
  return TRUE;
}

//--------------------------------------------------------------------------------------
//  update_paper_accessed
//
// The database keeps track of the number of downloads of each
// file (for interest mainly).
//
//--------------------------------------------------------------------------------------

function update_accessed($table, $keyfield, $num) {
  if (!$num || $num == "") {
    return FALSE;
  }

  db_connect();
  $query = "update $table set accessed = NOW(), accesses = accesses + 1 where $keyfield = " . sqlvalue($num, "N");
  $result = @mysql_query($query);
  if (!$result) {
    echo "get_paper: query \"$query\" failed.<br>\n";
    return FALSE;
  }
  return TRUE;
}

//--------------------------------------------------------------------------------------
//  update_modified
//
// The database keeps track of the number of downloads of each
// file (for interest mainly).
//
//--------------------------------------------------------------------------------------

function update_modified($table, $keyfield, $num) {
  if (!$num || $num == "") {
    return FALSE;
  }

  db_connect();
  $query = "update $table set modified = NOW() where $keyfield = " . sqlvalue($num, "N");
  $result = @mysql_query($query);
  if (!$result) {
    return FALSE;
  }
  return TRUE;
}


function update_paper_accessed($num) {
  return update_accessed("papers", "paperid", $num);
}

function update_paper_modified($num) {
  return update_modified("papers", "paperid", $num);
}

function update_proceeding_accessed($num) {
  return update_accessed("proceedings", "proceedingid", $num);
}

function update_proceeding_modified($num) {
  return update_modified("proceedings", "proceedingid", $num);
}


//--------------------------------------------------------------------------------------
//  get_proceeding_last_modified
//
// The database keeps track of the number of downloads of each
// file (for interest mainly).
//
//--------------------------------------------------------------------------------------

function get_proceeding_last_modified($num) {
  if (!$num || $num == "") {
    return FALSE;
  }

  db_connect();
  $query = "select MAX(papers.modified) as lastmod from papers,paperlist " .
    "where paperlist.paperid = papers.paperid and paperlist.proceedingid = " . sqlvalue($num, "N");
  $result = @mysql_query($query);
  if (!$result) {
    return FALSE;
  }
  $str = mysql_result($result, 0, "lastmod");
  return $str;
}


//--------------------------------------------------------------------------------------
//  get_allpapers_last_modified
//
// The database keeps track of the number of downloads of each
// file (for interest mainly).
//
//--------------------------------------------------------------------------------------

function get_allpapers_last_modified() {
  db_connect();
  $query = "select MAX(papers.modified) as lastmod from papers";
  $result = @mysql_query($query);
  if (!$result) {
    return FALSE;
  }
  $str = mysql_result($result, 0, "lastmod");
  return $str;
}
