<?
/**
 * login.php
 *
 * Copyright (c) 2000-2003 Ruth Ivimey-Cook
 * Licensed under the GNU GPL. For full terms see the file COPYING.
 *
 * Display the administrator login form
 *
 * $Id: login.php,v 1.1 2003/05/11 16:53:05 ruthc Exp $
 */

  require_once("html_output_fns.php");

  do_html_header("Administration");
?>
<form method=post action="admin.php">
  <table>
    <tr> 
      <td>Username:</td>
      <td><input type="text" name="username"></td>
    </tr>
    <tr> 
      <td>Password:</td>
      <td><input type="password" name="passwd"></td>
    </tr>
    <tr> 
      <td colspan=2 align=center> <input type="submit" value="Log in"></td>
    </tr>
    <tr> 
  </table>
</form>
<?

 do_html_footer();
?>
