<?php

/*  author  skynetdev
 *  email   skynetdev3@gmail.com
 *  
 */

require_once '../config.php';
require_once 'functions.php';
require_once '../modules/rcon.php';

//check security permissions
if(issecurity(true, true, 'entities') == false) {
    echo "Access Denied!!";
    exit;
}

if(isset($_POST['rcon_id']) && $_POST['rcon_id']!='false' ) $rcon_id= $_POST['rcon_id'];
else
    $rcon_id=false;

if(isset($_POST['player_name']) && $_POST['player_name']) $player_name= $_POST['player_name'];
if(isset($_POST['reason']) && $_POST['reason']) $reason= $_POST['reason'];

if($rcon_id!==false){
    rcon('Kick '.$rcon_id.' '.$reason);
    insert_player_log('KICK', $player_name, 'None', $reason); 
    echo 'done';
}
else
    echo "error rcon id is false!! = $rcon_id";
   



?>
