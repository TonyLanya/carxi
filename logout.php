<?php
	include_once("common.php");

	unset($_SESSION['sess_iUserId']);
	unset($_SESSION["sess_iCompanyId"]);
	unset($_SESSION["sess_vName"]);
	unset($_SESSION["sess_vEmail"]);
	unset($_SESSION["sess_user"]);

	session_destroy();

	header("Location:sign-in.php");
	exit;
?>
