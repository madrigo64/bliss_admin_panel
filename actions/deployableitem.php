<?php

/*  author  skynetdev
 *  email   skynetdev3@gmail.com
 *  
 */
$title ='Deployable Item';
 
$vc_id = $_GET['id'];

$query = "SELECT
        dep.class_name,
        instdep.*,
        pf.name as pfname,
        surv.is_dead,
        surv.id as svid,
        admo.*
    FROM
        deployable as dep,
        instance_deployable AS instdep,
        profile AS pf,
        survivor AS surv,
        adm_objects AS admo
    WHERE 
        instdep.id =$vc_id
        AND dep.id = instdep.deployable_id
        AND instdep.owner_id =  surv.id
        AND pf.unique_id = surv.unique_id
        AND admo.class_name = dep.class_name
    LIMIT 1
   
    
    
"; 


   
//echo $query;
$result = mysql_query($query) or die(mysql_error());
$number = mysql_num_rows($result);
$row=mysql_fetch_array($result);
	$Worldspace = str_replace("[", "", $row['worldspace']);
	$Worldspace = str_replace("]", "", $Worldspace);
	$Worldspace = str_replace("|", ",", $Worldspace);
	$Worldspace = explode(",", $Worldspace);
	
	$Backpack  = $row['inventory'];
	$Backpack = str_replace("|", ",", $Backpack);
	$Backpack  = json_decode($Backpack);



require_once 'templates/show_deployableitem.php';
?>
