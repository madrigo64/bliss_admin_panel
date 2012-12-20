<?php

/*  author  skynetdev
 *  email   skynetdev3@gmail.com
 *  
 */
//for ajax!!
if(file_exists('../config.php'))    require_once '../config.php';
if(file_exists('functions.php'))    require_once 'functions.php';

//check security permissions
if(!issecurity(true, true, 'entities')) {
    echo "<h2>Access Denied!!</h2>";
    exit;
}


$palyers_table_rows='';
$full_invetory=false;
$title ="Survivors Online";  

if(isset($_REQUEST['show']) && $_REQUEST['show']=='online') {
$_SESSION['msg_red']='';
$_SESSION['msg_green']='';
      
$show_type = $_REQUEST['show'];          
$answers =  getRconPlayers();

$players = $answers[0];
$answer = $answers[1];


	if (is_array($players)){
            
                $players_lobby_array = array();
                $count_players= count($players); //will be used in tamplate
                $some_body_in_lobby=false;

 		for ($i=0; $i< $count_players; $i++){
                      
                        $in_lobby=false;

                     
                        if(!isset($players[$i][4])) continue;
                       
                        $player_name = $players[$i][4];
                        $player_rcon_id = $players[$i][0];
                        $ip_port_array = explode(':',$players[$i][1]);
                        $player_IP= $ip_port_array[0];
                        $temp_guid=trim(str_replace('(?)', '', $players[$i][3]));
                        $player_GUID = mb_strlen($temp_guid, 'ASCII') == 32?$temp_guid:'Guid <br> length ='.mb_strlen($temp_guid, 'ASCII').' tempguid='.$temp_guid;
                       // echo "id = $player_IP <br> guid =$player_GUID <pre>";


                                
                             
                                if(strrpos($player_name, " (Lobby)")) {
                                    $some_body_in_lobby = $in_lobby = true;
                                    $player_name = str_replace(" (Lobby)", "", $player_name);
                                   
                                  }
                                else  $player_name = $player_name; 

     
                 // if player name is bad will be kicked     
                          
                if( $_SESSION['msg_red'] .= checkRconPlayerName( $player_name, $player_rcon_id ))   continue;                          
           
                                if ($pos_deffect = strpos($player_name, '(?) ')){
                                    
                                    $player_name_with_defect = $player_name;
                                    $player_name = substr($player_name, $pos_deffect+4);
                                    $_SESSION['msg_green'] .= "<br><font color=green>Name $player_name_with_defect is fixed  now name is  $player_name</font>";  
                                    
                                }
                                
                                // remove  defect symbols from player name ord 0,1,2 
                                $player_name_length = mb_strlen($player_name, 'UTF-8');
                                $new_player_name ='';
                                for($j=0; $player_name_length > $j; $j++){
                                    
                                            if(ord($player_name[$j])== 0 OR ord($player_name[$j])== 1 OR ord($player_name[$j])==2 ){
                                               // $_SESSION['msg_green'] .= "<br> <font color=gree> Found defect symbol with ord =".ord($player_name[$j])." in playername $player_name cleared </font>";
                                                continue;
                                            }
                                            $new_player_name .= $player_name[$j];   
                                    
                                } 
                               // echo "<br> i=$i vot =$new_player_name";
                                
                                
                                  $player_name = mysql_real_escape_string($new_player_name);
                                
                                $query = "
                                    SELECT 
                                        *,
                                        sv.inventory as svinventory,
                                        sv.backpack as svbackpack,
                                        sv.id as svid
                                    FROM 
                                        profile as pf,
                                        survivor as sv,
                                        instance as inst
                                    WHERE
                                        pf.name LIKE '%$player_name%'
                                        AND pf.unique_id=sv.unique_id
                                        AND inst.id = ".INSTANCE."
                                        AND inst.world_id=sv.world_id
                                    ORDER BY last_updated DESC
                                    LIMIT 1";
                               // echo "mysql =$query <br>";
                                $result = mysql_query($query);
                                $num_row = mysql_num_rows($result);
                             
                                                               
                                
           
                                if($players[$i][0]!=11 && $players[$i][0]!=12 && !$num_row && $in_lobby == false) {
                                    $_SESSION['msg_red'] .= "<br>Can not find player in DB <font color=yellow>".$_SESSION['bdname']."</font> his name is <b>'<font color=yellow>$player_name</font>'</b> if this message appear to long  check him manual!<br> ";
                                    $show_answer=true;
                                    continue;
                                 }
                                 
                                if(!$num_row && $in_lobby == true) { $_SESSION['msg_green'] .= "<br>New player not in DataBase <font color=yellow>".$_SESSION['bdname']."</font> yet <b>'<font color=blue>$player_name</font>' in Lobby</b><br> ";  continue;}
                                $row = mysql_fetch_assoc($result); 
                                $dead = "";
                                $x = 0;
                                $y = 0;
                                
                                $InventoryPreview = "";
                                $BackpackPreview = "";
                                $ip = $players[$i][1];
                                $ping = $players[$i][2];
                                $name = $player_name;
                                $uid = "";
                                if(isset($_POST['invetory']) && $_POST['invetory'] == 'true') $full_invetory=true;

                                $palyers_table_rows .=row_player( $row, 'online',$full_invetory,$in_lobby, $player_rcon_id, $player_IP, $player_GUID);                                
                                

		} //end for($players)
                

	} //end if(!$answer)
        else { 
            
            if(getProcessStatus(SERVEREXE))
            $_SESSION['msg_red'] .= "Warning!!! Rcon Not responding or maybe to busy! <br>(Recomendation:check or change rcon password, password should start with number and end with number. Turn on battleye on server.<br>";
            else  
            $_SESSION['msg_red'] .= "Warning!!! Server not runing!<br>";
            
       
            
        }    
        
        

if(isAjax())
{
      if((isset($show_answer) && $show_answer) || (isset($_POST['answer']) && $_POST['answer']=='true'))
      echo "<pre> Rcon answer<br>".$answer."<br /></pre>";
    require_once '../templates/show_players_table.php';
}

else
    require_once 'templates/show_players.php';        

} //enf if online



//show players who updated in bd last 3 minutes
if(isset($_POST['show']) && $_POST['show']=='alt_online' && is_int(ALTERNATIVE_ONLINE_MINUTES)) {
    $show_type = $_POST['show'];

    $query = "
       SELECT 
           *,
           sv.inventory as svinventory,
           sv.backpack as svbackpack,
           sv.id as svid 
      FROM 
           profile as pf,
           survivor as sv,
           instance as inst
       WHERE
           pf.unique_id=sv.unique_id
           AND inst.id = ".INSTANCE."
           AND inst.world_id=sv.world_id
           AND last_updated BETWEEN NOW()- INTERVAL ".ALTERNATIVE_ONLINE_MINUTES." MINUTE AND NOW()
       ORDER BY last_updated  DESC";   

    $result = mysql_query($query);
    $num_row = mysql_num_rows($result);
    $count_players= $num_row; //will be used in tamplate
    if(isset($_POST['invetory']) && $_POST['invetory'] == 'true') $full_invetory=true;
    while($row = mysql_fetch_assoc($result)) {
        $palyers_table_rows .=row_player($row,'alt_online',$full_invetory);      
    }
    
    if(isAjax())
    {

        require_once '../templates/show_players_table.php';
    }

    else
        require_once 'templates/show_players.php';         
    
    
    
}



if(isset($_GET['show']) && $_GET['show']=='all') {

 
 require_once 'modules/pagination/Helper.php';
 require_once 'modules/pagination/Manager.php';
 $paginationManager = new Krugozor_Pagination_Manager(10, 5, $_GET);     
    
    
    $query = "
       SELECT 
           *,
           sv.inventory as svinventory,
           sv.backpack as svbackpack,
           sv.id as svid
      FROM 
           profile as pf,
           survivor as sv,
           instance as inst
       WHERE
           pf.unique_id=sv.unique_id
           AND inst.id = ".INSTANCE."
           AND inst.world_id=sv.world_id
       ORDER BY last_updated  DESC";
    
      $query_pagination = " LIMIT " .
           $paginationManager->getStartLimit() . "," .
           $paginationManager->getStopLimit();   

    $result = mysql_query($query.$query_pagination);
    $count_players = mysql_num_rows($result);

    
    $result_all_rows = mysql_query($query);
    $all_rows = mysql_num_rows($result_all_rows);
    $paginationManager->setCount($all_rows); 
    
    if(isset($_POST['invetory']) && $_POST['invetory'] == 'true') $full_invetory=true;
    $counter = $paginationManager->getAutoincrementNum();
    while($row = mysql_fetch_assoc($result)) {
        $counter++;
        $palyers_table_rows .=row_player($row,'all',$full_invetory);      
    }
  require_once 'templates/show_players_bd.php';

    
}



if(isset($_GET['show']) && $_GET['show']=='alive') {

 require_once 'modules/pagination/Helper.php';
 require_once 'modules/pagination/Manager.php';
 $paginationManager = new Krugozor_Pagination_Manager(10, 5, $_GET);       
    
    $query = "
       SELECT 
           *,
           sv.inventory as svinventory,
           sv.backpack as svbackpack,
           sv.id as svid
       FROM 
           profile as pf,
           survivor as sv,
           instance as inst
           
       WHERE
           pf.unique_id=sv.unique_id
           AND sv.is_dead=0
           AND inst.id = ".INSTANCE."
           AND inst.world_id=sv.world_id
       ORDER BY last_updated  DESC"; 
    
      $query_pagination = " LIMIT " .
            $paginationManager->getStartLimit() . "," .
            $paginationManager->getStopLimit();   

    $result = mysql_query($query.$query_pagination);
    $count_players = mysql_num_rows($result);

    
    $result_all_rows = mysql_query($query);
    $all_rows = mysql_num_rows($result_all_rows);
    $paginationManager->setCount($all_rows); 
    
    if(isset($_POST['invetory']) && $_POST['invetory'] == 'true') $full_invetory=true;
    $counter = $paginationManager->getAutoincrementNum();
    while($row = mysql_fetch_assoc($result)) {
        $counter++;
        $palyers_table_rows .=row_player($row,'all',$full_invetory);      
    }
  require_once 'templates/show_players_bd.php';
  
}


if(isset($_GET['show']) && $_GET['show']=='dead') {

 require_once 'modules/pagination/Helper.php';
 require_once 'modules/pagination/Manager.php';
 $paginationManager = new Krugozor_Pagination_Manager(10, 5, $_GET);       
    
    $query = "
       SELECT 
           *,
           sv.inventory as svinventory,
           sv.backpack as svbackpack,
           sv.id as svid
       FROM 
           profile as pf,
           survivor as sv,
           instance as inst
           
       WHERE
           pf.unique_id=sv.unique_id
           AND sv.is_dead=1
           AND inst.id = ".INSTANCE."
           AND inst.world_id=sv.world_id
       ORDER BY last_updated  DESC"; 
    
      $query_pagination = " LIMIT " .
            $paginationManager->getStartLimit() . "," .
            $paginationManager->getStopLimit();   

    $result = mysql_query($query.$query_pagination);
    $count_players = mysql_num_rows($result);

    
    $result_all_rows = mysql_query($query);
    $all_rows = mysql_num_rows($result_all_rows);
    $paginationManager->setCount($all_rows); 
    
    if(isset($_POST['invetory']) && $_POST['invetory'] == 'true') $full_invetory=true;
    $counter = $paginationManager->getAutoincrementNum();
    while($row = mysql_fetch_assoc($result)) {
        $counter++;
        $palyers_table_rows .=row_player($row,'all',$full_invetory);      
    }
  require_once 'templates/show_players_bd.php';
  
    
}





?>
