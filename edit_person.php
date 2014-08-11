<?php
/**
 * edit_proceeding.php
 *
 * Copyright (c) 2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Display the form that enables you to edit the person details, or to add files.
 *
 * $Id: edit_person.php,v 1.3 2005/09/27 21:41:07 rivimey Exp $
 */

require_once('user_auth_fns.php');
require_once('misc_fns.php');
require_once('html_output_fns.php');
require_once('paper_fns.php');
require_once('admin_fns.php');
require_once('form_output_fns.php');


//-----------------------------------------------------------------
//  do_update
//
//  Update the person record in the database.
//
//-----------------------------------------------------------------

function do_update($num, $person) {
  echo "<table bgcolor=\"#ffe88e\">";
  echo "<tr bgcolor=\"#ffd84c\"><td colspan=\"2\">Summary of person updates</td></tr>\n";
  $update_person = FALSE;

  $title = stripslashes($_POST['title']);
  if ($person['title'] != $title) {
    $person['title'] = $title;
    echo "<tr><td><b>Title:</b></td>";
    $update_person = TRUE;
    echo "<td><input type=\"text\" name=\"title\" value=\"" . htmlspecialchars($title) . "\" size=\"50\" readonly></td></tr>\n";
  }
  $firstname = stripslashes($_POST['firstname']);
  if ($person['firstname'] != $firstname) {
    $person['firstname'] = $firstname;
    echo "<tr><td><b>Firstname:</b></td>";
    $update_person = TRUE;
    echo "<td><input type=\"text\" name=\"firstname\" value=\"" . htmlspecialchars($firstname) . "\" size=\"50\" readonly></td></tr>\n";
  }
  $lastname = stripslashes($_POST['lastname']);
  if ($person['lastname'] != $lastname) {
    $person['lastname'] = $lastname;
    echo "<tr><td><b>Lastname:</b></td>";
    $update_person = TRUE;
    echo "<td><input type=\"text\" name=\"lastname\" value=\"" . htmlspecialchars($lastname) . "\" size=\"50\" readonly></td></tr>\n";
  }
  $address1 = stripslashes($_POST['address1']);
  if ($person['address1'] != $_POST['address1']) {
    $person['address1'] = $address1;
    echo "<tr><td><b>Address 1</b></td>";
    $update_person = TRUE;
    echo "<td><input type=\"text\" name=\"address1\" value=\"" . htmlspecialchars($address1) . "\" size=\"50\" readonly></td></tr>\n";
  }
  $address2 = stripslashes($_POST['address2']);
  if ($person['address2'] != $_POST['address2']) {
    $person['address2'] = $address2;
    echo "<tr><td><b>Address 2</b></td>";
    $update_person = TRUE;
    echo "<td><input type=\"text\" name=\"address2\" value=\"" . htmlspecialchars($address2) . "\" size=\"50\" readonly></td></tr>\n";
  }
  $address3 = stripslashes($_POST['address3']);
  if ($person['address3'] != $_POST['address3']) {
    $person['address3'] = $address3;
    echo "<tr><td><b>Address 3</b></td>";
    $update_person = TRUE;
    echo "<td><input type=\"text\" name=\"address3\" value=\"" . htmlspecialchars($address3) . "\" size=\"50\" readonly></td></tr>\n";
  }
  $area = stripslashes($_POST['area']);
  if ($person['area'] != $_POST['area']) {
    $person['area'] = $area;
    echo "<tr><td><b>Area</b></td>";
    $update_person = TRUE;
    echo "<td><input type=\"text\" name=\"area\" value=\"" . htmlspecialchars($area) . "\" size=\"50\" readonly></td></tr>\n";
  }
  $city = stripslashes($_POST['city']);
  if ($person['city'] != $_POST['city']) {
    $person['city'] = $city;
    echo "<tr><td><b>City</b></td>";
    $update_person = TRUE;
    echo "<td><input type=\"text\" name=\"city\" value=\"" . htmlspecialchars($city) . "\" size=\"50\" readonly></td></tr>\n";
  }
  $country = stripslashes($_POST['country']);
  if ($person['country'] != $_POST['country']) {
    $person['country'] = $country;
    echo "<tr><td><b>Country</b></td>";
    $update_person = TRUE;
    echo "<td><input type=\"text\" name=\"country\" value=\"" . htmlspecialchars($country) . "\" size=\"50\" readonly></td></tr>\n";
  }
  $email = stripslashes($_POST['email']);
  if ($person['email'] != $_POST['email']) {
    $person['email'] = $email;
    echo "<tr><td><b>Email</b></td>";
    $update_person = TRUE;
    echo "<td><input type=\"text\" name=\"email\" value=\"" . htmlspecialchars($email) . "\" size=\"50\" readonly></td></tr>\n";
  }
  $homepage = stripslashes($_POST['homepage']);
  if ($person['homepage'] != $_POST['homepage']) {
    $person['homepage'] = $homepage;
    echo "<tr><td><b>Homepage</b></td>";
    $update_person = TRUE;
    echo "<td><input type=\"text\" name=\"homepage\" value=\"" . htmlspecialchars($homepage) . "\" size=\"50\" readonly></td></tr>\n";
  }
  $notes = stripslashes($_POST['notes']);
  if ($person['notes'] != $_POST['notes']) {
    $person['notes'] = $notes;
    echo "<tr><td><b>Notes</b></td>";
    $update_person = TRUE;
    echo "<td><input type=\"text\" name=\"notes\" value=\"" . htmlspecialchars($notes) . "\" size=\"50\" readonly></td></tr>\n";
  }

  if (!$update_person) {
    echo "<tr><td colspan=\"2\">You have not changed the person's name, address or details.</td></tr>\n";
  }
  echo "</table>\n";

  if ($update_person) {
    if (update_person($person['personid'], $person)) {
      do_para("Updated OK");
    }
    else {
      do_para("Update Failed.");
    }
  }
}

//---------------------------------------------------------------------------

session_start();
if (check_admin_user()) {
  $f = isset($_POST['f']) ? $_POST['f'] : $_GET['f'];
  $num = isset($_POST['num']) ? $_POST['num'] : $_GET['num'];

  if ($f < 1 || $f > 2 || $num == 0) {
    do_html_header("No person...", "none");
    do_para("No person specified\n");
    do_html_footer();
    exit;
  }

  if ($f == 1) {
    $person = get_person($num);
    do_html_header("Updating person \"" . make_name($person, 1) . "\"", "none");
    display_person_form($person);
    do_html_url("admin.php", "Back to administration menu");
  }
  elseif ($f == 2) {
    $person = get_person($num);
    do_html_header("Updating person \"" . make_name($person, 1) . "\"", "none");
    do_update($num, $person);

    echo "<p>Links:</p><ul><li>";
    do_html_url("insert_person_form.php", "Add a new person");
    echo "<li>";
    do_html_url("admin.php", "Back to administration menu");
    echo "<li>";
    do_html_url("index.php", "Back to main menu");
    echo "</ul>";
  }
}
else {
  do_html_header("Invalid Access", "none");
  do_para("You are not authorised to view this page.");
}
do_html_footer();
