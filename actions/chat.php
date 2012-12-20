<?php

/*  author  skynetdev
 *  email   skynetdev3@gmail.com
 *  
 */
require_once '../config.php';
require_once 'functions.php';
require_once '../modules/rcon.php';


if(!issecurity(true,true,'chat')) {echo "<h2>Access Denied!</h2>"; exit;} 


if(isset($_POST['msg'])){
    
    $msg = $_POST['msg'];
    rcon('Say -1 '.$msg );
    echo "done";
}

?>
