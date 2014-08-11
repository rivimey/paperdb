<?php
/**
 * list_papers.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * $Id: list_papers.php,v 1.6 2005/09/27 21:38:06 rivimey Exp $
 */

require_once("paper_fns.php");
require_once("misc_fns.php");
require_once("html_output_fns.php");

session_start();

do_html_header("List all Papers", "all");

if (isset($HTTP_POST_VARS['SortOrder']))   {
  $SortOrder = $HTTP_POST_VARS['SortOrder'];
} else {
  $SortOrder = "Title";
}

$paperlist = get_papers_and_proceedings($SortOrder);
if ($paperlist ) {
?>
<form method="post" action="list_papers.php">
<table>
<tr>
   <th><input type="submit" name="SortOrder" value="Title"/></th>
   <th><input type="submit" name="SortOrder" value="Date"/></th>
   <th><input type="submit" name="SortOrder" value="pp"/></th>
   <th colspan="2">Formats</th>
</tr>
<?php
  foreach ($paperlist as $paper)
  {
    echo "<tr><td>";
    echo "<a href=\"show_pap.php?f=1&amp;num=".$paper["paperid"]."\">".$paper["papertitle"]."</a>\n"; 

    $authorlist = get_authors_by_listid($paper["paperid"]);
    echo " ".make_namelist($authorlist, ", ", "[No authors recorded]", 1);

    echo "</td><td>".$paper["pubyear"]; 
    echo "</td><td>".$paper["pages"]; 
    echo "</td><td>";
    echo " <a href=\"show_pap.php?f=2&amp;num=".$paper["paperid"]."\">BibTEX</a>\n"; 
    echo "</td><td>";
    echo " <a href=\"show_pap.php?f=3&amp;num=".$paper["paperid"]."\">Refer</a>\n";
    echo "</td></tr>";
  }
  echo "</table></form>\n";
}
else {
  do_para("No papers in database.");
}
do_html_footer();

?>
