<?php
/*  author  skynetdev
 *  email   skynetdev3@gmail.com
 *  my youtube  channel only for russians https://www.youtube.com/user/bfpayer
 */

// WARNING FIRST YOU MUST IMPORT SQL FILE INTO Date Base MySQL From folder Installation


define('INSTANCE', 1);   //instance id
define('DESABLE_ZOOM_5_6', false);   // true/false Desable zoom 5 and 6 for map
define('GET_MAP_FOR_ZOOM_5AND6_LOCAL', true); // true/false if set true will download from internet on hard disk and display  pieces of map  for  zoom 5 and 6  if set to false will try to display map only local (from hard disk)
define('SITENAME', 'Admin Panel for Bliss Dayz server');  // not working now just ignore this row
define('SERVERIP', '127.0.0.1');//server ip
define('SERVERPORT', 2302);    //server port
define('USERNAME', 'dayz');   //mysql user
define('PASSWORD', 'dayz');  //mysql pass  
define('RCONPASSWORD', '3d057718a'); //rcon password  minimum 6 symbols and must start with digital and use more digitals than words (rcon working only with battleye on server)
define('DBNAME', 'dayz, dayz_backup, dayz_longhost');     // Mysql Data Base name. You can use several bdnames separated by comma
define('ALTERNATIVE_ONLINE_MINUTES', 3);  //show alternative online  players who stored in db last n minutes   by default n=3 (working only on players rcon online)
define('MAX_LENGTH_PLAYER_NAME', 20);   // kick if player name more than n symbols  by default n = 20, not perfect working, if set to 0 will desable this function (working only on players rcon online)
define('FORBIDDEN_SYMBOLS_FOR_PLAYER_NAME', "\"\\/'`:-*<>"); // kick player if forbidden symbols found in player name  (working only on players rcon online)
define('ASCII_SYMBOLS_ONLY_FOR_PLAYER_NAME', false);   //true/false Kick player if not ASCII symbols found in player name   true or false  false by default (working only on players rcon online)
define('UTF8_SYMBOLS_ONLY_FOR_PLAYER_NAME', true);    //true/false Kick player if not UTF-8 symbols found in player name   true or false   true by default (working only on players rcon online)
define('ACTION_FORBIDDEN_ITEMS', 3);   //Check player inventory and backpack for forbidden items  0 - disabled check, 1 - ban by Ip and GUI permanently, 2 - kick, 3 - only warning for admin . strong recomend to use this function with full inventory/backpack (working only on players rcon online)
define('VIP_PLAYERS', '');  //do not check forbidden items for VIP players  for example: define('VIP_PLAYERS','player_name1, player_name2');


// For start and stop  server from admin panel
define('GAME_PATH', 'D:'.DS.'Games'.DS.'ArmA2');  // game path of arma2
define('SERVEREXE', 'arma2oaserver.exe');  // exe file of server

define('SERVER_START_FILE', 'start_server.exe'); // this file needed for runing server from admin panel and will help when apache is running as service.
// admin panel should copy start_server.exe in first time when you will enter in Server Control ->Control (wich located in folder installation) 
//  in main folder of arma2 game. 
//   start_server.exe will search in register where isnstaled aram 2 OA automatic
//  File start_server.exe  will run file start_server.bat wich will be start server

define('SERVEREXE_PATH', GAME_PATH.DS."expansion".DS."beta".DS.SERVEREXE); 

// For start and stop  BEC from admin panel
//For download BEC go to  http://ibattle.org/
define('BEC_PATH', GAME_PATH.DS.'BEC');  //directory BEC  
define('BECEXE', 'BEC.exe');    //file BEC.exe  
define('BEC_STRING', ' -f config.cfg');



?>
