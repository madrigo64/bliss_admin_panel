<?php

/*  author  skynetdev
 *  email   skynetdev3@gmail.com
 *  
 */
?>
<h1><?php echo $title?> <?php if(isset($row)) echo $row['login']?></h1>

<div class="add_admin">
<form method="Post" action="?page=admins">
<input type="hidden" name="id" value="<?php echo $id?>">
<table   border="0" width="25%" cellpadding="0" cellspacing="0">
      <tr>
        <td align="right"> Login:</td><td> <?php if(isset($row)) echo $row['login']?></td>
      </tr>
      <tr>
        <td align="right"> Password:</td><td>  <input  name="password" type="password" ></td>
      </tr>
      <tr>
        <td align="right"> GUID:</td><td>  <input  name="guid" type="text" size="32" value="<?php if(isset($row)) echo $row['guid']?>"></td>
      </tr>      
      <tr>
        <td align="center" collspawn="2">Permissions:</td>
      </tr>
      <tr>
          <th collspawn="2" align="right">Control: <input type="checkbox" <?php if(isset($permissions)) echo in_array ('control', $permissions)?'checked':''?>  value="control" name="user_permissions[]"> </th>
      </tr>
      <tr>        
        <th collspawn="2" align="right">Admins:  <input type="checkbox" <?php if(isset($permissions)) echo in_array ('admins', $permissions)?'checked':''?>  value="admins" name="user_permissions[]"></th>
      </tr>
      <tr>        
        <th collspawn="2" align="right">Chat: <input type="checkbox" <?php if(isset($permissions)) echo in_array ('chat', $permissions)?'checked':''?>   value="chat" name="user_permissions[]"> </th>
      </tr>        
      <tr>        
        <th collspawn="2" align="right">Entities: <input type="checkbox" <?php if(isset($permissions)) echo in_array ('entities', $permissions)?'checked':''?>  value="entities" name="user_permissions[]"> </th>
      </tr>
      <tr>        
        <th collspawn="2" align="right">Map: <input type="checkbox" <?php if(isset($permissions)) echo in_array ('map', $permissions)?'checked':''?>   value="map" name="user_permissions[]"> </th>
      </tr>  
        
    
</table>
    <br>
 <input id="x" type="submit" name="edit_admin" value="Edit Admin">
</form>
</div>


