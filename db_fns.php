<?
/**
 * db_fns.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Database connection and utility functions.j
 *
 * $Id: db_fns.php,v 1.6 2005/08/12 21:04:15 rivimey Exp $
 */

require_once("/etc/paperdb/config.php");

//------------------------------------------------------------------------------------------------------------------------------
//  db_connect
//
//  Connect to the predefined database server. php itself remembers the connection
// id as the "current" connection, so we don't have to. We use a persistent connection
// and reopen it whenever we arent sure it's open; this makes life easy!
//
//------------------------------------------------------------------------------------------------------------------------------

function db_connect() {
  global $MySQLServerAddress, $MySQLUsername, $MySQLPassword, $MySQLDatabase;

  $result = @mysql_pconnect($MySQLServerAddress, $MySQLUsername, $MySQLPassword);
  if (!$result) {
    echo "db_connect: Could not connect to paper db at \"$MySQLUsername@$MySQLServerAddress\"<br>\n";
    return FALSE;
  }
  if (!@mysql_select_db($MySQLDatabase)) {
    echo "db_connect: Could not find database for paper db at \"$MySQLUsername@$MySQLServerAddress\"<br>\n";
    return FALSE;
  }

  return $result;
}


//------------------------------------------------------------------------------------------------------------------------------
//  db_result_to_array
//
//  Convert a mysql result to a tagged array.
//
//------------------------------------------------------------------------------------------------------------------------------

function db_result_to_array($result) {
  $res_array = array();

  for ($count = 0; $row = @mysql_fetch_array($result); $count++) {
    $res_array[$count] = $row;
  }

  return $res_array;
}

//--------------------------------------------------------------------------------------
//  sqlvalue
//
//  Return "str" suitably formatted for a mysql query string, according
//  to 'type': if "A" (alpha/string) then require single quotes, if numeric
//  then no quotes and transform "" to null
//--------------------------------------------------------------------------------------

function sqlvalue($str, $type = "A") {
  if ($type == "A") {
    if (is_numeric($str)) {
      return "'" . strval($str) . "'";
    }
    else {
      return "'" . mysql_real_escape_string($str) . "'";
    }
  }
  else { // numeric, but if passed "" string then convert to SQL-null
    if (is_string($str) && $str == "") {
      return "null";
    }
    else {
      return $str;
    }
  }
}
