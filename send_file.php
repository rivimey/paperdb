<?
/**
 * send_file.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Send the file identified by file id 'num' to the user, using the appropriate
 * MIME types, as an inline document.
 *
 * $Id: send_file.php,v 1.6 2005/09/27 21:39:10 rivimey Exp $
 */

/* change calls with id= to calls with num= */
if (isset($_GET['id']) || isset($_POST['id'])) {

  // get the id value, which should be a paper number
  $id = isset($_GET['id']) ? $_GET['id'] : $_POST['id'];

  // there should be a function number too...
  if ($id >= 0 && $id <= 9999999)
    $relative_url = "send_file.php?num=$id";
  else
    $relative_url = "send_file.php";

  header("Location: http://" . $_SERVER['HTTP_HOST']
		    . rtrim(dirname($_SERVER['PHP_SELF']), '/\\')
		    . "/" . $relative_url);

  header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
}


require_once("html_output_fns.php");
require_once("paper_fns.php");

session_cache_limiter('private');
session_start();
 
if ( !isset($_GET['num'])) {
    do_para("No file?");
    $paper="";
}
else {
    $num = $_GET['num'];
    $paper = get_file_by_id($num);
}

if (is_array($paper)) {
    if ($maintain_stats)
        update_paperfile_count($num);

    $contents = $paper['paper'];
    $len = $paper['length'];
    $md5 = $paper['md5'];
    $new_md5 = md5($contents);
    $filetype = $paper['filetype'];
    $filename = $paper['filename'];
    
    if ($filetype == "HTML") {
        $t = "text/html";
    } elseif ($filetype == "PS") {
        $t = "application/ps";
    } elseif ($filetype == "PDF") {
        $t = "application/pdf";
    } elseif ($filetype == "PPT") {
        $t = "application/vnd.ms-powerpoint";
    } elseif ($filetype == "MSW") {
        $t = "application/msword";
    } else {
        $t = "application/octet-stream";
    }
    
    if ($new_md5 == $md5) {
        header("Content-Type: $t"); 
        header("Content-Length: $len");
        if ($filetype == "HMTL"){
            header("Content-Disposition: inline");
        }
        elseif ($filetype == "PDF"){
            header("Content-Disposition: inline; filename=$filename");
        }
        else {
            header("Content-Disposition: attachment; filename=$filename");
        }
        header("Pragma: private"); // fix for IE

        echo $contents;
    }
    else {
        do_para("Bad file: MD5 sums don't match: $new_md5 vs $md5.");
        do_para("Please contact the administrator:.");
        echo "<address><a href=\"mailto:webweaver@wotug.org\">mailto:webweaver@wotug.org</a></address>\n";
    }
}
?>
