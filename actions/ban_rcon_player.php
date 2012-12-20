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
 
if($_POST['player_ip']) $player_ip= $_POST['player_ip'];
if(isset($_POST['rcon_id']) && $_POST['rcon_id']!='false' ) $rcon_id= $_POST['rcon_id'];
else
    $rcon_id=false;

if($_POST['ban_minutes']) $ban_minutes= $_POST['ban_minutes']; 
else $ban_minutes = '0';
if($_POST['reason']) $reason= $_POST['reason'];

$player_name = 'None';
if(isset($_POST['player_name']))  $player_name = $_POST['player_name'];



    
if($player_ip !=false)
    rcon('addban '.$player_ip.' '.$ban_minutes.' '.$reason );


if($rcon_id !==false)
 rcon('ban '.$rcon_id.' '.$ban_minutes.' '.$reason );    

insert_player_log('BAN', $player_name, $player_ip, 'minutes ='.$ban_minutes.' '.$reason);

rcon('loadBans' );    
echo "done";


?>
