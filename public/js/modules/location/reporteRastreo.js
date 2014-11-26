var mapReport = null;
var geocoder;
var infoWindowRep;
var infoLocation;
var markersRep = [];
var boundsRep;
var arrayTravelsRep="";
parent.App.startPageLoading();
$( document ).ready(function() {

  /*$(".chosen-select").chosen({disable_search_threshold: 10});*/
    $('#tabs').tab();
    var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
    var dateInter  = parseInt(nowTemp.getMonth())+1;  
    var todayMonth = (dateInter<10) ? "0"+dateInter : dateInter;
    var todayDay   = (nowTemp.getDate()<10) ? "0"+nowTemp.getDate(): nowTemp.getDate();        

    if($("#inputFechaIn").val()==""){
      $("#inputFechaIn").val(nowTemp.getFullYear()+"-"+todayMonth+"-"+todayDay+ ' 00:00');      
    }

    if($("#inputFechaFin").val()==""){
      $("#inputFechaFin").val(nowTemp.getFullYear()+"-"+todayMonth+"-"+todayDay+ ' 23:59');    
    }
    
    var checkin = $('#inputFechaIn').datetimepicker({
        format: "yyyy-mm-dd HH:ii",
        showMeridian: false,
        autoclose: true,
        todayBtn: true,
        startDate:"1920-01-01 01:01:01"
    }).on('changeDate', function(ev) {
      if(ev.date.valueOf() > $('#inputFechaFin').datetimepicker('getDate').valueOf()){
        $('#inputFechaFin').datetimepicker('setDate', ev.date);   
      }

      $('#inputFechaFin').datetimepicker('setStartDate', ev.date);      
      $('#inputFechaFin').prop('disabled', false);
      $('#inputFechaFin')[0].focus();      
    });

    var checkout = $('#inputFechaFin').datetimepicker({
        format: "yyyy-mm-dd HH:ii",
        showMeridian: false,
        autoclose: true,
        todayBtn: true,
        startDate:"1920-01-01 01:01:01"
    }).on('changeDate', function(ev) {
      /*if(ev.date.valueOf() < $('#inputFechaIn').datetimepicker('getDate').valueOf()){
        $('#inputFechaIn').datetimepicker('setDate', ev.date);   
      }*/
      $('#inputFechaIn').datetimepicker('setEndDate', ev.date);
    });
    $('#dataTableRep').dataTable( {
        "sDom": "<'row'<' 'l><' 'f>r>t<'row'<' 'i><' 'p>>",
        "sPaginationType": "bootstrap",
        "bDestroy": true,
        "bLengthChange": false,
        "bPaginate": true,
        "bFilter": false,
        "bSort": true,
        "bJQueryUI": true,
        "iDisplayLength": 5,      
        "bProcessing": true,
        "bAutoWidth": true,
        "bSortClasses": false,
          "oLanguage": {
              "sInfo": "",
              "sEmptyTable": "",
              "sInfoEmpty" : "",
              "sInfoFiltered": "",
              "sLoadingRecords": "Leyendo informaci√≥n",
              "sProcessing": "Procesando",
              "sSearch": "Buscar:",
              "sZeroRecords": "Sin registros",
              "oPaginate": {
                "sPrevious": "Anterior",
                "sNext": "Siguiente"
              }          
          }
    } );  
  
    setTimeout(function(){
        initMapToDraw();
        google.maps.event.trigger(mapReport, "resize");
        mapReport.setZoom( mapReport.getZoom() );
        
    }, 1000);  
    App.stopPageLoading();
});


function initMapToDraw(){
  infoWindowRep = new google.maps.InfoWindow;
    var mapOptions = {
      zoom: 5,
      center: new google.maps.LatLng(24.52713, -104.41406),
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
  mapReport = new google.maps.Map(document.getElementById('MapReport'),mapOptions);  
  boundsRep = new google.maps.LatLngBounds();

  printPositionsMap();
}

function mapClearMap(){
  if(markersRep || markersRep.length>-1){
    for (var i = 0; i < markersRep.length; i++) {
            markersRep[i].setMap(null);
    } 
    markersRep = [];
  }
  arrayTravelsRep=null;
}


function printPositionsMap(){
  var result = $("#positionsRep").html();
  if(result!=""){
        arrayTravelsRep=new Array();
        arrayTravelsRep=result.split('!');
    var content     = '';
    var markerTable = null;

    for(var i=0;i<arrayTravelsRep.length;i++){    
      var travelInfo = arrayTravelsRep[i].split('|');
        var markerTable = null;
        if(travelInfo[4]!="null" && travelInfo[5]!="null" ){
            content='<table width="350" class="table-striped" >'+  
                '<tr><td align="right"><b>Hora</b></td><td width="200" align="left">'+travelInfo[1]+'</td><tr>'+
                '<tr><td align="right"><b>Tipo GPS</b></td><td width="200" align="left">'+travelInfo[2]+'</td><tr>'+
                '<tr><td align="right"><b>Evento</b></td><td width="200" align="left">'+travelInfo[3]+'</td><tr>'+
                '<tr><td align="right"><b>Velocidad</b></td><td align="left">'+travelInfo[6]+' kms/h.</td><tr>'+
                '<tr><td align="right"><b>Bateria</b></td><td align="left">'+travelInfo[7]+' %</td><tr>'+
                '<tr><td align="right"><b>Ubicaci√≥n</b></td><td align="left">'+travelInfo[8]+'</td><tr>'+
                '</table>';
            var Latitud  = parseFloat(travelInfo[4])
            var Longitud = parseFloat(travelInfo[5])

            markerTable = new google.maps.Marker({
              map: mapReport,
              position: new google.maps.LatLng(Latitud,Longitud),
              title:  travelInfo[1],
              icon:   '/images/icons/carMarker.png'
            });
            markersRep.push(new google.maps.LatLng(Latitud,Longitud));
            infoMarkerTableRep(markerTable,content);   
            boundsRep.extend( markerTable.getPosition() );
        }   
      }



      if(arrayTravelsRep.length>1){        
        var iconsetngs = {
            path: google.maps.SymbolPath.FORWARD_OPEN_ARROW,
            strokeColor: '#155B90',
            fillColor: '#155B90',
            fillOpacity: 1,
            strokeWeight: 4        
        };

        
        var line = new google.maps.Polyline({
          path: markersRep,
          strokeColor: "#098EF3",
          strokeOpacity: 1.0,
          strokeWeight: 2,
            icons: [{
                icon: iconsetngs,
                repeat:'35px',         
                offset: '100%'}]
        });   
        
        mapReport.fitBounds(boundsRep);  
        line.setMap(mapReport);
      }else if(arrayTravelsRep.length==1){
        mapReport.setZoom(13);
        mapReport.panTo(markerTable.getPosition());  
      }   
  }    
}

function infoMarkerTableRep(marker,content){ 
    google.maps.event.addListener(marker, 'click',function() {
        if(infoWindowRep){infoWindowRep.close();infoWindowRep.setMap(null);}
        var marker = this;
        var latLng = marker.getPosition();
        infoWindowRep.setContent(content);
        infoWindowRep.open(mapReport, marker);
        mapReport.setZoom(18);
        mapReport.setCenter(latLng); 
        mapReport.panTo(latLng);     
  });
}

function obtainReport(){
  $('body').modalmanager('loading');
  $("#FormData").submit();
}