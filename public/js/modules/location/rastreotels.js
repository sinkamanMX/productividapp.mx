$( document ).ready(function() {
    $('.chzn-select').select2({
        placeholder: "Select an option",
        allowClear: true
    });		

    var iStatus = $("#istatus").val();
    if(iStatus=='1'){
    	$( "#li1" ).click();
    }else if(iStatus=='2'){
    	$( "#li2" ).click();
    }else if(iStatus=='3'){
    	$( "#li3" ).click();
    }else if(iStatus=='4'){	
    	$( "#li4" ).click();
    }
});

function filterSearch(option){
	if(option!="-1"){
		$("#FormData").submit();
	}
}

function goToReport(inputSelect){
	location.href="/location/main/reporte?strInput="+inputSelect;
}

function selectableOptions(classOptions){
    $('.divcontentvisible').hide('slow');    
    $('.'+classOptions).show('slow');
}