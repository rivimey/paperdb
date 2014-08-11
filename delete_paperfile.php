<?
/**
 * delete_paperfile.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Back-end administration functions, only used by the administrator (not web-users!).
 * Delete a file associated with a paper.
 *
 * $Id: delete_paperfile.php,v 1.3 2005/09/27 21:43:20 rivimey Exp $
 */

require_once("html_output_fns.php");
require_once("user_auth_fns.php");
require_once("admin_fns.php");
require_once("paper_fns.php");

session_start();

$paperid = $_POST['paperid'];
$fileid = $_POST['fileid'];

do_html_header("Deleting a file associated with a paper");
if (check_admin_user())
{ 
  $paper = get_paper($paperid);
  $file = get_file_by_id($fileid);
  $ftitle = $file['filename'];

  echo "Deleting file ".$file['filename'].", &lt;".$fileid."&gt; from paper ".$paper['title']." &lt;$paperid&gt;<br>";

  if(delete_paper_file($fileid) == false)
    echo "File '$ftitle' could not be removed from the database.<br>";
  else
    echo "File '$ftitle' was removed from the database.<br>";


  echo "<ul><li>";
  do_html_url("show_pap.php?f=1&amp;num=".$paperid, "To paper details.");
  echo "<li>";
  do_html_url("insert_papers_form.php", "Add another paper.");
  echo "<li>";
  do_html_url("admin.php", "Back to administration menu");
  echo "</ul>";

}
else 
  echo "You are not authorised to view this page."; 

do_html_footer();

?>
