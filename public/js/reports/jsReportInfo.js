var map = null;
var infoWindowRep;
var bounds;
var markers = [];

$( document ).ready(function() {    
   initMapToDraw();

  $('.popup-gallery').magnificPopup({
    delegate: 'a',
    type: 'image',
    tLoading: 'Loading image #%curr%...',
    mainClass: 'mfp-img-mobile',
    gallery: {
      enabled: true,
      navigateByImgClick: true,
      preload: [0,1] // Will preload 0 - before current, and 1 after the current image
    },
    image: {
      tError: '<a href="%url%">The image #%curr%</a> could not be loaded.',
      titleSrc: function(item) {
        return item.el.attr('title');
      }
    },
    zoom: {
      enabled: true,
      duration: 300 // don't foget to change the duration also in CSS
    }
  });

  $('.thumbnails').magnificPopup({
    type: 'image',
    closeOnContentClick: true,
    closeBtnInside: false,
    fixedContentPos: true,
    mainClass: 'mfp-no-margins mfp-with-zoom', // class to remove default margin from left and right side
    image: {
      verticalFit: true
    },
    gallery: {
      enabled: true,
      navigateByImgClick: true,
      preload: [0,1] // Will preload 0 - before current, and 1 after the current image
    },    
    zoom: {
      enabled: true,
      duration: 300 // don't foget to change the duration also in CSS
    }
  });

    var iHeight = $("#tableResult").height();

    $('#rowGallery').slimscroll({
        position: 'right',
        size: '4px',
        color: themeprimary,
        height: iHeight,
    });   
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