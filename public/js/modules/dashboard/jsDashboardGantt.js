var adataSource = [];
var bChangeFlag = 0;

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

    $('#iFrameSearch').load(function(){
        $("#loader1").hide();
        $('#iFrameSearch').show();
    });
});


function showDetail(idDate){
    $("#loader1").show();
    $('#iFrameSearch').hide();
    $('#iFrameSearch').attr('src','/dates/main/getdateinfo?catId='+idDate+'&calledFrom=rgantt');
    $("#myLargeModalLabel").modal("show");
}

function submitForm(){
    $( "#FormData" ).submit();
}


function assignValue(option){
  bChangeFlag = option;
}

function closeWindow(){
  $("#myLargeModalLabel").modal("hide");
  if(bChangeFlag=='1'){
    $('#formDbman').bootstrapValidator('defaultSubmit',true);
  }
}
