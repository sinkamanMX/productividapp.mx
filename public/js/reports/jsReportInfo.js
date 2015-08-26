var map = null;
var infoWindowRep;
var bounds;
var markers = [];

$( document ).ready(function() {    
   initMapToDraw()
});  

function initMapToDraw(){
    infoWindowRep = new google.maps.InfoWindow;
    var mapOptions = {
      zoom: 5,
      center: new google.maps.LatLng(24.52713, -104.41406),
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      disableDefaultUI: true
    };
  map   = new google.maps.Map(document.getElementById('map'),mapOptions);  
  bounds = new google.maps.LatLngBounds();
  addMaker()
}

function addMaker(){
    var latitude  = parseFloat($("#inputLatitud").val());
    var longitude = parseFloat($("#inputLongitud").val());

    var position = new google.maps.LatLng(latitude, longitude);

    marker1 = new google.maps.Marker({
        map: map,
        position: position
    }); 
    markers.push(marker1);

    map.setZoom(16);
    map.setCenter(position); 
    map.panTo(position);        
}