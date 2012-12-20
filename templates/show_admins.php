<?php

/*  author  skynetdev
 *  email   skynetdev3@gmail.com
 *  
 */
?>
<h1><?php echo $title?></h1>
 <div class="add_admin_lnk"><a title="Add New Admin" href="?page=admins&action=show_add"><img width="100" src="images/main/admin_add.png"></a></div>
<!--  start message-green -->
<div id="msg">
  <?php if(isset($info)) {?>         
        <div id="message-green">
            <table border="0" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                        <td class="green-left"><? echo $info; ?></td>
                </tr>
            </table>
        </div>

    <?php }
         if(isset($errort) && $errort == true) {?>  

        <div id="message-red">
            <table border="0" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                        <td class="red-left"><?php if(isset($errort)) echo $errort?></td>
                </tr>
            </table>
        </div>
   <?php } ?> 
</div>
<!--  end message-green -->

<div class="showadmins">
        <!--  start table-content  -->
        <div id="table-content">
                <table    border="0" width="75%" cellpadding="0" cellspacing="0">
                <tr height="30px">
                        <th width="15%" colspan="2">Actions</th>
                        <th  width="15%">GUID</th>
                        <th  width="20%">Username</th>
                        <th  width="35%">Permissions</th>
                        <th  width="30%">Last access</th>
                </tr>
                <?php

                        $users="";
                       $nums =  mysql_num_rows($result); 
                while ($row=mysql_fetch_array($result)) { 

                    ?>
                        <tr align="center">
                            <td>
                                <?php if( ($row['id'] == 1 && $_SESSION['login']=='superadmin') or $row['id']!=1) {?>  
                                <a  href="?page=admins&action=show_edit&id=<?php echo $row['id'] ?>"><img width="20" alt="edit admin" title="edit" src="images/main/edit.png"> </a>
                                  <?php }else  {?>  
                              <?php echo "Super Admin"; } ?>  
                            </td>
                            <td>
                               <?php if($row['id']!=1 ) {?>  
                                <a onclick="if(!confirm('Are you sure?')) return false;" href="?page=admins&action=del&id=<?php echo $row['id'] ?>"><img title="delete admin" alt="delete" src="images/main/delete.gif"> </a>
                              <?php }else  {?>  
                              <?php echo "Super Admin"; } ?>  
                            </td>
                            <td> <?php echo $row['guid'] ?></td>
                            <td><?php echo $row['login'] ?></td>
                            <td><?php echo $row['permissions'] ?></td>
                            <td><?php echo $row['lastlogin'] ?></td>
                        </tr>
                   <?php    
                }

                ?>				
                </table>

                </div>

        <!--  end table-content  -->
</div>
<br>

<div class="admins_logs">
    <h2>Admins Logs</h2>
    <table  class="main_table" border="1"  width="25%" cellpadding="0" cellspacing="0">
        <tr>
            <th>Created</th>
            <th>Admin</th>
            <th>Action</th>
        </tr>
  <?php

while ($row_log1=mysql_fetch_array($admins_logs)):
  ?>      
        <tr>
            <td><?php echo $row_log1['created_at'];?></td>
            <td><?php echo $row_log1['admin'];?></td>
            <td><?php echo $row_log1['action'];?></td>
        </tr>                        
 <?php endwhile; ?>       
    </table>

</div>
<br>
<div class="players_logs">
    <h2>Players Logs</h2>
    <table class="main_table"  border="1"  width="45%" cellpadding="0" cellspacing="0">
        <tr>
            <th>Created</th>
            <th>Action</th>
            <th>Player</th>
            <th>Ip</th>
            <th>Reason</th>
            <th>Admin Name</th>
        </tr>
  <?php

while ($row_log2=mysql_fetch_array($players_logs)):
  ?>      
        <tr>
            <td><?php echo $row_log2['created_at'];?></td>
            <td><?php echo $row_log2['action'];?></td>
            <td><?php echo $row_log2['player_name'];?></td>
            <td><?php echo $row_log2['player_ip'];?></td>
            <td><?php echo $row_log2['reason'];?></td>
            <td><?php echo $row_log2['admin_name_session'];?></td>
           
        </tr>                        
 <?php endwhile; ?>       
    </table>

</div>





