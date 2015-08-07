var adataSource = [];

$( document ).ready(function() {

    $("#inputSucursal").select2({
        placeholder: "Seleccione un tipo",
        allowClear: true
    });
    $(".gantt").gantt({
        source: adataSource,
        navigate: "scroll",
        maxScale: "months",
        minScale: "hours",
        scale:    "days",
        itemsPerPage: 20,
        scrollToToday: true,
        onItemClick: function(data) {    
            showDetail(data);
        },
        onAddClick: function(dt, rowId) {
            //alert("Empty space clicked - add an item!");
        },
        onRender: function() {
            if (window.console && typeof console.log === "function") {
                //console.log("chart rendered");
            }
        }
    });

    /*
    $('#iFrameDetCita').on('load', function () {        
        $('#loader').hide();
        $('#iFrameDetCita').show();
    });                
    */
});
/*
function showDetail(idDate){
    $('#loader').show();  

    $("#myModalinfoVis").modal("show");        
    $('#iFrameDetCita').attr('src','/atn/main/citadetalle?strInput='+idDate);    
}
*/

function submitForm(){
    $( "#FormData" ).submit();
}