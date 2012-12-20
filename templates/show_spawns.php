<?php

/*  author  skynetdev
 *  email   skynetdev3@gmail.com
 *  
 */
?>
<h1><?php echo $title;?></h1>

<h2>
    Total Vehicles: <?php echo $total_vehicles?>
</h2>
<br>
<table border="0" width="60%" cellpadding="0" cellspacing="0" class="objects-table">
                <tr>
                    <th  width="1%">World Vehicle ID</th>
                    <th  width="1%">Position</th>
                    <th  width="3%">Classname</th>
                    <th  width="5%">Image</th>
                    <th  width="4%">Chance</th>
                    <th class="object_inventory" width="25%">Inventory</th>
                    <th  width="25%">Parts for damage</th>                    
                  </tr>
                <?php echo $vehicle_table_rows; ?>				
</table>