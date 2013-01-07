<?php

/*  author  skynetdev
 *  email   skynetdev3@gmail.com
 *  
 */

// copy SERVER_START_FILE to  $game_path (arma2 main folder)
if(!file_exists(SERVER_START_FILE)){
   
   
   copy('installation'.DS.'ADM_START_SERVER.bat', SERVER_START_FILE) or die("can't copy file from installation".DS.'ADM_START_SERVER.bat'." to ".SERVER_START_FILE."  please check path or copy this file manualy"); 
  
   echo "<font color=red size=5>Now check main game folder and you can edit file ADM_START_SERVER.bat <br> for correctly running server</font>";
    
}

$title ="Server Control";  
?>

<script>


   
$(document).ready(function() {

    function getServerStatus() {
        var result = null;
        $.ajax({
          url: 'actions/server_control.php',
           data: { action: "get_server_status" },
           type: "POST",
           async: false,
          success: function(data) {
             $('#server_status_img').attr('src','images/icons/power_'+data+'.png');
              result = data;
          }
        });
        return result;
    }

    function getBecStatus(){
       var result = null;
        $.ajax({
         url: 'actions/server_control.php',
          data: { action: "get_bec_status" },
          type: "POST",
          async: false,
         success: function(data) {
            $('#bec_status_img').attr('src','images/icons/power_'+data+'.png');
            result = data;
         }
       });
       return result;
    }


   getServerStatus();
   
   getBecStatus();
 

     $('#server_status_img').click(function(){ 
            var server_status = getServerStatus();
            var action = '';
            if(server_status == 'on') action = 'server_off'; else action='server_on';
          
            
            $('#ajax_loader_server').show(500);    
            $.ajax({
             url: 'actions/server_control.php',
              data: { action: action },
              type: "POST",
              complete : function() {
                getServerStatus();
                getBecStatus();
                $('#ajax_loader_server').hide(500); 
             }
           });               


     });


     $('#bec_status_img').click(function(){ 
          if(getServerStatus() == 'on'){
                    var bec_status = getBecStatus();
                    var action = '';
                    if(bec_status == 'on') action = 'bec_off'; else action='bec_on';



                    $('#ajax_loader_bec').show(500);    
                    $.ajax({
                     url: 'actions/server_control.php',
                      data: { action: action },
                      type: "POST",
                     complete : function() {
                        getBecStatus();
                        $('#ajax_loader_bec').hide(500); 
                     }
                   });           
          }
          else alert('can not start bec because server is off');
     });
     

});



</script>



<h1><?php echo $title?></h1>



<table class="players-table" width="30%" cellspacing="0" cellpadding="0" border="0" class="players-table">
     <tr>
         <th> Name </th>
         <th >Action/status</th>
    </tr>
    <tr>
        <td>Server</td>
        <td align="center"><img id="server_status_img" title="On/Off" src=""><img id="ajax_loader_server" src="images/main/ajax-loader.gif"></td>
    </tr>
  <?php if(file_exists(BEC_PATH.DS.BECEXE)):?>  
    <tr>
        <td>Bec</td>
        <td align="center"><img id="bec_status_img" title="On/Off" src=""><img id="ajax_loader_bec" src="images/main/ajax-loader.gif"></td>
    </tr>    
 <?php else: ?>
   <tr> 
    <td colspan="2">    
        <font color="#25A7E8" size="1"> Battleye Extended Control (BEC) not found in <?php echo BEC_PATH.DS.BECEXE ?> <br>
     if you like to use it check adm_config.php file </font>
    </td>
   </tr> 
<?php  endif; ?>
    
</table>    
    
    
    








