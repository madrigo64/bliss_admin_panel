<?php

/*  author  skynetdev
 *  email   skynetdev3@gmail.com
 *  functions
 */


// get array of Pages for include
function getPagesName(){
    
    $actions_folder = 'actions/';
    $templates_folder= 'templates/';
    //array key is page name 
    $pages = array (
         'admins' =>            $actions_folder.'admins.php',
         'chat'   =>            $templates_folder.'show_chat.php',  
         'control' =>           $templates_folder.'show_server_control.php',
         'players' =>           $actions_folder.'players.php',
         'player' =>            $actions_folder.'player.php',
         'vehicles' =>          $actions_folder.'vehicles.php',
         'vehicle' =>           $actions_folder.'vehicle.php',
         'deployableitem' =>    $actions_folder.'deployableitem.php',
         'deployableitems' =>   $actions_folder.'deployableitems.php',        
         'map_online' =>        $templates_folder.'show_map_online.php',
         'map_static' =>        $templates_folder.'show_map_static.php',
         'logout' =>            $actions_folder.'logout.php'
        );
    
   return $pages; 
        
}





// get google map makers for players via bd
function getMapMakersPlayersBD($query,  $map_perameters_array, $zindex){
    
        $markers = '';
        $result = mysql_query($query);
        $num_row = mysql_num_rows($result);

        //if(isset($_GET['invetory']) && $_GET['invetory'] == 'true') $full_invetory=true;
       
        while($row = mysql_fetch_assoc($result)) {
                $icon ='';
                $dead = "";
                $name = $row['name'];
                if($row['is_dead']) $icon ='_dead';
                $x = 0;
                $y = 0;                                
                $Worldspace = str_replace("[", "", $row['worldspace']);
                $Worldspace = str_replace("]", "", $Worldspace);
                $Worldspace = explode(',', $Worldspace);					
                if(array_key_exists(2,$Worldspace)){$x = $Worldspace[2];}
                if(array_key_exists(1,$Worldspace)){$y = $Worldspace[1];}                                
                $uid = $row['svid'];
                $description = "<h2><a target=\"_blank\" href=\"?page=player&svid=".$row['svid']."\">".htmlspecialchars($name, ENT_QUOTES)." - ".$uid."</a></h2><table><tr><td><img style=\"max-width: 100px;\" src=\"images/models/".str_replace('"', '', $row['model']).".png\"></td><td>&nbsp;</td><td style=\"vertical-align:top; \"><h2>Position:</h2>horizontal:".world_x($x,getMapName())." vertical:".world_y($y,getMapName())."</td></tr></table>";
                $markers .= "['".htmlspecialchars($name, ENT_QUOTES)."', '".$description."',".$y.", ".$x.", ".$zindex++.", 'images/icons/player".$icon.".png'],";            

        }     
        
       return  $markers;
    
    
}




function insert_admin_log($action)
{
    if($_SESSION['login']) {
    $query = "INSERT INTO adm_admins_log(action, admin, created_at) VALUES ('$action','$_SESSION[login]',NOW())";
    mysql_query($query) or die(mysql_error());    
    }
    
}






// check rcon player name 
function checkRconPlayerName($player_name, $player_rcon_id){
    
    $message_red = '';

                                       
                                
                                $palyer_name_fixed = htmlspecialchars($player_name);
// start kick player if length is larger that set 
                                $str_length=mb_strlen($player_name, 'UTF-8');
                                 if( MAX_LENGTH_PLAYER_NAME !=0 &&  $str_length > MAX_LENGTH_PLAYER_NAME ) {
                                    $message_red.= "<br><font color=red>Name is to large max '".MAX_LENGTH_PLAYER_NAME."' symbols length= $str_length Player '$palyer_name_fixed'  kicked!!</font>";  
                                    rcon('Kick '.$player_rcon_id.' Name is to large max '.MAX_LENGTH_PLAYER_NAME.' symbols');
                                    insert_player_log('KICK', $palyer_name_fixed, 'None',  'Name is to large max '.MAX_LENGTH_PLAYER_NAME.' symbols');
                                    return $message_red;
                                 
                                 }
                                 
                                 if( strlen($player_name) <= 1 ) {
                                    $message_red.= "<br><font color=red>Name is to small min 2 symbols. Player $palyer_name_fixed  kicked!!</font>";  
                                    rcon('Kick '.$player_rcon_id.' Name is to small min 2 symbols.');
                                    insert_player_log('KICK', $palyer_name_fixed, 'None',  'Name is to small min 2 symbols');
                                     return $message_red;
                                                                  
                                 }                                 
                                
                                
				
 // start kick player with bad nicnanme for not ASCII symbol
                                if( ASCII_SYMBOLS_ONLY_FOR_PLAYER_NAME && mb_detect_encoding($player_name, 'ASCII', true) === false ) {
                                    $message_red.= "<br><font color=red>Not ASCII symbols in player name'{$palyer_name_fixed}' kicked!!</font>";
                                    rcon('Kick '.$player_rcon_id.' Bad nikcname not ASCII symbols!');
                                    insert_player_log('KICK', $palyer_name_fixed, 'None',  'Not ASCII symbols in player name!');
                                     return $message_red;
                                   
                                }
                                
 // start kick player with bad nicnanme for not UTF-8 symbol
                                if( UTF8_SYMBOLS_ONLY_FOR_PLAYER_NAME && mb_detect_encoding($player_name, 'UTF-8', true) === false ) {
                                    $message_red.= "<br>Not UTF-8 symbols in player name'{$palyer_name_fixed}' kicked!!";
                                    rcon('Kick '.$player_rcon_id.' Not UTF8 symbols in player name!');
                                    insert_player_log('KICK', $palyer_name_fixed, 'None', 'Not UTF8 symbols in player name!');
                                     return $message_red;
                                    
                                }                                
                                
                               if($forbidden_symbol=strpbrk($player_name,FORBIDDEN_SYMBOLS_FOR_PLAYER_NAME)) {
                                   $message_red .= "<br>Bad player name forbidden symbols _'{$palyer_name_fixed}' kicked!!";
                                   rcon('Kick '.$player_rcon_id.' Bad Player Name forbidden symbol '.substr($forbidden_symbol,0,1));
                                   insert_player_log('KICK', $palyer_name_fixed, 'None',  ' Bad Player Name forbidden symbol '.substr($forbidden_symbol,0,1)    );
                                     return $message_red;
                                    
                                }
                                
 // end kick player with bad nanme           

    
    
}



// get player array rcon online
function getRconPlayers(){
    
        if(isAjax())  {
            require_once '../modules/rcon.php';
        }
         else    require_once 'modules/rcon.php';
	$cmd = "Players";
	$answer = rcon($cmd);
       if($answer) {
		$k = strrpos($answer, "---");
		$l = strrpos($answer, "(");
		$out = substr($answer, $k+4, $l-$k-5);
//                echo "<pre> arary=";
//                print_r($out);
//                $end = strpos($out, '?)  ');
//                echo 
		$array = preg_split ('/$\R?^/m', $out);


		$players = array();
//		for ($j=0; $j<count($array); $j++){
//			$players[] = "";
//		}

               if($array[0]=='(0 players in total')  $array = array();

		for ($i=0; $i < count($array); $i++)
		{
                    $players[$i]=array_values(array_diff(explode(' ', $array[$i]), array(null)));
                }

                $answers[0]= $players;
                $answers[1]= $answer;
         return $answers;
       }
       
}







//get db names from config.php
function getDBNameOptions(){
    $dbName_array = explode(',', DBNAME);
    foreach($dbName_array as $name){
        $name = trim($name);
        echo "<option ".(isset($_SESSION['bdname']) && $_SESSION['bdname'] == $name?'selected':'')." value='$name'>$name</option>";
    }
    
    
}


//get process status if online or offline
function getProcessStatus($process_name){
    
    $status = exec('tasklist /FI "IMAGENAME eq '.$process_name.'" /FO CSV');
    $status = explode(",", strtolower($status));
    $status = $status[0];
    $status = str_replace('"', "", $status);

    if ($status == strtolower($process_name)) return true;
    
    return false;
}




// get php path by action
function  getPageByName($name){

    $pages= getPagesName();
    if(array_key_exists($name,$pages) )  return $pages[$name];
    
    return false;

}


function isAjax (){
    if (
    isset($_SERVER['HTTP_X_REQUESTED_WITH'])
    && $_SERVER['HTTP_X_REQUESTED_WITH'] == "XMLHttpRequest")
    return true;
    return false;
}



//check page if is secured
function issecurity($check_login=true, $check_permission=true, $perm=false)
{

  
   if($check_login)  {
       if(!isset($_SESSION['user_id'])) return false;
   }
       
   if($check_permission && $perm!=false) {
      $permissions=  explode(',', $_SESSION['user_permissions']);
      if(!in_array($perm, $permissions)) return false;
       
   }
    
    return true;
    
    
}





// generate table rows for players
function row_player( $row, $show_type,  $full_invetory=false, $in_lobby=false, $player_rcon_id=false, $player_IP=false, $player_GUID=false){
	$Worldspace = str_replace("[", "", $row['worldspace']);
	$Worldspace = str_replace("]", "", $Worldspace);
	$Worldspace = explode(",", $Worldspace);

        
        $player_name = $row['name'];
	$x = 0;
	$y = 0;
        
        // for inventory and backpack
        if($full_invetory)  $limit=100;  
        else
	$limit = 6;
        
        
	if(array_key_exists(1,$Worldspace)){$x = $Worldspace[1];}
	if(array_key_exists(2,$Worldspace)){$y = $Worldspace[2];}
//        Echo "<pre>";
//	print_r($row);
//        exit;
	$Inventory = $row['svinventory'];
	$Inventory = str_replace("|", ",", $Inventory);
	$Inventory  = json_decode($Inventory);
	if(is_array($Inventory) && array_key_exists(0,$Inventory)){
		if(is_array($Inventory) && array_key_exists(1,$Inventory)){
			$Inventory = (array_merge($Inventory[0], $Inventory[1]));
		} else {
			$Inventory = $Inventory[0];
		}
	} else {
		if(is_array($Inventory) && array_key_exists(1,$Inventory)){
			$Inventory = $Inventory[1];
		}
	}
	$InventoryPreview = "";
        
        
        

        
        
	for ($i=0; $i< $limit; $i++){
               
		if(is_array($Inventory) && array_key_exists($i,$Inventory)){
			//$InventoryPreview .= $Inventory[$i];
			$curitem = $Inventory[$i];
                        // I noticed in inventory sometimes apear item Hatchet_Swing when in DB is not exist
                        if($curitem == 'Hatchet_Swing' || $curitem == '') continue; 
			$icount = "";
			if (is_array($curitem)){
                            $curitem = $Inventory[$i][0]; $icount = ' - '.$Inventory[$i][1].' rounds';
                        }
                        
                        //check if item is in table adm_objects
                        $unknow_item = false;
                        if(!is_array(getObjectByClassName($curitem))){
                           
                            $unknow_item = true;
                            $_SESSION['unknow_item'][$player_name][] = $curitem;
                            insert_player_log('Unknow Item!!!', $player_name, $player_IP, 'Forbidden item '.$curitem);
                           
                        }
                        
                        
                        // check item if FORBIDDEN
                        $is_forbidden_item ='';
                        if($unknow_item == false && ACTION_FORBIDDEN_ITEMS != 0  && $curitem!='') {
                                if($is_forbidden_item = is_forbidden_item($curitem, $player_name, $show_type) == true){ 
                                  if($show_type == 'online') //kick or ban only when user is online
                                     if(forbidden_item($curitem, $player_name, $player_rcon_id, $player_IP, $player_GUID) == false) continue;
                                }    
                        }
                        $vss='';
                        if($is_forbidden_item) $vss = 'vss=forbidden';
                        if($unknow_item) $vss = 'vss=forbidden_disabled';   
                        
                        
			$InventoryPreview .= $curitem?'
                            <div class="preview_gear_slot'.($is_forbidden_item?' forbidden':'').($unknow_item?' forbidden unknow':'').'"     '.($full_invetory?"style='display:inline-block'":"style='display:table-cell'").' > 
                            <img '.$vss.'  onclick="item_preview(this)"  src="images/thumbs/'.$curitem.'.png" title="'.$curitem.$icount.'" alt="'.$curitem.$icount.'"/>
                            </div>':'';
		} 	
	}
	
	$Backpack  = $row['svbackpack'];
	$Backpack = str_replace("|", ",", $Backpack);
	$Backpack  = json_decode($Backpack);
	if(is_array($Backpack) && array_key_exists(0,$Backpack)){ 
		$bpweapons = array();
		$bpweapons[] = $Backpack[0];
		if(array_key_exists(1,$Backpack)){
                            if(isset($Backpack[1][0])){
                                $bpweaponscount = count($Backpack[1][0]);				
                                for ($m=0; $m<$bpweaponscount; $m++){
                                                for ($mi=0; $mi<$Backpack[1][1][$m]; $mi++){
                                                        $bpweapons[] = $Backpack[1][0][$m];
                                                }
                                }
                            }
                        
		}
		$bpitems = array();
		if(array_key_exists(1,$Backpack)){
                      if(isset($Backpack[2][0])){
                            $bpitemscount = count($Backpack[2][0]);
                            for ($m=0; $m<$bpitemscount; $m++){
                                    for ($mi=0; $mi<$Backpack[2][1][$m]; $mi++){
                                            $bpitems[] = $Backpack[2][0][$m];
                                    }
                            }
                      }  
		}
		$Backpack = (array_merge($bpweapons, $bpitems));
	}
	$BackpackPreview = "";
        if(is_array($Backpack))
	for ($i=0; $i< $limit; $i++){
		if(is_array($Backpack) && array_key_exists($i,$Backpack)){
			$curitem = $Backpack[$i];
                        $icount='';
			if (is_array($curitem)){
				if ($i != 0){
					$curitem = $Backpack[$i][0];
                                        $icount = ' - '.$Backpack[$i][1].' rounds';
				}
			}
                        
                        if($curitem == 'Hatchet_Swing' || $curitem =='') continue;
                        //check if item is in table adm_objects
                        $unknow_item = false;
                        if(!is_array(getObjectByClassName($curitem))){
                            
                            $unknow_item = true;
                            $_SESSION['unknow_item'][$player_name][] = $curitem;
                            insert_player_log('Unknow Item!!!', $player_name, $player_IP, 'Forbidden item '.$curitem);
                        }
                        
                        
                        // check item if FORBIDDEN
                        $is_forbidden_item ='';
                        if($unknow_item == false && ACTION_FORBIDDEN_ITEMS != 0  && $curitem!='') {
                                if($is_forbidden_item = is_forbidden_item($curitem, $player_name, $show_type) == true){ 
                                   if($show_type == 'online') //kick or ban only when user is online 
                                        if(forbidden_item($curitem, $player_name, $player_rcon_id, $player_IP, $player_GUID) == false) continue;
                                  
                                }    
                        }
                        $vss='';
                        if($is_forbidden_item) $vss = 'vss=forbidden';
                        if($unknow_item) $vss = 'vss=forbidden_disabled';                         
                        
                        
			$BackpackPreview .= $curitem?'<div class="preview_gear_slot'.($is_forbidden_item?' forbidden':'').'" '.($full_invetory?"style='display:inline-block'":"style='display:table-cell'").'>
                            <img '.$vss.' onclick="item_preview(this)" src="images/thumbs/'.$curitem.'.png" title="'.$curitem.$icount.'" alt="'.$curitem.$icount.'"/>
                            </div>':'';
   
		}		
	}
	
        //get health
	$Medical = str_replace("[", "", $row['medical']);
	$Medical = str_replace("]", "", $Medical);
	$Medical = explode(",", $Medical);
	$health = 0;
	if(array_key_exists(2,$Worldspace))  $health = number_format ($Medical[7],0);
	


	$icon = '<img src="images/icons/'.($row['is_dead'] ? 'dead' : 'live').'.png" title="player '.$player_name.' is '.($row['is_dead'] ? 'dead' : 'live').'" alt=""/>';

        $survival_time =$row['survival_time'];
        $created = $row['start_time'];
        $last_updated  = $row['last_updated'];
        
	$tablerow = "<tr id='rcon_id_".$player_rcon_id."'>";
           if($show_type == 'online') {

                                $tablerow .="<td align=\"center\" class=\"table-players-td action\"> <img showtime=$show_type onclick=\"ban_player_form('$player_rcon_id','$row[name]','$player_IP','$player_GUID')\" width=50 title=\"ban player ".$player_name." \" src=\"images/icons/ban.png\"></td>
                                 <td align=\"center\" class=\"table-players-td action\"> <img onclick=\"kick_player_form('$player_rcon_id','$player_name')\" title=\"kick player ".$player_name."\" src=\"images/icons/kick.png\"></td>";

           }
                        
               
           
          $tablerow .= "<td align=\"center\" class=\"table-players-td\">".$icon."</td>
                           
			
			<td align=\"center\" class=\"table-players-td\">".$row['unique_id']."</td>
			<td align=\"center\" class=\"table-players-td\"><a target='_blank' href=\"?page=map_static&type=players_all&id=".$row['svid']."\">".world_x($x, getMapName()).world_y($y, getMapName())."</a></td>
                        <td  align=\"center\" class=\"table-players-td\">".($survival_time)." min/$created</td>    
			<td align=\"center\" class=\"table-players-td\">$last_updated</td>
                        <td align=\"center\" class=\"table-players-td\"><a target=\"_blank\" href=\"?page=player&svid=".$row['svid']."\"><b>".htmlspecialchars($player_name).($in_lobby?' <font color=yellow>(Lobby)</font>':'')."</b></a></td>
			<td align=\"left\" valign=\"top\" class=\"table-players-td inventory\">".$InventoryPreview."</td>
			<td align=\"left\" valign=\"top\" class=\"table-players-td\">".$BackpackPreview. "</td>
		</tr>";
	
	return $tablerow;	
}




///////////////////////////////
// show vehicles 
/////////////////////////////


function row_vehicle($row) {
	$x = 0;
	$y = 0;
	$Worldspace = str_replace("[", "", $row['worldspace']);
	$Worldspace = str_replace("]", "", $Worldspace);
	$Worldspace = explode(",", $Worldspace);					
	if(array_key_exists(1,$Worldspace)){$x = $Worldspace[1];}
	if(array_key_exists(2,$Worldspace)){$y = $Worldspace[2];}
	$Inventory  = $row['ivcinventory'];
	$Inventory = str_replace("|", ",", $Inventory);
	$Inventory  = json_decode($Inventory);
	$limit = 6;
	if(count($Inventory) >0){ 
		$bpweapons = array();
		if(array_key_exists(0,$Inventory)){ 
			$bpweaponscount = count($Inventory[0][0]);				
			for ($m=0; $m<$bpweaponscount; $m++){
                                        if(isset($Inventory[0][1][$m]))
					for ($mi=0; $mi<$Inventory[0][1][$m]; $mi++){
						$bpweapons[] = $Inventory[0][0][$m];
					}
			}							
		}

						
		$bpitems = array();
		if(array_key_exists(1,$Inventory)){ 
			$bpitemscount = count($Inventory[1][0]);
			for ($m=0; $m<$bpitemscount; $m++){
                                if(isset($Inventory[1][1][$m]))
				for ($mi=0; $mi<$Inventory[1][1][$m]; $mi++){
					$bpitems[] = $Inventory[1][0][$m];
				}
			}
		}
		$bpacks = array();
		if(array_key_exists(2,$Inventory)){ 
			$bpackscount = count($Inventory[2][0]);
			for ($m=0; $m<$bpackscount; $m++){
                                if(isset($Inventory[2][1][$m]))
				for ($mi=0; $mi<$Inventory[2][1][$m]; $mi++){
					$bpacks[] = $Inventory[2][0][$m];
				}
			}
		}
		$Inventory = (array_merge($bpweapons, $bpacks, $bpitems));
	}
	$InventoryPreview = "";
	for ($i=0; $i< $limit; $i++){
		if(isset($Inventory) && array_key_exists($i,$Inventory)){
			$curitem = $Inventory[$i];
                        $icount='';
			if (is_array($curitem)){
				if ($i != 0){
					$curitem = $Inventory[$i][0]; $icount = ' - '.$Inventory[$i][1].' rounds';
				}
			}
			$InventoryPreview .= $curitem?'<div class="preview_gear_slot_vehicle"><img src="images/thumbs/'.$curitem.'.png" title="'.$curitem.$icount.'" alt="'.$curitem.$icount.'"/></div>':'';
		} else {
			$InventoryPreview .= '<div class="preview_gear_slot_vehicle"></div>';
		}			
	}
	
	$Hitpoints  = $row['ivcparts'];
	$Hitpoints = str_replace("|", ",", $Hitpoints);
	$Hitpoints  = json_decode($Hitpoints);
	$HitpointsPreview = "";
	for ($i=0; $i< $limit; $i++){
		if(array_key_exists($i,$Hitpoints)){
			$curitem = $Hitpoints[$i];
			$HitpointsPreview .= '<div class="preview_gear_slot_vehicle" style="background-color: rgba(100,'.round((255/100)*(100 - ($curitem[1]*100))).',0,0.8);"><img  src="images/hits/'.$curitem[0].'.png" title="'.$curitem[0].' - '.round(100 - ($curitem[1]*100)).'%" alt="'.$curitem[0].' - '.round(100 - ($curitem[1]*100)).'%"/></div>';
		}			
	}	
	

		//$tablerow = "<tr>".$chbox."
                $tablerow = "<tr>    
			<td align=\"center\" class=\"gear_preview\" >".$row['ivcid']."</td>
			<td align=\"center\" class=\"gear_preview\" ><a target='_blank' href=\"index.php?page=map_static&type=vehicle&id=".$row['ivcid']."\">".world_x($x, getMapName()).world_y($y, getMapName())."</a></td>
                        <td align=\"center\" class=\"gear_preview\" style=\"background-color: rgba(100,".round((255/100)*(100 - ($row['damage']*100))).",0,0.8);\">".  number_format($row['damage'],2)."</td>                            
			<td align=\"center\" class=\"gear_preview\" ><a target=\"_blank\" href=\"?page=vehicle&id=".$row['ivcid']."\">".$row['class_name']."
                             <br>             
                             <img width=90 src='images/vehicles/".$row['class_name'].".jpg'/> 
                             </a>
                        </td>
			<td align=\"center\" class=\"gear_preview_vehicle\">".$InventoryPreview."</td>
			<td align=\"left\" class=\"gear_preview_vehicle\">".$HitpointsPreview. "</td>
		</tr>";

	return $tablerow;
}



///////////////////////////////
// show spawns locations for vehicles 
/////////////////////////////


function row_vehicle_spawns($row) {
	$x = 0;
	$y = 0;
	$Worldspace = str_replace("[", "", $row['worldspace']);
	$Worldspace = str_replace("]", "", $Worldspace);
	$Worldspace = explode(",", $Worldspace);					
	if(array_key_exists(1,$Worldspace)){$x = $Worldspace[1];}
	if(array_key_exists(2,$Worldspace)){$y = $Worldspace[2];}
        

        $Inventory  = $row['vinventory'];
	$Inventory = str_replace("|", ",", $Inventory);
	$Inventory  = json_decode($Inventory);
	$limit = 10;
	if(count($Inventory) >0){ 
		$bpweapons = array();
		if(array_key_exists(0,$Inventory)){ 
			$bpweaponscount = count($Inventory[0][0]);				
			for ($m=0; $m<$bpweaponscount; $m++){
                                        if(isset($Inventory[0][1][$m]))
					for ($mi=0; $mi<$Inventory[0][1][$m]; $mi++){
						$bpweapons[] = $Inventory[0][0][$m];
					}
			}							
		}

						
		$bpitems = array();
		if(array_key_exists(1,$Inventory)){ 
			$bpitemscount = count($Inventory[1][0]);
			for ($m=0; $m<$bpitemscount; $m++){
                                if(isset($Inventory[1][1][$m]))
				for ($mi=0; $mi<$Inventory[1][1][$m]; $mi++){
					$bpitems[] = $Inventory[1][0][$m];
				}
			}
		}
		$bpacks = array();
		if(array_key_exists(2,$Inventory)){ 
			$bpackscount = count($Inventory[2][0]);
			for ($m=0; $m<$bpackscount; $m++){
                                 if(isset($Inventory[2][1][$m]))
				for ($mi=0; $mi<$Inventory[2][1][$m]; $mi++){
					$bpacks[] = $Inventory[2][0][$m];
				}
			}
		}
		$Inventory = (array_merge($bpweapons, $bpacks, $bpitems));
	}
	$InventoryPreview = "";
	for ($i=0; $i< $limit; $i++){
		if(isset($Inventory) && array_key_exists($i,$Inventory)){
			$curitem = $Inventory[$i];
                        $icount='';
			if (is_array($curitem)){
				if ($i != 0){
					$curitem = $Inventory[$i][0]; $icount = ' - '.$Inventory[$i][1].' rounds';
				}
			}
			$InventoryPreview .= $curitem?'<div class="preview_gear_slot_vehicle"><img src="images/thumbs/'.$curitem.'.png" title="'.$curitem.$icount.'" alt="'.$curitem.$icount.'"/></div>':'';
		} 		
	}
	
	$Hitpoints  = $row['parts'];
	$Hitpoints  = explode(',',$Hitpoints);
	$HitpointsPreview = "";
	for ($i=0; $i< $limit; $i++){
		if(array_key_exists($i,$Hitpoints)){
			if($curitem = $Hitpoints[$i])
			$HitpointsPreview .= '<div class="preview_gear_slot_vehicle" ><img  src="images/hits/'.$curitem.'.png" title="'.$curitem.'" alt="'.$curitem.'"/></div>';
		}			
	}	        
        


                $tablerow = "<tr>    
			<td align=\"center\" class=\"gear_preview\" >".$row['wvid']."</td>
                        <td align=\"center\" class=\"gear_preview\" ><a target='_blank' href=\"index.php?page=map_static&type=vehicle_spawns&id=".$row['wvid']."\">".world_x($x, getMapName()).world_y($y, getMapName())."</a></td>
                        <td align=\"center\" class=\"gear_preview\" >".$row['class_name']."</td>
                        <td align=\"center\" class=\"gear_preview\" style=\"color:#000; background-color: rgba(100,".round((255/100)*( ($row['chance']*100))).",0,0.8);\" ><img width=130 src='images/vehicles/".$row['class_name'].".jpg'/></td>
                        <td align=\"center\" class=\"gear_preview\" style=\"color:#000; background-color: rgba(100,".round((255/100)*( ($row['chance']*100))).",0,0.8);\">".substr($row['chance'], 2, 2)."%</td>                            
                        <td align=\"center\" class=\"gear_preview_vehicle\" align='left'>".$InventoryPreview."</td>
			<td align=\"left\" class=\"gear_preview_vehicle\" align='left'>".$HitpointsPreview."</td>
                       
		</tr>";

	return $tablerow;
}




///////////////////////////////
//  row deployable items
/////////////////////////////


function row_deployable_items($row, $chbox) {
    $x = 0;
	$y = 0;   
	$Worldspace = str_replace("[", "", $row['worldspace']);
	$Worldspace = str_replace("]", "", $Worldspace);
	$Worldspace = explode(",", $Worldspace);
	if(array_key_exists(1,$Worldspace)){$x = $Worldspace[1];}	
	if(array_key_exists(2,$Worldspace)){$y = $Worldspace[2];}	
     
	$Inventory  = $row['inventory'];
	$Inventory = str_replace("|", ",", $Inventory);
	$Inventory  = json_decode($Inventory);
	$limit = 6;
        $icount ='';
	
	if(count($Inventory) >0){ 
		$bpweapons = array();
		if(array_key_exists(0,$Inventory)){ 
			$bpweaponscount = count($Inventory[0][0]);				
			for ($m=0; $m<$bpweaponscount; $m++){for ($mi=0; $mi<$Inventory[0][1][$m]; $mi++){$bpweapons[] = $Inventory[0][0][$m];}}							
		}
		
		$bpitems = array();
		if(array_key_exists(1,$Inventory)){ 
			$bpitemscount = count($Inventory[1][0]);
			for ($m=0; $m<$bpitemscount; $m++){for ($mi=0; $mi<$Inventory[1][1][$m]; $mi++){$bpitems[] = $Inventory[1][0][$m];}}
		}
		
		$bpacks = array();
		if(array_key_exists(2,$Inventory)){ 
			$bpackscount = count($Inventory[2][0]);
			for ($m=0; $m<$bpackscount; $m++){for ($mi=0; $mi<$Inventory[2][1][$m]; $mi++){$bpacks[] = $Inventory[2][0][$m];}}
		}
		
		$Inventory = (array_merge($bpweapons, $bpacks, $bpitems));
	}
	
	$InventoryPreview = "";
	
	for ($i=0; $i< $limit; $i++){
		if(array_key_exists($i,$Inventory)){
			$curitem = $Inventory[$i];
			if (is_array($curitem)){
				if ($i != 0){$curitem = $Inventory[$i][0]; $icount = ' - '.$Inventory[$i][1].' rounds';}
			}
			$InventoryPreview .= '<div class="preview_gear_slot" style="display: table-cell;margin-top:0px;width:47px;height:47px;"><img style="max-width:43px;max-height:43px;" src="images/thumbs/'.$curitem.'.png" title="'.$curitem.$icount.'" alt="'.$curitem.$icount.'"/></div>';
		} else {
			$InventoryPreview .= '';
		}			
	}


	$tablerow = "<tr>".$chbox."
		<td align=\"center\" class=\"gear_preview\" >".$row['id']."</td>
		<td align=\"center\" class=\"gear_preview\" ><a target=\"_blank\" href=\"?page=player&svid=".$row['svid']."\"> ".$row['name']."</a></td>
                <td align=\"center\" class=\"gear_preview\" ><a target='_blank' href=\"index.php?page=map_static&type=deployable&id=".$row['id']."\">".world_x($x, getMapName()).world_y($y, getMapName())."</a></td>                    
		<td align=\"center\" class=\"gear_preview\" ><a target=\"_blank\" href=\"?page=deployableitem&id=".$row['id']."\">".$row['class_name']."<br> <img width=100 src=\"images/vehicles/".$row['class_name'].".jpg\"> </a></td>			
		<td align=\"left\" class=\"gear_preview\">".$InventoryPreview."</td>
		</tr>";

	return $tablerow;
}



// get map name from instance table

function getMapNameForGoogleMap(){

    $query = "
        SELECT world.name
        FROM instance, world as world
        WHERE instance.id = ".INSTANCE."
          AND instance.world_id = world.id
        LIMIT 1
    ";
    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);
    if ($row['name'] == 'panthera2')  $row['name'] = 'panthera';
    if ($row['name'] == 'lingor')     $row['name'] = 'lingor2';
    if ($row['name'] == 'tavi')       $row['name'] = 'taviana';
    return $row['name'];
    
    
}



function getMapName(){

    $query = "
        SELECT world.name
        FROM instance, world as world
        WHERE instance.id = ".INSTANCE."
          AND instance.world_id = world.id
        LIMIT 1
    ";
    $result = mysql_query($query);
    $row = mysql_fetch_assoc($result);
    return $row['name'];

}


function getMapParameters($map_name)
{
    
    $map_array = array();
        switch ($map_name){
            case "chernarus":
           	$map_array['pixelOrigin_'] = '117.9, 96.4';
		$map_array['pixelsPerLonDegree_'] = '227 / 360';
		$map_array['pixelsPerLonRadian_'] = '227 / (2 * Math.PI)';
                $map_array['supported'] = true;                
                break;
            case "lingor":
           	$map_array['pixelOrigin_'] = '92.2, 67.9';
		$map_array['pixelsPerLonDegree_'] = '184.4 / 360';
		$map_array['pixelsPerLonRadian_'] = '184.4 / (2 * Math.PI)';
                $map_array['supported'] = true;
                break;
            case "panthera2":
           	$map_array['pixelOrigin_'] = '117.2, 42.9';
		$map_array['pixelsPerLonDegree_'] = '234.4 / 360';
		$map_array['pixelsPerLonRadian_'] = '234.4 / (2 * Math.PI)';
                $map_array['supported'] = true;
                break;
            case "takistan":
           	$map_array['pixelOrigin_'] = '114.7, 79.6';
		$map_array['pixelsPerLonDegree_'] = '260 / 360';
		$map_array['pixelsPerLonRadian_'] = '260 / (2 * Math.PI)';
                $map_array['download_map_name'] = 'takistan';
                $map_array['supported'] = true;
                break;
            case "fallujah":
           	$map_array['pixelOrigin_'] = '103.9, 56.1';
		$map_array['pixelsPerLonDegree_'] = '207.8 / 360';
		$map_array['pixelsPerLonRadian_'] = '207.8 / (2 * Math.PI)';
                $map_array['supported'] = true;
                break;
            case "namalsk":
           	$map_array['pixelOrigin_'] = '129.9, 70.1';
		$map_array['pixelsPerLonDegree_'] = '260 / 360';
		$map_array['pixelsPerLonRadian_'] = '260 / (2 * Math.PI)';
                $map_array['supported'] = true;
                break;
            case "tavi":
           	$map_array['pixelOrigin_'] = '313.6, 86.5';
		$map_array['pixelsPerLonDegree_'] = '627 / 360';
		$map_array['pixelsPerLonRadian_'] = '627 / (2 * Math.PI)';
                $map_array['supported'] = true;
                break;            
            default: 
           	$map_array['pixelOrigin_'] = '117.1, 96.5';
		$map_array['pixelsPerLonDegree_'] = '227.5 / 360';
		$map_array['pixelsPerLonRadian_'] = '227.5 / (2 * Math.PI)';
                $map_array['supported'] = false;
        }

    return $map_array;
    
    
}



function world_x($x, $world){
	if($world=="chernarus") {
		$result = ($x/100);
	} else if($world=="lingor") {
		$result = ($x/100);
	} else if($world=="utes") {
		$result = ($x/100);
	} else if($world=="panthera") {
		$result = ($x/100);
	} else if($world=="takistan") {
		$result = ($x/100);
	} else if($world=="fallujah") {  
		$result = ($x/100); //fucking coordinats on this map!!
	} else if($world=="namalsk") {
		$result = ($x/100);
	} else if($world=="mbg_celle2") {
		$result = ($x/100);
	} else if($world=="zargabad") {
		$result = ($x/100);
	} else if($world=="tavi") {
		$result = ($x/100);
	}  
       return sprintf("%03d",$result);
}

function world_y($y, $world){
	if($world=="chernarus") {
		$result = (154-($y/100));
	} else if($world=="lingor") {
		$result = ($y/100);
	} else if($world=="utes") {
		$result = ($y/100);
	} else if($world=="panthera") {
		$result = (103-$y/100);
	} else if($world=="takistan") {
		$result = ($y/100);
	} else if($world=="fallujah") {
		$result = ($y/100); //fucking coordinats on this map!!
	} else if($world=="namalsk") {
		$result = (128-$y/100);
	} else if($world=="mbg_celle2") {
		$result = ($y/100);
	} else if($world=="zargabad") {
		$result = ($y/100);
	} else if($world=="tavi") {
		$result = (256-$y/100);
	}        
        
        return sprintf("%03d",$result);
        
}

// return row as array
function getObjectByClassName($class_name){
    
    
        $query = "SELECT * FROM adm_objects WHERE class_name = '$class_name'";
        $result = mysql_query($query) or die(mysql_error());
	return mysql_fetch_assoc($result);

}



// get Languages
function getLanguages($languages){
      
    foreach($languages as $key => $lang){
        $lang = trim($lang);
        echo "<option ".(isset($_SESSION['language']) && $_SESSION['language'] == $key?'selected':'')." value='$key'>$lang</option>";
    }  
    
}

// check player for forbidden item  return true if item forbidden
function is_forbidden_item($curitem, $player_name, $show_type) {
   if(strpos(VIP_PLAYERS, $player_name) === false ) {
        $adm_object = getObjectByClassName($curitem);
        if($adm_object['allowed'] == 0){
            if($show_type == 'online') // set warning message only for online
                $_SESSION['forbidden_item'][$player_name][] = $curitem;   
            
            return true;
        }
        if($adm_object['allowed'] == 1) return false;
        return true;
   }else return false; 
       
   
   return true;
   
   
    
}

// kick or ban player with forbidden item
function  forbidden_item($curitem, $player_name, $player_rcon_id, $player_IP, $player_GUID){
    
      if(ACTION_FORBIDDEN_ITEMS == 1)  {
        $_SESSION['msg_red'] .= "<br><font color=red>forbidden item $curitem Player $player_name  Banned!!</font>";    
        rcon('addban '.$player_IP.' 0 Cheating/Hacking' );
        rcon('ban '.$player_rcon_id.' 0 Cheating/Hacking');
        insert_player_log('BAN', $player_name, $player_IP, 'Forbidden item '.$curitem);
        return false;

       }
     

    if(ACTION_FORBIDDEN_ITEMS == 2)  {
        $_SESSION['msg_red'] .= "<br><font color=red>forbidden item $curitem Player $player_name  kicked!!</font>";  
        rcon('Kick '.$player_rcon_id.' Forbidden item '.$curitem);
        insert_player_log('KICK', $player_name, $player_IP, 'Forbidden item '.$curitem);
        return false;
    }   
    
    return true;
        
}


function insert_player_log($action, $player_name, $player_ip,  $reason){
    if($player_name) {
        $query = "INSERT INTO adm_players_log(action, player_name, player_ip, admin_name_session, reason, created_at) VALUES ('$action','$player_name','$player_ip','$_SESSION[login]','$reason',NOW())";
        mysql_query($query) or die(mysql_error());    
    }  
    
}



?>
