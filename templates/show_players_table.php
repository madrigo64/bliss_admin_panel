<?php

/*  author  skynetdev
 *  email   skynetdev3@gmail.com
 *  
 */

?>



<h2>Players total: <?php echo isset($count_players)?$count_players:'error' ?></h2>
<div class="clean-red">
    <?php
    if(isset($_SESSION['msg_red']) && $_SESSION['msg_red']!='') {
        
        echo $_SESSION['msg_red'];
        unset($_SESSION['msg_red']);
    }
    
    if(isset($_SESSION['forbidden_item'])) foreach($_SESSION['forbidden_item'] as $pl_name => $rd_msg){
         
        echo '<hr>Player <font color=yellow>'.$pl_name.'</font> have forbidden items <br>';
        foreach ($rd_msg as $item): ?>
           <div class="preview_gear_slot forbidden" <?php echo ($full_invetory?"style='display:inline-block'":"style='display:table-cell'") ?> > 
               <img vss="forbidden" onclick="item_preview(this)" src="images/thumbs/<?php echo $item?>.png" title="<?php echo $item?>" alt="<?php echo $item?>"/>
           </div>
            
        <?php endforeach;  
    unset($_SESSION['forbidden_item']);    
    }
    
    
    
    if(isset($_SESSION['unknow_item'])) foreach($_SESSION['unknow_item'] as $pl_name => $rd_msg){
         
        echo '<hr>Player <font color=orange>'.$pl_name.'</font> have Unknow items <br>';
        foreach ($rd_msg as $item): ?>
           <div class="preview_gear_slot unknow" style='display:table-cell' > 
             <img  vss="forbidden_disabled" onclick="item_preview(this)" src="images/thumbs/<?php echo $item?>.png" title="<?php echo $item?>" alt="<?php echo $item?>"/>
           </div>
            
        <?php endforeach;  
    unset($_SESSION['unknow_item']);    
    }     
    
     
    ?>
</div>
<div class="clean-green"><?php 

    if(isset($_SESSION['msg_green']) && $_SESSION['msg_green']!='') {
        
        echo $_SESSION['msg_green'];
        unset($_SESSION['msg_green']);
    }

?></div>
<br>
<?php if(isset($count_players) && $count_players >0):?>
					<table border="0" width="100%" cellpadding="0" cellspacing="0" class="players-table">
						<tr>
                                                    <?php if(isset($show_type) && $show_type != 'alt_online'):?>
                                                    <th  width="5%" colspan="2">Actions</th>
                                                    <?php endif;?>
                                                    <th  width="5%">Status</th>
                                                    <th  width="5%">Unique id</th>
                                                    <th  width="5%">Position</th>
                                                    <th  width="10%">Survival time/created</th>
                                                    <th  width="5%">Last Updated</th>
                                                    <th  width="5%">Player Name</th>
                                                    <th class="player_inventory" width="30%">Inventory preview</th>
                                                    <th  width="30%">Backpack preview</th>
                                                </tr>
						<?php echo $palyers_table_rows; ?>				
					</table>
  
                                       <?php 
 endif;?> 


