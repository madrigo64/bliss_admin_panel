<?php

/*  author  skynetdev
 *  email   skynetdev3@gmail.com
 *  
 */

//$query = "SELECT * FROM  instance_vehicle WHERE id = ".$_GET["id"]." LIMIT 1"; 
$vc_id = $_GET['id'];
$query = "
    SELECT   *,
            ivc.parts as ivcparts,
            ivc.inventory as ivcinventory,
            ivc.worldspace as ivcworldspace
            FROM instance_vehicle as ivc
            LEFT JOIN world_vehicle as wvc ON ivc.world_vehicle_id=wvc.id
            LEFT JOIN  vehicle as vc ON vc.id=wvc.vehicle_id
            LEFT JOIN adm_objects as adm_objects ON adm_objects.class_name = vc.class_name
    WHERE 
        ivc.id=$vc_id 
        AND ivc.instance_id=".INSTANCE."
    LIMIT 1";

//echo $query;
$result = mysql_query($query) or die(mysql_error());
$number = mysql_num_rows($result);
$row=mysql_fetch_array($result);
	$Worldspace = str_replace("[", "", $row['ivcworldspace']);
	$Worldspace = str_replace("]", "", $Worldspace);
	$Worldspace = str_replace("|", ",", $Worldspace);
	$Worldspace = explode(",", $Worldspace);
	
	$Backpack  = $row['ivcinventory'];
	$Backpack = str_replace("|", ",", $Backpack);
	$Backpack  = json_decode($Backpack);

        
	
	$Hitpoints  = $row['ivcparts'];
	//$Hitpoints  ='[["wheel_1_1_steering",0.2],["wheel_2_1_steering",0],["wheel_1_4_steering",1],["wheel_2_4_steering",1],["wheel_1_3_steering",1],["wheel_2_3_steering",1],["wheel_1_2_steering",0],["wheel_2_2_steering",1],["motor",0.1],["karoserie",0.4]]';
	$Hitpoints = str_replace("|", ",", $Hitpoints);
	//$Backpack  = str_replace('"', "", $Backpack );
	$Hitpoints  = json_decode($Hitpoints);
	
        
        $query = "SELECT * FROM adm_objects WHERE type='object'";
        $result = mysql_query($query);
	$class_name_objects = mysql_fetch_assoc($result);


require_once 'templates/show_vehicle.php';
?>
