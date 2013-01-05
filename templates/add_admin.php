<?php

/*  author  skynetdev
 *  email   skynetdev3@gmail.com
 *  
 */
?>
<h1><?php echo $title?></h1>

<div class="add_admin">
<form method="Post" action="?page=admins">
<table   border="0" width="25%" cellpadding="0" cellspacing="0">
      <tr>
        <td align="right"> Login:</td><td> <input required="true" name="login"></td>
      </tr>
      <tr>
        <td align="right"> Password:</td><td>  <input required="true" name="password" type="password"></td>
      </tr>
      <tr>
        <td align="right"> GUID:(not implemented)</td><td>  <input disabled name="guid" type="text" size="32"></td>
      </tr>           
      <tr>
        <td align="center" collspawn="2">Privileges:</td>
      </tr>
      <tr>
        <th collspawn="2" align="right">Control: <input type="checkbox" checked="checked" value="control" name="user_permissions[]"> </th>
      </tr>
      <tr>        
        <th collspawn="2" align="right">Admins:  <input type="checkbox" checked="checked" value="admins" name="user_permissions[]"></th>
      </tr>
      <tr>        
        <th collspawn="2" align="right">Chat:  <input type="checkbox" checked="checked" value="chat" name="user_permissions[]"></th>
      </tr>      
      <tr>        
        <th collspawn="2" align="right">Entities: <input type="checkbox" checked="checked" value="entities" name="user_permissions[]"> </th>
      </tr>
      <tr>        
        <th collspawn="2" align="right">Map: <input type="checkbox" checked="checked" value="map" name="user_permissions[]"> </th>
        
        
    
</table>
    <br>
 <input id="x" type="submit" name="add_admin" value="Add new Admin">
</form>
</div>

