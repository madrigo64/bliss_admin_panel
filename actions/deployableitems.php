<?php

/*  author  skynetdev
 *  email   skynetdev3@gmail.com
 *  show vehicles 
 */

$deployable_table_rows='';
$full_invetory=false;
$serverrunning = true;
$title ='Deployable Items';

//check security permissions
if(!issecurity(true, true, 'entities')) {
    echo "<h2>Access Denied!!</h2>";
    exit;
}

       // for delete!
    	if (isset($_POST["deployable"])) {
		$items = $_POST["deployable"];

		for($i=0; $i < count($items); $i++)
		{
			$query2 = "SELECT deployable.class_name, instance_deployable.* FROM `deployable`, `instance_deployable` AS `instance_deployable` WHERE deployable.id = instance_deployable.deployable_id AND instance_deployable.unique_id = ".$aDoor[$i].""; 
			$res2 = mysql_query($query2) or die(mysql_error());
			while ($row2=mysql_fetch_array($res2)) {
				$query2 = "INSERT INTO `log_tool`(`action`, `user`, `timestamp`) VALUES ('DELETE VEHICLE: ".$row2['class_name']." - ".$row2['unique_id']."','{$_SESSION['login']}',NOW())";
				$sql2 = mysql_query($query2) or die(mysql_error());
				$query2 = "DELETE FROM `instance_deployable` WHERE unique_id ='".$aDoor[$i]."'";
				$sql2 = mysql_query($query2) or die(mysql_error());
				$delresult = "Deployable ".$row2['class_name']." - ".$row2['unique_id']." successfully removed!";
			}		
			//echo($aDoor[$i] . " ");
		}
		//echo $_GET["deluser"];
	}
	
$query = "SELECT
        dep.class_name,
        instdep.*,
        pf.name,
        surv.is_dead,
        surv.id as svid
    FROM
        deployable as dep,
        instance_deployable AS instdep,
        profile AS pf,
        survivor AS surv
    WHERE 
        instdep.instance_id = ".INSTANCE."
        AND dep.id = instdep.deployable_id
        AND instdep.owner_id =  surv.id
        AND pf.unique_id = surv.unique_id
"; 	


	$result = mysql_query($query) or die(mysql_error());
	$total_deployable = mysql_num_rows($result);
	$chbox = "";
	
	if (!$serverrunning){ 
		$chbox = "<th class=\"table-header-repeat line-left\"><a href=\"\">Delete</a></th>";
		$formhead = '<form action="index.php?view=table&show=5" method="post">';
		$formfoot = '<input type="submit" class="submit-login"  /></form>';
	}
	
		
	while ($row=mysql_fetch_array($result)) {
		if (!$serverrunning){$chbox = "<td class=\"gear_preview\"><input name=\"deployable[]\" value=\"".$row['unique_id']."\" type=\"checkbox\"/></td>";}	
		$deployable_table_rows .= row_deployable_items($row, $chbox);
	}        
        
        require_once 'templates/show_deployableitems.php';

?>
