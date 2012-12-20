<?php

/*  author  skynetdev
 *  email   skynetdev3@gmail.com
 *  
 */
  if(!issecurity(true,true,'chat')) {echo "<h2>Access Denied!</h2>"; exit;} 
  
$title = 'Chat';
$server_status = getProcessStatus(SERVEREXE);   
   
?>

<script>

function send_msg(){
    
        msg = $('#chat_text').val();
        
        
        
        $.post("<?php echo str_replace('/index.php', '', $_SERVER['SCRIPT_NAME'])?>/actions/chat.php", {msg: msg },
                function(data) {
                  if(data) {
                      if(data =='done'){
                          alert('Message sent');
                          $('#chat_text').val('');
                        }
                        
                  }    
               });        
        
}


</script>

<h1><?php echo $title?></h1>
<?php if($server_status):?>
<h2>Send Massage:  <input id="chat_text" size="90"></h2>
<input class="x_submit" type="submit" value="Send" onclick="send_msg()">

<?php else: ?>

<h2><font color=red>Server is off</font></h2>

<?php endif;?>
    