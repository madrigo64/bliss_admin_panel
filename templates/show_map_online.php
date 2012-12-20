<?php

/*  author  skynetdev
 *  email   skynetdev3@gmail.com
 *  
 */
     //check security permissions
if(!issecurity(true, true, 'map')) {
    echo "<h2>Access Denied!!</h2>";
    exit;
}

$title='Map';
?>


<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
<style type="text/css">
  html { height: 100% }
  body { height: 100%; margin: 0px; padding: 0px }
</style>
<script type="text/javascript"
    src="https://maps.google.com/maps/api/js?sensor=false">
</script>


<script type="text/javascript">
    
var markersArray = [];
var mapNam = '<?php echo getMapNameForGoogleMap()?>';
 <?php $map=getMapParameters(getMapName());
 
 if($map['supported']==false):
  ?>
alert('map is not supported!');

<?php endif;?>
function DayzMapType(theme, backgroundColor) {


            this.name = this._theme = theme;
            this._backgroundColor = backgroundColor;
}



DayzMapType.prototype.tileSize = new google.maps.Size(256,256);
DayzMapType.prototype.minZoom = 2;
DayzMapType.prototype.maxZoom = <?php echo DESABLE_ZOOM_5_6?'4':'6';?>;


DayzMapType.prototype.getTile = function(coord, zoom, ownerDocument) {
    var tilesCount = Math.pow(2, zoom);
    if (coord.x >= tilesCount || coord.x < 0 || coord.y >= tilesCount || coord.y < 0) {
        var div = ownerDocument.createElement('div');
        div.style.width = this.tileSize.width + 'px';
        div.style.height = this.tileSize.height + 'px';
        div.style.backgroundColor = this._backgroundColor;
        return div;
    }
  
  var img = ownerDocument.createElement('IMG');
  var workfolder = '<?php echo str_replace('/index.php', '', $_SERVER['SCRIPT_NAME'])?>/maps/';
  img.width = this.tileSize.width;
  img.height = this.tileSize.height;
  
  img.src = workfolder+this._theme + '/' + zoom + '/' + coord.x + '_' + coord.y + '.png';

  if(<?php echo GET_MAP_FOR_ZOOM_5AND6_LOCAL==false?'false && ':'true && '?> (zoom == 5 || zoom==6) ) {
   var map_folder ='';   
   if(this._theme == 'chernarus') map_folder ='/'; else map_folder = '/'+this._theme+'/';
       
       workfolder = 'http://static.dayzdb.com/tiles'+map_folder;
   
    
    img.src = workfolder + zoom + '/' + coord.x + '_' + coord.y + '.png';
    //download map to folder
    
    $.post("actions/map_download.php", { url: img.src, map:  this._theme } );
  }
  return img;
};





DayzMapType.prototype.name = "<?php echo getMapNameForGoogleMap()?>";
DayzMapType.prototype.alt = "Tile Coordinate Map Type";




var map;
var chicago = new google.maps.LatLng(0,0);
var coordinateMapType = new DayzMapType('<?php echo getMapNameForGoogleMap()?>', '#4E4E4E');


function radiansToDegrees(rad) { return rad / (Math.PI / 180);   }
                  
                  
infowindow = new google.maps.InfoWindow({
                content: "loading..."
        });                  


   function showGetResult(url, players, vehicles, deployable)
    {
         var result = null;
         $('#ajax_loader_panel').show();
         $.ajax({
            url: url,
            type: 'post',
            dataType: 'html',
            data: {players: players, vehicles: vehicles, deployables: deployable},
            async: false,
            success: function(data) {
                result = data;
            },
            complete : function() { $('#ajax_loader_panel').hide(2500); }
         });
         return result;
    }



function  showMarkers(markers,map){
    
   
    var pixelOrigin_ = new google.maps.Point(<?php echo $map['pixelOrigin_']?>);
    var pixelsPerLonDegree_ = <?php echo $map['pixelsPerLonDegree_']?>;
    var pixelsPerLonRadian_ = <?php echo $map['pixelsPerLonRadian_']?>;
         for (i = 0; i < markers.length; i++) { 

            var lng = ((markers[i][2]/64) - pixelOrigin_.x) / pixelsPerLonDegree_;
            var latRadians = (((markers[i][3])/64) - pixelOrigin_.y) / pixelsPerLonRadian_;
            var lat = radiansToDegrees(2 * Math.atan(Math.exp(latRadians)) - Math.PI / 2);

            marker = new google.maps.Marker({
                            position: new google.maps.LatLng(lat, lng),
                            map: map,
                            title: markers[i][0],
                            clickable: true,
                            icon: markers[i][5],
                            zIndex:  markers[i][4]
                            });
            marker.setDraggable(true);
            markersArray.push(marker);


            google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {
                            infowindow.setContent(markers[i][1]);
                            infowindow.open(map, marker);
                    }
            })(marker, i));
    }                   

    return markersArray;

    
    
}


 function show_vehicles(){
    $('#show_vehicles').change(function() {
        updatemarkers();
    });
    

    if($('#show_vehicles:checked').val())
       $('#vehicles').html('<font color=green>Vehicles</font>'); 
       else 
       $('#vehicles').html('<font color=red>Vehicles</font>');

};   



function show_deployable(){
    
     $('#show_deployable').change(function() {
        updatemarkers();
    });
    

    if($('#show_deployable:checked').val())
       $('#deployable').html('<font color=green>Deployable</font>'); 
       else 
       $('#deployable').html('<font color=red>Deployable</font>');   
    
    
    
    
    
}


//initializion
$(document).ready(function() {



    var mapOptions = {
              zoom: 2,
              center: new google.maps.LatLng(22.371767300523047, -10),
              mapTypeControl: false,
              streetViewControl: false,   
              mapTypeControlOptions: {
                mapTypeIds: ['coordinate', google.maps.MapTypeId.ROADMAP],
                style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
              }
    };
  



    map = new google.maps.Map(document.getElementById("map_canvas"),
        mapOptions);

    // Now attach the coordinate map type to the map's registry
    map.mapTypes.set('coordinate',coordinateMapType);

    // We can now set the map to use the 'coordinate' map type
    map.setMapTypeId('coordinate');
    
    var markers = eval(showGetResult('actions/getMapMarkers.php','rcon_online','vehicles'));
    var markersArray =  showMarkers(markers,map);
    
   updatemarkers = function(){
        var vehicles ='';
        var deployable = '';
        var mode_online = 'rcon_online';
        for (i in markersArray) {
              markersArray[i].setMap(null);
         } 
        if($("#show_vehicles:checked").val())  vehicles = 'vehicles'; 
        if($("#show_deployable:checked").val())  deployable = 'deployable';
        if($("#mode_online option:selected").val() == 'alt_online') 
            mode_online = 'alt_online';
        else 
            mode_online = 'rcon_online';
        markers = eval(showGetResult('actions/getMapMarkers.php',mode_online,vehicles,deployable));
       // console.log("markers="+markers);
        markersArray =  showMarkers(markers,map); 
     
        
 }   
 
 //put markers
 //updatemarkers();
 var my_interval;
  set_interval= function(delay_sec)
 {
       delay_sec=delay_sec*1;
       if(isNaN(delay_sec)) delay_sec=30; // if not correct set to default 30 sec
       if(delay_sec < 20 ) { alert('minimum 20 sec!'); $('#interval_sec').val('20'); delay_sec=20; }
        
       delay_sec = delay_sec * 1000;
       clearInterval(my_interval); //first clear my interval
       if($('#set_interval:checked').val()) {
        $('#on_off_timer').html('<font color=green>On</font>');    
        my_interval = setInterval(updatemarkers,delay_sec);
      }
      else{
       clearInterval(my_interval);
       $('#on_off_timer').html('<font color=red>Off</font>');   
      }
     
     
     
 }
 

set_interval($('#interval_sec').val());


    
});







</script>
</head>
<body>
<h1><?php echo $title.' '.getMapName()?> </h1>   
<br>
<br>
<div class="clean-red"></div>
<div class="clean-gray">
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tbody>
            <tr>
                <td valign="top" width ="20px">
                    <img id="ajax_loader_panel" src="images/main/ajax-loader2.gif">
                </td>
                <td >
            
                <div id="ajax_control">
                    Update data every  <input onchange="set_interval(this.value); if(!isNaN(this.value)) alert('ok timer set to '+this.value+' sec'); else { alert('error timer set to 30 sec'); this.value='30'; set_interval(this.value);} " type="number" size="3" min="10" id="interval_sec" value="30" maxlength="3"> sec &nbsp;
                    Timer:&nbsp;<b id="on_off_timer"><font color=green>On</font></b> <input checked="checked" type="checkbox" name="interval_onoff" onclick="set_interval(getElementById('interval_sec').value)" id="set_interval">&nbsp;&nbsp;&nbsp;
                    Players
                    <select id="mode_online" onchange="alert('changed');updatemarkers()">
                        <option value="rcon_online">Rcon Online</option>
                        <option value="alt_online">Online last <?php echo ALTERNATIVE_ONLINE_MINUTES?> min</option>    
                    </select>
                  
                       <b id="vehicles"><font color=green>Vehicles</font></b> <input  title="show with vehicles" style="cursor: help" type="checkbox" checked name="vehicles" onclick="show_vehicles()" id="show_vehicles">
                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;    
                      <b id="deployable"><font color=red>Deployable</font></b> <input  title="show with Deployable items" style="cursor: help" type="checkbox"  name="deployable" onclick="show_deployable()" id="show_deployable">
                </div> 
                </td>
        </tr>
        </tbody>
    </table>    
    
    
</div>    

 


  <div id="map_canvas"></div>
 
</body>
</html>