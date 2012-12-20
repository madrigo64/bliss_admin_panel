<?php

/*  author  skynetdev
 *  email   skynetdev3@gmail.com
 *  
 */




require_once 'modules/GameQ.php';



// GameQ Server define, fixed by Crosire to allow multiple instances
$servers = array(
    'dayzserver' => array('armedassault2', SERVERIP, SERVERPORT)
);



// Call the class, and add your servers.
$gq = new GameQ();
$gq->addServers($servers);
    
// You can optionally specify some settings
$gq->setOption('timeout', 200);

// You can optionally specify some output filters,
// these will be applied to the results obtained.
$gq->setFilter('normalise');
$gq->setFilter('sortplayers', 'gq_ping');

// Send requests, and parse the data
$oresults = $gq->requestData();
//print_r($oresults);

// Some functions to print the results
function print_results($oresults) {

    foreach ($oresults as $id => $data) {
        //printf("<h2>%s</h2>\n", $id);		
        print_table($data);
    }

}

function print_table($data) {  
$server_status = getProcessStatus(SERVEREXE);
	if (!$data['gq_online']) {
                if($server_status) {
		echo "<h2>Server is On <br> Status can't be displayed while reporting_ip is not 127.0.0.1 in server config</h2>";
		return;
                }
                if(!$server_status) {
                    echo "<h2><font color=red>Server is off</font></h2>";
                    
                }
	}
        else "NEtu!!Suka!";
        
        
        
        if($server_status):
	?>
	<!--  start table-content  -->
            <h2>Server name:</h2>
			<h2><a href='#'><?php echo $data['gq_hostname']; ?></a></h2>
			<h2>Address:</h2><a href='#'><h3><?php echo $data['gq_address']; ?>:<?php echo $data['gq_port']; ?></a></h3>
			<h2>Mods:</h2><a href='#'><h3><?php echo $data['gq_mod']; ?></a></h3>
			<h2>Max players:</h2><a href='#'><h3><?php echo $data['gq_maxplayers']; ?></a></h3>
			<h2>Online players:</h2><a href='#'><h3><?php echo $data['gq_numplayers']; ?></a></h3>	
		<!--  end table-content  -->
	<?php
        endif;
}


print_results($oresults);


?>
