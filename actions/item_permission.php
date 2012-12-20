<?php

/*  author  skynetdev
 *  email   skynetdev3@gmail.com
 *  
 */
require_once '../config.php';
require_once 'functions.php';
if(!issecurity(true,false)) {echo "<h2>Access Denied!</h2>"; exit;} 

$class_name = str_replace(' ', '', $_POST['class_name']);
$action = $_POST['action'];

if($action == 'on') $status = 1; else $status = 0;

$query = "UPDATE adm_objects SET allowed=$status WHERE class_name ='$class_name'";
mysql_query($query) or die('Errof! '.  mysql_error());
echo 'done';
?>
