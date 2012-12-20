<?php

/*  author  skynetdev
 *  email   skynetdev3@gmail.com
 *  
 */

include_once '../config.php';
require_once 'functions.php';
if (isset($_SESSION['user_id']))header('Location: index.php');


if (!empty($_POST)){
	$login = (isset($_POST['login'])) ? mysql_real_escape_string($_POST['login']) : '';
	
        $password = md5($_POST['password']);

        $query = "SELECT id FROM adm_admins WHERE login='$login' AND password='$password' LIMIT 1";
        $result = mysql_query($query);
        
	if (mysql_num_rows($result)){
            $row = mysql_fetch_assoc($result)  or die(mysql_error());
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['login'] = $login;
            $time = 86400;

            $query = "SELECT permissions FROM adm_admins WHERE login='$login' LIMIT 1";
            $sql = mysql_query($query) or die(mysql_error());
            $row = mysql_fetch_assoc($sql);
            $_SESSION['user_permissions'] = $row['permissions'];

//            if (isset($_POST['remember']))
//            {
//                    setcookie('login', $login, time()+$time, "/");
//                    setcookie('password', $password, time()+$time, "/");
//            }
            $query = "UPDATE adm_admins SET lastlogin= NOW() WHERE login='$login' LIMIT 1";
            $sql2 = mysql_query($query) or die(mysql_error());
            insert_admin_log('LOGIN');
            header('Location: ../');
			


		
	}
	else header('Location: ../?login_error=1');
	
}
?>
