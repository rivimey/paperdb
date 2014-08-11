<?php
/**
 * SAMPLE config.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Configuration information for paper database.
 *
 * $Id: config.php,v 1.3 2004/11/16 00:52:20 rivimey Exp $
 */


$configVersioni     = '1';
$siteName           = "YourSite";
$defaultLanguage    = 'en_US';
$defaultCharset     = 'iso-8859-1';
$MySQLServerAddress = 'example.org';
$MySQLUsername      = 'SQLUsername';
$MySQLPassword      = 'DBpasswd';
$MySQLDatabase      = 'exampleDBName';
$documentRootDir    = '/var/www/site/';   // document root of the webserver
$paperdbSubDir      = 'paperdb/';         // location of the paperdb source files, relative to docroot.
$maintain_stats     = true;               // write count and access time of significant ops.

//------------------------------------------------------------------------------------------------------------------------------
//  local_html_header
//
//  Write out the generic stuff that each page should have. Mostly, this is 
// going to be related to the rest of the website rather than specific to paperdb.
//
//------------------------------------------------------------------------------------------------------------------------------

function local_html_header()
{
  global $documentRootDir;

  include $documentRootDir."style/style.htm";
  echo "</head><body>\n";
  include $documentRootDir."style/banner.htm";
}

//------------------------------------------------------------------------------------------------------------------------------
//  local_html_footer
//
//  Complete whatever constructs were started in the header and write out any
// footer that is required. The /body and /html tags are added after this.
//
//------------------------------------------------------------------------------------------------------------------------------

function local_html_footer()
{
  global $documentRootDir;
?>
  <p>
    <?php include $documentRootDir."/style/copy.htm" ?>
  </p>
<?
}

?>
