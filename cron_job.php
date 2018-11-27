<?php

include_once("common.php");

$sql = "DELETE FROM `register_user`
        WHERE (vEmail = '' OR vEmail IS NULL)";

$obj->MySQLSelect($sql);
?>

 