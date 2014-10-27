$( document ).ready(function() {
	TableManaged.init();
});

function beforeDelete(idtableRow){
	$("#inputDelete").val(idtableRow);	
	$('#static').on('hidden.bs.modal', function () {
        App.stopPageLoading();
    });
}

function deleteRow(){ 
	App.startPageLoading();
	var idItem 	  = $("#inputDelete").val();
	var moduleUrl = $("#inputModule").val();
	var divError  = $("#dErrorAlert");
	$("#static").modal('hide'); 

    $.ajax({
        url 	: moduleUrl,
        type    : "GET",
        dataType: 'json',
        data    : { catId : idItem,
            	    optReg: 'delete'},
        success: function(data) {
            var result = data.answer; 
       		App.stopPageLoading();     

            if(result == 'deleted'){
              location.reload();
            }else{
              divError.show();
            }
        }
    });
}