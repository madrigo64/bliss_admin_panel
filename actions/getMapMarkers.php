<?php

/*  author  skynetdev
 *  email   skynetdev3@gmail.com
 *  get Markers for Google Map API
 */
require_once '../config.php';
require_once 'functions.php';
require_once '../modules/rcon.php';
if(!issecurity(true,true,'map')) {echo "<h2>Access Denied!</h2>"; exit;} 

$map_perameters_array = getMapParameters(getMapName());              
$markers= "[";
$zindex = 0;

if(  (isset($_POST['type']) && $_POST['type'] =='vehicle') || (isset($_POST['vehicles']) && !empty($_POST['vehicles']) )) {
          
           $ivcid ='';
          if(isset($_POST['id']) && !empty($_POST['id']) ) $ivcid = 'AND ivc.id = '.$_POST['id']*1;

               $query = "SELECT 
                            *,
                            ivc.parts as ivcparts,
                            ivc.id as ivcid,
                            vc.class_name as class_name,
                            ivc.worldspace as ivcworldspace,
                            ivc.inventory as ivcinventory 
                        FROM 
                            instance_vehicle as ivc 
                        LEFT JOIN world_vehicle as wvc ON ivc.world_vehicle_id=wvc.id
                        LEFT JOIN  vehicle as vc ON vc.id=wvc.vehicle_id
                        LEFT JOIN  adm_objects as admo ON admo.class_name=vc.class_name
                        WHERE  ivc.instance_id=".INSTANCE."
                        $ivcid
                        ORDER BY ivcid";        
                $result = mysql_query($query) or die(mysql_error());
                $total_vehicles = mysql_num_rows($result);
                 
                        
                    
                while ($row=  mysql_fetch_assoc($result)) {
                    $x = 0;
                    $y = 0;
                    $Worldspace = str_replace("[", "", $row['ivcworldspace']);
                    $Worldspace = str_replace("]", "", $Worldspace);
                    $Worldspace = explode(",", $Worldspace);					
                    if(array_key_exists(1,$Worldspace)){$x = $Worldspace[1];}
                    if(array_key_exists(2,$Worldspace)){$y = $Worldspace[2];}
                    $is_destroyed = $row['damage']==1?'yes':'no';
                    
                   
                    $description = "<h2><a target=\"_blank\" href=\"?page=vehicle&id=".$row['ivcid']."\">".$row['class_name']."</a></h2><table ".($is_destroyed=='yes'?'bgcolor=red':'')."><tr><td><img style=\"max-width: 100px;\" src=\"images/vehicles/".$row['class_name'].".jpg\"></td><td>&nbsp;</td><td style=\"vertical-align:top; \"><h2>Position:</h2> horizontal:".world_x($x,getMapName())." vertical:".world_y($y,getMapName())."</br>Destroyed? $is_destroyed</td></tr></table>";
                    $markers .= "['".$row['class_name']."', '".$description."',".$x.", ".$y.", ".$zindex++.", 'images/icons/".(file_exists("../images/icons/".$row['icon_type'].".png")?$row['icon_type']:'unknow').".png'],";                  
                    
                    
                }  
                
               

               
      
}
      
      
      
      
if(isset($_POST['type']) && $_POST['type'] =='vehicle_spawns') {
    $wvid ='';
    if(isset($_POST['id']) && !empty($_POST['id'])) $wvid = 'AND wv.id = '.$_POST['id']*1;

          $query="SELECT 
              *,wv.id as wvid 
          FROM 
              world_vehicle as wv,
              instance as i,
              vehicle as v,
              adm_objects as ob
          WHERE  
              wv.world_id=i.world_id 
              AND i.id=".INSTANCE." 
              AND wv.vehicle_id=v.id
              AND v.class_name = ob.class_name
              $wvid
              ";    

          $result = mysql_query($query) or die(mysql_error());
          $total_vehicles = mysql_num_rows($result);



          while ($row =  mysql_fetch_assoc($result)) {
          $x = 0;
          $y = 0;
          $Worldspace = str_replace("[", "", $row['worldspace']);
          $Worldspace = str_replace("]", "", $Worldspace);
          $Worldspace = explode(",", $Worldspace);					
          if(array_key_exists(1,$Worldspace)){$x = $Worldspace[1];}
          if(array_key_exists(2,$Worldspace)){$y = $Worldspace[2];}




              $description = "<h2>".$row['class_name']."</h2><table ><tr><td><img style=\"max-width: 100px;\" src=\"images/vehicles/".$row['class_name'].".jpg\"></td><td>&nbsp;</td><td style=\"vertical-align:top; \"><h2>ID:".$row['wvid']."</h2><h2>Change:".($row['chance']*100)."%</h2><h2>Position:</h2>horizontal:".world_x($x,getMapName())." vertical:".world_y($y,getMapName())."</td></tr></table>";
              $markers .= "['".$row['class_name']."', '".$description."',".$x.", ".$y.", ".$zindex++.", 'images/icons/".(file_exists("../images/icons/".$row['icon_type'].".png")?$row['icon_type']:'unknow').".png'],";                  
          }  
}      
      
      
      
if(isset($_POST['type']) && $_POST['type'] == 'deployable' || isset($_POST['deployables']) && $_POST['deployables'] == 'deployable'  ){
    $insdepid ='';
    if(isset($_POST['id']) && !empty($_POST['id'])) $insdepid = 'AND instance_deployable.id = '.$_POST['id']*1;          

  $query = "SELECT
          instance_deployable.id as indepid,
          deployable.class_name,
          instance_deployable.*,
          admo.*
      FROM
          deployable,
          instance_deployable AS instance_deployable,
          adm_objects as admo
      WHERE
          instance_deployable.instance_id  = ".INSTANCE."
          AND deployable.id = instance_deployable.deployable_id
          AND admo.class_name = deployable.class_name
          $insdepid
    ";   

  $result = mysql_query($query) or die(mysql_error());


  while ($row=mysql_fetch_array($result)) {
          $Worldspace = str_replace("[", "", $row['worldspace']);
          $Worldspace = str_replace("]", "", $Worldspace);
          $Worldspace = str_replace("|", ",", $Worldspace);
          $Worldspace = explode(",", $Worldspace);
          $x = 0;
          $y = 0;
          if(array_key_exists(1,$Worldspace)){$x = $Worldspace[1];}
          if(array_key_exists(2,$Worldspace)){$y = $Worldspace[2];}

          $class_name = $row['class_name'];
          $type = $row['icon_type'];


          $description = "<h2><a href=\"?page=deployableitem&id=".$row['indepid']."\">".$class_name."</a></h2><table><tr><td><img style=\"max-width: 100px;\" src=\"images/vehicles/".$class_name.".jpg\"></td><td>&nbsp;</td><td style=\"vertical-align:top; \"><h2>Position:</h2>horizontal:".world_x($x,getMapName())." vertical:".world_y($y,getMapName())."</td></tr></table>";
          $markers .= "['".$class_name."', '".$description."',".$x.", ".$y.", ".$zindex++.", 'images/icons/".(file_exists("../images/icons/".$type.".png")?$type:'unknow').".png'],";                  
  };          


}
              
      
if(isset($_POST['players']) && $_POST['players']=='rcon_online') {  
$answers =  getRconPlayers();       
$players = $answers[0];

if (is_array($players)){
        $pnumber = count($players);
        $players_lobby_array = array();
        for ($i=0; $i<count($players); $i++){
                $in_lobby=false;
                $icon ='';
                if(!isset($players[$i][4])) continue;
                $player_name = $players[$i][4];        

                if(strrpos($player_name, " (Lobby)")) {
                    $in_lobby = true;
                    $player_name = str_replace(" (Lobby)", "", $player_name);

                  }
                else  $player_name = $player_name;                         
//TODO: display message red
                if($_SESSION['msg_red'] .= checkRconPlayerName($player_name, $players[$i][0])) continue;                                                 

                $query = "
                    SELECT *,sv.inventory as svinventory,sv.backpack as svbackpack,sv.id as svid FROM 
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
//                                echo "mysql =$query";
                $res = mysql_query($query);
                $num_row = mysql_num_rows($res);



                $row = mysql_fetch_assoc($res); 
                //echo "<pre>";
                //print_r($row);
                $dead = "";
                $ip = $players[$i][1];
                $ping = $players[$i][2];
                $name = $players[$i][4];
                if($row['is_dead']) $icon ='_dead';
                if($in_lobby) $icon = '_lobby';
                $x = 0;
                $y = 0;                                
                $Worldspace = str_replace("[", "", $row['worldspace']);
                $Worldspace = str_replace("]", "", $Worldspace);
                $Worldspace = explode(',', $Worldspace);					
                if(array_key_exists(1,$Worldspace)){$x = $Worldspace[1];}
                if(array_key_exists(2,$Worldspace)){$y = $Worldspace[2];}                                
                $uid = $row['svid'];


                $description = "<h2><a target=\"_blank\" href=\"?page=player&svid=".$row['svid']."\">".htmlspecialchars($name, ENT_QUOTES)." - ".$uid."</a></h2><table><tr><td><img style=\"max-width: 100px;\" src=\"images/models/".str_replace('"', '', $row['model']).".png\"></td><td>&nbsp;</td><td style=\"vertical-align:top; \"><h2>Position:</h2>horizontal:".world_x($x,getMapName())." vertical:".world_y($y,getMapName())."</td></tr></table>";
                $markers .= "['".$name."', '".$description."',".$x.", ".$y.", ".$zindex++.", 'images/icons/player".$icon.".png'],";

        }  //end count players
} // end if no answer
}      
      
  
      
if(isset($_POST['players']) && $_POST['players']=='alt_online'  && is_int(ALTERNATIVE_ONLINE_MINUTES)) {       

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

$markers .= getMapMakersPlayersBD($query,$map_perameters_array, $zindex);    


}
  
  
  
if(isset($_POST['type']) && $_POST['type']=='players_all') {       

  $body_id ='';  
  if(isset($_POST['id']) && !empty($_POST['id'])) $body_id ='AND sv.id='.$_POST['id']*1;  

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
         $body_id
     ORDER BY last_updated  DESC";

 $markers .= getMapMakersPlayersBD($query,$map_perameters_array, $zindex);     


}  
  
  
  
if(isset($_POST['type']) && $_POST['type']=='players_alive') {       



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

 $markers .= getMapMakersPlayersBD($query,$map_perameters_array, $zindex);     


}    
  
  
  
if(isset($_POST['type']) && $_POST['type']=='players_dead') {       



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

 $markers .= getMapMakersPlayersBD($query,$map_perameters_array, $zindex);     


}    
    
  
$markers .= "['Edge of map', 'Edge of Chernarus', 0.0, 0.0, 2, 'images/thumbs/null.png']];";
echo $markers;  //return markers for players
?>
