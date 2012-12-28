<?php

/*  author  skynetdev
 *  email   skynetdev3@gmail.com
 *  
 */



require_once '../config.php';   
require_once 'functions.php';


if(!issecurity(true,true,'control')) {echo "<h2>Access Denied!</h2>"; exit;} 




$server_start_command = 'start "" /d '.GAME_PATH.'  /b "'.SERVER_START_FILE.'"';
$bec_start_command = ' start "" /d  '.BEC_PATH.'  /b "'.BEC_PATH.DS.BECEXE.'" '.BEC_STRING;




$serverrunning = false;
$becrunning = false;   

   
if(!isAjax()){   
require_once 'templates/show_server_control.php';
  
   
}  else {

    if($_POST['action'] == 'get_server_status'){   
     echo getProcessStatus(SERVEREXE)?"on":"off";
    }
    
    if($_POST['action'] == 'get_bec_status'){   
     echo getProcessStatus(BECEXE)?"on":"off";
    } 
    
 if($_POST['action'] == 'bec_off'){   
        exec('taskkill /f /IM '.BECEXE);
        insert_admin_log('BEC STOP');
    } 
    
    
 if($_POST['action'] == 'bec_on'){   
        pclose(popen($bec_start_command,'r'));
        insert_admin_log('BEC START');
        sleep(8);
    } 
    
 if($_POST['action'] == 'server_off'){   
        exec('taskkill /f /IM '.SERVEREXE);
        exec('taskkill /f /IM '.'restarter.exe');
        sleep(3);
        insert_admin_log('SERVER STOP');
    }
    
 if($_POST['action'] == 'server_on'){   
  
         pclose(popen($server_start_command, 'r'));
       // exec($server_start_command);
        sleep(25);
        //exec('taskkill /f /IM cmd.exe');
        insert_admin_log('SERVER START');

    }      
     
    
    
    
}

        
   
   
?>
