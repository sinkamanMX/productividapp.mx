App.startPageLoading();

$( document ).ready(function() {
	Portfolio.init();
    $('.chzn-select').select2({
        placeholder: "Select an option",
        allowClear: true
    });		

    App.stopPageLoading();
});

function filterSearch(option){
	if(option!="-1"){
		App.startPageLoading();
		$("#FormData").submit();
	}
}

function goToReport(inputSelect){
	App.startPageLoading();
	location.href="/location/main/reporte?strInput="+inputSelect;
}