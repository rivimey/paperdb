<?
/**
 * insert_person.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Process the add person form.
 *
 * $Id: insert_person.php,v 1.3 2005/09/27 21:38:06 rivimey Exp $
 */

session_start();

require_once("html_output_fns.php");
require_once("user_auth_fns.php");
require_once("admin_fns.php");

do_html_header("Adding a person");
if (check_admin_user())
{ 
  $title = $_POST['title'];
  $firstname = $_POST['firstname'];
  $lastname = $_POST['lastname'];
  $address1 = $_POST['address1'];
  $address2 = $_POST['address2'];
  $address3 = $_POST['address3'];
  $city = $_POST['city'];
  $area = $_POST['area'];
  $country = $_POST['country'];
  $email = $_POST['email'];
  $homepage = $_POST['homepage'];
  $notes = $_POST['notes'];
  $organisation = $_POST['organisation'];

  if (isset($lastname) && $lastname != "") {
    $num = insert_person($title, $firstname, $lastname, $address1, $address2, $address3,
 			                  $city, $area, $country, $email, $homepage, $notes, $organisation);
    
    if($num == false)
      echo "Person '$firstname $lastname' could not be added to the database.<br>";
    else
      echo "Person '$firstname $lastname' was added to the database with id $num.<br>";
  }
  else {
    echo "'lastname' is a required field. Please try again.<br>";
  }

  echo "<ul><li>";
  do_html_url("insert_person_form.php", "Add another person");
  echo "<li>";
  do_html_url("admin.php", "Back to administration menu");
  echo "<li>";
  do_html_url("index.php", "Back to main menu");
  echo "</ul>";
}
else 
  echo "You are not authorised to view this page."; 

do_html_footer();

?>
