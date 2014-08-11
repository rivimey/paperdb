<?

require_once("html_output_fns.php");
require_once("user_auth_fns.php");
require_once("form_output_fns.php");

session_start();

do_html_header("Add Organisation");
if (check_admin_user())
{
  display_org_form();

  echo "<ul><li>";
  do_html_url("admin.php", "Back to administration menu");
  echo "</ul>";
}
else
  echo "You are not authorized to enter the administration area.";

do_html_footer();

?>
