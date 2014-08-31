<?php
/**
 * form_output_fns.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Functions that display forms containing info for/from the database.
 *
 * $Id: form_output_fns.php,v 1.11 2005/09/27 21:43:20 rivimey Exp $
 */

require_once("compat_fns.php");
require_once("paper_fns.php");

//--------------------------------------------------------------------------------------
//  display_password_form
//
//  Display the form used to request a username and password.
//
//--------------------------------------------------------------------------------------

function display_password_form() {
// displays html change password form
  ?>
  <br>
  <form action="change_password.php" method=post>
  <table width=250 cellpadding=2 cellspacing=0>
    <tr>
      <td>Old password:</td>
      <td><input type=password name=old_passwd size=16 maxlength=16 /></td>
    </tr>
    <tr>
      <td>New password:</td>
      <td><input type=password name=new_passwd size=16 maxlength=16 /></td>
    </tr>
    <tr>
      <td>Repeat new password:</td>
      <td><input type=password name=new_passwd2 size=16 maxlength=16 /></td>
    </tr>
    <tr>
      <td colspan=2 align=center><input type=submit value="Change password">
      </td>
    </tr>
  </table>
  <br>
<?php
}


//--------------------------------------------------------------------------------------
//  display_person_form
//
//  Display the form used to add or edit a new person record.
//
//--------------------------------------------------------------------------------------

function display_person_form($person = "") {
  $org_array = get_organisations();

  $edit = is_array($person);
  $action = $edit ? "edit_person.php" : "insert_person.php";
  echo "<form enctype=\"multipart/form-data\" method=\"post\" action=\"$action\">\n";
  ?>
  <table class="Papertable">
    <?php if ($edit) { ?>
      <input type="hidden" name="f" value="2" />
      <tr>
        <td>ID:</td>
        <td>
          <input type="text" readonly name="num" value="<?= $edit ? $person['personid'] : '' ?>" />
        </td>
      </tr>
    <?php } ?>
    <tr>
      <td>Title:</td>
      <td>
        <input type="text" name="title" value="<?= $edit ? $person['title'] : '' ?>" />
      </td>
    </tr>
    <tr>
      <td>First Name:</td>
      <td>
        <input type="text" name="firstname" value="<?= $edit ? $person['firstname'] : '' ?>" />
      </td>
    </tr>
    <tr>
      <td>Surname: *</td>
      <td>
        <input type="text" name="lastname" value="<?= $edit ? $person['lastname'] : '' ?>" />
      </td>
    </tr>
    <tr>
      <td colspan="2">
        <small>Warning: Entering information about living people that is not publicly
          published will probably render the database subject to registration under EU Data Protection
          legislation.
        </small>
      </td>
    </tr>
    <tr>
      <td>Address 1:</td>
      <td>
        <input type="text" name="address1" value="<?= $edit ? $person['address1'] : '' ?>" />
      </td>
    </tr>
    <tr>
      <td>Address 2:</td>
      <td>
        <input type="text" name="address2" value="<?= $edit ? $person['address2'] : '' ?>" />
      </td>
    </tr>
    <tr>
      <td>Address 3:</td>
      <td>
        <input type="text" name="address3" value="<?= $edit ? $person['address3'] : '' ?>" />
      </td>
    </tr>
    <tr>
      <td>City:</td>
      <td>
        <input type="text" name="city" value="<?= $edit ? $person['city'] : '' ?>" />
      </td>
    </tr>
    <tr>
      <td>Area:</td>
      <td>
        <input type="text" name="area" value="<?= $edit ? $person['area'] : '' ?>" />
      </td>
    </tr>
    <tr>
      <td>Country:</td>
      <td>
        <input type="text" name="country" value="<?= $edit ? $person['country'] : '' ?>" />
      </td>
    </tr>
    <tr>
      <td>Organisation:</td>
      <td>
        <?php
        echo "<select name=\"organisation\">\n";
        echo "<option value=\"0\">None</option>";
        if (is_array($org_array)) {
          // list of possible orgs comes from database
          foreach ($org_array as $thisorg) {
            $str = $thisorg["name"];
            $num = $thisorg["orgid"];
            echo "<option value=\"$num\">$str\n";
            echo "</option>";
          }
        }
        echo "</select>\n";
        ?>
      </td>
    </tr>
    <tr>
      <td>Email Address:</td>
      <td>
        <input type="text" name="email" value="<?= $edit ? $person['email'] : '' ?>" />
      </td>
    </tr>
    <tr>
      <td>Homepage URL:</td>
      <td>
        <input type="text" name="homepage" value="<?= $edit ? $person['homepage'] : '' ?>" />
      </td>
    </tr>
    <tr>
      <td>Notes:</td>
      <td>
        <input type="textarea" name="notes" value="<?= $edit ? $person['notes'] : '' ?>" />
      </td>
    </tr>
    <tr>
      <td colspan="2" align="center">
        <input type="submit" value="<?= $edit ? "Update" : "Insert" ?>" /></td>
    </tr>
    <tr>
  </table>
  </form>
<?php
}


//--------------------------------------------------------------------------------------
//  display_org_form
//
//  Display the form used to add or edit an organization, such as
// a university or company.
//
//--------------------------------------------------------------------------------------

function display_org_form($org = '') {
  // a form asking for name and password
  $org_array = get_organisations();

  ?>
  <form method=post action="insert_org.php">
    <table class="Papertable">
      <tr>
        <td>Name:</td>
        <td><input type="text" name="name"></td>
      </tr>
      <tr>
        <td>Address 1:</td>
        <td><input type="text" name="address1"></td>
      </tr>
      <tr>
        <td>Address 2:</td>
        <td><input type="text" name="address2"></td>
      </tr>
      <tr>
        <td>Address 3:</td>
        <td><input type="text" name="address3"></td>
      </tr>
      <tr>
        <td>City:</td>
        <td><input type="text" name="city"></td>
      </tr>
      <tr>
        <td>Area:</td>
        <td><input type="text" name="area"></td>
      </tr>
      <tr>
        <td>Country:</td>
        <td><input type="text" name="country"></td>
      </tr>
      <tr>
        <td>Enquiries Email:</td>
        <td><input type="text" name="email"></td>
      </tr>
      <tr>
        <td>Homepage URL:</td>
        <td><input type="text" name="homepage"></td>
      </tr>
      <tr>
        <td>Notes:</td>
        <td><input type="textarea" name="notes"></td>
      </tr>
      <tr>
        <td colspan=2 align=center><input type=submit value="Submit"></td>
      </tr>
      <tr>
    </table>
  </form>
<?php
}

//--------------------------------------------------------------------------------------
//  display_proceeding_form
//
//  Display the form for adding or amending a proceeding.
// This form can be used for inserting or editing proceedings. To insert,
// don't pass any parameters. This will set $edit to false, and the
// form will go to insert_proceeding.php. To update, pass an array
// containing a proceeding. The form will be displayed with the old
// data and point to update_proceeding.php. It will also add a "Delete
// proceeding" button.
//
//--------------------------------------------------------------------------------------

function display_proceeding_form($proceeding = "") {
  $pub_array = get_publishers();
  if (!is_array($pub_array)) {
    do_para("No publishers defined. Add one first.");
    return;
  }
  $peop_array = get_people();
  if (!is_array($peop_array)) {
    do_para("No editors defined. Add (at least) one first.");
    return;
  }

  // if passed an existing proceeding, proceed in "edit mode"
  $edit = is_array($proceeding);
  if ($edit) {
    $num = $proceeding["proceedingid"];
    echo "<form method=post action=\"edit_proceeding.php\">";
    echo "<input type=hidden name=\"num\" value=\"$num\">";
    echo "<input type=hidden name=\"f\" value=\"3\">"; // f=3 => summary, f=4 => do update

    $edit_array = get_editors_by_listid($num);
    if (!is_array($peop_array)) {
      do_para("No editors defined. Add (at least) one first.");
      return;
    }
  }
  else {
    echo "<form method=post action=\"insert_proceeding.php\">";
    $edit_array = array();
  }
  ?>
  <table border=0>
    <tr>
      <td>Proceeding Title:</td>
      <td><input size=60 type=text name=title
                 value="<?= $edit ? $proceeding["title"] : ""; ?>"></td>
    </tr>
    <tr>
      <td>Proceeding Subtitle:</td>
      <td><input size=60 type=text name=subtitle
                 value="<?= $edit ? $proceeding["subtitle"] : ""; ?>"></td>
    </tr>
    <tr>
      <td>Series:</td>
      <td><input size=60 type=text name=series
                 value="<?= $edit ? $proceeding["series"] : ""; ?>"></td>
    </tr>
    <tr>
      <td>Date:</td>
      <td>
        <input size=4 type=text name=pubyear value="<?= $edit ? $proceeding["pubyear"] : ""; ?>"> /
        <input size=4 type=text name=pubmonth value="<?= $edit ? $proceeding["pubmonth"] : ""; ?>"> /
        <input size=4 type=text name=pubday value="<?= $edit ? $proceeding["pubday"] : ""; ?>">
        (YYYY / MM / DD)
      </td>
    </tr>
    <tr>
      <td>ISBN:</td>
      <td><input size=12 type=text name=isbn
                 value="<?= $edit ? $proceeding["isbn"] : ""; ?>"></td>
    </tr>
    <tr>
      <td>ISSN:</td>
      <td><input size=12 type=text name=issn
                 value="<?= $edit ? $proceeding["issn"] : ""; ?>"></td>
    </tr>
    <tr>
      <td>Volume in series:</td>
      <td><input size=6 type=text name=volume
                 value="<?= $edit ? $proceeding["volume"] : ""; ?>"></td>
    </tr>
    <tr>
      <td>Publisher:</td>
      <td>
        <select size="2" name="publisherid">
          <?php
          // list of possible publishers comes from database
          foreach ($pub_array as $thispub) {
            echo "<option value=\"";
            echo $thispub["publisherid"];
            echo "\"";
            // if existing proceeding, put in current pub
            if ($edit && $thispub["publisherid"] == $proceeding["publisherid"]) {
              echo " selected";
            }
            echo ">";
            echo $thispub["name"];
            echo "</option>\n";
          }
          ?>
        </select>
        <!-- input type="button" name="addPub" value="Add Publisher" onClick="displayPubform()" -->
      </td>
    </tr>
    <tr>
      <td>Editor(s):</td>
      <td>
        <select name="editors[]" size="15" multiple>
          <?php
          // list of possible editors comes from database
          foreach ($peop_array as $thispers) {
            echo "<option value=\"";
            echo $thispers["personid"];
            echo "\"";
            // if existing proceeding, put in current pub
            if ($edit) {
              foreach ($edit_array as $ed) {
                if ($thispers["personid"] == $ed["personid"]) {
                  echo " selected";
                }
              }
            }
            echo ">";
            echo $thispers["title"] . " ";
            echo $thispers["firstname"] . " ";
            echo $thispers["lastname"];
            echo "</option>\n";
          }
          ?>
        </select>
        <?= $edit ? "<input type=checkbox name=editor_ordering value=false />" : ""; ?>
      </td>
    </tr>
    <tr>
      <td>Totpages:</td>
      <td>
        <input size=6 type=text name=totpages value="<?= $edit ? $proceeding["totpages"] : ""; ?>" />
      </td>
    </tr>

    <tr>
      <td <?php if (!$edit) {
        echo "colspan=2";
      } ?> align=center>
        <?php
        if ($edit)
          // we might need the old isbn to find proceeding in database
          // if the isbn is being updated
        {
          echo "<input type=hidden name=\"oldisbn\" value=\"" . $proceeding["isbn"] . "\">";
        }
        ?>
        <input type=submit
               value="<?= $edit ? "Update" : "Add"; ?> Proceeding">
        </form></td>
      <?php
      if ($edit) {
        echo "<td>";
        echo "<form method=post action=\"delete_proceeding.php\">";
        echo "<input type=hidden name=\"isbn\" value=\"" . $proceeding["isbn"] . "\">";
        echo "<input type=submit value=\"Delete proceeding\">";
        echo "</form></td>";
      }
      ?>
      </td>
    </tr>
  </table>
  </form>
<?php
}

//------------------------------------------------------------------------------------------------------------------------------
//  display_papers_form
//
//  Create a form used to add or edit a paper.
//
// To insert, don't pass any parameters.  This will set $edit to false, and the
// form will go to insert_paper.php.
//
// To update, pass an array containing a paper.  The form will contain the old
// data and point to edit_paper.php. It will also add a "Delete paper" button
// and the option to add attached files.
//
//------------------------------------------------------------------------------------------------------------------------------

function display_papers_form($paper = "") {
  // if passed an existing paper, proceed in "edit mode"
  $edit = is_array($paper);

  $proc_array = get_proceedings(FALSE);
  if (!is_array($proc_array)) {
    do_para("No proceedings defined. Add the proceeding this paper is in first.");
    return;
  }

  $people_array = get_people();
  if ($edit) {
    $num = $paper["paperid"];
    $assoc_files = get_paper_file_ids($num);
    $procs_set = get_proceedings_by_paperid($num);
    $authors = get_authors_by_paperid($num); // array of authorids
  }
  else {
    $assoc_files = FALSE;
  }

  $action = $edit ? "edit_paper.php" : "insert_papers.php";
  echo "<form enctype=\"multipart/form-data\" method=\"post\" action=\"$action\">\n";
  echo "<table border=0>\n";

  echo "<tr><td valign=\"top\">Proceeding:</td>\n";
  echo "<td>\n";
  if (count($proc_array) < 6) {
    $c_p = count($proc_array);
  }
  else {
    $c_p = 6;
  }
  echo "<select multiple name=\"procid[]\" size=\"$c_p\">\n";
  // list of possible proceedings from database
  foreach ($proc_array as $thisproc) {
    $str = htmlspecialchars($thisproc["title"]);
    if (strlen($str) > 60) { // limit length to something shortish...
      $str = substr($str, 0, 58) . "...";
    }
    // If editing, iterate through the proceedings for this paper and
    // mark them selected.
    $prid = $thisproc["proceedingid"];
    echo "<option value=\"" . $prid . "\"";
    if ($edit && is_array($procs_set)) {
      foreach ($procs_set as $setproc) {
        if ($prid == $setproc["proceedingid"]) {
          echo " selected";
        }
      }
    }
    echo ">$str</option>\n";
  }
  echo "</select></td></tr>\n";

  echo "<tr><td>Paper Title:</td>\n";
  echo "<td>\n";
  $value = htmlspecialchars($edit ? $paper["title"] : "");
  echo "<input type=\"text\" name=\"title\" size=\"80\" maxlength=\"120\" value=\"$value\">\n";
  echo "</td></tr>\n";

  echo "<tr><td valign=\"top\">Author:</td>\n";
  echo "<td>";
  if (count($people_array) < 14) {
    $c_p = count($people_array);
  }
  else {
    $c_p = 14;
  }

  if ($edit) {
    echo "<table border=\"1\" cellpadding=\"3\"><tr><td>Currently Defined:&nbsp;</td><td>Select:&nbsp;</td></tr><tr><td>\n";

    // list out the currently assigned authors, if any
    if (is_array($authors)) {
      echo "<select disabled name=\"oldauthors[]\" size=\"$c_p\">\n";
      foreach ($authors as $au) {
        // use already-loaded people_array to look up the names of the author ids.
        foreach ($people_array as $thisp) {
          if ($thisp['personid'] == $au['authorid']) {
            $name = "";
            if ($thisp["title"] != "") {
              $name .= $thisp["title"] . " ";
            }
            if ($thisp["firstname"] != "") {
              $name .= $thisp["firstname"] . " ";
            }
            $name .= $thisp["lastname"];
            echo "<option>" . $name . "</option>\n";
          }
        }
      }
      echo "</select>\n";
    }
    echo "</td><td>\n";
  }

  echo "<select name=\"authors[]\" multiple size=\"$c_p\">\n";
  // list of possible people comes from database
  if (is_array($people_array)) {
    foreach ($people_array as $thisp) {
      $name = "";
      if ($thisp["title"] != "") {
        $name .= $thisp["title"] . " ";
      }
      if ($thisp["firstname"] != "") {
        $name .= $thisp["firstname"] . " ";
      }
      $name .= $thisp["lastname"];
      //$name = htmlspecialchars($name); shouldn't be needed as already specialised.

      echo "<option value=\"" . $thisp["personid"] . "\"";
      if ($edit && is_array($authors)) {
        foreach ($authors as $au) {
          if ($edit && $thisp["personid"] == $au['authorid']) {
            echo " selected";
          }
        }
      }
      echo ">" . $name . "</option>\n";
    }
  }
  echo "</select>\n";
  if ($edit) {
    echo "</td></tr></table>\n";
  }
  echo "</td></tr>\n";

  echo "<tr><td valign=\"top\">Abstract:</td>\n";
  echo "<td>\n";
  $value = htmlspecialchars($edit ? $paper["abstract"] : "");
  echo "<textarea name=\"abstract\" rows=\"12\" cols=\"80\">$value</textarea>\n";
  echo "</td></tr>\n";

  echo "<tr><td valign=\"top\">Paper URL:</td>\n";
  echo "<td>\n";
  $value = htmlspecialchars($edit ? $paper["paper_url"] : "");
  echo "<input type=\"text\" name=\"paper_url\" size=\"40\" maxlength=\"240\" value=\"$value\">\n";
  echo "</td></tr>\n";

  echo "<tr><td valign=\"top\">QuickRef Text:</td>\n";
  echo "<td>\n";
  $value = htmlspecialchars($edit ? $paper["reftext"] : "");
  echo "<input type=\"text\" name=\"reftext\"  size=\"16\" maxlength=\"20\" value=\"$value\">\n";
  echo "</td></tr>\n";

  if ($edit) {
    echo "<tr><td>\n";
    echo "  <input type=\"hidden\" name=\"num\" value=\"" . $paper["paperid"] . "\">";
    echo "  <input type=\"hidden\" name=\"f\" value=\"3\">"; // f=3 => collect rest of data, f=4 => do it
    echo "  <input type=\"submit\"  value=\"Update Paper\">";
    echo "</td>\n";
    echo "</form>"; // form started at top of this fn.

    echo "<td>";
    echo "  <form method=\"post\" action=\"edit_paper.php\">"; // form just for this button
    echo "    <input type=\"hidden\" name=\"num\" value=\"" . $paper["paperid"] . "\">";
    echo "    <input type=\"hidden\" name=\"f\" value=\"4\">"; // f=5 => delete single paper.
    echo "    <input type=\"submit\" value=\"Delete paper\">";
    echo "  </form>";
    echo "</td></tr>";
    echo "</table>\n";
  }
  else {
    echo "<tr><td colspan=\"2\" align=\"center\">\n";
    echo "  <b><input type=\"submit\"  value=\"Add Paper\"></b></form>";
    echo "</td></tr>\n";
    echo "</table>\n";
  }

  if ($assoc_files) {
    echo "<table border=0>\n";
    echo "<tr><td colspan=2>Current files:</td>\n";
    foreach ($assoc_files as $file) {
      echo "<tr><td>\"" . $file["filename"] . "\" (" . $file["filetype"] . ")\n";
      echo "<td>";
      echo "  <form method=\"post\" action=\"delete_paperfile.php\">";
      echo "    <input type=\"hidden\" name=\"paperid\" value=\"" . $paper["paperid"] . "\">";
      echo "    <input type=\"hidden\" name=\"fileid\" value=\"" . $file["fileid"] . "\">";
      echo "    <input type=\"submit\" value=\"Delete file\">";
      echo "  </form>";
      echo "</td></tr>";
    }
    echo "</table>\n";
  }
  echo "<hr/>";

  if ($edit) {
    /* can only add files to an existing paper. */
    display_addfile_form($paper, 0, $people_array);
  }
}


//------------------------------------------------------------------------------------------------------------------------------
//  display_addfile_form
//
//  Create a form used to add attached files to a paper. You can add
// up to two files per invocation (e.g. PostScript & PDF). Note that
// php has limits on file upload size, memory use etc.
//
//------------------------------------------------------------------------------------------------------------------------------

function display_addfile_form($paper, $inctitle = 1, $auths) {
  echo "<form enctype=\"multipart/form-data\" method=\"post\" " .
    "action=\"add_file.php\">\n";
  echo "<table border=0 cellpadding=2>\n";
  if ($inctitle) {
    echo "<tr><td width=\"100\">Title:</td><td><b>" . $paper["title"] . "</b></td></tr>";
    echo "<tr><td width=\"100\">Authors:</td><td><b>" . $auths . "</b></td></tr>";
  }
  ?>
  <tr>
    <td>Add Paper:</td>
    <td>
      <p>
        <input type="hidden" name="MAX_FILE_SIZE" value="8388608"> <!-- 8MBytes -->
        <input type="hidden" name="f" value="2">
        <input type="hidden" name="num" value="<?php echo $paper["paperid"]; ?>">

        File 1: <input type="file" size="24" maxlength="256" name="filedata1" />&nbsp;<select name="filetype1">
          <option value="PDF">PDF</option>
          <option value="PS">PostScript</option>
          <option value="PPT">PowerPoint</option>
          <option value="MSW">MS Word</option>
          <option value="HTML">HTML</option>
        </select></p>
      <p>
        File 2: <input type="file" size="24" maxlength="256" name="filedata2" />&nbsp;<select name="filetype2">
          <option value="PDF">PDF</option>
          <option value="PS">PostScript</option>
          <option value="PPT">PowerPoint</option>
          <option value="MSW">MS Word</option>
          <option value="HTML">HTML</option>
        </select></p>
    </td>
  </tr>
  <tr>
    <td colspan="2" align="center">
      <b><input type="submit" value="Add Paper"></b></form>
    </td>
  </tr>
  </table>
  </form>
  <?php
  return;
}
