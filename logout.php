<?php

/*  author  skynetdev
 *  email   skynetdev3@gmail.com
 *  
 */
        require_once 'config.php';
        require_once 'actions/functions.php';
    
        insert_admin_log('LOGOUT');
	
	if (isset($_SESSION['user_id'])) session_destroy();
		
	setcookie('login', '', 0, "/");
	setcookie('password', '', 0, "/");

	header('Location: '.str_replace('logout.php', '', $_SERVER['SCRIPT_NAME']));
        
?>
