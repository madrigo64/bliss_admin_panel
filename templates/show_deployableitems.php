<?php

/*  author  skynetdev
 *  email   skynetdev3@gmail.com
 *  
 */
?>
<h1><?php echo $title;?></h1>

<h2>
    Total Deployable Items: <?php echo $total_deployable?>
</h2>
<br>
<table border="0" width="100%" cellpadding="0" cellspacing="0" class="objects-table">
                <tr>
                    <th  width="5%">ID</th>
                    <th  width="3%">Owner</th>
                    <th  width="3%">Position</th>
                    <th  width="5%">Classname</th>
                    <th class="object_inventory" width="25%">Inventory Prevew</th>
                  </tr>
                <?php echo $deployable_table_rows; ?>				
</table>