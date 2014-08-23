<?php
/**
 * misc_fns.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Functions dealing with namelists.
 *
 * $Id: misc_fns.php,v 1.8 2005/09/27 21:33:53 rivimey Exp $
 */

require_once("compat_fns.php");
require_once("user_auth_fns.php");

/**
 * Used by many routines to create a name from a person db record using
 * one of the styles.
 * If this is an admin session, make the names links to the person edit
 * page.
 *
 * @param $person
 * @param $style
 *  Style == 0 is:
 *        lastname, title firstname.
 *  Style == 1 is:
 *        title firstname lastname, whereas
 *
 *  Style + 2 means never put an edit user link in the name.
 *
 *  Style + 4 means don't include the title
 *
 * @return string
 *   the name
 */
function make_name($person, $style) {
  $res = "";
  if ((($style & 4) == 4) || (empty($person["title"]))) {
    $ttl = "";
  }
  else {
    $ttl = $person["title"] . " ";
  }

  if (($style & 1) == 1) {
    $res .= $ttl . $person["firstname"] . " " . $person["lastname"];
  }
  else {
    $res .= $person["lastname"] . ", " . $ttl . $person["firstname"];
  }

  if (check_admin_user() && ($style & 2) == 0) {
    $res .= "<a href=\"edit_person.php?f=1&amp;num=" . $person['personid'] . "\">^</a>";
  }
  return $res;
}

//-------------------------------------------------------------------------
// make_namelist
//
//  Used by many routines to return a string of the names for each person
// in the array "list".
//  Sep is the separator between each person, for example " and "
//  Emptymsg is the string returned if the list is empty.
//  style is passed to make_name.
//-------------------------------------------------------------------------

function make_namelist($list, $sep, $emptymsg, $style) {
  if (is_array($list)) {
    $res = "";
    $num = count($list) - 1;
    for ($count = 0; $count <= $num; $count++) {
      $res .= make_name($list[$count], $style);
      if ($count < $num) {
        $res .= $sep;
      }
    }
  }
  else {
    $res = $emptymsg;
  }
  return $res;
}
