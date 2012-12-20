<?php
/*  author  skynetdev 
 *  email   skynetdev3@gmail.com
 *  
 */

?>
  
<!-- Start  MENU -->
<ul class="pureCssMenu pureCssMenum0">
	<li class="pureCssMenui0"><a class="pureCssMenui0" href="<?php echo str_replace('/index.php', '', $_SERVER['SCRIPT_NAME'])?>/"><?php echo $lng_home[$ses_lng]?></a></li>
	<li class="pureCssMenui0"><a class="pureCssMenui0" href="#"><span><?php echo $lng_srv_control[$ses_lng]?></span><![if gt IE 6]></a><![endif]><!--[if lte IE 6]><table><tr><td><![endif]-->
            <ul class="pureCssMenum">
                    <li class="pureCssMenui"><a class="pureCssMenui" href="?page=admins"><?php echo $lng_admins[$ses_lng]?></a></li>
                    <li class="pureCssMenui"><a class="pureCssMenui" href="?page=control"><?php echo $lng_control[$ses_lng]?> </a></li>
                    <li class="pureCssMenui"><a class="pureCssMenui" href="?page=chat"><?php echo $lng_chat[$ses_lng]?> </a></li>
            </ul>
	<!--[if lte IE 6]></td></tr></table></a><![endif]-->
        </li>
	<li class="pureCssMenui0"><a class="pureCssMenui0" href="#"><span><?php echo $lng_enitities[$ses_lng]?></span><![if gt IE 6]></a><![endif]><!--[if lte IE 6]><table><tr><td><![endif]-->
            <ul class="pureCssMenum">
                    <li class="pureCssMenui"><a class="pureCssMenui" href="#"><span><?php echo $lng_survivor[$ses_lng]?></span><![if gt IE 6]></a><![endif]><!--[if lte IE 6]><table><tr><td><![endif]-->
                    <ul class="pureCssMenum">
                            <li class="pureCssMenui"><a class="pureCssMenui" href="?page=players&show=online"><?php echo $lng_online[$ses_lng]?></a></li>
                            <li class="pureCssMenui"><a class="pureCssMenui" href="?page=players&show=all"><?php echo $lng_all[$ses_lng]?></a></li>
                            <li class="pureCssMenui"><a class="pureCssMenui" href="?page=players&show=alive"><?php echo $lng_alive[$ses_lng]?></a></li>
                            <li class="pureCssMenui"><a class="pureCssMenui" href="?page=players&show=dead"><?php echo $lng_dead[$ses_lng]?></a></li>
                    </ul>
                    </li>
                    <li class="pureCssMenui"><a class="pureCssMenui" href="#"><span><?php echo $lng_vehicles[$ses_lng]?></span><![if gt IE 6]></a><![endif]><!--[if lte IE 6]><table><tr><td><![endif]-->
                        <ul class="pureCssMenum">
                                <li class="pureCssMenui"><a class="pureCssMenui" href="?page=vehicles&show=in_game"><?php echo $lng_ingame[$ses_lng]?></a></li>
                                <li class="pureCssMenui"><a class="pureCssMenui" href="?page=vehicles&show=spawns"><?php echo $lng_spawns[$ses_lng]?></a></li>
                        </ul>
                    <!--[if lte IE 6]></td></tr></table></a><![endif]-->
                    </li>
                   <li class="pureCssMenui"><a class="pureCssMenui" href="?page=deployableitems"><?php echo $lng_deployable[$ses_lng]?></a></li>
            </ul>
	<!--[if lte IE 6]></td></tr></table></a><![endif]--></li>
	<li class="pureCssMenui0"><a class="pureCssMenui0" target="_blank" href="#"><span><?php echo $lng_map[$ses_lng]?></span></a>
             <ul class="pureCssMenum">
                <li class="pureCssMenui"><a class="pureCssMenui" href="#"><span><?php echo $lng_survivor[$ses_lng]?></span><![if gt IE 6]></a><![endif]>
                    <ul class="pureCssMenum">
                                    <li class="pureCssMenui"><a class="pureCssMenui" href="?page=map_online&type=rcon_online"><?php echo $lng_online[$ses_lng]?></a></li>
                                    <li class="pureCssMenui"><a class="pureCssMenui" href="?page=map_static&type=players_all"><?php echo $lng_all[$ses_lng]?></a></li>
                                    <li class="pureCssMenui"><a class="pureCssMenui" href="?page=map_static&type=players_alive"><?php echo $lng_alive[$ses_lng]?></a></li>
                                    <li class="pureCssMenui"><a class="pureCssMenui" href="?page=map_static&type=players_dead"><?php echo $lng_dead[$ses_lng]?></a></li>
                                    
                    </ul>
                </li>
                 <li class="pureCssMenui"><a class="pureCssMenui" href="#"><span><?php echo $lng_vehicles[$ses_lng]?></span><![if gt IE 6]></a><![endif]><!--[if lte IE 6]><table><tr><td><![endif]-->
                        <ul class="pureCssMenum">
                                <li class="pureCssMenui"><a class="pureCssMenui" href="?page=map_static&type=vehicle_spawns"><?php echo $lng_spawns[$ses_lng]?></a></li>
                        </ul>
                    <!--[if lte IE 6]></td></tr></table></a><![endif]-->
                 </li>
                 <li class="pureCssMenui"><a class="pureCssMenui" href="?page=map_static&type=deployable"><?php echo $lng_deployable[$ses_lng]?></a></li>
             </ul>
        
        </li>
	<li class="pureCssMenui0"><a class="pureCssMenui0" href="logout.php"><?php echo $lng_logout[$ses_lng]?></a></li>
</ul>
<!-- End  MENU -->
