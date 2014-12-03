$( document ).ready(function() {
    $('#dataTable').dataTable( {
        "bDestroy": true,
        "bLengthChange": false,
        "bPaginate": true,
        "bFilter": true,
        "bSort": true,
        "iDisplayLength": 10,      
        "bProcessing": true,
        "bAutoWidth": true,
        "bSortClasses": false,
          "oLanguage": {
              "sInfo": "Mostrando _TOTAL_ registros (_START_ a _END_)",
              "sEmptyTable": "Sin registros.",
              "sInfoEmpty" : "Sin registros.",
              "sInfoFiltered": " - Filtrado de un total de  _MAX_ registros",
              "sLoadingRecords": "Leyendo información",
              "sProcessing": "Procesando",
              "sSearch": "Buscar:",
              "sZeroRecords": "Sin registros",
              "oPaginate": {
                "sPrevious": "Anterior",
                "sNext": "Siguiente"
              }          
          }
    } );    
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