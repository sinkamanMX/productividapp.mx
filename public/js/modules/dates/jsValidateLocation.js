var map = null;
var geocoder      = new google.maps.Geocoder();
var infoWindowRep;
var infoLocation;
var bounds;
var markers = [];

$().ready(function(){
    $("#inputAddress").select2({
        placeholder: "Seleccione un tipo",
        allowClear: true
    }).on("change", function(e) {
          validateOption(e.val);
    });

    $('#formDbman').bootstrapValidator({
        live: 'true',
        excluded: [':hidden'],
        resetFormData: true,
        feedbackIcons: {
            valid: 'icon-checkmark-circle',
            invalid: 'icon-cancel-circle',
            validating: 'icon-spinner7'
        },
        fields: {            
            inputAddress: {
                validators: {
                    callback: {
                        message: 'Debe de seleccionar una opción',
                        callback: function(value, validator, $field) {
                            var bRespuesta = false;
                            if(value>-1 || value=="na"){
                                bRespuesta = true;
                            }
                            return bRespuesta;
                        }
                    }
                }
            },
            inputCalle: {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    },
                }
            },
            inputNext: {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    },
                }
            },
            inputColonia: {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    },
                }
            },    
            inputMun: {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    },
                }
            },  
            inputEdo: {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    },
                }
            },  
            inputRefs: {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    },
                }
            },  
            /*
            inputCp: {
                validators: {
                    notEmpty: {
                        message: 'Campo requerido'
                    },
                    numeric: {
                        message: 'Este campo acepta solo números'
                    },
                }
            },*/
            inputLatitud: {
                validators: {
                    numeric: {
                        message: 'Este campo acepta solo números'
                    },
                }
            },
            inputLongitud: {
                validators: {                
                    numeric: {
                        message: 'Este campo acepta solo números'
                    },
                }
            },                       
        }        
    }).on('success.form.fv', function(e) {
        $('.loading-container').removeClass('loading-inactive');
            e.preventDefault();
            var $form = $(e.target);
            var fv = $form.data('FormDataGral');
                fv.defaultSubmit();
    }).on('err.field.fv', function(e, data) {
           // data.fv.disableSubmitButtons(false);
        })
    .on('success.field.fv', function(e, data) {
        //data.fv.disableSubmitButtons(false);
    });

    initMapToDraw();

    if($("#inputLatitud").val()!="" && $("#inputLongitud").val()!=""){
        centerOnMap(0);
        $('.centerButton').show('slow');
    }
});

function initMapToDraw(){
    geocoder = new google.maps.Geocoder();
    infoWindowRep = new google.maps.InfoWindow;
    var mapOptions = {
      zoom: 5,
      center: new google.maps.LatLng(24.52713, -104.41406),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
  map   = new google.maps.Map(document.getElementById('map'),mapOptions);  
  bounds = new google.maps.LatLngBounds();
}

function validateOption(inputValue){  
    $('.formInput').attr('readonly', false);    
    $('.centerButton').show('slow');
    $("#inputEdo").val("");
    $("#inputMun").val("");
    $("#inputColonia").val("");
    $("#inputCalle").val("");
    $("#inputCp").val("");
    $("#inputNint").val("");
    $("#inputNext").val("");
    $("#inputRefs").val("");
    $("#inputLatitud").val("");
    $("#inputLongitud").val("");

    if(inputValue>-1){
        $('.optionsShow').show('slow');
        var options = aDataAddress[inputValue];
        var aDatos = options.split(',');   
        $("#inputEdo").val(aDatos[0]);
        $("#inputMun").val(aDatos[1]);
        $("#inputColonia").val(aDatos[2]);
        $("#inputCalle").val(aDatos[3]);
        $("#inputCp").val(aDatos[4]);
        $("#inputNint").val(aDatos[5]);
        $("#inputNext").val(aDatos[6]);        
        $("#inputRefs").val(aDatos[7]);
        $("#inputLatitud").val(aDatos[8]);
        $("#inputLongitud").val(aDatos[9]);
        centerOnMap(0);
    }else if(inputValue=='na'){
        $('.optionsShow').show('slow');
        $('.formInput').attr('readonly', false);  
        removeMap();      
    }
    $('#formDbman').bootstrapValidator('updateStatus', 'inputCalle', 'NOT_VALIDATED');
    $('#formDbman').bootstrapValidator('updateStatus', 'inputColonia', 'NOT_VALIDATED');
    $('#formDbman').bootstrapValidator('updateStatus', 'inputEdo', 'NOT_VALIDATED');
    $('#formDbman').bootstrapValidator('updateStatus', 'inputMun', 'NOT_VALIDATED');
    /*$('#formDbman').bootstrapValidator('updateStatus', 'inputCp', 'NOT_VALIDATED');*/
    $('#formDbman').bootstrapValidator('updateStatus', 'inputNext', 'NOT_VALIDATED');
    $('#formDbman').bootstrapValidator('updateStatus', 'inputLatitud', 'NOT_VALIDATED');
    $('#formDbman').bootstrapValidator('updateStatus', 'inputLongitud', 'NOT_VALIDATED');
    $('#formDbman').bootstrapValidator('updateStatus', 'inputRefs', 'NOT_VALIDATED');
    $('#formDbman').bootstrapValidator('updateStatus', 'inputAddress', 'NOT_VALIDATED');
}

function centerOnMap(iOption){
    var iLatitud    = $("#inputLatitud").val();
    var iLongitud   = $("#inputLongitud").val();
        marker      = null;

    if(iLatitud!="null" && iLongitud != "0.00000000" && iLongitud !=""){        
        addMaker(iLatitud,iLongitud);
        if(iOption==1){
            codeLatLng();
        }
    }     
    
}

function addMaker(iLatitud,iLongitud){
    removeMap();
    var latitude  = parseFloat(iLatitud);
    var longitude = parseFloat(iLongitud);

    var position = new google.maps.LatLng(latitude, longitude);

    marker1 = new google.maps.Marker({
        map: map,
        position: position,
        draggable:true,
        animation: google.maps.Animation.DROP,
    }); 
    markers.push(marker1);
    google.maps.event.addListener(marker1, 'click', toggleBounce);  

    google.maps.event.addListener(marker1, "dragend", function(event) {
        $("#inputLatitud").val(event.latLng.lat());
        $("#inputLongitud").val(event.latLng.lng());
        codeLatLng();
    }); 

    map.setZoom(18);
    map.setCenter(position); 
    map.panTo(position);        
}

function removeMap(){
    if(markers || markers.length>-1){
        for (var i = 0; i < markers.length; i++) {
              markers[i].setMap(null);
        }   
        markers = [];
    }
}

function toggleBounce() {
  if (marker.getAnimation() != null) {
    marker.setAnimation(null);
  } else {
    marker.setAnimation(google.maps.Animation.BOUNCE);
  }
}

function codeLatLng(){
  var lat = parseFloat($("#inputLatitud").val());
  var lng = parseFloat($("#inputLongitud").val());
  var latlng = new google.maps.LatLng(lat, lng);

  geocoder.geocode({'latLng': latlng}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
        console.log(results[0].address_components)
        var sNumber = extractFromAdress(results[0].address_components, "street_number");                 
        var sCalle  = extractFromAdress(results[0].address_components, "route");
        var sCp     = extractFromAdress(results[0].address_components, "postal_code");
        var sEstado = extractFromAdress(results[0].address_components, "administrative_area_level_1");
        var sMun    = extractFromAdress(results[0].address_components, "locality");
        var sCol    = extractFromAdress(results[0].address_components, "sublocality_level_1");

        $("#inputCp").val(sCp);
        $("#inputEdo").val(sEstado);
        $("#inputMun").val(sMun);
        $("#inputColonia").val(sCol);
        $("#inputCalle").val(sCalle);
    }else{
        var shortCutFunction= 'error';        
        var title           = "Atención!"
        var msg             = 'No fue posible encontrar la dirección';
        toastr.options = {
          "closeButton": true,
          "debug": false,
          "positionClass": "toast-top-right",
          "showDuration": "1000",
          "hideDuration": "1000",
          "timeOut": "5000",
          "extendedTimeOut": "1000",
          "showEasing": "swing",
          "hideEasing": "linear",
          "showMethod": "fadeIn",
          "hideMethod": "fadeOut"
        }
        var $toast = toastr[shortCutFunction](msg, title);    
    }
  });
}

function extractFromAdress(components, type){
    for (var i=0; i<components.length; i++)
        for (var j=0; j<components[i].types.length; j++)
            if (components[i].types[j]==type) return components[i].long_name;
    return "";
}

function searchAddress(){
    var sCalle  = $("#inputCalle").val();
    var sCp     = $("#inputCp").val();
    var sEstado = $("#inputEdo").val();
    var sMun    = $("#inputMun").val();
    var sCol    = $("#inputColonia").val();

    var address = sCalle+','+sCol+','+sMun+','+sEstado+'MX,CP. '+sCp;
  
    geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            $("#inputLatitud").val(results[0].geometry.location.lat());
            $("#inputLongitud").val(results[0].geometry.location.lng());
            centerOnMap(0);
        } else {
            var shortCutFunction= 'error';        
            var title           = "Atención!"
            var msg             = 'No fue posible encontrar la dirección';
            toastr.options = {
              "closeButton": true,
              "debug": false,
              "positionClass": "toast-top-right",
              "showDuration": "1000",
              "hideDuration": "1000",
              "timeOut": "5000",
              "extendedTimeOut": "1000",
              "showEasing": "swing",
              "hideEasing": "linear",
              "showMethod": "fadeIn",
              "hideMethod": "fadeOut"
            }
            var $toast = toastr[shortCutFunction](msg, title);    
        }
    });
}
