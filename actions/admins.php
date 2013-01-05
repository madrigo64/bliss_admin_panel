<?php

/*  author  skynetdev
 *  email   skynetdev3@gmail.com
 *  
 */

	$info = "";
        $title ="Manage admins";   
   if(!issecurity(true,true,'admins')) {echo "<h2>Access Denied!</h2>"; exit;} 
        

$query_admin_logs = "SELECT * FROM adm_admins_log ORDER BY created_at DESC LIMIT 50";
$admins_logs = mysql_query($query_admin_logs) or die(mysql_error());

$query_players_logs = "SELECT * FROM adm_players_log ORDER BY created_at DESC LIMIT 50";
$players_logs = mysql_query($query_players_logs) or die(mysql_error());

   
   
   if(isset($_GET['action']))  {
       
       if($_GET['action']=='show_add') {
           $title ="Add admin";
           require_once 'templates/add_admin.php'; 
           exit;
       }     
       if($_GET['action']=='show_edit') { 
           $title ="Edit admin";
                if(is_int($_GET['action']*1)) {
                    $id = $_GET['id'];
                    $query = "SELECT * FROM adm_admins WHERE id=".$id;
                    $result = mysql_query($query);
                    $row = mysql_fetch_assoc($result);
                    $permissions = explode(',', $row['permissions']);    
                  
                    
                    
                    require_once 'templates/edit_admin.php'; 
                    exit;
                }
 
           }
        if($_GET['action'] =='del' ){ 
            if(isset($_GET['id']) && $_GET['id']!=1){
            $query = "DELETE FROM adm_admins WHERE id=".$_GET['id'];
             mysql_query($query) or die(mysql_error());
            }
        }

   }
       

   if(isset($_POST['edit_admin'])) {
       
        $guid ='';
        $errort = '';
        $admin_permissions='';
       if(is_int($_POST['id']*1)) $id = $_POST['id']; else  $errort = 'Error!';
       if(isset($_POST['user_permissions'])) {
           
            foreach($_POST['user_permissions'] as $permission){
                
                 $admin_permissions .= $permission.',';
             }

         $permissions =  mysql_real_escape_string($admin_permissions);    

       } 
    

           
        $query = "SELECT login FROM adm_admins WHERE id= $id LIMIT 1";
        $result = mysql_query($query);
        $row = mysql_fetch_assoc($result);
        


        $login = $row['login'];
	$password = (isset($_POST['password'])) ? mysql_real_escape_string($_POST['password']) : '';    
	$password = md5($password);
	
	if(isset($_POST['guid']) && $_POST['guid']!='') {
            
            if(strlen($_POST['guid']) == 32) $guid = $_POST['guid'];
            else
            $errort .= 'GUID incorrect!<br />';              
            
        } 
        

	
        if($admin_permissions =='' )$errort .= 'One of the permissions must be checked.<br />';            
        if($password) {
            if (strlen($password) < 6) $errort .= 'Password must be at least 6 characters.<br />';
            $sql_pass="password='$password',";
            $password = md5($password);
        }  else $sql_pass='';
        
        
	if ($errort==''){
		
	 		$query = "UPDATE adm_admins SET
						$sql_pass
						guid='$guid',
						permissions='$permissions'
                                 WHERE id=$id
                                ";
		mysql_query($query) or die(mysql_error());

                insert_admin_log('ADED ADMIN');
                $info="admin $login is succesfully edited!";
		
	}          
       
       
       
   }
   
       
        
    if(isset($_POST['add_admin'])){
        
     	$login = (isset($_POST['login'])) ? mysql_real_escape_string($_POST['login']) : '';
	$password = (isset($_POST['password'])) ? mysql_real_escape_string($_POST['password']) : '';
        $guid ='';          
        $user_permissions='';
         
       if(isset($_POST['user_permissions'])) {
           $permissions_array = $_POST['user_permissions'];
            foreach($permissions_array as $permission){
                
                 $user_permissions .= $permission.',';
             }

         $permissions =  mysql_real_escape_string($user_permissions);    

       } 
    
       
           
        


            
	
	$errort = '';
	if(isset($_POST['guid']) && $_POST['guid']!='') {
            
            if(strlen($guid) == 32) $guid = $_POST['guid'];
            else
            $errort .= 'GUID incorrect!<br />';    
            
        } 
            
	if (strlen($login) < 2)	$errort .= 'Login must be at least 2 characters.<br />';
	
	if (strlen($password) < 6) $errort .= 'Password must be at least 6 characters.<br />';
	
        
        if($user_permissions =='')  $errort .= 'One of the permissions must be checked.<br />';            
       
        
	$query = "SELECT id FROM adm_admins WHERE login='$login' LIMIT 1";
	$sql = mysql_query($query) or die(mysql_error());
	if (mysql_num_rows($sql)==1)  $errort .= 'Login already used.<br />';
	

	if ($errort==''){
		$password = md5($password);
	 		$query = "INSERT INTO adm_admins SET
						login='$login',
						password='$password',
						guid='$guid',
						permissions='$permissions'";
		mysql_query($query) or die(mysql_error());

                insert_admin_log('ADED ADMIN');
                $info="New admin $login is succesfully added!";
		
	}   
    }   
        
        
        
        
        
        
	if (isset($_POST["user_id"])){
		$user_id = $_POST["user_id"];

			$query = "SELECT * FROM adm_admins WHERE id = $user_id LIMIT 1"; 
			$result = mysql_query($query) or die(mysql_error());
			if($row=  mysql_fetch_assoc($result)) {
                                insert_admin_log('DELETED ADMIN '.$row['login']);
				$query = "DELETE FROM adm_admins WHERE id=$user_id";
				mysql_query($query) or die(mysql_error());
                                
				$info .= "Admin {$row['login']} successfully removed!<br>";
			}		
	}
	
	$query = "SELECT * FROM adm_admins ORDER BY id ASC"; 
	$result = mysql_query($query) or die(mysql_error());
	$row_number = mysql_num_rows($result);
	


        require_once 'templates/show_admins.php';
?>
