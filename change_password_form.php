<?php
require_once("compat_fns.php");
require_once("html_output_fns.php");
require_once("user_auth_fns.php");

session_start();
do_html_header("Change Administrator Password");
check_admin_user();

display_password_form();

do_html_url("admin.php", "Back to Administration Menu");
do_html_footer();

