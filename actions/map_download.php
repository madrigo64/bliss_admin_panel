<?php

/*  author  skynetdev
 *  email   skynetdev3@gmail.com
 *  Downlaod pieces of map from dayzdb.com and save on hard disk 
 */ 

if(isset($_POST['url']) && $_POST['url']) {

    $url = $_POST['url'];
    $map_name = $_POST['map'];


     //examle http://static.dayzdb.com/tiles/6/0_42.png
    $url = str_replace('htt://', '', $url);
    $url_array = explode('/', $url);
    $url_array = array_reverse($url_array);

    $zoom = $url_array[1];
    $file_name = $url_array[0];
    $new_file = '../maps/'.$map_name.'/'.$zoom.'/'.$file_name;
    if(!file_exists($new_file)) {


        if(!is_dir('../maps/'.$map_name)) mkdir('../maps/'.$map_name);
        if(!is_dir('../maps/'.$map_name.'/'.$zoom)) mkdir('../maps/'.$map_name.'/'.$zoom);


        $content = file_get_contents($url);
        $fd = fopen($new_file,'w');
        fwrite($fd, $content);
        fclose($fd);
        echo "file $new_file downloaded";
    }
    else echo "file $new_file exist ignore";
} 




?>
