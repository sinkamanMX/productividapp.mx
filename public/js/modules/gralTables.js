$( document ).ready(function() {            
  var oTable = $('.table').dataTable({
      "sDom": "Tflt<'row DTTTFooter'<'col-sm-6'i><'col-sm-6'p>>",
      "bPaginate": true,
      "bDestroy": true,
      "bLengthChange": false,
      "iDisplayLength": 10,
      "oTableTools": {
          "aButtons": [
              "print"
          ],
          "sSwfPath": "assets/swf/copy_csv_xls_pdf.swf"
      },
      "aLengthMenu": [],
      "language": {
        "sLengthMenu": "",
        "sInfo": "Mostrando _TOTAL_ registros (_START_ a _END_)",
        "sEmptyTable": "Sin registros.",
        "sInfoEmpty" : "Sin registros.",
        "sInfoFiltered": " - Filtrado de un total de  _MAX_ registros",
        "sLoadingRecords": "Leyendo información",
        "sProcessing": "Procesando",
        "sSearch": "",
        "sZeroRecords": "Sin registros",
        "oPaginate": {
          "sPrevious": "Anterior",
          "sNext": "Siguiente"
        }
      }
  }); 
});   

function beforeDelete(idtableRow){
  $("#inputDelete").val(idtableRow);  
    bootbox.confirm("¿Realmente desea eliminar este registro?", function (result) {
    if (result) {
      deleteRow();
    }
  });
}         

function deleteRow(){ 
	 var idItem 	  = $("#inputDelete").val();
	 var moduleUrl = $("#inputModule").val();   
   $('.loading-container').removeClass('loading-inactive');
    $.ajax({
        url 	  : "/dbman/json/operations",
        type    : "GET",
        dataType: 'json',
        data    : { catId : idItem,
            	      optReg: 'delete',
                    ssIdource: moduleUrl},
        success: function(data) {
            var result = data.answer;
            $('.loading-container').addClass('loading-inactive');            

            if(result == 'deleted'){
                Notify('El registro fue eliminado correctamente.', 'top-right', '5000', 'success', 'fa-check', true);                 
                location.reload();                
            }else{
                Notify('Ocurrio un error al eliminar el registro.', 'top-right', '5000', 'warning', 'fa-warning', true);
            }            
        }
    });
}