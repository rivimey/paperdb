<?php
/**
 * admin_fns.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Various back-end administration functions, only used by the
 * administrator (not web-users!).
 *
 * $Id: admin_fns.php,v 1.10 2005/07/23 23:23:36 rivimey Exp $
 */

require_once("compat_fns.php");
require_once("db_fns.php");
require_once("html_output_fns.php");

//--------------------------------------------------------------------------------------
//  mysql_get_insert_id
//
//  Do the query mysql demands of us to get the autoincrement id from the last
// update query.
//
//--------------------------------------------------------------------------------------

function mysql_get_insert_id($idstr) {
  $result = mysql_query("select last_insert_id()");
  if (!$result) {
    echo "<p>$idstr: failed to get insert id.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "</p>\n";
    return FALSE;
  }
  else {
    return mysql_result($result, 0, "last_insert_id()");
  }
}

//--------------------------------------------------------------------------------------
//  display_admin_menu
//
//  Display the administrator menu for new proceedings, papers, orgs and people.
//
//--------------------------------------------------------------------------------------

function display_admin_menu() {
  ?>
  <p>Entering information:</p>
  <ul>
    <li><a href="insert_proceeding_form.php">Add a new proceeding</a></li>
    <li><a href="insert_papers_form.php">Add a paper to a proceeding</a></li>
    <li><a href="insert_org_form.php">Add a new organisation</a></li>
    <li><a href="insert_person_form.php">Add a new person</a></li>
  </ul>
  <p>Editing information:</p>
  <ul>
    <li><a href="list_proceeds.php?f=1">List proceedings</a></li>
    <li><a href="list_papers.php">List papers</a></li>
    <li><a href="list_authors.php">List authors</a></li>
    <li><a href="list_editors.php">List editors</a></li>
  </ul>
  <p>General Admin</p>
  <ul>
    <li><a href="change_password_form.php">Change admin password</a></li>
    <li><a href="create_refs.php">Fill in unset ref texts</a></li>
    <li><a href="fixup_text.php?f=1">Fix up incorrectly coded text in tables</a>
    </li>
  </ul>
  <p>Specialist functions</p>
  <ul>
    <li>
      <a href="list_proceeds.php?f=4">Export database as BibTex, plain-text</a>
    </li>
    <li><a href="list_proceeds.php?f=5">Export database as Refer, plain-text</a>
    </li>
  </ul>
  <p>Quit</p>
  <ul>
    <li><a href="logout.php">Log out of admin mode</a></li>
    <li><a href="index.php">Go to main site</a></li>
  </ul>
<?php
}


//--------------------------------------------------------------------------------------
//  insert_proceeding
//
// Insert a new proceeding into the database
//
//--------------------------------------------------------------------------------------

function insert_proceeding($title, $subtitle, $pubid, $series, $editors, $isbn,
                           $issn, $volm, $totpg, $url, $pubyear, $pubmonth, $pubday) {
  $conn = db_connect();

  if ($title == '' || !is_array($editors)) {
    echo "insert_proceeding: bad params\n";
    return FALSE;
  }

  // check proceedings does not already exist
  $query = "select * from proceedings where title=" . sqlvalue($title);
  $result = mysql_query($query);
  if (!$result) {
    echo "insert_proceeding: check proceeding query failed.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>\n";
    return FALSE;
  }
  if (mysql_num_rows($result) != 0) {
    echo "insert_proceeding: proceeding title already exists.<br>\n";
    return FALSE;
  }

  // insert this record;
  $query = "insert into proceedings set  title=".sqlvalue($title).", subtitle=".sqlvalue($subtitle).", publisherid=".sqlvalue($pubid, "N").", ".
  				"pubyear=".sqlvalue($pubyear, "N").", pubmonth=".sqlvalue($pubmonth, "N").", pubday=".sqlvalue($pubday, "N").", ".
				"series=".sqlvalue($series).", isbn=".sqlvalue($isbn).", issn=".sqlvalue($issn).", volume=".sqlvalue($volm, "N").", ".
				"totpages=".sqlvalue($totpg, "N").",  proceedingurl=".sqlvalue($url);
  $result = mysql_query($query);
  if (!$result) {
    echo "insert_proceeding: insert proceeding failed.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>\n";
    return FALSE;
  }
  else {
    $pubid = mysql_get_insert_id("insert_proceeding");
    if (!$pubid == FALSE) {
      foreach ($editors as $edid) {
        $query = "insert into editorlist set editors=" . sqlvalue($pubid, "N") . ", editorid=" . sqlvalue($edid, "N");
        $result = mysql_query($query);
        if (!$result) {
          echo "insert_proceeding: insert editorlist failed.<br>\n";
          echo mysql_errno() . ": " . mysql_error() . "<br>\n";
          return FALSE;
        }
      }
    }
  }
  return TRUE;
}

//--------------------------------------------------------------------------------------
//  delete_proceeding
//
//  Deletes the proceeding identified by $id from the database.
//
//--------------------------------------------------------------------------------------

function delete_proceeding($id, $paperstoo) {
  $conn = db_connect();

  $query = "delete from proceedings where proceedingid=" . sqlvalue($id, "N");
  $result = @mysql_query($query);
  if (!$result) {
    return FALSE;
  }
  else {
    return TRUE;
  }
}


//--------------------------------------------------------------------------------------
//  add_paper_file
//
//  Add a file to a paper. The file is expected to be on the local server, and it is read
// and md5-summed to check consistency through the various transforms.
//
//--------------------------------------------------------------------------------------

function add_paper_file($filename, $fileloc, $cmprs, $filetype = "PS") {
  if (is_uploaded_file($fileloc)) {
    $len = filesize($fileloc);
    $fd = fopen($fileloc, "rb");
    $contents = fread($fd, $len);
    fclose($fd);

    $d5 = md5($contents);

    $conn = db_connect();
    if ($cmprs == "Gzip") {
      $cmprs = "Gzip";
    }
    else {
      $cmprs = "No";
    }

    //
    // This string doesn't have the paper contents so if there's an error the echo of
    // $query doesn't echo $contents to the web page.
    $query = "insert into paperfile set filename=" . sqlvalue($filename) . ", length=" . sqlvalue($len, "N") . ", compressed=" . sqlvalue($cmprs) . ", " .
      "filetype=" . sqlvalue($filetype) . ", md5=" . sqlvalue($d5) . ", created=NOW(), paper=";

    $result = mysql_query($query . sqlvalue($contents));
    if (!$result) {
      echo "add_paper_file: query \"$query\" failed<br>\n";
      echo mysql_errno() . ": " . mysql_error() . "<br>\n";
      return FALSE;
    }

    return mysql_get_insert_id("add_paper_file");
  }
  else {
    echo "Possible file upload attack. Filename: " . $_FILES['userfile']['name'] . "<br>";
    return FALSE;
  }
}

//--------------------------------------------------------------------------------------
//  delete_paper_file
//
//  Deletes the file identified by $fileid from the database and unlinks from $paperid.
//
//--------------------------------------------------------------------------------------

function delete_paper_file($fileid) {
  $conn = db_connect();

  $query = "delete from paperfilelist where fileid=" . sqlvalue($fileid, "N");
  $result = @mysql_query($query);
  if (!$result) {
    echo "delete_paper_file: query \"$query\" failed<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>\n";
    return FALSE;
  }

  $query = "delete from paperfile where fileid=" . sqlvalue($fileid, "N");
  $result = @mysql_query($query);
  if (!$result) {
    echo "delete_paper_file: query \"$query\" failed<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>\n";
    return FALSE;
  }
  return TRUE;
}


//--------------------------------------------------------------------------------------
//  add_person
//
//  Add another person (not necessarily a paper author) to the database of people.
// Other details must be filled in later. If you have the details to hand, use
// insert_person() instead.
//
// The personid is returned.
//
//--------------------------------------------------------------------------------------

function add_person($firstname, $lastname) {
  $conn = db_connect();

  // check person does not already exist
  $query = "select personid from people where firstname=" . sqlvalue($firstname) . " and lastname=" . sqlvalue($lastname);
  $result = mysql_query($query);
  if (!$result) {
    do_para("add_person: check person query failed.");
    echo mysql_errno() . ": " . mysql_error() . "<br>\n";
    return FALSE;
  }
  if (mysql_num_rows($result) != 0) {
    do_para("The record is not sufficiently unique because there is already a person " .
      "with that title, firstname and lastname in the database.");
    return FALSE;
  }

  // insert entry into person table
  $query = "insert into people set firstname=" . sqlvalue($firstname) . ", lastname=" . sqlvalue($lastname);
  $result = mysql_query($query);
  if (!$result) {
    echo "add_author: insert author failed: $query<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>\n";
    return FALSE;
  }
  return mysql_get_insert_id("add_person");
}

//--------------------------------------------------------------------------------------
//  insert_person
//
//  Add another person (not necessarily a paper author) to the database of people.
// If you DO NOT have all the person's details to hand, use add_person() instead.
//
// The personid is returned.
//
//--------------------------------------------------------------------------------------

function insert_person($title, $firstname, $lastname, $address1, $address2, $address3,
                       $city, $area, $country, $email, $homepage, $notes, $organisation) {
  $conn = db_connect();

  // check person does not already exist
  $query = "select personid from people where firstname=" . sqlvalue($firstname) . " and " .
    "lastname=" . sqlvalue($lastname);
  //echo $query. "<br>\n";
  $result = mysql_query($query);
  if (!$result) {
    do_para("insert_person: check person query failed.");
    echo mysql_errno() . ": " . mysql_error() . "<br>\n";
    return FALSE;
  }

  if (mysql_num_rows($result) != 0) {
    do_para("The record is not sufficiently unique because there is already a person " .
      "with that title, firstname and lastname in the database.");
    return FALSE;
  }
  else {
    do_para("Good, Nobody called $firstname $lastname in the database already.");
  }
  // insert new person
  $query = "insert into people set title=" . sqlvalue($title) . ", firstname=" . sqlvalue($firstname) . ", " .
    "lastname=" . sqlvalue($lastname) . ", address1=" . sqlvalue($address1) . ", address2=" . sqlvalue($address2) . ", " .
    "address3=" . sqlvalue($address3) . ", city=" . sqlvalue($city) . ", area=" . sqlvalue($area) . ", country=" . sqlvalue($country) . ", " .
    "organisation=" . sqlvalue($organisation) . ", email=" . sqlvalue($email) . ", notes=" . sqlvalue($notes);
  //echo $query. "<br>\n";
  $result = mysql_query($query);
  if (!$result) {
    echo "insert_person: query '$query' failed.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>\n";
    return FALSE;
  }
  else {
    return mysql_get_insert_id("insert_person");
  }
}

//--------------------------------------------------------------------------------------
//  update_person
//
//  Modify a person (not necessarily a paper author) in the database of people.
//
//--------------------------------------------------------------------------------------

function update_person($personid, $person) {
  $conn = db_connect();

  $query = "update people set title=" . sqlvalue($person['title']) . ", firstname=" . sqlvalue($person['firstname']) . ", " .
    "lastname=" . sqlvalue($person['lastname']) . ", address1=" . sqlvalue($person['address1']) . ", " .
    "address2=" . sqlvalue($person['address2']) . ", address3=" . sqlvalue($person['address3']) . ", " .
    "city=" . sqlvalue($person['city']) . ", area=" . sqlvalue($person['area']) . ", " .
    "country=" . sqlvalue($person['country']) . ", organisation=" . sqlvalue($person['organisation']) . ", " .
    "email=" . sqlvalue($person['email']) . ", lastverified=NOW(), notes=" . sqlvalue($person['notes']) . " " .
    "where personid= " . sqlvalue($personid, "N");
  $result = mysql_query($query);
  if (!$result) {
    echo "update_person: query '$query' failed.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>\n";
    return FALSE;
  }
  else {
    return TRUE;
  }
}

//--------------------------------------------------------------------------------------
// insert_author_to_paper
//
//  Add another author (well, really just a person, not necessarily a paper author) to
// the database of people. Other details must be filled in later. The personid is returned.
//
//--------------------------------------------------------------------------------------

function insert_author_to_paper($paperid, $authorid, $order) {
  $conn = db_connect();
  // delete an entry from the paper->author mapping table
  $query = "insert into authorlist set authors=" . sqlvalue($paperid, "N") . ", " .
    "authorid=" . sqlvalue($authorid, "N") . ", ordering=" . sqlvalue($order, "N");
  $result = mysql_query($query);
  if (!$result) {
    echo "insert_author_to_paper: insert author failed: $query<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>\n";
    return FALSE;
  }
  return TRUE;
}

//--------------------------------------------------------------------------------------
// insert_editor_to_proceeding
//
//  Add another editor to the indicated proceeding. Both an editor
// record and the proceeding record must exist.
//
//--------------------------------------------------------------------------------------

function insert_editor_to_proceeding($proceedingid, $editorid, $order) {
  $conn = db_connect();
  // delete an entry from the paper->editor mapping table
  $query = "insert into editorlist set editors=" . sqlvalue($proceedingid, "N") . ", " .
    "editorid=" . sqlvalue($editorid, "N") . ", ordering=" . sqlvalue($order, "N");
  echo "$editorid - $order - $query<br>\n";
  $result = mysql_query($query);
  if (!$result) {
    echo "insert_editor: insert editor failed: $query<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>\n";
    return FALSE;
  }
  return TRUE;
}

//--------------------------------------------------------------------------------------
// delete_editor_from_proceeding
//
// Disassociate an editor from a proceeding.
//
//--------------------------------------------------------------------------------------

function delete_editor_from_proceeding($proceedingid, $editorid) {
  $conn = db_connect();
  // delete an entry from the paper->editor mapping table
  $query = "delete from editorlist where " .
    "editors=" . sqlvalue($proceedingid, "N") . " and editorid=" . sqlvalue($editorid, "N");
  $result = mysql_query($query);
  if (!$result) {
    echo "delete_editor: delete editor failed: $query<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>\n";
    return FALSE;
  }
  return TRUE;
}

//--------------------------------------------------------------------------------------
// delete_author_from_paper
//
//  Disassociate an author from a paper.
//
//--------------------------------------------------------------------------------------

function delete_author_from_paper($paperid, $authorid) {
  $conn = db_connect();
  // delete an entry from the paper->author mapping table
  $query = "delete from authorlist where " .
    "authors=" . sqlvalue($paperid, "N") . " and authorid=" . sqlvalue($authorid, "N");
  $result = mysql_query($query);
  if (!$result) {
    echo "delete_author: delete author failed: $query<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>\n";
    return FALSE;
  }
  return TRUE;
}

//--------------------------------------------------------------------------------------
// insert_proceeding_for_paper
//
//  Associate a proceeding with a paper. Both paper and proceeding must
// exist. firstpage and lastpage are the place (pages) in the proceeding that
/// the paper appears.
//
//--------------------------------------------------------------------------------------

function insert_proceeding_for_paper($paperid, $proceedingid, $firstpage, $lastpage) {
  $conn = db_connect();
  // delete an entry from the paper->proceeding mapping table
  $query = "insert into paperlist set " .
    "paperid=" . sqlvalue($paperid, "N") . ", proceedingid=" . sqlvalue($proceedingid, "N") . ", " .
    "firstpage=" . sqlvalue($firstpage, "N") . ", lastpage=" . sqlvalue($lastpage, "N");
  $result = mysql_query($query);
  if (!$result) {
    echo "insert_proceeding_for_paper: insert proceeding failed: $query<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>\n";
    return FALSE;
  }
  return TRUE;
}

//-----------------------------------------------------------------------------
// delete_proceeding_for_paper
//
//  Delete the association between the paper and the proceeding, but
// leave both paper and proceeding themselves intact.
//
//-----------------------------------------------------------------------------

function delete_proceeding_for_paper($paperid, $proceedingid) {
  $conn = db_connect();
  // delete an entry from the paper->proceeding mapping table
  $query = "delete from paperlist where " .
    "paperid=" . sqlvalue($paperid, "N") . " and proceedingid=" . sqlvalue($proceedingid, "N");
  $result = mysql_query($query);
  if (!$result) {
    echo "delete_proceeding_for_paper: delete proceeding failed: $query<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>\n";
    return FALSE;
  }
  return TRUE;
}

//-----------------------------------------------------------------------------
//  insert_paper
//
//  Insert a paper into the database. It is expected to be associated with a
// proceedings (procid)...  Files (if any) must be added later.
//
// procids is expected to be an array!
//
//-----------------------------------------------------------------------------

function insert_paper($procids, $title, $papers, $authors, $url, $reftext, $abstract) {
  $conn = db_connect();

  // check paper does not already exist
  $query = "select paperid from papers where title=" . sqlvalue($title);
  $result = mysql_query($query);
  if (!$result) {
    echo "Check paper unique test $query failed.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>\n";
    return FALSE;
  }
  if (mysql_num_rows($result) != 0) {
    echo "Check paper found another paper with the same title.<br>\n";
    return FALSE;
  }
  // insert new category
  $query = "insert into papers set " .
    "title=" . sqlvalue($title) . ", paper_url=" . sqlvalue($url) . ", " .
    "reftext=" . sqlvalue($reftext) . ", abstract=" . sqlvalue($abstract) . ", " .
    "created=NOW(), modified=NOW(), accessed=NOW(), accesses=0";
  //echo "insert_paper: $query.<br>\n";
  $result = mysql_query($query);
  if (!$result) {
    echo "insert_paper: insert paper $query failed.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>\n";
    return FALSE;
  }

  $papid = mysql_get_insert_id("insert_paper");

  if ($papid == FALSE) {
    return FALSE;
  }

  $order = 0;
  if (isset($authors)) {
    foreach ($authors as $auid) {
      insert_author_to_paper($papid, $auid, $order);
      $order++;
    }
  }

  foreach ($papers as $pfid) {
    $query = "insert into paperfilelist set paperid=" . sqlvalue($papid, "N") . ", fileid=" . sqlvalue($pfid, "N");
    //echo "insert_paper: $query.<br>\n";
    $result = mysql_query($query);
    if (!$result) {
      echo "insert_paper: insert paperfilelist $query failed.<br>\n";
      echo mysql_errno() . ": " . mysql_error() . "<br>\n";
      return FALSE;
    }
  }

  if (isset($procids)) {
    foreach ($procids as $pid) {
      $query = "insert into paperlist set proceedingid=" . sqlvalue($pid, "N") . ", paperid=" . sqlvalue($papid, "N");
      //echo "insert_paper: insert $query.<br>\n";
      $result = mysql_query($query);
      if (!$result) {
        echo "insert_paper: insert paperlist $query failed.<br>\n";
        echo mysql_errno() . ": " . mysql_error() . "<br>\n";
        return FALSE;
      }
    }
  }
  else {
    echo "Warning: paper has no proceedings associated.<br>\n";
  }
  return $papid;
}

//-----------------------------------------------------------------------------
//  update_paper_details
//
//  Update paper details in the database.
//
//-----------------------------------------------------------------------------

function update_paper_details($paperid, $title, $url, $reftext, $abstract) {
  $conn = db_connect();

  $query = "update papers set " .
    "title=" . sqlvalue($title) . ", paper_url=" . sqlvalue($url) . ", " .
    "reftext=" . sqlvalue($reftext) . ", abstract=" . sqlvalue($abstract) . " " .
    "where paperid=" . sqlvalue($paperid, "N");
  $result = mysql_query($query);
  if (!$result) {
    echo "update_paper_details: update paper failed.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>\n";
    return FALSE;
  }
  return TRUE;
}

//-----------------------------------------------------------------------------
//  update_proceeding_details
//
//  Update proceeding details from the details in the record.
//
//-----------------------------------------------------------------------------

function update_proceeding_details($proceedingid, $proceeding) {
  $conn = db_connect();

  $query = "update proceedings set " .
    "title=" . sqlvalue($proceeding['title']) . ", subtitle=" . sqlvalue($proceeding['subtitle']) . ", " .
    "publisherid=" . sqlvalue($proceeding['publisherid'], "N") . ", series=" . sqlvalue($proceeding['series']) . ", " .
    "isbn=" . sqlvalue($proceeding['isbn']) . ", issn=" . sqlvalue($proceeding['issn']) . ", " .
    "volume=" . sqlvalue($proceeding['volume'], "N") . ", totpages=" . sqlvalue($proceeding['totpages'], "N") . ", " .
    "proceedingurl=" . sqlvalue($proceeding['proceedingurl']) . ", pubyear=" . sqlvalue($proceeding['pubyear'], "N") . ", " .
    "pubmonth=" . sqlvalue($proceeding['pubmonth'], "N") . ", pubday=" . sqlvalue($proceeding['pubday'], "N") . " " .
    "where proceedingid=" . sqlvalue($proceedingid, "N");
  echo $query;
  $result = mysql_query($query);
  if (!$result) {
    echo "update_proceeding_details: update proceeding failed.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>\n";
    return FALSE;
  }
  return TRUE;
}

//------------------------------------------------------------------------------
//  insert_org
//
//
//
//------------------------------------------------------------------------------

// insert a new paper into the database
function insert_org($name, $address1, $address2, $address3,
                    $city, $area, $country, $email, $homepade, $notes) {
  $conn = db_connect();

  // check paper does not already exist
  $query = "select * from organisations where name=" . sqlvalue($name);
  $result = mysql_query($query);

  if (!$result || mysql_num_rows($result) != 0) {
    echo "insert_org: Could not check organisation '$name' is unique<br>\n";
    return FALSE;
  }

  // insert new category
  $query = "insert into organisations set " .
    "name=" . sqlvalue($name) . ", address1=" . sqlvalue($address1) . ", " .
    "address2=" . sqlvalue($address2) . ", address3=" . sqlvalue($address3) . ", " .
    "city=" . sqlvalue($city) . ", area=" . sqlvalue($area) . ", " .
    "country=" . sqlvalue($country) . ", email=" . sqlvalue($email) . ", " .
    "notes=" . sqlvalue($notes);
  $result = mysql_query($query);
  if (!$result) {
    echo "insert_org: Insertion of organisation '$name' failed " . mysql_error() . "<br>\n";
    return FALSE;
  }
  else if ($result = mysql_query("select last_insert_id()")) {
    return mysql_result($result, 0, "last_insert_id()");
  }
  else {
    echo "insert_org: Insertion of organisation '$name': failed to get Id<br>\n";
    return FALSE;
  }
}

//------------------------------------------------------------------------------
//  link_file_to_paper
//
//  As the name says: update the paperfilelist to record the association.
//
//------------------------------------------------------------------------------

function link_file_to_paper($paperid, $fileid) {
  $query = "insert into paperfilelist set paperid='$paperid', fileid='$fileid'";
  $result = mysql_query($query);
  if (!$result) {
    echo "Error: link_file query \"$query\" failed.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>\n";
    return FALSE;
  }
  return TRUE;
}

//------------------------------------------------------------------------------
//  generic_update
//
//  As the name says: do some sort of generic update. Don't forget to use
// sqlvalue on any paramters!
//
//------------------------------------------------------------------------------

function generic_update($sql) {
  $conn = db_connect();

  $result = mysql_query($sql);
  if (!$result) {
    echo "generic_update: query '$sql' failed.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "<br>\n";
    return FALSE;
  }
  else {
    return TRUE;
  }
}

//------------------------------------------------------------------------------
//  last_proceeding_mod_time
//
//  Return the last time the proceeding or any of it's papers was modfied.
//
//------------------------------------------------------------------------------

function last_modified() {
  $conn = db_connect();

  $query = "select MAX(modified) from proceedings";
  $result = mysql_query($sql);
  if (!$result) {
    echo "<p>last_modified: failed to calculate max mod for proceedings.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "</p>\n";
    return FALSE;
  }
  $num = @mysql_num_rows($result);
  if ($num == 0) {
    return FALSE;
  }

  $procmax = mysql_result($result, 0, "MAX(modified)");

  $query = "select MAX(modified) from papers";
  $result = mysql_query($sql);
  if (!$result) {
    echo "<p>last_modified: failed to calculate max mod for papers.<br>\n";
    echo mysql_errno() . ": " . mysql_error() . "</p>\n";
    return FALSE;
  }
  $num = @mysql_num_rows($result);
  if ($num == 0) {
    return FALSE;
  }

  $papmax = mysql_result($result, 0, "MAX(modified)");

  return max($procmax, $papmax);
}

?>
