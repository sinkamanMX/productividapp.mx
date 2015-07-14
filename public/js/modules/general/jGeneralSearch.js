$( document ).ready(function() {            
  var oTable = $('.table').dataTable({
      "sDom": "Tflt<'row DTTTFooter'<'col-sm-6'i><'col-sm-6'p>>",
      "bPaginate": true,
      "bLengthChange": false,
      "iDisplayLength": 7,
      "oTableTools": {
          "aButtons": [
              "csv"
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
