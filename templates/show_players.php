<?php

/*  author  skynetdev
 *  email   skynetdev3@gmail.com
 *  
 */
?>
<script>
    

   
 function update_data(){
        var mode_online = 'rcon_online';
        
        if($('#invetory_full:checked').val())  
          invetory_full=true; 
        else 
          invetory_full=false;

       if($('#rcon_answer:checked').val())  
           rcon_answer=true; 
       else 
           rcon_answer=false;     
       
       if($("#mode_online option:selected").val() == 'alt_online') 
            mode_online = 'alt_online';
       else 
            mode_online = 'online';

      $('#ajax_loader_panel').show();
      $.ajax({
        url: "actions/players.php?show=<?php echo $_GET['show']?>",
        data: { invetory: invetory_full, answer: rcon_answer, show: mode_online },
        type: "POST",
        success: function(data){
            $('#show_table_main').html(data);

        },
        complete : function() { $('#ajax_loader_panel').hide(2500); }
      });  
  
  
 }  

    
    
    ///ajax request
   var my_interval;
   set_interval = function () {
    
       delay_sec=$('#interval_sec').val();
       if(isNaN(delay_sec))  { alert('Error! timer will be set to 30 sec'); $('#interval_sec').val('30'); delay_sec=30; }
       if(delay_sec < 10 ) { alert('minimum 10 sec!'); $('#interval_sec').val('10'); delay_sec=10; }
       delay_sec = delay_sec * 1000;
       clearInterval(my_interval);
        if($('#set_interval:checked').val()) {
                   $('#on_off_timer').html('<font color=green>On</font>');
                   my_interval = setInterval(function() {
                                               update_data();
                                             }, delay_sec); //30 seconds  
       }
       else  {
            clearInterval(my_interval);
            $('#on_off_timer').html('<font color=red>Off</font>');
       }
      
      
       
       
   }
   

   
 function invetory_full_on_off(){
    
    update_data();
    set_interval();   
    if($('#invetory_full:checked').val())
       $('#on_off_invetory_full').html('<font color=green>On</font>'); 
    else               
       $('#on_off_invetory_full').html('<font color=red>Off</font>');
       
       
   

};   


function rcon_show(){
       update_data();
       set_interval();
}

function rcon_on_off()
{
  if($("#mode_online option:selected").val() == 'alt_online')
    
    $('#rcon_answer').removeAttr('checked').attr('disabled','disabled'); 
  else
    $('#rcon_answer').removeAttr('disabled');  
    
}


function ban_player_form(rcon_id, player_name, player_ip, player_guid){
    
  $('#backgroundPopup').show();
  $('#ban_form').show(500);
  $('#ban_rcon_id').val(rcon_id);
  $('#ban_rcon_ip').html(player_ip);
  $('#ban_rcon_guid').html(player_guid);
  $('#ban_player_name').html(player_name);
  
  $('#ban_time_type').change(function() {
        if($('#ban_time_type option:selected').val() == '0')
            $('#ban_time').attr('disabled','disabled').val('0');
        else
            $('#ban_time').removeAttr('disabled').val('');
  });
    
}


function ban_player(){
    if($('#by_ip:checked').val())
    player_ip = $('#ban_rcon_ip').html();
    else
    player_ip = false; 

    

    if($('#by_guid:checked').val()) 
    rcon_id = $('#ban_rcon_id').val();
    else
    rcon_id = false; 

    player_name = $('#ban_player_name').html();

    if($('#ban_time_type option:selected').val()=='0') ban_time = '0';
    else{
      if($('#ban_time_type option:selected').val()=='min')   
        ban_time = $('#ban_time').val();
      if($('#ban_time_type option:selected').val()=='day')   
        ban_time = $('#ban_time').val()*1440;  

    }
    
   

    reason_text = $('#ban_reason').val();
  if(!reason_text) reason_text = 'without reason';
    


    if(player_ip != false || rcon_id !== false)
    {    

        $('#ajax_loader_ban').show(500);
        $.post("<?php echo str_replace('/index.php', '', $_SERVER['SCRIPT_NAME'])?>/actions/ban_rcon_player.php",
                {rcon_id: rcon_id, player_ip: player_ip,  ban_minutes: ban_time, reason: reason_text, player_name: player_name },
                function(data) {
                  if(data) {
                      if(data =='done'){
                      $('#backgroundPopup, #kick_form, #ban_form').hide(); 
                      $('#rcon_id_'+rcon_id).hide(1800);
                      }
                      else  alert('Rcon to busy, try again.');
                  }    
               });
        $('#ajax_loader_ban').hide();       
    }
    else alert('You must select by IP or by GUID ban');
}


function kick_player(){

    if($('#by_guid:checked').val()) 
    rcon_id = $('#kick_rcon_id').val();
    else
    rcon_id = false; 

    reason_text = $('#kick_reason').val();
    player_name = $('#kick_player_name').html();
    if(!reason_text) reason_text ='Without reason';

    if(rcon_id !== false) { 
    $('#ajax_loader_kick').show(500);
    $.post("<?php echo str_replace('/index.php', '', $_SERVER['SCRIPT_NAME'])?>/actions/kick_rcon_player.php", 
                {rcon_id: rcon_id, reason: reason_text, player_name: player_name },
                function(data) {
                  if(data) {
                      if(data =='done'){
                      $('#backgroundPopup, #kick_form, #ban_form').css('display','none'); 
                      $('#rcon_id_'+rcon_id).hide(1800);
                      }
                      else alert('Rcon to busy, try again.');
                  }    
               });
            $('#ajax_loader_kick').hide();               
    }           
    else alert('error player not found');



}



function kick_player_form(rcon_id, player_name){
    
    $('#backgroundPopup').show();
    $('#kick_form').show(500);
    $('#kick_rcon_id').val(rcon_id);
    $('#kick_player_name').html(player_name);    
    
    
}
   
function item_preview(obj){
    
   $('#backgroundPopup').show(); 
   $('#item_preview').show(300); 
   $('#item_class_name').html($(obj).attr('title'));  
   $('#item_img_preview').attr('src',$(obj).attr('src'));  
   if(vvs=$(obj).attr('vss')){
       $('#new-button').removeAttr('checked');
       $('#status_item').html('Forbidden').css('color','red');
       if(vvs == 'forbidden_disabled') {
            $('#button_label').remove();
             $('#status_item').html('Unknow. Not found in mysql table adm_objects').css('color','orange');
       }      
   }
   else {
       $('#new-button').attr('checked','checked');
       $('#status_item').html('Allowed').css('color','green');
   }    
}


$(document).ready(function() {
    //first run
    update_data(); 
    set_interval(); 
    $('#close_kick_form, #close_ban_form, #close_item_preview').click(function(){
       $('#backgroundPopup, #kick_form, #ban_form, #item_preview').css('display','none'); 
    });
 

 $('#new-button').click(function(){
     
     var status='';
     var status_text = '';
     if($('#new-button:checked').val()) status = 'on'; else status = 'off';
     if(status == 'on') status_text = 'Allowed'; else status_text ='Forbidden';
     
      $.ajax({
        url: "<?php echo str_replace('/index.php', '', $_SERVER['SCRIPT_NAME'])?>/actions/item_permission.php",
        data: {class_name: $('#item_class_name').html(), action: status },
        type: "POST",
        success: function(data) {
            if(data =='done'){
                if(status =='on')
                $('#status_item').html(status_text).css('color','green');
                else
                $('#status_item').html(status_text).css('color','red');    
                 update_data();
            }  else {
                          alert('Error!');
                          window.location.href="<?php echo str_replace('/index.php', '', $_SERVER['SCRIPT_NAME'])?>/";
           }        

        },
        error: function() { 
                          alert('Error!');
                          window.location.href="<?php echo str_replace('/index.php', '', $_SERVER['SCRIPT_NAME'])?>/";        
        },
        complete : function() {  }
      });       
     
 
});
 
    
});




    </script>

    
<div id="backgroundPopup" style=""></div>
<div id="item_preview">
    Class name: <div id="item_class_name"></div>
    <br> <img id="item_img_preview" src="">
    <br> <div id="status_item">Allowed</div>
    <br> 
 <?php if(ACTION_FORBIDDEN_ITEMS == true):?>   
    <section>
        <input type="checkbox" checked="checked" name="new-button" id="new-button">
        <label id="button_label" for="new-button">
            <a>&#xF011;</a>
        </label>	   
    </section>
 <?php endif;?>   
    <img title="close" src="images/icons/close.png" id="close_item_preview">
</div>


<div id="kick_form">
   <img id="ajax_loader_kick" src="images/main/ajax-loader2.gif"> 
   Kick player 
   <input type="hidden" id="kick_rcon_id" value="">
   <div id="kick_player_name"></div>
   Kick Reason <input id="kick_reason" name="kick_reason" maxlength="64"><br>
   <fieldset>
   <legend>Reason:</legend>
    <input type="radio" checked="checked" name="pre_reason" onclick="$('#kick_reason').val('Without reason')" value="0"> Without reason
    <br>  <input type="radio" name="pre_reason" onclick="$('#kick_reason').val('Bad Language')" value="1"> Bad Language
    <br>  <input type="radio" name="pre_reason" onclick="$('#kick_reason').val('Bad Player Name')" value="1"> Bad Player Name
    <br>  <input type="radio" name="pre_reason" onclick="$('#kick_reason').val('Ignoring Admin')" value="2"> Ignoring Admin
    <br>  <input type="radio" name="pre_reason" onclick="$('#kick_reason').val('For VIP enter')" value="2"> For VIP enter
   </fieldset>
   
   <br>
   <center>
   <input onclick="kick_player()" type="submit" class="x_submit" value="Kick">
   </center>
   <img id="close_kick_form" src="images/icons/close.png" title="close">
</div>

<div id="ban_form">
    <img id="ajax_loader_ban" src="images/main/ajax-loader2.gif">  
    Ban for player<br>
    <div id="ban_player_name"></div>
    Reason <input id="ban_reason" name="ban_reason" maxlength="64" size="40">
    <input type="hidden" id="ban_rcon_id" value="">
   <fieldset>
     
   <legend>Reason:</legend>
    <input type="radio" checked="checked"  name="pre_reason" onclick="$('#ban_reason').val('')" value="0"> Other
    <br>  <input type="radio" name="pre_reason" onclick="$('#ban_reason').val('Cheating/Hacking')" value="1"> Cheating/Hacking
    <br>  <input type="radio" name="pre_reason" onclick="$('#ban_reason').val('Bad Language')" value="2"> Bad Language
    <br>  <input type="radio" name="pre_reason" onclick="$('#ban_reason').val('Excessive Teamkiling')" value="3"> Excessive Teamkiling
   </fieldset>
   <fieldset>
   <legend>Ban time:</legend>   
   <select id="ban_time_type">
       <option selected value="0">Permanently</option>
       <option value="min">Minute</option>
       <option value="day">Day</option>
   </select>
    <br> <input disabled id="ban_time" size="2"  value="0">
    <br> 
   </fieldset>       
      
   <fieldset>
   <legend>Ban By:</legend>   
    <input  id="by_ip"  checked type="checkbox" value="1"> IP <div id="ban_rcon_ip"></div>
    <input id="by_guid" checked type="checkbox" value="1"> GUID <div id="ban_rcon_guid"></div>
   </fieldset>
   <center>
   <input onclick="ban_player()" type="submit" class="x_submit" value="Ban">
   </center>
   <img id="close_ban_form" src="images/icons/close.png" title="close">
</div>    
    
    

<h1><?php echo $title?></h1>

   <div class="clean-gray">
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tbody>
            <tr>
                <td valign="top" width ="20px">
                    <img id="ajax_loader_panel" src="images/main/ajax-loader2.gif">
                </td>              
                </td>
                <td >
                     <div id="ajax_control">

                         Update data every  <input onchange="set_interval(this.value); if(!isNaN(this.value)) alert('ok timer set to '+this.value+' sec'); else alert('error timer set to 60 sec'); " type="number" size="3" min="10" id="interval_sec" value="60" maxlength="3"> sec &nbsp;&nbsp;&nbsp; 
                         Timer:&nbsp;<b id="on_off_timer"><font color=green>On</font></b> <input checked="checked" type="checkbox" name="interval_onoff" onclick="set_interval()" id="set_interval">&nbsp;&nbsp;&nbsp;
                         Full invetory/backpack   <b id="on_off_invetory_full"><font color=red>Off</font></b> <input  title="show full invetory" style="cursor: help" type="checkbox" name="invetory_full" onclick="invetory_full_on_off()" id="invetory_full">&nbsp;&nbsp;&nbsp;
                         <br>
                         Players
                           <select id="mode_online" onchange="rcon_on_off();alert('changed');update_data(); ">
                               <option value="rcon_online">Rcon Online</option>
                               <option value="alt_online">Online last <?php echo ALTERNATIVE_ONLINE_MINUTES?> min</option>    
                           </select> &nbsp;&nbsp;&nbsp;                        
                         Rcon answer: <input  title="show rcon answer" style="cursor: help" type="checkbox" name="rcon_answer" onclick="rcon_show()" id="rcon_answer">

                     </div>             

                </td>
        </tr>
        </tbody>
    </table>
    </div>
    <br>

    
<div id="show_table_main">
  <?php
    if(isAjax()) 
    require_once '../templates/show_players_table.php';
    else
   require_once '/templates/show_players_table.php';
  ?>  
</div>
    
