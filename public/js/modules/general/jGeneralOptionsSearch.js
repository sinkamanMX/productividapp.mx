var sNameAssignInput = '';

$( document ).ready(function() {    
    $('#iFrameSearch').load(function(){
        $("#loader1").hide();
        $('#iFrameSearch').show();
    });
});       

function openSearch(sOptions,sNameinput){
	sNameAssignInput = sNameinput;
    $("#loader1").show(); 
    $('#iFrameSearch').hide();
    $('#iFrameSearch').attr('src','/dbman/search/index?ssIdource='+sOptions);
    $("#myLargeModalLabel").modal("show");
}

function assignValue(nameValue,IdValue){
    $("#"+sNameAssignInput).val(IdValue);
    $("#inputSearch").val(nameValue);
    $("#myLargeModalLabel").modal("hide");
}

function cleanSearch(sNameinput){
    $("#"+sNameinput).val("NULL");
    $("#inputSearch").val("");
    $("#spanSearchOff").hide('slow');
    $("#spanSearchOn").show('slow');
}