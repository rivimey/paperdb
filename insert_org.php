<?php

// include function files for this application
require_once("html_output_fns.php");
require_once("user_auth_fns.php");
require_once("form_output_fns.php");
session_start();

do_html_header("Adding an Organisation");
if (check_admin_user()) {
  $name = $HTTP_GET_VARS['name'];
  $address1 = $HTTP_GET_VARS['address1'];
  $address2 = $HTTP_GET_VARS['address2'];
  $address3 = $HTTP_GET_VARS['address3'];
  $city = $HTTP_GET_VARS['city'];
  $area = $HTTP_GET_VARS['area'];
  $country = $HTTP_GET_VARS['country'];
  $email = $HTTP_GET_VARS['email'];
  $homepage = $HTTP_GET_VARS['homepage'];
  $notes = $HTTP_GET_VARS['notes'];

  if ($name != "") {
    if (insert_org($name, $address1, $address2, $address3, $city, $area, $country, $email, $homepade, $notes)) {
      echo "Organisation '$name' was added to the database.<br>";
    }
    else {
      echo "Organisation '$name' could not be added to the database.<br>";
    }
  }
  else {
    echo "'name' is a required field. Please try again.<br>";
  }
  echo "<ul><li>";
  do_html_url("insert_org_form.php", "Add another organisation");
  echo "<li>";
  do_html_url("admin.php", "Back to administration menu");
  echo "</ul>";
}
else {
  do_para("You are not authorised to view this page.");
}

do_html_footer();
