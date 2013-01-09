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
if(!isset($_REQUEST['type'])) {echo "<h1>Error!</h1>"; exit;}

$title='Map';

$id ='';
if(isset($_REQUEST['id'])) $id =  $_REQUEST['id']*1;

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
 <?php $map=getMapParameters(getMapName());?>

function DayzMapType(theme, backgroundColor) {

            this.name = this._theme = theme;
            this._backgroundColor = backgroundColor;
}



DayzMapType.prototype.tileSize = new google.maps.Size(256,256);
DayzMapType.prototype.minZoom = 2;
DayzMapType.prototype.maxZoom = <?php echo DISABLE_ZOOM_5_6==true?(getMapNameForGoogleMap()=='taviana'?'5':'4'):(getMapNameForGoogleMap()=='taviana'?'7':'6');?>;


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

 if(<?php echo GET_MAP_FOR_ZOOM_5AND6_LOCAL==false?'false && ':'true && '?> (zoom == 5 || zoom==6 || zoom==7) ) {
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


   function showGetResult(url, type, id)
    {
         var result = null;
         $('#ajax_loader_panel').show();
         $.ajax({
            url: url,
            type: 'post',
            dataType: 'html',
            data: {type: type, id:id},
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
    
    var markers = eval(showGetResult('actions/getMapMarkers.php','<?php echo $_GET['type']?>','<?php echo $id?>'));
    var markersArray =  showMarkers(markers,map);
    




    
});







</script>
</head>
<body>
    <h1><?php echo $title.' '.getMapName().' '.$_REQUEST['type']?></h1>    
<br>
<br>
<div class="clean-red"></div>
  <div id="map_canvas"></div>
 
</body>
</html>