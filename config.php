<?php
session_start();

$languages = array('en' => 'English','ru' => 'Russian');

if(isset($_GET['lang']) && array_key_exists($_GET['lang'],$languages)) {
    
    $_SESSION['language'] = $_GET['lang'];

}
if(!isset($_SESSION['language'])) $ses_lng= $_SESSION['language'] = 'en';
else 
    $ses_lng= $_SESSION['language'];

include 'languages.php'; 
defined('DS') ? null : define('DS',DIRECTORY_SEPARATOR);



include_once  'adm_config.php';

if(!defined('INSTANCE')) {
    
    echo "
     <meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" />   
    <font color=red>Warning config file not found!! 
    In English.    
    <br> Rename file adm_config.incEng.php or adm_config.incRus.php to adm_config.php
    <br>  and endit it!
    <br>
    <br>
    In Russian.    
    <br> Переименуйте файл adm_config.incEng.php или  adm_config.incRus.php в adm_config.php
    <br>  и отредактируйте его!
<font>"; 
exit;}


if(!@mysql_connect(SERVERIP, USERNAME, PASSWORD)) { echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\" /><font color=red size=6>".$lng_mysql_err_connect[$ses_lng]."</font> ";  exit;}

if(isset($_SESSION['bdname'])) $dbSessionName=$_SESSION['bdname']; else  {
    
$dbNames = explode(',', DBNAME);
$dbSessionName = $_SESSION['bdname'] = $dbNames[0];
}

if(isset($_GET['dbName'])&& !empty($_GET['dbName'])){
    $dbSessionName = $_SESSION['bdname'] = $_GET['dbName'];
    
}

if(!mysql_select_db($dbSessionName)) {
    $dbNames = explode(',', DBNAME);
    echo "<font color=red>Wrong DbName $dbSessionName! dbName will be reset to value $dbNames[0] Mysql Error Message: ". mysql_error().'</font>'; 
    
    $dbSessionName = $_SESSION['bdname'] = $dbNames[0];
    mysql_select_db($dbSessionName) or die(mysql_error());
    };
mysql_query("SET NAMES 'utf8'");

?>
