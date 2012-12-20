<?php

/*  author  skynetdev
 *  email   skynetdev3@gmail.com
 *  
 */

?>



	<div id="page-heading">
                <h1>
                    <br>Body status:&nbsp; <img style="cursor:help" title="<?php echo $status?'dead':'live';?>" src="images/icons/<?php echo $status?'dead':'live';?>.png">
                    <br> Body ID:&nbsp;&nbsp;&nbsp; <font color=#D56E22><?php echo $row['id']; ?></font>
                    <br>Player name:&nbsp;&nbsp;&nbsp;&nbsp; <font color=#D56E22><?php echo $name; ?></font>
                    <br>Player Unique ID:&nbsp;&nbsp;&nbsp; <font color=#D56E22><?php echo $row['unique_id']; ?></font>
                    <br>Last save:&nbsp;&nbsp;<?php echo $row['last_updated']; ?>
                    <br> Created:&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row['start_time']; ?>
                    
               </h1>
	</div>
	<!-- end page-heading -->

	<table border="0" width="100%" cellpadding="0" cellspacing="0" id="content-table">
	<tr>
		<td id="tbl-border-left"></td>
		<td>
		<!--  start content-table-inner ...................................................................... START -->
		<div id="content-table-inner">
		
			<!--  start table-content  -->
			<div id="table-content">
				<div id="gear_player">	
					<div class="gear_info">
						<img class="playermodel" src='images/models/<?php echo str_replace('"', '', $model); ?>.png'/>
						<div id="gps" style="margin-left:46px;margin-top:54px">
							<div class="gpstext" style="font-size: 22px;width:60px;text-align: left;margin-left:47px;margin-top:13px">
							<?php
								echo round(($Worldspace[0]/100));  
							?>
							</div>
							<div class="gpstext" style="font-size: 22px;width:60px;text-align: left;margin-left:47px;margin-top:34px">
							<?php
								if(array_key_exists(3,$Worldspace)){
									echo round(($Worldspace[3]/100));
								} else {
									echo "0";
								}
								
							?>
							</div>
							<div class="gpstext" style="width:120px;margin-left:13px;margin-top:61px">
							<?php
                                                              
                                                               if(isset($Worldspace[1]) && $Worldspace[2])
		                                               echo world_x(($Worldspace[1]),getMapName()).world_y(($Worldspace[2]),getMapName() );
                                                               else echo "------";
                                                               
							?>
							</div>							
						</div>
						<div class="statstext" style="width:180px;margin-left:205px;margin-top:-115px">
							<?php echo 'Health:&nbsp;'.$health;?>
						</div>                                                
						<div class="statstext" style="width:180px;margin-left:205px;margin-top:-95px">
							<?php echo 'Zombie kills:&nbsp;'.$zombie_kills;?>
						</div>
						<div class="statstext" style="width:180px;margin-left:205px;margin-top:-75px">
							<?php echo 'Zombie headshots:&nbsp;'.$zombi_headshots;?>
						</div>
						<div class="statstext" style="width:180px;margin-left:205px;margin-top:-55px">
							<?php echo 'Survivor killed:&nbsp;'.$survivor_kills;?>
						</div>
						<div class="statstext" style="width:180px;margin-left:205px;margin-top:-35px">
							<?php echo 'Bandit killed:&nbsp;'.$bandit_kills;?>
						</div>
					</div>
					<div class="gear_inventory">
						<div class="gear_slot" style="margin-left:1px;margin-top:48px;width:80px;height:80px;">
						<?php
							if(array_key_exists(0,$binocular)){
								echo $binocular[0];
							} else {
								echo '<img style="max-width:78px;max-height:78px;" src="images/gear/binocular.png" title="" alt=""/>';
							}
						?>
						</div>
						<div class="gear_slot" style="margin-left:292px;margin-top:48px;width:80px;height:80px;">
						<?php
							if(array_key_exists(1,$binocular)){
								echo $binocular[1];
							} else {
								echo '<img style="max-width:78px;max-height:78px;" src="images/gear/binocular.png" title="" alt=""/>';
							}
						?>
						</div>
						<div class="gear_slot" style="margin-left:0px;margin-top:130px;width:224px;height:96px;">
							<?php
								echo $rifle;
							?>
						</div>
						<div class="gear_slot" style="margin-left:0px;margin-top:228px;width:224px;height:96px;">
						<?php							
							if(is_array($Backpack) && array_key_exists(0, $Backpack)){
								echo '<img style="max-width:220px; max-height:92px;" src="images/thumbs/'.$Backpack[0].'.png" title="'.$Backpack[0].'" alt="'.$Backpack[0].'"/>';
							} else {
								echo $second;
							}
						?>
						</div>
						<div class="gear_slot" style="margin-left:30px;margin-top:326px;width:96px;height:96px;">
						<?php
							echo $pistol;
						?>
						</div>
						<?php							
							$jx = 226;
							$jy = 130;
							$jk = 0;
							$jl = 0;
							$maxslots = 12;
							for ($j=0; $j<$maxslots; $j++){
								if ($jk > 2){ $jk = $jk - 3;$jl++;}
								
								//big ammo
								$hammo = '<img style="max-width:43px;max-height:43px;" src="images/gear/heavyammo.png" title="" alt=""/>';
								if ($j > 5){
									$hammo = '<img style="max-width:43px;max-height:43px;" src="images/gear/grenade.png" title="" alt=""/>';
								}
								if(array_key_exists($j,$heavyammo)){
									$hammo = $heavyammo[$j]['image'];									
									echo '<div class="gear_slot" style="margin-left:'.($jx+(49*$jk)).'px;margin-top:'.($jy+(49*$jl)).'px;width:47px;height:47px;">'.$hammo.'</div>';
									$jk = $jk - 1 + $heavyammo[$j]['slots'];
									$heavyammoslots = $heavyammoslots + $heavyammo[$j]['slots'];
								} else {
									if($heavyammoslots==$maxslots){
										break;
									}
									$heavyammoslots++;
									
									echo '<div class="gear_slot" style="margin-left:'.($jx+(49*$jk)).'px;margin-top:'.($jy+(49*$jl)).'px;width:47px;height:47px;">'.$hammo.'
								</div>';
								}
								$jk++;
								
							}
                                                        
                                                        ////////////////////////
                                                        
                                                        
                                                        
                                                        
							$jx = 128;
							$jy = 326;
							$jk = 0;
							$jl = 0;
							for ($j=0; $j<8; $j++){
								if ($jk > 3){ $jk = 0;$jl++;}
								//small ammo
								$sammo = '<img style="max-width:43px;max-height:43px;" src="images/gear/smallammo.png" title="" alt=""/>';
								if(array_key_exists($j,$smallammo)){
									$sammo = $smallammo[$j];
								}
								echo '<div class="gear_slot" style="margin-left:'.($jx+(49*$jk)).'px;margin-top:'.($jy+(49*$jl)).'px;width:47px;height:47px;">'.$sammo.'
								</div>';								
								$jk++;
							}
                                                        
                                                        
                                                        
                                                        
                                                        
							$jx = 30;
							$jy = 424;
							$jk = 0;
							$jl = 0;
							for ($j=0; $j<12; $j++){
								if ($jk > 5){ $jk = 0;$jl++;}
								//items
								$uitem = '';
								if(array_key_exists($j,$usableitems)){
									$uitem = $usableitems[$j];
								}
								echo '<div class="gear_slot" style="margin-left:'.($jx+(49*$jk)).'px;margin-top:'.($jy+(49*$jl)).'px;width:47px;height:47px;">'.$uitem.'
								</div>';								
								$jk++;
							}
						?>
					</div>
					<!-- Backpack -->
					<div class="gear_backpack">						
						<?php

							$maxmagazines = 24;
							$BackpackName = $Backpack[0];
								is_array($object_array_for_inventory)&&$maxmagazines = $object_array_for_inventory['transport_max_magazines']?$object_array_for_inventory['transport_max_magazines']:$maxmagazines;
							
							
							$bpweapons = array();
							if(is_array($Backpack[1]) && array_key_exists(0, $Backpack[1])){
								$bpweaponscount = count($Backpack[1][0]);							
								for ($m=0; $m<$bpweaponscount; $m++){
										for ($mi=0; $mi<$Backpack[1][1][$m]; $mi++){
											$bpweapons[] = $Backpack[1][0][$m];
										}
								}
							}

							
							$bpitems = array();
                                                        
							if(is_array($Backpack[2]) && array_key_exists(0, $Backpack[2])){
								$bpitemscount = count($Backpack[2][0]);							
								for ($m=0; $m<$bpitemscount; $m++){
									for ($mi=0; $mi<$Backpack[2][1][$m]; $mi++){
										$bpitems[] = $Backpack[2][0][$m];
									}
								}
							}
							
                                                          
							$Backpack = (array_merge($bpweapons, $bpitems));
							
							$backpackslots = 0;
							$backpackitem = array();
							$bpweapons = array();
                                            
                                                   
							for ($i=0; $i<count($Backpack); $i++){
                                                      
                                                              $object_array_for_backpack = getObjectByClassName($Backpack[$i]);
                                                           
     								if(is_array($object_array_for_backpack)){
									switch($object_array_for_backpack['subtype']){
										case 'binocular':
											$backpackitem[] = array('image' => '<img style="max-width:43px;max-height:43px;" src="images/thumbs/'.$Backpack[$i].'.png" title="'.$Backpack[$i].'" alt="'.$Backpack[$i].'"/>', 'slots' => $object_array_for_backpack['slots']);
											break;
										case 'rifle':
											$bpweapons[] = array('image' => '<img style="max-width:124px;max-height:92px;" src="images/thumbs/'.$Backpack[$i].'.png" title="'.$Backpack[$i].'" alt="'.$Backpack[$i].'"/>', 'slots' => $object_array_for_backpack['slots']);
											break;
										case 'pistol':
											$bpweapons[] = array('image' => '<img style="max-width:92px;max-height:92px;" src="images/thumbs/'.$Backpack[$i].'.png" title="'.$Backpack[$i].'" alt="'.$Backpack[$i].'"/>', 'slots' => $object_array_for_backpack['slots']);
											break;
										case 'backpack':
											break;
										case 'heavyammo':
											$backpackitem[] = array('image' => '<img style="max-width:43px;max-height:43px;" src="images/thumbs/'.$Backpack[$i].'.png" title="'.$Backpack[$i].'" alt="'.$Backpack[$i].'"/>', 'slots' => $object_array_for_backpack['slots']);
											break;
										case 'smallammo':
											$backpackitem[] = array('image' => '<img style="max-width:43px;max-height:43px;" src="images/thumbs/'.$Backpack[$i].'.png" title="'.$Backpack[$i].'" alt="'.$Backpack[$i].'"/>', 'slots' => $object_array_for_backpack['slots']);
											break;
										case 'item':
											$backpackitem[] = array('image' => '<img style="max-width:43px;max-height:43px;" src="images/thumbs/'.$Backpack[$i].'.png" title="'.$Backpack[$i].'" alt="'.$Backpack[$i].'"/>', 'slots' => $object_array_for_backpack['slots']);
											break;
									
									}
								}
							}	

							$weapons = count($bpweapons);
							$magazines = $maxmagazines;
							$freeslots = $magazines;
                                                        
                                                        
							$jx = 1;
							$jy = 48;
							$jk = 0;
							$jl = 0;
                                                      
							for ($j=0; $j< $weapons; $j++){
								if ($jk > 1){ $jk = 0;$jl++;}
								echo '<div class="gear_slot" style="margin-left:'.($jx+(130*$jk)).'px;margin-top:'.($jy+(98*$jl)).'px;width:128px;height:96px;">'.$bpweapons[$j]['image'].'</div>';
								$magazines = $magazines - $bpweapons[$j]['slots'];	
								$freeslots = $freeslots - $magazines;
								$jk++;
							}
							
							
							$jx = 1;
							$jy = 48 + (98*round($weapons/2));
							$jk = 0;
							$jl = 0;

							for ($j=0; $j < $magazines; $j++){
                                                            
								if ($jk > 6){ 
                                                                    $jk = 0; $jl++;
                                                                  
                                                                }
                                                                
								if ($j < count($backpackitem)){
									echo '<div class="gear_slot" style="margin-left:'.($jx+(49*$jk)).'px;margin-top:'.($jy+(49*$jl)).'px;width:47px;height:47px;">'.$backpackitem[$j]['image'].'</div>';
									$jk = $jk - 1 + $backpackitem[$j]['slots'];
									$backpackslots = $backpackslots + $backpackitem[$j]['slots'];
									$freeslots = $freeslots - $backpackitem[$j]['slots'];
								} else {
									if($backpackslots==$maxmagazines){
										break;
									}
									$backpackslots++;
									echo '<div class="gear_slot" style="margin-left:'.($jx+(49*$jk)).'px;margin-top:'.($jy+(49*$jl)).'px;width:47px;height:47px;"></div>';
								}								
								$jk++;
							}	 			
						?>
						<div class="backpackname">
						<?php
							echo $BackpackName.'&nbsp;&nbsp;(&nbsp;'.$freeslots.'&nbsp;/&nbsp;'.$maxmagazines.'&nbsp;)';
						?>
						</div>
					</div>
					<!-- Backpack -->
				</div>			
			</div>
			<!--  end table-content  -->

			<div class="clear"></div>
		 
		</div>
		<!--  end content-table-inner ............................................END  -->
		</td>
		<td id="tbl-border-right"></td>
	</tr>
	<tr>
		<th class="sized bottomleft"></th>
		<td id="tbl-border-bottom">&nbsp;</td>
		<th class="sized bottomright"></th>
	</tr>
	</table>

	<div class="clear">&nbsp;</div>
        